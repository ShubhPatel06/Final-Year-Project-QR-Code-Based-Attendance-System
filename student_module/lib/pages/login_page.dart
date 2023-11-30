// ignore_for_file: use_build_context_synchronously, library_private_types_in_public_api

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'home_screen.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'user_provider.dart'; // Import the UserProvider class
import '../components/progress_dialog_component.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController admissionController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();

  @override
  void initState() {
    super.initState();
  }

  Future<void> loginUser(
      int admissionNumber, String password, UserProvider userProvider) async {
    ProgressDialogComponent progressDialog =
        ProgressDialogComponent(context, 'Logging in...');
    progressDialog.show(); // Show the progress dialog

    // final Uri loginUri = Uri.parse('http://10.0.2.2:8000/api/login');
    final Uri loginUri =
        Uri.parse('https://014d-41-90-177-116.ngrok-free.app/api/login');

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
      print('Login successful');
      print('Token: ${responseData['token']}');
      print('User: ${responseData['user']}');

      // Set user details in the provider
      userProvider.setUserDetails(
        responseData['admission_number'],
        responseData['token'],
        responseData['user'],
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

      print('Login failed: ${errorData['error']}');
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
                style: TextStyle(color: Colors.grey.shade700, fontSize: 40),
              ),

              const SizedBox(height: 20),

              // Text
              Text(
                "Login",
                style: TextStyle(color: Colors.grey.shade700, fontSize: 30),
              ),

              const SizedBox(height: 25),

              // Text field
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                child: TextField(
                  controller: admissionController,
                  keyboardType:
                      TextInputType.number, // Set input type to number
                  decoration: InputDecoration(
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade500),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade900),
                    ),
                    fillColor: Colors.grey.shade200,
                    filled: true,
                    hintText: 'Admission Number',
                  ),
                ),
              ),

              const SizedBox(height: 25),

              // Password Field
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                child: TextField(
                  controller: passwordController,
                  obscureText: true,
                  decoration: InputDecoration(
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade500),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(25),
                      borderSide: BorderSide(color: Colors.grey.shade900),
                    ),
                    fillColor: Colors.grey.shade200,
                    filled: true,
                    hintText: 'Password',
                  ),
                ),
              ),

              const SizedBox(height: 25),

              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 25.0),
                child: ElevatedButton(
                  onPressed: () {
                    int admissionNumber = int.parse(admissionController.text);
                    String password = passwordController.text;

                    // Access the UserProvider using Provider.of
                    var userProvider =
                        Provider.of<UserProvider>(context, listen: false);

                    // Call the loginUser function here
                    loginUser(admissionNumber, password, userProvider);
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
