import 'dart:typed_data';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'api_client.dart';
import 'document_pdf_service.dart';

const apiBaseUrl = String.fromEnvironment(
  'FAIRLIFE_API_BASE_URL',
  defaultValue: 'https://fairlife.grinpath.com/mobile/api',
);
const logoIconAsset = 'assets/logo.png';
const brandName = 'Fairlife';
const brandTagline = 'Benefit Benefit Swaziland.';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final preferences = await SharedPreferences.getInstance();
  runApp(FairlifeApp(preferences: preferences));
}

class FairlifeApp extends StatelessWidget {
  const FairlifeApp({super.key, required this.preferences});

  final SharedPreferences preferences;

  @override
  Widget build(BuildContext context) {
    final scheme = ColorScheme.fromSeed(
      seedColor: const Color(0xFF012970),
      primary: const Color(0xFF012970),
      secondary: const Color(0xFF4154F1),
      tertiary: const Color(0xFFFFC107),
      surface: Colors.white,
    );

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Fairlife',
      theme: ThemeData(
        colorScheme: scheme,
        scaffoldBackgroundColor: const Color(0xFFF6F9FF),
        textTheme: GoogleFonts.manropeTextTheme(),
        useMaterial3: true,
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: Colors.white,
          border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(18),
              borderSide: BorderSide.none),
          contentPadding:
              const EdgeInsets.symmetric(horizontal: 18, vertical: 16),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: const Color(0xFFFFC107),
            foregroundColor: const Color(0xFF1C2434),
            minimumSize: const Size.fromHeight(54),
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
            textStyle: const TextStyle(fontWeight: FontWeight.w800),
          ),
        ),
      ),
      home: AppRoot(preferences: preferences),
    );
  }
}

class AppRoot extends StatefulWidget {
  const AppRoot({super.key, required this.preferences});

  final SharedPreferences preferences;

  @override
  State<AppRoot> createState() => _AppRootState();
}

class _AppRootState extends State<AppRoot> {
  late final ApiClient api = ApiClient(baseUrl: apiBaseUrl);
  String? token;
  String? name;

  @override
  void initState() {
    super.initState();
    token = widget.preferences.getString('token');
    name = widget.preferences.getString('name');
  }

  Future<void> saveSession(Map<String, dynamic> data) async {
    final member = data['member'] as Map<String, dynamic>;
    await widget.preferences.setString('token', data['token'] as String);
    await widget.preferences.setString('name', member['name'] as String);
    setState(() {
      token = data['token'] as String;
      name = member['name'] as String;
    });
  }

