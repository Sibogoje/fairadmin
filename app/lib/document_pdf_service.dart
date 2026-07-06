import 'dart:io';
import 'dart:typed_data';

import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;

class RequestDocument {
  const RequestDocument({required this.path, required this.label});

  final String path;
  final String label;
}

class DocumentPdfService {
  Future<Uint8List> buildCombinedPdf({
    required List<RequestDocument> requestLetters,
    required List<RequestDocument> supportingDocuments,
  }) async {
    final pdf = pw.Document();
    final allDocuments = [...requestLetters, ...supportingDocuments];

    for (final document in allDocuments) {
      final bytes = await File(document.path).readAsBytes();
      final image = pw.MemoryImage(bytes);
      pdf.addPage(
        pw.Page(
          pageFormat: PdfPageFormat.a4,
          margin: const pw.EdgeInsets.all(24),
          build: (context) => pw.Column(
            crossAxisAlignment: pw.CrossAxisAlignment.stretch,
            children: [
              pw.Text(document.label, style: pw.TextStyle(fontSize: 13, fontWeight: pw.FontWeight.bold)),
              pw.SizedBox(height: 12),
              pw.Expanded(child: pw.Center(child: pw.Image(image, fit: pw.BoxFit.contain))),
            ],
          ),
        ),
      );
    }

    return pdf.save();
  }
}