import 'package:flutter_test/flutter_test.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:fairlife_member_app/main.dart';

void main() {
  testWidgets('shows the member login screen', (WidgetTester tester) async {
    SharedPreferences.setMockInitialValues({});
    final preferences = await SharedPreferences.getInstance();

    await tester.pumpWidget(FairlifeApp(preferences: preferences));

    expect(find.text('Member sign in'), findsOneWidget);
    expect(find.text('Sign in'), findsOneWidget);
  });
}