  Future<void> logout() async {
    await widget.preferences.clear();
    setState(() {
      token = null;
      name = null;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (token != null) {
      return HomeScreen(
          api: api, token: token!, name: name ?? 'Member', onLogout: logout);
    }

    return LoginScreen(api: api, onAuthenticated: saveSession);
  }
}

class LoginScreen extends StatefulWidget {
  const LoginScreen(
      {super.key, required this.api, required this.onAuthenticated});

  final ApiClient api;
  final ValueChanged<Map<String, dynamic>> onAuthenticated;

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final memberNo = TextEditingController();
  final password = TextEditingController();
  bool loading = false;

  Future<void> submit() async {
    setState(() => loading = true);
    try {
      final data = await widget.api
          .login(memberNo: memberNo.text.trim(), password: password.text);
      widget.onAuthenticated(data);
    } on ApiException catch (error) {
      if ((error.code == 'FIRST_LOGIN_REQUIRED' ||
              error.code == 'SECURITY_SETUP_REQUIRED') &&
          mounted) {
        await Navigator.of(context).push(MaterialPageRoute(
          builder: (_) => FirstLoginScreen(
              api: widget.api,
              initialMemberNo: memberNo.text.trim(),
              onAuthenticated: widget.onAuthenticated),
        ));
      } else if (mounted) {
        showMessage(context, error.message);
      }
    } finally {
      if (mounted) setState(() => loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthScaffold(
      title: 'Member sign in',
      subtitle: 'Request adhoc funds and track your submission securely.',
      child: Column(
        children: [
          AppTextField(
              controller: memberNo,
              label: 'Member number',
              icon: Icons.badge_outlined),
          const SizedBox(height: 14),
          AppTextField(
              controller: password,
              label: 'Password',
              icon: Icons.lock_outline,
              obscure: true),
          const SizedBox(height: 22),
          ElevatedButton(
              onPressed: loading ? null : submit,
              child: Text(loading ? 'Signing in...' : 'Sign in')),
          const SizedBox(height: 18),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              TextButton(
                onPressed: () => Navigator.of(context).push(MaterialPageRoute(
                  builder: (_) => FirstLoginScreen(
                      api: widget.api, onAuthenticated: widget.onAuthenticated),
                )),
                child: const Text('First login'),
              ),
              TextButton(
                onPressed: () => Navigator.of(context).push(MaterialPageRoute(
                    builder: (_) => ForgotPasswordScreen(api: widget.api))),
                child: const Text('Forgot password'),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class FirstLoginScreen extends StatefulWidget {
  const FirstLoginScreen(
      {super.key,
      required this.api,
      required this.onAuthenticated,
      this.initialMemberNo});

  final ApiClient api;
  final ValueChanged<Map<String, dynamic>> onAuthenticated;
  final String? initialMemberNo;

  @override
  State<FirstLoginScreen> createState() => _FirstLoginScreenState();
}

class _FirstLoginScreenState extends State<FirstLoginScreen> {
  final memberNo = TextEditingController();
  final deceasedId = TextEditingController();
  final memberId = TextEditingController();
  final dateOpened = TextEditingController();
  final password = TextEditingController();
  final confirmPassword = TextEditingController();
  final answers = List.generate(3, (_) => TextEditingController());
  final questions = <String>[
    'What was your first school?',
    'What city were you born in?',
    'What is your mother\'s maiden name?',
  ];
  bool loading = false;
  String? statusMessage;
  bool statusIsError = false;

  void setStatus(String message, {bool isError = true}) {
    setState(() {
      statusMessage = message;
      statusIsError = isError;
    });
  }

  @override
  void initState() {
    super.initState();
    memberNo.text = widget.initialMemberNo ?? '';
  }

  Future<void> pickDate() async {
    final picked = await showDatePicker(
      context: context,
      firstDate: DateTime(1970),
      lastDate: DateTime.now(),
      initialDate: DateTime.now(),
    );
    if (picked != null) {
      dateOpened.text = DateFormat('yyyy-MM-dd').format(picked);
    }
  }

  Future<void> submit() async {
    if (memberNo.text.trim().isEmpty ||
        deceasedId.text.trim().isEmpty ||
        memberId.text.trim().isEmpty ||
        dateOpened.text.trim().isEmpty ||
        password.text.isEmpty ||
        confirmPassword.text.isEmpty ||
        answers.any((answer) => answer.text.trim().isEmpty)) {
      setStatus('Please complete all first login fields.');
      return;
    }
    if (password.text.length < 8) {
      setStatus('Password must be at least 8 characters.');
      return;
    }
    if (password.text != confirmPassword.text) {
      setStatus('Passwords do not match.');
      return;
    }
    setState(() {
      loading = true;
      statusMessage = 'Checking your details...';
      statusIsError = false;
    });
    try {
      final data = await widget.api.firstLogin({
        'memberno': memberNo.text.trim(),
        'deceased_id_number': deceasedId.text.trim(),
        'member_id_number': memberId.text.trim(),
        'date_account_opened': dateOpened.text.trim(),
        'password': password.text,
        'security_questions': List.generate(
            3,
            (index) => {
                  'question': questions[index],
                  'answer': answers[index].text,
                }),
      });
      widget.onAuthenticated(data);
      if (mounted) {
        setStatus('First login complete. Opening your dashboard...',
            isError: false);
        Navigator.of(context).pop();
      }
    } on ApiException catch (error) {
      if (mounted) setStatus(error.message);
    } catch (error) {
      if (mounted) setStatus('First login failed: $error');
    } finally {
      if (mounted) setState(() => loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthScaffold(
      title: 'First login setup',
      subtitle:
          'Confirm your member record, create a new password, then secure recovery.',
      showBack: true,
      child: Column(
        children: [
          AppTextField(
              controller: memberNo,
              label: 'Member number',
              icon: Icons.badge_outlined),
          const SizedBox(height: 12),
          AppTextField(
              controller: deceasedId,
              label: 'Deceased national ID',
              icon: Icons.fingerprint),
          const SizedBox(height: 12),
          AppTextField(
              controller: memberId,
              label: 'Member national ID',
              icon: Icons.assignment_ind_outlined),
          const SizedBox(height: 12),
          AppTextField(
              controller: dateOpened,
              label: 'Date account opened',
              icon: Icons.event_outlined,
              readOnly: true,
              onTap: pickDate),
          const SizedBox(height: 12),
          AppTextField(
              controller: password,
              label: 'New password',
              icon: Icons.lock_outline,
              obscure: true),
          const SizedBox(height: 12),
          AppTextField(
              controller: confirmPassword,
              label: 'Confirm password',
              icon: Icons.verified_user_outlined,
              obscure: true),
          const SizedBox(height: 18),
          ...List.generate(
              3,
              (index) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: AppTextField(
                        controller: answers[index],
                        label: questions[index],
                        icon: Icons.question_answer_outlined),
                  )),
          const SizedBox(height: 10),
          ElevatedButton(
              onPressed: loading ? null : submit,
              child: Text(loading ? 'Setting up...' : 'Complete first login')),
          if (statusMessage != null) ...[
            const SizedBox(height: 14),
            StatusPanel(message: statusMessage!, isError: statusIsError),
          ],
        ],
      ),
    );
  }
}

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key, required this.api});

  final ApiClient api;

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final memberNo = TextEditingController();
  final password = TextEditingController();
  final answers = List.generate(3, (_) => TextEditingController());
  List<String> questions = [];
  bool loading = false;

  Future<void> loadQuestions() async {
    setState(() => loading = true);
    try {
      questions = await widget.api.fetchSecurityQuestions(memberNo.text.trim());
    } on ApiException catch (error) {
      if (mounted) showMessage(context, error.message);
    } finally {
      if (mounted) setState(() => loading = false);
    }
  }

  Future<void> reset() async {
    setState(() => loading = true);
    try {
      await widget.api.resetPassword(
        memberNo: memberNo.text.trim(),
        password: password.text,
        answers: answers.map((answer) => answer.text).toList(),
      );
      if (mounted) {
        showMessage(context, 'Password reset successful.');
        Navigator.of(context).pop();
      }
    } on ApiException catch (error) {
      if (mounted) showMessage(context, error.message);
    } finally {
      if (mounted) setState(() => loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthScaffold(
      title: 'Reset password',
      subtitle: 'Answer your security questions to set a new password.',
      showBack: true,
      child: Column(
        children: [
          AppTextField(
              controller: memberNo,
              label: 'Member number',
              icon: Icons.badge_outlined),
          const SizedBox(height: 14),
          ElevatedButton(
              onPressed: loading ? null : loadQuestions,
              child: Text(
                  questions.isEmpty ? 'Load questions' : 'Reload questions')),
          const SizedBox(height: 18),
          ...List.generate(
              questions.length,
              (index) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: AppTextField(
                        controller: answers[index],
                        label: questions[index],
                        icon: Icons.question_answer_outlined),
                  )),
          if (questions.isNotEmpty) ...[
            AppTextField(
                controller: password,
                label: 'New password',
                icon: Icons.lock_outline,
                obscure: true),
            const SizedBox(height: 18),
            ElevatedButton(
                onPressed: loading ? null : reset,
                child: Text(loading ? 'Resetting...' : 'Reset password')),
          ],
        ],
      ),
    );
  }
}

class HomeScreen extends StatefulWidget {
  const HomeScreen(
      {super.key,
      required this.api,
      required this.token,
      required this.name,
      required this.onLogout});

  final ApiClient api;
  final String token;
  final String name;
  final VoidCallback onLogout;

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final amount = TextEditingController();
  final details = TextEditingController();
  final imagePicker = ImagePicker();
  final pdfService = DocumentPdfService();
  final requestLetters = <RequestDocument>[];
  final supportDocs = <RequestDocument>[];
  bool loading = false;

  Future<void> addImage(List<RequestDocument> target, String labelPrefix,
      ImageSource source) async {
    final image = await imagePicker.pickImage(source: source, imageQuality: 88);
    if (image == null) return;
    setState(() => target.add(RequestDocument(
        path: image.path, label: '$labelPrefix ${target.length + 1}')));
  }

  Future<void> addFile(List<RequestDocument> target, String labelPrefix) async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['jpg', 'jpeg', 'png', 'webp'],
      allowMultiple: true,
    );
    if (result == null) return;
    setState(() {
      for (final file in result.files.where((file) => file.path != null)) {
        target.add(RequestDocument(
            path: file.path!, label: '$labelPrefix ${target.length + 1}'));
      }
    });
  }

  Future<void> submit() async {
    if (requestLetters.isEmpty) {
      showMessage(context, 'Please add at least one request letter photo.');
      return;
    }
    final parsedAmount =
        double.tryParse(amount.text.replaceAll(',', '').trim());
    if (parsedAmount == null ||
        parsedAmount <= 0 ||
        details.text.trim().isEmpty) {
      showMessage(context, 'Amount and usage details are required.');
      return;
    }

    setState(() => loading = true);
    try {
      final Uint8List pdfBytes = await pdfService.buildCombinedPdf(
        requestLetters: requestLetters,
        supportingDocuments: supportDocs,
      );
      final data = await widget.api.submitAdhocRequest(
        token: widget.token,
        amount: parsedAmount,
        details: details.text.trim(),
        pdfBytes: pdfBytes,
      );
      if (mounted) {
        amount.clear();
        details.clear();
        setState(() {
          requestLetters.clear();
          supportDocs.clear();
        });
        showMessage(context,
            'Request submitted. Reference: ${data['request']['reference']}');
      }
    } on ApiException catch (error) {
      if (mounted) showMessage(context, error.message);
    } finally {
      if (mounted) setState(() => loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: ListView(
          padding: const EdgeInsets.all(20),
          children: [
            Row(
              children: [
                Container(
                  width: 48,
                  height: 48,
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16)),
                  child: Image.asset(logoIconAsset, fit: BoxFit.contain),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Align(
                        alignment: Alignment.centerLeft,
                        child: AppBrandText(
                            color: Color(0xFF012970), compact: true),
                      ),
                      Text(widget.name,
                          style: Theme.of(context)
                              .textTheme
                              .bodyMedium
                              ?.copyWith(color: Colors.black54)),
                    ],
                  ),
                ),
                IconButton.filledTonal(
                    onPressed: widget.onLogout, icon: const Icon(Icons.logout)),
              ],
            ),
            const SizedBox(height: 24),
            Container(
              padding: const EdgeInsets.all(22),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(28),
                gradient: const LinearGradient(
                    colors: [Color(0xFF012970), Color(0xFF4154F1)]),
                boxShadow: const [
                  BoxShadow(
                      color: Color(0x26012970),
                      blurRadius: 24,
                      offset: Offset(0, 14))
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Icon(Icons.account_balance_wallet_outlined,
                      color: Colors.white, size: 34),
                  const SizedBox(height: 20),
                  Text('Adhoc funds request',
                      style: Theme.of(context)
                          .textTheme
                          .headlineSmall
                          ?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.w900)),
                  const SizedBox(height: 8),
                  Text(
                      'Submit amount, details, and one combined PDF built from your request letter and supporting images.',
                      style: TextStyle(
                          color: Colors.white.withOpacity(.78), height: 1.4)),
                ],
              ),
            ),
            const SizedBox(height: 22),
            AppTextField(
                controller: amount,
                label: 'Amount requested',
                icon: Icons.payments_outlined,
                keyboardType: TextInputType.number),
            const SizedBox(height: 14),
            AppTextField(
                controller: details,
                label: 'Where the funds will be used',
                icon: Icons.notes_outlined,
                maxLines: 4),
            const SizedBox(height: 20),
            DocumentPickerPanel(
              title: 'Request letter',
              documents: requestLetters,
              onCamera: () => addImage(
                  requestLetters, 'Request letter', ImageSource.camera),
              onGallery: () => addImage(
                  requestLetters, 'Request letter', ImageSource.gallery),
              onFiles: () => addFile(requestLetters, 'Request letter'),
              onRemove: (index) =>
                  setState(() => requestLetters.removeAt(index)),
            ),
            const SizedBox(height: 16),
            DocumentPickerPanel(
              title: 'Supporting documents',
              documents: supportDocs,
              onCamera: () => addImage(
                  supportDocs, 'Supporting document', ImageSource.camera),
              onGallery: () => addImage(
                  supportDocs, 'Supporting document', ImageSource.gallery),
              onFiles: () => addFile(supportDocs, 'Supporting document'),
              onRemove: (index) => setState(() => supportDocs.removeAt(index)),
            ),
            const SizedBox(height: 22),
            ElevatedButton(
                onPressed: loading ? null : submit,
                child: Text(loading ? 'Submitting...' : 'Submit request')),
          ],
        ),
      ),
    );
  }
}

class DocumentPickerPanel extends StatelessWidget {
  const DocumentPickerPanel(
      {super.key,
      required this.title,
      required this.documents,
      required this.onCamera,
      required this.onGallery,
      required this.onFiles,
      required this.onRemove});

