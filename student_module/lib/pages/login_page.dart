import 'package:flutter/material.dart';

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController admissionController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      body: SafeArea(
        child: Center(
          child: Column(
            children: [
              SizedBox(height: 50),

              // Logo
              Icon(
                Icons.lock,
                size: 100,
                color: Colors.blue[500],
              ),

              SizedBox(height: 50),

              // Text
              Text(
                "Attendance System",
                style: TextStyle(color: Colors.grey.shade700, fontSize: 40),
              ),

              SizedBox(height: 20),

              // Text
              Text(
                "Login",
                style: TextStyle(color: Colors.grey.shade700, fontSize: 30),
              ),

              SizedBox(height: 25),

              // Text field
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 25.0),
                child: TextField(
                  controller: admissionController,
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

              SizedBox(height: 25),

              // Password Field
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 25.0),
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

              SizedBox(height: 25),

              Padding(
                padding: EdgeInsets.symmetric(horizontal: 25.0),
                child: ElevatedButton(
                  onPressed: () {
                    // TODO: Add login functionality using the provided data
                    String admissionNumber = admissionController.text;
                    String password = passwordController.text;

                    // Add your authentication logic here
                    // You can make an API call to your Laravel backend for authentication
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue[500],
                    minimumSize:
                        Size(double.infinity, 50), // Set width and height
                  ),
                  child: Text(
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
