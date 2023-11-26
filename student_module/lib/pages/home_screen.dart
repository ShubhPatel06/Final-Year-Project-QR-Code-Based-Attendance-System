import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'user_provider.dart';
import 'login_page.dart';
import '../components/progress_dialog_component.dart';

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    // Access the UserProvider using Provider.of
    var userProvider = Provider.of<UserProvider>(context);
    var progressDialog = ProgressDialogComponent(context, 'Logging out...');

    return Scaffold(
      appBar: AppBar(
        title: Text('Home'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              // Show the progress dialog
              progressDialog.show();
              // Access the UserProvider using Provider.of
              var userProvider =
                  Provider.of<UserProvider>(context, listen: false);

              // Call the logout API endpoint
              await logoutUser(userProvider.token);

              // Navigate back to the login page
              Navigator.of(context).pushReplacement(
                MaterialPageRoute(
                  builder: (context) => LoginPage(),
                ),
              );

              // Clear user details in the provider
              userProvider.logout();

              // Hide the progress dialog
              progressDialog.hide();
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

  Future<void> logoutUser(String token) async {
    final Uri logoutUri = Uri.parse(
        'http://10.0.2.2:8000/api/logout'); // Replace with your network ip when u need to run on physical device

    final response = await http.post(
      logoutUri,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      print('Logout successful');
    } else {
      print('Logout failed');
    }
  }
}