  final String title;
  final List<RequestDocument> documents;
  final VoidCallback onCamera;
  final VoidCallback onGallery;
  final VoidCallback onFiles;
  final ValueChanged<int> onRemove;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: const [
            BoxShadow(
                color: Color(0x12012970), blurRadius: 18, offset: Offset(0, 10))
          ]),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title,
              style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w900,
                  color: Color(0xFF012970))),
          const SizedBox(height: 12),
          Row(
            children: [
              PickerButton(icon: Icons.photo_camera_outlined, onTap: onCamera),
              const SizedBox(width: 10),
              PickerButton(
                  icon: Icons.photo_library_outlined, onTap: onGallery),
              const SizedBox(width: 10),
              PickerButton(icon: Icons.upload_file_outlined, onTap: onFiles),
            ],
          ),
          if (documents.isNotEmpty) ...[
            const SizedBox(height: 12),
            ...List.generate(
                documents.length,
                (index) => ListTile(
                      contentPadding: EdgeInsets.zero,
                      leading: const Icon(Icons.description_outlined,
                          color: Color(0xFF4154F1)),
                      title: Text(documents[index].label),
                      trailing: IconButton(
                          icon: const Icon(Icons.close),
                          onPressed: () => onRemove(index)),
                    )),
          ],
        ],
      ),
    );
  }
}

