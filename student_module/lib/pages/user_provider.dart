// user_provider.dart
// ignore_for_file: use_build_context_synchronously

import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:student_module/pages/login_page.dart';

class UserProvider extends ChangeNotifier {
  int _admissionNumber = 0;
  String _token = "";
  dynamic _user;
  dynamic _course;

  int get admissionNumber => _admissionNumber;
  String get token => _token;
  dynamic get user => _user;
  dynamic get course => _course;

  void setUserDetails(
      int admissionNumber, String token, dynamic user, dynamic course) {
    _admissionNumber = admissionNumber;
    _token = token;
    _user = user;
    _course = course;
    notifyListeners();
  }

  void setAdmissionNumber(int admissionNumber) {
    _admissionNumber = admissionNumber;
    notifyListeners();
  }

  void setToken(String token) {
    _token = token;
    notifyListeners();
  }

  void setUser(dynamic user) {
    _user = user;
    notifyListeners();
  }

  void setCourse(dynamic course) {
    _course = course;
    notifyListeners();
  }

  Future<void> loadUserDataFromSharedPreferences(BuildContext context) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    int? admissionNumber = prefs.getInt('admissionNumber');
    String? token = prefs.getString('token');
    dynamic user = jsonDecode(prefs.getString('user') ?? "{}");
    dynamic course = jsonDecode(prefs.getString('course') ?? "{}");

    // Check for null values and set user data
    if (admissionNumber != null &&
        token != null &&
        user != null &&
        course != null) {
      setUserDetails(admissionNumber, token, user, course);
    } else {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text('Error'),
            content:
                const Text('Failed to load user data. Please log in again.'),
            actions: [
              TextButton(
                onPressed: () {
                  // Log the user out and redirect to the login screen
                  logoutUser(context);
                  Navigator.of(context).pop(); // Close the dialog
                },
                child: const Text('OK'),
              ),
            ],
          );
        },
      );
    }
    notifyListeners();
  }

  void logoutUser(BuildContext context) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.remove('isLoggedIn');

    // Clear user details in the provider
    logout();
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(
        builder: (context) => const LoginPage(),
      ),
      (route) => false, // This removes all existing routes from the stack
    );
  }

  void logout() {
    _admissionNumber = 0;
    _token = "";
    _user = null;
    _course = null;
    notifyListeners();
  }
}
