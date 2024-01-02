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

  @override
  void initState() {
    super.initState();
    fetchAttendanceRecords();
  }

  Future<void> fetchAttendanceRecords() async {
    var userProvider = Provider.of<UserProvider>(context, listen: false);

    try {
      final response = await http.get(
        Uri.parse(
            'https://c000-41-90-185-67.ngrok-free.app/api/get-attendance-records/${widget.admissionNumber}/${widget.lectureId}/${widget.groupId}'),
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
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Attendance Records'),
        backgroundColor: Colors.blue,
      ),
      body: ListView.builder(
        itemCount: attendanceRecords.length,
        itemBuilder: (context, index) {
          final record = attendanceRecords[index];
          final DateTime dateTime = DateTime.parse(record['date']);
          final String formattedDate = _formatDate(dateTime);

          return Column(
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
                          fontSize: 16, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Total Hours: ${calculateTotalHoursSum()}',
                      style: const TextStyle(fontSize: 16),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 10),
              ListTile(
                title: Text('Date: $formattedDate'),
                subtitle: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text('Start Time: ${formatTime(record['start_time'])}'),
                        const SizedBox(width: 10),
                        Text('End Time: ${formatTime(record['end_time'])}'),
                      ],
                    ),
                    Text(
                        'Total Hours: ${calculateTotalHours(record['start_time'], record['end_time'])}'),
                    Text(
                      record['student_attendance'][0]['is_present'] == 1
                          ? 'Present'
                          : 'Absent',
                      style: TextStyle(
                        color:
                            record['student_attendance'][0]['is_present'] == 1
                                ? Colors.green
                                : Colors.red,
                      ),
                    ),
                    // Add more details as needed
                  ],
                ),
              ),
            ],
          );
        },
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

  int calculateTotalHours(String startTime, String endTime) {
    DateTime startDateTime = DateTime.parse('2000-01-01 $startTime');
    DateTime endDateTime = DateTime.parse('2000-01-01 $endTime');

    Duration difference = endDateTime.difference(startDateTime);
    int totalHours = difference.inMinutes ~/ 60.0;

    return totalHours;
  }

  int calculateTotalHoursSum() {
    int totalHoursSum = 0;

    for (var record in attendanceRecords) {
      String startTime = record['start_time'];
      String endTime = record['end_time'];

      DateTime startDateTime = DateTime.parse('2000-01-01 $startTime');
      DateTime endDateTime = DateTime.parse('2000-01-01 $endTime');

      Duration difference = endDateTime.difference(startDateTime);
      int totalHours = difference.inMinutes ~/ 60;

      totalHoursSum += totalHours;
    }

    return totalHoursSum;
  }
}