class PickerButton extends StatelessWidget {
  const PickerButton({super.key, required this.icon, required this.onTap});

  final IconData icon;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          height: 50,
          decoration: BoxDecoration(
              color: const Color(0xFFF6F9FF),
              borderRadius: BorderRadius.circular(16)),
          child: Icon(icon, color: const Color(0xFF012970)),
        ),
      ),
    );
  }
}

class AppBrandText extends StatelessWidget {
  const AppBrandText({super.key, required this.color, this.compact = false});

  final Color color;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisSize: MainAxisSize.min,
      children: [
        Text(
          brandName,
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
                color: color,
                fontSize: compact ? 20 : 26,
                fontWeight: FontWeight.w900,
                height: .95,
              ),
        ),
        const SizedBox(height: 3),
        Text(
          brandTagline,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: color,
                fontSize: compact ? 11 : 14,
                fontWeight: FontWeight.w700,
                height: 1.1,
              ),
        ),
      ],
    );
  }
}

class StatusPanel extends StatelessWidget {
  const StatusPanel({super.key, required this.message, required this.isError});

  final String message;
  final bool isError;

  @override
  Widget build(BuildContext context) {
    final color = isError ? const Color(0xFFB42318) : const Color(0xFF027A48);

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: color.withValues(alpha: .1),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withValues(alpha: .35)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(
            isError ? Icons.error_outline : Icons.check_circle_outline,
            color: color,
            size: 20,
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              message,
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    color: color,
                    fontWeight: FontWeight.w700,
                    height: 1.35,
                  ),
            ),
          ),
        ],
      ),
    );
  }
}

