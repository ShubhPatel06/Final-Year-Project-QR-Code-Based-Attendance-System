import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:student_module/pages/home_screen.dart';
import 'pages/login_page.dart';
import 'package:provider/provider.dart';
import 'pages/user_provider.dart'; // Import the UserProvider class

// void main() {
//   runApp(
//     MultiProvider(
//       providers: [
//         ChangeNotifierProvider(create: (context) => UserProvider()),
//       ],
//       child: const MyApp(),
//     ),
//   );
// }

// class MyApp extends StatelessWidget {
//   const MyApp({super.key});

//   @override
//   Widget build(BuildContext context) {
//     return MaterialApp(
//       title: 'My Attendance App',
//       theme: ThemeData(
//         fontFamily: 'Poppins',
//       ),
//       home: const LoginPage(),
//     );
//   }
// }

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  SharedPreferences prefs = await SharedPreferences.getInstance();
  bool? isLoggedIn = prefs.getBool('isLoggedIn');

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (context) => UserProvider()),
      ],
      child: MyApp(isLoggedIn: isLoggedIn ?? false), // Provide a default value
    ),
  );
}

class MyApp extends StatelessWidget {
  final bool isLoggedIn;

  const MyApp({required this.isLoggedIn, Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final userProvider = Provider.of<UserProvider>(context);

    // Load user data from SharedPreferences if the user is logged in
    if (isLoggedIn) {
      userProvider.loadUserDataFromSharedPreferences(context);
    }
    return MaterialApp(
      title: 'My Attendance App',
      theme: ThemeData(
        fontFamily: 'Poppins',
      ),
      home: isLoggedIn ? const HomeScreen() : const LoginPage(),
    );
  }
}
