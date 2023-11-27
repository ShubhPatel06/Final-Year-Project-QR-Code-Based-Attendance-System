import 'package:flutter/material.dart';
import '../components/qr_code_widget.dart';

class QRCodeScannerPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('QR Code Scanner'),
      ),
      body: QRCodeScannerWidget(
        onScanned: () {
          Navigator.pop(context); // Pop the scanner page
        },
      ),
    );
  }
}
