import 'package:flutter/material.dart';
import 'pages/login_page.dart';
import 'package:provider/provider.dart';
import 'pages/user_provider.dart'; // Import the UserProvider class

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (context) => UserProvider()),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Your App',
      theme: ThemeData(
          // Your theme settings
          ),
      home: const LoginPage(),
    );
  }
}
