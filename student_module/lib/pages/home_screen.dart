// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'user_provider.dart';
import 'login_page.dart';
import '../components/progress_dialog_component.dart';
import 'qr_code_scanner.dart';
import 'dart:async';
import 'dart:convert';

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

  String? selectedOption;
  List<DropdownMenuItem<String>> options = [];
  List<Map<String, dynamic>> lectureData = [];

  @override
  void initState() {
    super.initState();
    areButtonsDisabled = widget.areButtonsDisabled;
    remainingDisableTime = widget.remainingDisableTime;
    _startTimer();

    Future.delayed(Duration.zero, () {
      fetchOptions().then((_) {
        // Call fetchContent with the default selected option
        fetchContent(selectedOption!);
      });
    });
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

  Future<void> fetchOptions() async {
    final userProvider = Provider.of<UserProvider>(context, listen: false);
    final response = await http.get(Uri.parse(
        'https://73da-41-90-180-216.ngrok-free.app/api/get-groups/${userProvider.admissionNumber}'));

    final parsedResponse = jsonDecode(response.body) as Map<String, dynamic>;
    final parsedGroups = parsedResponse['groups'] as List<dynamic>;

    // Sort groups by group_name
    parsedGroups.sort((a, b) => a['group']['group_name']
        .toString()
        .compareTo(b['group']['group_name'].toString()));

    setState(() {
      options = parsedGroups
          .map((group) => DropdownMenuItem(
                value: group['group_id'].toString(),
                child: Text(
                  '${group['group']['group_name']} (Year ${group['group']['year']}, Sem ${group['group']['semester']})',
                ),
              ))
          .toList();
      // Optionally set a default selected option
      selectedOption = options.first.value;
    });
  }

  @override
  Widget build(BuildContext context) {
    var userProvider = Provider.of<UserProvider>(context);
    var progressDialog = ProgressDialogComponent(context, 'Logging out...');

    Future<void> logoutUser(String token) async {
      final Uri logoutUri =
          Uri.parse('https://73da-41-90-180-216.ngrok-free.app/api/logout');

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

    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
        backgroundColor: Colors.blue, // Adjust the color
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
          children: [
            DropdownButton<String>(
              value: selectedOption,
              items: options,
              onChanged: (value) {
                setState(() {
                  selectedOption = value;
                });
                fetchContent(value!);
              },
            ),
            const SizedBox(height: 20),
            if (lectureData.isNotEmpty)
              Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  for (var data in lectureData)
                    Column(
                      children: [
                        Text(
                          '${data['lecture']['lecture_code']} - ${data['lecture']['lecture_name']}',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 18,
                          ),
                        ),
                        Text(
                          'Lecturer: ${data['lecture']['lecturer_allocation'][0]['lecturer']['user']['first_name']} ${data['lecture']['lecturer_allocation'][0]['lecturer']['user']['last_name']}',
                          style: const TextStyle(fontSize: 16),
                        ),
                        const SizedBox(height: 10),
                        Text(
                          'Total Hours: ${data['lecture']['total_hours']}',
                          style: const TextStyle(fontSize: 16),
                        ),
                        const Text(
                          'Hours Absent: ',
                          style: TextStyle(fontSize: 16),
                        ),
                        const SizedBox(height: 20),
                      ],
                    ),
                ],
              ),
          ],
        ),
      ),
    );
  }

  Future<void> fetchContent(String optionValue) async {
    final userProvider = Provider.of<UserProvider>(context, listen: false);

    try {
      final response = await http.get(Uri.parse(
          'https://73da-41-90-180-216.ngrok-free.app/api/get-lectures/${userProvider.admissionNumber}/$optionValue'));

      if (response.statusCode == 200) {
        final parsedResponse =
            jsonDecode(response.body) as Map<String, dynamic>;
        final lectureDataList = parsedResponse['data'] as List<dynamic>;

        setState(() {
          lectureData = List<Map<String, dynamic>>.from(lectureDataList);
        });
      } else {
        throw Exception('Failed to fetch data');
      }
    } catch (error) {
      print(error);
    }
  }
}
