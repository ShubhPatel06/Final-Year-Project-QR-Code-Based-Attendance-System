// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'user_provider.dart';
import '../components/progress_dialog_component.dart';

class ScannedDataFormPage extends StatelessWidget {
  final String scannedData;

  const ScannedDataFormPage({super.key, required this.scannedData});

  @override
  Widget build(BuildContext context) {
    Map<String, dynamic> decodedData = json.decode(scannedData);

    var userProvider = Provider.of<UserProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Scanned Data Form'),
        backgroundColor: Colors.blue, // Adjust the color
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
                    entry.key != 'Verification Token')
                .map((entry) {
              return _buildInfoTile(
                  entry.key, entry.value.toString(), Colors.grey.shade400);
            }).toList(),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          // Check for null or unexpected values
          if (decodedData.containsKey('Record ID') &&
              decodedData.containsKey('Verification Token')) {
            // Call the API to update attendance
            await _submitAttendance(
              context,
              userProvider.admissionNumber,
              decodedData['Record ID']!,
              decodedData['Verification Token']!,
            );
          } else {
            // Handle unexpected data
            print('Invalid QR code data');
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
            fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black),
      ),
      subtitle: Text(
        value,
        style: const TextStyle(fontSize: 14, color: Colors.white),
      ),
      tileColor: color,
    );
  }

  Future<void> _submitAttendance(
    BuildContext context,
    int admissionNumber,
    int recordId,
    String verificationToken,
  ) async {
    const apiUrl =
        'https://5c13-41-90-186-173.ngrok-free.app/api/update-attendance';

    try {
      ProgressDialogComponent progressDialog =
          ProgressDialogComponent(context, 'Submitting Attendance');

      // Show the progress dialog
      progressDialog.show();

      // Send request to update attendance
      final response = await http.post(
        Uri.parse(apiUrl),
        body: {
          'admission_number': admissionNumber.toString(),
          'record_id': recordId.toString(),
          'verification_token': verificationToken,
        },
      );

      // Hide the progress dialog
      progressDialog.hide();

      if (response.statusCode == 200) {
        // Handle successful response
        print('Attendance updated successfully');

        // Show success dialog
        showDialog(
          context: context,
          barrierDismissible: false,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Attendance Submitted'),
              content: const Text(
                  'Your attendance has been submitted successfully.'),
              actions: <Widget>[
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop(); // Close the success dialog
                    Navigator.of(context)
                        .pop(); // Navigate back to the home screen
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        // Handle error response
        print('Failed to update attendance: ${response.body}');

        // Show error dialog
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content:
                  const Text('Failed to submit attendance. Please try again.'),
              actions: [
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop(); // Close the error dialog
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      }
    } catch (error) {
      // Handle unexpected error
      print('Error submitting attendance: $error');

      // Show error dialog
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
                  Navigator.of(context).pop(); // Close the error dialog
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
