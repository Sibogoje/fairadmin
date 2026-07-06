import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;
import 'package:http/io_client.dart';

class ApiException implements Exception {
  ApiException(this.message, {this.code, this.statusCode});

  final String message;
  final String? code;
  final int? statusCode;

  @override
  String toString() => message;
}

class ApiClient {
  ApiClient({
    required this.baseUrl,
    this.allowSelfSignedCertificate = false,
    http.Client? httpClient,
  }) : _httpClient = httpClient ??
            _defaultClient(
              baseUrl,
              allowSelfSignedCertificate: allowSelfSignedCertificate,
            );

  final String baseUrl;
  final bool allowSelfSignedCertificate;
  final http.Client _httpClient;
  static const requestTimeout = Duration(seconds: 30);

  static http.Client _defaultClient(
    String baseUrl, {
    required bool allowSelfSignedCertificate,
  }) {
    final apiHost = Uri.parse(baseUrl).host.toLowerCase();
    final httpClient = HttpClient();

    if (allowSelfSignedCertificate) {
      httpClient.badCertificateCallback = (certificate, host, port) {
        return host.toLowerCase() == apiHost;
      };
    }

    return IOClient(httpClient);
  }

  Uri _uri(String path, [Map<String, String>? query]) {
    return Uri.parse('$baseUrl/$path').replace(queryParameters: query);
  }

  Future<Map<String, dynamic>> login({
    required String memberNo,
    required String password,
  }) {
    return _postJson('login.php', {
      'memberno': memberNo,
      'password': password,
      'device_name': Platform.operatingSystem,
    });
  }

  Future<Map<String, dynamic>> firstLogin(Map<String, dynamic> payload) {
    return _postJson('first-login.php', {
      ...payload,
      'device_name': Platform.operatingSystem,
    });
  }

  Future<List<String>> fetchSecurityQuestions(String memberNo) async {
    final response = await _jsonResponse(() =>
        _httpClient.get(_uri('forgot-password.php', {'memberno': memberNo})));
    final data = _decode(response);
    return (data['questions'] as List<dynamic>).cast<String>();
  }

  Future<void> resetPassword({
    required String memberNo,
    required String password,
    required List<Map<String, String>> answers,
  }) async {
    await _postJson('forgot-password.php', {
      'memberno': memberNo,
      'password': password,
      'answers': answers,
    });
  }

  Future<Map<String, dynamic>> submitAdhocRequest({
    required String token,
    required double amount,
    required String details,
    required List<int> pdfBytes,
  }) async {
    final request = http.MultipartRequest('POST', _uri('adhoc-request.php'));
    request.headers['Authorization'] = 'Bearer $token';
    request.fields['amount'] = amount.toStringAsFixed(2);
    request.fields['details'] = details;
    request.files.add(http.MultipartFile.fromBytes(
      'combined_pdf',
      pdfBytes,
      filename: 'adhoc_request.pdf',
    ));

    final streamed = await _request(() => _httpClient.send(request));
    final response = await http.Response.fromStream(streamed);
    return _decode(response);
  }

  Future<Map<String, dynamic>> _postJson(
      String path, Map<String, dynamic> body) async {
    final response = await _jsonResponse(() => _httpClient.post(
          _uri(path),
          headers: {'Content-Type': 'application/json'},
          body: jsonEncode(body),
        ));
    return _decode(response);
  }

  Future<http.Response> _jsonResponse(
      Future<http.Response> Function() send) async {
    final response = await _request(send);
    if (_looksLikeJson(response.body)) {
      return response;
    }

    final retryResponse = await _request(send);
    if (_looksLikeJson(retryResponse.body)) {
      return retryResponse;
    }

    return response;
  }

  bool _looksLikeJson(String body) {
    final trimmed = body.trimLeft();
    return trimmed.startsWith('{') || trimmed.startsWith('[');
  }

  Future<T> _request<T>(Future<T> Function() send) async {
    try {
      return await send().timeout(requestTimeout);
    } on SocketException {
      throw ApiException(
          'Could not connect to the Fairlife server. Check your internet connection and API URL.');
    } on HandshakeException {
      throw ApiException(
          'Could not verify the Fairlife server certificate. The app is currently configured for $baseUrl.');
    } on HttpException {
      throw ApiException(
          'The Fairlife server closed the request unexpectedly.');
    } on FormatException {
      throw ApiException(
          'The Fairlife server returned an unreadable response.');
    } on ApiException {
      rethrow;
    } catch (error) {
      throw ApiException('Request failed: $error');
    }
  }

  Map<String, dynamic> _decode(http.Response response) {
    final Map<String, dynamic> data;
    try {
      data = jsonDecode(response.body.isEmpty ? '{}' : response.body)
          as Map<String, dynamic>;
    } on FormatException {
      final preview = response.body.replaceAll(RegExp(r'\s+'), ' ').trim();
      throw ApiException(
        preview.isEmpty
            ? 'The Fairlife server returned an empty response.'
            : 'The Fairlife server returned a non-JSON response: ${preview.substring(0, preview.length > 120 ? 120 : preview.length)}',
        statusCode: response.statusCode,
      );
    }
    if (response.statusCode >= 400 || data['success'] == false) {
      throw ApiException(
        (data['message'] as String?) ?? 'Request failed.',
        code: data['code'] as String?,
        statusCode: response.statusCode,
      );
    }
    return data;
  }
}
