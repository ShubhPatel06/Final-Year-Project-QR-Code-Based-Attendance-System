// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';
import 'package:student_module/pages/attendance_records.dart';
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
  bool isLoading = false;

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
    setState(() {
      isLoading = true;
    });

    try {
      final response = await http.get(
        Uri.parse(
            'https://5775-41-90-185-67.ngrok-free.app/api/get-groups/${userProvider.admissionNumber}'),
        headers: {
          'Authorization': 'Bearer ${userProvider.token}',
        },
      );

      if (response.statusCode == 200) {
        final parsedResponse =
            jsonDecode(response.body) as Map<String, dynamic>;
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
          selectedOption = options.last.value;
          isLoading = false;
        });
      } else {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content: const Text('Error fetching groups! Try again later.'),
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
        setState(() {
          isLoading = false;
        });
      }
    } catch (e) {
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
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    var userProvider = Provider.of<UserProvider>(context);
    var progressDialog = ProgressDialogComponent(context, 'Logging out...');

    Future<void> logoutUser(String token) async {
      final Uri logoutUri =
          Uri.parse('https://5775-41-90-185-67.ngrok-free.app/api/logout');

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
        title: const Text(
          'Home',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Colors.blue, // Adjust the color
        iconTheme: const IconThemeData(color: Colors.white),
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
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(),
            )
          : Container(
              color: Colors.white,
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 10.0),
                      child: Text(
                        'Welcome, ${userProvider.user?['first_name'] ?? 'Guest'} ${userProvider.user?['last_name'] ?? ''}',
                        style: TextStyle(
                          color: Colors.blue[500],
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),

                    // Course name
                    Text(
                      '${userProvider.course?['course_name']} (${userProvider.course?['course_code']})',
                      style: TextStyle(
                          color: Colors.grey[700],
                          fontSize: 18,
                          fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 10),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 15.0),
                      child: DropdownButton<String>(
                          isExpanded: true,
                          value: selectedOption,
                          icon: const Icon(Icons.arrow_drop_down),
                          iconSize: 24,
                          elevation: 16,
                          style: const TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                          ),
                          underline: Container(
                            height: 2,
                            color: Colors.blue[500],
                          ),
                          onChanged: (value) {
                            setState(() {
                              selectedOption = value;
                            });
                            fetchContent(value!);
                          },
                          items: options),
                    ),
                    const SizedBox(height: 15),
                    if (lectureData.isNotEmpty)
                      Expanded(
                        child: ListView.builder(
                          itemCount: lectureData.length,
                          itemBuilder: (context, index) {
                            var data = lectureData[index];

                            return Material(
                              color: Colors.transparent,
                              child: InkWell(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) =>
                                          AttendanceRecordsScreen(
                                        lectureId: data['lecture']
                                            ['lecture_id'],
                                        admissionNumber:
                                            userProvider.admissionNumber,
                                        groupId: data['group']['group_id'],
                                      ),
                                    ),
                                  );
                                },
                                child: Container(
                                  margin:
                                      const EdgeInsets.symmetric(vertical: 8.0),
                                  decoration: BoxDecoration(
                                    color: Colors.grey[100],
                                    border:
                                        Border.all(color: Colors.grey.shade300),
                                    borderRadius: BorderRadius.circular(8.0),
                                  ),
                                  child: ListTile(
                                    title: Text(
                                      '${data['lecture']['lecture_code']} - ${data['lecture']['lecture_name']}',
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                        fontSize: 15,
                                      ),
                                    ),
                                    subtitle: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          'Lecturer: ${data['lecture']['lecturer_allocation'][0]['lecturer']['user']['first_name']} ${data['lecture']['lecturer_allocation'][0]['lecturer']['user']['last_name']}',
                                          style: const TextStyle(fontSize: 14),
                                        ),
                                      ],
                                    ),
                                    trailing:
                                        const Icon(Icons.arrow_forward_ios),
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                      ),
                  ],
                ),
              ),
            ),
    );
  }

  Future<void> fetchContent(String optionValue) async {
    setState(() {
      isLoading = true;
    });

    final userProvider = Provider.of<UserProvider>(context, listen: false);

    try {
      final response = await http.get(
        Uri.parse(
            'https://5775-41-90-185-67.ngrok-free.app/api/get-lectures/${userProvider.admissionNumber}/$optionValue'),
        headers: {
          'Authorization': 'Bearer ${userProvider.token}',
        },
      );

      if (response.statusCode == 200) {
        final parsedResponse =
            jsonDecode(response.body) as Map<String, dynamic>;
        final lectureDataList = parsedResponse['data'] as List<dynamic>;

        setState(() {
          lectureData = List<Map<String, dynamic>>.from(lectureDataList);
          isLoading = false;
        });
      } else {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content: const Text(
                  'Failed to fetch data. An unexpected error occurred. Please try again.'),
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
        setState(() {
          isLoading = false;
        });
      }
    } catch (error) {
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
      setState(() {
        isLoading = false;
      });
    }
  }
}
