// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'user_provider.dart';
import 'login_page.dart';
import '../components/progress_dialog_component.dart';
import 'qr_code_scanner.dart';
import 'dart:async';

class HomeScreen extends StatefulWidget {
  final bool areButtonsDisabled;
  final int remainingDisableTime;

  const HomeScreen({
    super.key,
    this.areButtonsDisabled = false,
    this.remainingDisableTime = 0,
  });

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool areButtonsDisabled = false;
  int remainingDisableTime = 0;

  @override
  void initState() {
    super.initState();
    areButtonsDisabled = widget.areButtonsDisabled;
    remainingDisableTime = widget.remainingDisableTime;
    _startTimer();
  }

  void _startTimer() {
    if (areButtonsDisabled && remainingDisableTime > 0) {
      Timer(const Duration(seconds: 1), () {
        setState(() {
          remainingDisableTime--;
          if (remainingDisableTime == 0) {
            areButtonsDisabled = false;
          } else {
            _startTimer();
          }
        });
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    var userProvider = Provider.of<UserProvider>(context);
    var progressDialog = ProgressDialogComponent(context, 'Logging out...');

    Future<void> logoutUser(String token) async {
      final Uri logoutUri =
          Uri.parse('https://2321-41-90-180-216.ngrok-free.app/api/logout');

      try {
        final response = await http.post(
          logoutUri,
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $token',
          },
        );

        if (response.statusCode == 200) {
          // Clear user details in the provider
          userProvider.logout();

          progressDialog.hide();

          Navigator.pushAndRemoveUntil(
            context,
            MaterialPageRoute(
              builder: (context) => const LoginPage(),
            ),
            (route) => false, // This removes all existing routes from the stack
          );
        } else {
          progressDialog.hide();

          // Show error dialog
          showDialog(
            context: context,
            builder: (BuildContext context) {
              return AlertDialog(
                title: const Text('Error'),
                content: Text('Logout failed: ${response.body}'),
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
        progressDialog.hide();

        // Show error dialog for unexpected errors
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

    // return Scaffold(
    //   appBar: AppBar(
    //     title: const Text('Home'),
    //     actions: [
    //       IconButton(
    //         icon: const Icon(Icons.qr_code),
    //         onPressed: areButtonsDisabled
    //             ? null
    //             : () async {
    //                 await Navigator.push(
    //                   context,
    //                   MaterialPageRoute(
    //                     builder: (context) => const QRCodeScannerPage(),
    //                   ),
    //                 );
    //               },
    //       ),
    //       IconButton(
    //         icon: const Icon(Icons.logout),
    //         onPressed: areButtonsDisabled
    //             ? null
    //             : () async {
    //                 // Show the progress dialog
    //                 progressDialog.show();

    //                 // Call the logout API endpoint
    //                 await logoutUser(userProvider.token);
    //               },
    //       ),
    //     ],
    //   ),
    //   body: Center(
    //     child: Column(
    //       mainAxisAlignment: MainAxisAlignment.center,
    //       children: [
    //         const Text('Welcome to the Home Screen!'),
    //         Text('Admission Number: ${userProvider.admissionNumber}'),
    //       ],
    //     ),
    //   ),
    // );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
        actions: [
          IconButton(
            icon: const Icon(Icons.qr_code),
            onPressed: areButtonsDisabled
                ? null
                : () async {
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
            onPressed: areButtonsDisabled
                ? null
                : () async {
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
