// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'user_provider.dart';
import '../components/progress_dialog_component.dart';
import 'home_screen.dart';
import 'package:dart_ipify/dart_ipify.dart';

class ScannedDataFormPage extends StatelessWidget {
  final String scannedData;

  const ScannedDataFormPage({Key? key, required this.scannedData})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    Map<String, dynamic> decodedData = json.decode(scannedData);

    var userProvider = Provider.of<UserProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Scanned Data',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Colors.blue,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: ListView(
          children: [
            _buildInfoTile('Admission Number',
                userProvider.admissionNumber.toString(), Colors.blue.shade200),
            const SizedBox(height: 16),
            ...decodedData.entries
                .where((entry) =>
                    entry.key != 'Record ID' &&
                    entry.key != 'Verification Token' &&
                    entry.key != 'IP Address')
                .map((entry) {
              return _buildInfoTile(
                  entry.key, entry.value.toString(), Colors.grey.shade400);
            }).toList(),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          if (decodedData['Session Type'] == 'physical') {
            final ipv4 = await Ipify.ipv4();
            await _submitAttendance(context, userProvider.admissionNumber,
                decodedData['Record ID']!, decodedData['Verification Token']!,
                ipv4: ipv4);
          } else {
            await _submitAttendance(
              context,
              userProvider.admissionNumber,
              decodedData['Record ID']!,
              decodedData['Verification Token']!,
            );
          }
        },
        backgroundColor: Colors.blue,
        child: const Icon(Icons.send),
      ),
    );
  }

  Widget _buildInfoTile(String label, String value, Color color) {
    return ListTile(
      title: Text(
        label,
        style: const TextStyle(
            fontSize: 16, fontWeight: FontWeight.w600, color: Colors.black),
      ),
      subtitle: Text(
        value,
        style: const TextStyle(
            fontSize: 14, fontWeight: FontWeight.w700, color: Colors.white),
      ),
      tileColor: color,
    );
  }

  Future<void> _submitAttendance(
    BuildContext context,
    int admissionNumber,
    int recordId,
    String verificationToken, {
    String? ipv4,
  }) async {
    final userProvider = Provider.of<UserProvider>(context, listen: false);

    const apiUrl =
        'https://78c5-41-90-184-100.ngrok-free.app/api/update-attendance';

    try {
      ProgressDialogComponent progressDialog =
          ProgressDialogComponent(context, 'Submitting Attendance');

      progressDialog.show();

      final requestBody = {
        'admission_number': admissionNumber.toString(),
        'record_id': recordId.toString(),
        'verification_token': verificationToken,
      };

      if (ipv4 != null) {
        requestBody['ipv4'] = ipv4;
      }

      // Send request to update attendance
      final response = await http.post(Uri.parse(apiUrl),
          headers: {
            'Authorization': 'Bearer ${userProvider.token}',
          },
          body: requestBody);

      progressDialog.hide();

      if (response.statusCode == 200) {
        // Show success dialog
        showDialog(
          context: context,
          barrierDismissible: false,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Attendance Submitted'),
              content: const Text(
                  'Your attendance has been submitted successfully. \nPermission to scan a QR Code or Logging out is disabled for a few minutes.'),
              actions: <Widget>[
                TextButton(
                  onPressed: () {
                    Navigator.pop(context); // Close the success dialog
                    Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const HomeScreen(
                          areButtonsDisabled: true,
                          remainingDisableTime: 1 * 60, // Disable for 1 minute
                        ),
                      ),
                      (route) => false, // Remove all previous routes
                    );
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        Map<String, dynamic> errorData = jsonDecode(response.body);

        // Show error dialog with backend response message
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content:
                  Text('Failed to submit attendance. ${errorData['error']}'),
              actions: [
                TextButton(
                  onPressed: () {
                    Navigator.pop(context); // Close the error dialog
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      }
    } catch (error) {
      // Show generic error dialog for exceptions
      print(error);
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text('Error'),
            content:
                const Text('An unexpected error occurred. Please try again.'),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.pop(context); // Close the error dialog
                },
                child: const Text('OK'),
              ),
            ],
          );
        },
      );
    }
  }
}
