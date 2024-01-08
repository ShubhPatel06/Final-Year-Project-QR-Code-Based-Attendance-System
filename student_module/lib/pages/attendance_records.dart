// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:student_module/pages/user_provider.dart';

class AttendanceRecordsScreen extends StatefulWidget {
  final int lectureId;
  final int admissionNumber;
  final int groupId;

  const AttendanceRecordsScreen({
    Key? key,
    required this.lectureId,
    required this.admissionNumber,
    required this.groupId,
  }) : super(key: key);

  @override
  _AttendanceRecordsScreenState createState() =>
      _AttendanceRecordsScreenState();
}

class _AttendanceRecordsScreenState extends State<AttendanceRecordsScreen> {
  List<Map<String, dynamic>> attendanceRecords = [];
  Map<String, dynamic> lectureDetails = {};
  bool isLoading = false;
  int presentHours = 0;
  int absentHours = 0;
  double absentPercent = 0;

  @override
  void initState() {
    super.initState();
    fetchAttendanceRecords();
  }

  Future<void> fetchAttendanceRecords() async {
    setState(() {
      isLoading = true;
    });
    var userProvider = Provider.of<UserProvider>(context, listen: false);

    try {
      final response = await http.get(
        Uri.parse(
            'https://c778-41-90-184-100.ngrok-free.app/api/get-attendance-records/${userProvider.admissionNumber}/${widget.lectureId}/${widget.groupId}'),
        headers: {
          'Authorization': 'Bearer ${userProvider.token}',
        },
      );

      if (response.statusCode == 200) {
        final parsedResponse =
            jsonDecode(response.body) as Map<String, dynamic>;

        setState(() {
          attendanceRecords = List<Map<String, dynamic>>.from(
              parsedResponse['AttendanceRecord']);
          lectureDetails = attendanceRecords.isNotEmpty
              ? Map<String, dynamic>.from(attendanceRecords[0]['lecture'])
              : {};
          presentHours = parsedResponse['presentHours'];
          absentHours = parsedResponse['absentHours'];
          absentPercent = parsedResponse['absentPercent'].toDouble();
          isLoading = false;
        });
      } else {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content: const Text(
                  'Failed to fetch attendance records. Please try again.'),
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
      setState(() {
        isLoading = false;
      });
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Attendance Records',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Colors.blue,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(),
            )
          : attendanceRecords.isEmpty
              ? const Center(
                  child: Text(
                    'No attendance records',
                    style: TextStyle(fontSize: 25),
                  ),
                )
              : Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            '${lectureDetails['lecture_code']} - ${lectureDetails['lecture_name']}',
                            style: const TextStyle(
                                fontSize: 17, fontWeight: FontWeight.bold),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Hours Present: $presentHours',
                            style: const TextStyle(fontSize: 16),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Hours Absent: $absentHours',
                            style: const TextStyle(fontSize: 16),
                          ),
                          const SizedBox(height: 8),
                          Row(
                            children: [
                              const Text(
                                'Absent Percentage: ',
                                style: TextStyle(fontSize: 16),
                              ),
                              Text(
                                '$absentPercent%',
                                style: const TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 10),
                    Expanded(
                      child: ListView.builder(
                        itemCount: attendanceRecords.length,
                        itemBuilder: (context, index) {
                          final record = attendanceRecords[index];
                          final DateTime dateTime =
                              DateTime.parse(record['date']);
                          final String formattedDate = _formatDate(dateTime);

                          return Column(
                            children: [
                              Container(
                                margin: const EdgeInsets.symmetric(
                                    vertical: 0, horizontal: 15),
                                decoration: BoxDecoration(
                                  color: Colors.grey[100],
                                  borderRadius: BorderRadius.circular(10),
                                ),
                                padding: const EdgeInsets.all(1),
                                child: ListTile(
                                  title: Text(
                                    formattedDate,
                                    style: const TextStyle(
                                        fontWeight: FontWeight.bold),
                                  ),
                                  subtitle: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Text(
                                            'Start Time: ${formatTime(record['start_time'])}',
                                            style: const TextStyle(
                                                fontWeight: FontWeight.bold),
                                          ),
                                          const SizedBox(width: 10),
                                          Text(
                                            'End Time: ${formatTime(record['end_time'])}',
                                            style: const TextStyle(
                                                fontWeight: FontWeight.bold),
                                          ),
                                        ],
                                      ),
                                      Text(
                                        'Hours: ${record['student_attendance'][0]['hours']}',
                                        style: const TextStyle(
                                            fontWeight: FontWeight.w600),
                                      ),
                                      Text(
                                        record['student_attendance'][0]
                                                    ['is_present'] ==
                                                1
                                            ? 'Present'
                                            : record['student_attendance'][0]
                                                        ['is_present'] ==
                                                    2
                                                ? 'Absent'
                                                : 'Not Marked',
                                        style: TextStyle(
                                          color: record['student_attendance'][0]
                                                      ['is_present'] ==
                                                  1
                                              ? Colors.green
                                              : record['student_attendance'][0]
                                                          ['is_present'] ==
                                                      2
                                                  ? Colors.red
                                                  : Colors
                                                      .amber, // Yellow-orangish color
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ],
                                  ),
                                  trailing: Icon(
                                    record['student_attendance'][0]
                                                ['is_present'] ==
                                            1
                                        ? Icons.check_circle
                                        : record['student_attendance'][0]
                                                    ['is_present'] ==
                                                2
                                            ? Icons.cancel
                                            : Icons.remove, // 'dash' icon
                                    color: record['student_attendance'][0]
                                                ['is_present'] ==
                                            1
                                        ? Colors.green
                                        : record['student_attendance'][0]
                                                    ['is_present'] ==
                                                2
                                            ? Colors.red
                                            : Colors
                                                .amber, // Yellow-orangish color for 'dash' icon
                                    size: 45,
                                  ),
                                ),
                              ),
                              const SizedBox(
                                  height: 16), // Add SizedBox after each record
                            ],
                          );
                        },
                      ),
                    ),
                  ],
                ),
    );
  }

  String _formatDate(DateTime date) {
    final String dayOfMonth = _getDayOfMonthSuffix(date.day);
    final String month = _getMonthName(date.month);

    return '${getDayOfWeek(date.weekday)} ${date.day}$dayOfMonth $month, ${date.year}';
  }

  String _getDayOfMonthSuffix(int day) {
    if (day >= 11 && day <= 13) {
      return 'th';
    }

    switch (day % 10) {
      case 1:
        return 'st';
      case 2:
        return 'nd';
      case 3:
        return 'rd';
      default:
        return 'th';
    }
  }

  String _getMonthName(int month) {
    switch (month) {
      case DateTime.january:
        return 'January';
      case DateTime.february:
        return 'February';
      case DateTime.march:
        return 'March';
      case DateTime.april:
        return 'April';
      case DateTime.may:
        return 'May';
      case DateTime.june:
        return 'June';
      case DateTime.july:
        return 'July';
      case DateTime.august:
        return 'August';
      case DateTime.september:
        return 'September';
      case DateTime.october:
        return 'October';
      case DateTime.november:
        return 'November';
      case DateTime.december:
        return 'December';
      default:
        return '';
    }
  }

  String getDayOfWeek(int day) {
    switch (day) {
      case DateTime.monday:
        return 'Monday';
      case DateTime.tuesday:
        return 'Tuesday';
      case DateTime.wednesday:
        return 'Wednesday';
      case DateTime.thursday:
        return 'Thursday';
      case DateTime.friday:
        return 'Friday';
      case DateTime.saturday:
        return 'Saturday';
      case DateTime.sunday:
        return 'Sunday';
      default:
        return '';
    }
  }

  String formatTime(String time) {
    List<String> timeComponents = time.split(':');
    int hour = int.parse(timeComponents[0]);
    int minute = int.parse(timeComponents[1]);

    String formattedTime = '$hour:${minute == 0 ? '00' : minute}';
    return formattedTime;
  }
}
