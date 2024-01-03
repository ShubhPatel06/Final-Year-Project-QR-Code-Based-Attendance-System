// user_provider.dart
import 'package:flutter/material.dart';

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

  void logout() {
    _admissionNumber = 0;
    _token = "";
    _user = null;
    _course = null;
    notifyListeners();
  }
}
