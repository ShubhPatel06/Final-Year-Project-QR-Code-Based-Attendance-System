// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'user_provider.dart';
import 'login_page.dart';
import '../components/progress_dialog_component.dart';
import 'qr_code_scanner.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    var userProvider = Provider.of<UserProvider>(context);
    var progressDialog = ProgressDialogComponent(context, 'Logging out...');

    Future<void> logoutUser(String token) async {
      // final Uri logoutUri = Uri.parse('http://10.0.2.2:8000/api/logout');
      final Uri logoutUri =
          Uri.parse('https://73f8-41-90-186-173.ngrok-free.app/api/logout');

      try {
        final response = await http.post(
          logoutUri,
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $token',
          },
        );

        if (response.statusCode == 200) {
          print('Logout successful');
          // Clear user details in the provider
          userProvider.logout();

          progressDialog.hide();

          // Navigate back to the login page
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(
              builder: (context) => const LoginPage(),
            ),
          );
        } else {
          print('Logout failed');
          progressDialog.hide();
        }
      } catch (error) {
        print('Error during logout: $error');
        progressDialog.hide();
      }
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
        actions: [
          IconButton(
            icon: const Icon(Icons.qr_code),
            onPressed: () async {
              // Show QR code scanner
              await Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const QRCodeScannerPage(),
                ),
              );
            },
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              // Show the progress dialog
              progressDialog.show();

              // Call the logout API endpoint
              await logoutUser(userProvider.token);
            },
          ),
        ],
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Text('Welcome to the Home Screen!'),
            Text('Admission Number: ${userProvider.admissionNumber}'),
          ],
        ),
      ),
    );
  }
}
