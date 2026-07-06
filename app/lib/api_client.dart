import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;

class ApiException implements Exception {
  ApiException(this.message, {this.code, this.statusCode});

  final String message;
  final String? code;
  final int? statusCode;

  @override
  String toString() => message;
}

class ApiClient {
  ApiClient({required this.baseUrl, http.Client? httpClient})
      : _httpClient = httpClient ?? http.Client();

  final String baseUrl;
  final http.Client _httpClient;

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
    final response = await _httpClient.get(_uri('forgot-password.php', {'memberno': memberNo}));
    final data = _decode(response);
    return (data['questions'] as List<dynamic>).cast<String>();
  }

  Future<void> resetPassword({
    required String memberNo,
    required String password,
    required List<String> answers,
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

    final streamed = await _httpClient.send(request);
    final response = await http.Response.fromStream(streamed);
    return _decode(response);
  }

  Future<Map<String, dynamic>> _postJson(String path, Map<String, dynamic> body) async {
    final response = await _httpClient.post(
      _uri(path),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode(body),
    );
    return _decode(response);
  }

  Map<String, dynamic> _decode(http.Response response) {
    final data = jsonDecode(response.body.isEmpty ? '{}' : response.body) as Map<String, dynamic>;
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