class AuthScaffold extends StatelessWidget {
  const AuthScaffold(
      {super.key,
      required this.title,
      required this.subtitle,
      required this.child,
      this.showBack = false});

  final String title;
  final String subtitle;
  final Widget child;
  final bool showBack;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: ListView(
          padding: const EdgeInsets.all(22),
          children: [
            if (showBack)
              Align(
                  alignment: Alignment.centerLeft,
                  child: IconButton.filledTonal(
                      onPressed: () => Navigator.of(context).pop(),
                      icon: const Icon(Icons.arrow_back))),
            const SizedBox(height: 18),
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: const Color(0xFF012970),
                borderRadius: BorderRadius.circular(30),
                boxShadow: const [
                  BoxShadow(
                      color: Color(0x26012970),
                      blurRadius: 26,
                      offset: Offset(0, 14))
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 58,
                        height: 58,
                        padding: const EdgeInsets.all(9),
                        decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(18)),
                        child: Image.asset(logoIconAsset, fit: BoxFit.contain),
                      ),
                      const SizedBox(width: 14),
                      const Expanded(child: AppBrandText(color: Colors.white)),
                    ],
                  ),
                  const SizedBox(height: 24),
                  Text(title,
                      style: Theme.of(context)
                          .textTheme
                          .headlineMedium
                          ?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.w900)),
                  const SizedBox(height: 8),
                  Text(subtitle,
                      style: TextStyle(
                          color: Colors.white.withOpacity(.78), height: 1.45)),
                ],
              ),
            ),
            const SizedBox(height: 24),
            child,
          ],
        ),
      ),
    );
  }
}

class AppTextField extends StatelessWidget {
  const AppTextField(
      {super.key,
      required this.controller,
      required this.label,
      required this.icon,
      this.obscure = false,
      this.readOnly = false,
      this.onTap,
      this.keyboardType,
      this.maxLines = 1});

  final TextEditingController controller;
  final String label;
  final IconData icon;
  final bool obscure;
  final bool readOnly;
  final VoidCallback? onTap;
  final TextInputType? keyboardType;
  final int maxLines;

  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: controller,
      obscureText: obscure,
      readOnly: readOnly,
      onTap: onTap,
      keyboardType: keyboardType,
      maxLines: obscure ? 1 : maxLines,
      decoration: InputDecoration(prefixIcon: Icon(icon), labelText: label),
    );
  }
}

void showMessage(BuildContext context, String message) {
  ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message), behavior: SnackBarBehavior.floating));
}
