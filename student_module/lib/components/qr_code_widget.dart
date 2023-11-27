import 'dart:io';
import 'package:flutter/material.dart';
import 'package:qr_code_scanner/qr_code_scanner.dart';
import '../pages/scanned_data.dart';

class QRCodeScannerWidget extends StatefulWidget {
  final VoidCallback onScanned;

  const QRCodeScannerWidget({required this.onScanned, Key? key})
      : super(key: key);

  @override
  _QRCodeScannerWidgetState createState() => _QRCodeScannerWidgetState();
}

class _QRCodeScannerWidgetState extends State<QRCodeScannerWidget> {
  final GlobalKey qrKey = GlobalKey(debugLabel: 'QR');
  Barcode? result;
  QRViewController? controller;

  @override
  void reassemble() {
    super.reassemble();
    if (Platform.isAndroid) {
      controller!.pauseCamera();
    } else if (Platform.isIOS) {
      controller!.resumeCamera();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        alignment: Alignment.center,
        children: <Widget>[
          _buildQrView(context),
          if (result != null) _buildResultView(),
        ],
      ),
    );
  }

  Widget _buildQrView(BuildContext context) {
    return QRView(
      key: qrKey,
      onQRViewCreated: _onQRViewCreated,
      overlay: QrScannerOverlayShape(
        borderColor: Colors.white,
        borderRadius: 10,
        borderLength: 30,
        borderWidth: 10,
        cutOutSize: MediaQuery.of(context).size.width * 0.8,
      ),
    );
  }

  Widget _buildResultView() {
    return Positioned(
      bottom: 16.0,
      child: Container(
        padding: EdgeInsets.all(8.0),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(8.0),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              'Barcode Type: ${result!.format.name}',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            Text(
              'Data: ${result!.code ?? 'No Data'}',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            ElevatedButton(
              onPressed: () {
                widget.onScanned();
              },
              child: Text('Submit'),
            ),
          ],
        ),
      ),
    );
  }

  void _onQRViewCreated(QRViewController controller) {
    this.controller = controller;

    controller.scannedDataStream.listen((scanData) async {
      setState(() {
        result = scanData;
      });

      // Print the scanned data to the console
      print("Scanned Data: ${result!.code}");

      // Close the scanner after scanning
      controller.pauseCamera();

      // Navigate to the form page with the scanned data
      await Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) =>
              ScannedDataFormPage(scannedData: result!.code ?? ''),
        ),
      );
    });
  }

  @override
  void dispose() {
    controller?.dispose();
    super.dispose();
  }
}
