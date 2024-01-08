// ignore_for_file: use_build_context_synchronously, library_private_types_in_public_api

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'home_screen.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'user_provider.dart'; // Import the UserProvider class
import '../components/progress_dialog_component.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController admissionController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  bool isPasswordVisible = false;

  @override
  void initState() {
    super.initState();
  }

  bool isInputValid() {
    return admissionController.text.isNotEmpty &&
        passwordController.text.isNotEmpty;
  }

  Future<void> loginUser(
      int admissionNumber, String password, UserProvider userProvider) async {
    ProgressDialogComponent progressDialog =
        ProgressDialogComponent(context, 'Logging in...');
    progressDialog.show(); // Show the progress dialog

    // final Uri loginUri = Uri.parse('http://10.0.2.2:8000/api/login');
    final Uri loginUri =
        Uri.parse('https://c778-41-90-184-100.ngrok-free.app/api/login');

    try {
      final response = await http.post(
        loginUri,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'admission_number': admissionNumber,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        // Login successful, handle the response
        Map<String, dynamic> responseData = jsonDecode(response.body);
        SharedPreferences prefs = await SharedPreferences.getInstance();
        prefs.setBool('isLoggedIn', true);

        prefs.setInt('admissionNumber', responseData['admission_number']);
        prefs.setString('token', responseData['token']);
        prefs.setString('user', jsonEncode(responseData['user']));
        prefs.setString('course', jsonEncode(responseData['course']));

        // Set user details in the provider
        userProvider.setUserDetails(
          responseData['admission_number'],
          responseData['token'],
          responseData['user'],
          responseData['course'],
        );
        progressDialog.hide(); // Hide the progress dialog

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const HomeScreen()),
        );
      } else {
        // Login failed, handle the error
        Map<String, dynamic> errorData = jsonDecode(response.body);
        progressDialog.hide(); // Hide the progress dialog

        // Show error dialog
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content: Text('Login failed: ${errorData['error']}'),
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
      progressDialog.hide(); // Hide the progress dialog
      print(error);

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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: Center(
          child: Column(
            children: [
              const SizedBox(height: 50),

              // Logo
              Icon(
                Icons.lock,
                size: 100,
                color: Colors.blue[500],
              ),

              const SizedBox(height: 50),

              // Text
              Text(
                "Attendance System",
                style: TextStyle(color: Colors.grey.shade700, fontSize: 35),
              ),

              const SizedBox(height: 20),

              // Text
              Text(
                "Login",
                style: TextStyle(color: Colors.grey.shade700, fontSize: 30),
              ),

              const SizedBox(height: 25),

              // Text field
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                height: 60,
                child: TextFormField(
                  controller: admissionController,
                  keyboardType: TextInputType.number,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter admission number';
                    }
                    return null;
                  },
                  decoration: InputDecoration(
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade500),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade900),
                    ),
                    errorBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: const BorderSide(color: Colors.red),
                    ),
                    focusedErrorBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: const BorderSide(color: Colors.red),
                    ),
                    fillColor: Colors.grey.shade200,
                    filled: true,
                    hintText: 'Admission Number',
                    errorStyle: const TextStyle(
                      color: Colors.red,
                      fontStyle: FontStyle.italic,
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Password Field
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                height: 60,
                child: TextFormField(
                  controller: passwordController,
                  obscureText: !isPasswordVisible,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter password';
                    }
                    return null;
                  },
                  decoration: InputDecoration(
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade500),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade900),
                    ),
                    errorBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: const BorderSide(color: Colors.red),
                    ),
                    focusedErrorBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: const BorderSide(color: Colors.red),
                    ),
                    fillColor: Colors.grey.shade200,
                    filled: true,
                    hintText: 'Password',
                    suffixIcon: IconButton(
                      icon: Icon(
                        isPasswordVisible
                            ? Icons.visibility
                            : Icons.visibility_off,
                        color: Colors.grey.shade600,
                      ),
                      onPressed: () {
                        // Toggle the password visibility
                        setState(() {
                          isPasswordVisible = !isPasswordVisible;
                        });
                      },
                    ),
                    errorStyle: const TextStyle(
                      color: Colors.red,
                      fontStyle: FontStyle.italic,
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 25),

              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                child: ElevatedButton(
                  onPressed: () {
                    if (isInputValid()) {
                      int admissionNumber =
                          int.tryParse(admissionController.text) ??
                              0; // Handle invalid integer input
                      String password = passwordController.text;

                      // Access the UserProvider using Provider.of
                      var userProvider =
                          Provider.of<UserProvider>(context, listen: false);

                      // Call the loginUser function here
                      loginUser(admissionNumber, password, userProvider);
                    } else {
                      // Show an error message for empty input fields
                      showDialog(
                        context: context,
                        builder: (BuildContext context) {
                          return AlertDialog(
                            title: const Text('Error'),
                            content: const Text(
                                'Please enter both admission number and password.'),
                            actions: [
                              TextButton(
                                onPressed: () {
                                  Navigator.of(context)
                                      .pop(); // Close the error dialog
                                },
                                child: const Text('OK'),
                              ),
                            ],
                          );
                        },
                      );
                    }
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue[500],
                    minimumSize:
                        const Size(double.infinity, 50), // Set width and height
                  ),
                  child: const Text(
                    'Login',
                    style: TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
