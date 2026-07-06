import 'dart:io';

import 'package:image/image.dart' as img;

void main() {
  final logoFile = File('assets/logo.png');
  if (!logoFile.existsSync()) {
    throw StateError('assets/logo.png was not found.');
  }

  final logo = img.decodeImage(logoFile.readAsBytesSync());
  if (logo == null) {
    throw StateError('Could not decode assets/logo.png.');
  }

  final splashLogo = img.Image(width: 960, height: 960, numChannels: 4);
  img.fill(splashLogo, color: img.ColorRgba8(0, 0, 0, 0));

  final targetLogoSize = 300;
  final resizedLogo = img.copyResize(
    logo,
    width: logo.width >= logo.height ? targetLogoSize : null,
    height: logo.height > logo.width ? targetLogoSize : null,
    interpolation: img.Interpolation.cubic,
  );
  img.compositeImage(
    splashLogo,
    resizedLogo,
    dstX: (splashLogo.width - resizedLogo.width) ~/ 2,
    dstY: (splashLogo.height - resizedLogo.height) ~/ 2,
  );
  File('assets/splash_logo.png').writeAsBytesSync(img.encodePng(splashLogo));

  final branding = img.Image(width: 820, height: 180, numChannels: 4);
  img.fill(branding, color: img.ColorRgba8(0, 0, 0, 0));
  final white = img.ColorRgb8(255, 255, 255);

  img.drawString(
    branding,
    'Fairlife',
    font: img.arial48,
    x: 0,
    y: 20,
    color: white,
  );
  img.drawString(
    branding,
    'Benefit Benefit Swaziland.',
    font: img.arial24,
    x: 2,
    y: 92,
    color: white,
  );
  File('assets/splash_branding.png').writeAsBytesSync(img.encodePng(branding));
}
