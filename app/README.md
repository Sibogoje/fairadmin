# Fairlife Member App

Flutter Android app for members to complete first login, manage password recovery security questions, and submit adhoc fund requests into the existing `clientr` queue reviewed in `Transactions/clientr.php`.

## Setup

1. Install Flutter on this machine and add `flutter` to `PATH`.
2. From this folder, generate the Android platform files if they are not present:

   ```powershell
   flutter create --platforms=android .
   flutter pub get
   ```

3. Run against the live API URL:

   ```powershell
   flutter run --dart-define=FAIRLIFE_API_BASE_URL=https://your-domain.example/mobile/api
   ```

   If the API host uses a self-signed certificate chain, the app allows that certificate for the configured API host by default. To require a publicly trusted certificate, run with:

   ```powershell
   flutter run --dart-define=FAIRLIFE_ALLOW_SELF_SIGNED_CERTIFICATE=false
   ```

The PHP API creates `mobile_api_tokens` and `mobile_security_questions` automatically on first use. The same SQL is also included in `mobile/api/schema.sql` for manual deployment.