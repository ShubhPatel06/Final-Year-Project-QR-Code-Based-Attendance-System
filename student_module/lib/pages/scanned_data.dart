// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'user_provider.dart';
import '../components/progress_dialog_component.dart';
import 'home_screen.dart';
import 'package:geolocator/geolocator.dart';
import 'dart:math';

class ScannedDataFormPage extends StatelessWidget {
  final String scannedData;

  const ScannedDataFormPage({Key? key, required this.scannedData})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    Map<String, dynamic> decodedData = json.decode(scannedData);

    var userProvider = Provider.of<UserProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Scanned Data Form'),
        backgroundColor: Colors.blue, // Adjust the color
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: ListView(
          children: [
            _buildInfoTile('Admission Number',
                userProvider.admissionNumber.toString(), Colors.blue.shade200),
            const SizedBox(height: 16),
            ...decodedData.entries
                .where((entry) =>
                    entry.key != 'Record ID' &&
                    entry.key != 'Verification Token' &&
                    entry.key != 'Latitude' &&
                    entry.key != 'Longitude')
                .map((entry) {
              return _buildInfoTile(
                  entry.key, entry.value.toString(), Colors.grey.shade400);
            }).toList(),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          // Check if location services are enabled
          bool isLocationServiceEnabled = await _checkLocationService();

          if (!isLocationServiceEnabled) {
            // Location services are not enabled, show the dialog
            await _showLocationServiceDialog(context);
          } else {
            // Check and request location permission
            LocationPermission permission = await Geolocator.checkPermission();

            if (permission == LocationPermission.denied) {
              permission = await Geolocator.requestPermission();
            }

            if (permission == LocationPermission.denied) {
              // Permissions are denied, show the dialog to the user
              await _showLocationPermissionDialog(
                context,
                'Location Permissions Denied',
                'Please enable location permissions to use this feature.',
              );
            } else if (permission == LocationPermission.deniedForever) {
              // Permissions are denied forever, show the dialog to the user
              await _showLocationPermissionDialog(
                context,
                'Location Permissions Denied Forever',
                'Location permissions are permanently denied. You cannot use this feature.',
              );
            } else {
              // Location services and permissions are enabled, proceed with your logic
              Position currentPosition = await _determinePosition();
              double latitude = currentPosition.latitude;
              double longitude = currentPosition.longitude;

              // Now you can use the 'latitude' and 'longitude' variables as needed
              print('Latitude: $latitude, Longitude: $longitude');

              double? qrLatitude = double.parse(decodedData['Latitude']);
              double? qrLongitude = double.parse(decodedData['Longitude']);

              double distance = haversineDistance(
                latitude,
                longitude,
                qrLatitude,
                qrLongitude,
              );

              print('Distance: $distance km');

              print('QR Latitude: $qrLatitude, QR Longitude: $qrLongitude');

              // double distanceInMeters = Geolocator.distanceBetween(
              //     qrLatitude, qrLongitude, latitude, longitude);

              // print('$distanceInMeters');

              // Call the API to update attendance
              await _submitAttendance(
                context,
                userProvider.admissionNumber,
                decodedData['Record ID']!,
                decodedData['Verification Token']!,
              );
            }
          }
        },
        backgroundColor: Colors.blue,
        child: const Icon(Icons.send),
      ),
    );
  }

  Widget _buildInfoTile(String label, String value, Color color) {
    return ListTile(
      title: Text(
        label,
        style: const TextStyle(
            fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black),
      ),
      subtitle: Text(
        value,
        style: const TextStyle(fontSize: 14, color: Colors.white),
      ),
      tileColor: color,
    );
  }

  Future<bool> _checkLocationService() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    return serviceEnabled;
  }

  Future<void> _showLocationServiceDialog(BuildContext context) async {
    return showDialog<void>(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Location Services Disabled'),
          content: Text('Please enable location services to use this feature.'),
          actions: <Widget>[
            TextButton(
              onPressed: () async {
                // Open device settings
                await Geolocator.openLocationSettings();
                Navigator.of(context).pop(); // Dialog is dismissed
              },
              child: Text('Enable'),
            ),
          ],
        );
      },
    );
  }

  Future<void> _showLocationPermissionDialog(
      BuildContext context, String title, String content) async {
    return showDialog<void>(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          content: Text(content),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(); // Dismiss the dialog
              },
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

  Future<void> _submitAttendance(
    BuildContext context,
    int admissionNumber,
    int recordId,
    String verificationToken,
  ) async {
    const apiUrl =
        'https://014d-41-90-177-116.ngrok-free.app/api/update-attendance';

    try {
      ProgressDialogComponent progressDialog =
          ProgressDialogComponent(context, 'Submitting Attendance');

      progressDialog.show();

      // Send request to update attendance
      final response = await http.post(
        Uri.parse(apiUrl),
        body: {
          'admission_number': admissionNumber.toString(),
          'record_id': recordId.toString(),
          'verification_token': verificationToken,
        },
      );

      progressDialog.hide();

      if (response.statusCode == 200) {
        print('Attendance updated successfully');

        // Show success dialog
        showDialog(
          context: context,
          barrierDismissible: false,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Attendance Submitted'),
              content: const Text(
                  'Your attendance has been submitted successfully.'),
              actions: <Widget>[
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop(); // Close the success dialog
                    Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(
                          builder: (context) => const HomeScreen()),
                    );
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        print('Failed to update attendance: ${response.body}');

        // Show error dialog
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Error'),
              content:
                  const Text('Failed to submit attendance. Please try again.'),
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
      print('Error submitting attendance: $error');

      // Show error dialog
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

  double haversineDistance(double lat1, double lon1, double lat2, double lon2) {
    const earthRadius = 6371.0; // Radius of the Earth in kilometers

    // Convert latitude and longitude from degrees to radians
    final lat1Rad = _degreesToRadians(lat1);
    final lon1Rad = _degreesToRadians(lon1);
    final lat2Rad = _degreesToRadians(lat2);
    final lon2Rad = _degreesToRadians(lon2);

    // Calculate the differences
    final dLat = lat2Rad - lat1Rad;
    final dLon = lon2Rad - lon1Rad;

    // Haversine formula
    final a = pow(sin(dLat / 2), 2) +
        cos(lat1Rad) * cos(lat2Rad) * pow(sin(dLon / 2), 2);
    final c = 2 * atan2(sqrt(a), sqrt(1 - a));

    // Calculate the distance
    final distance = earthRadius * c;

    return distance;
  }

  double _degreesToRadians(double degrees) {
    return degrees * (pi / 180.0);
  }

  Future<Position> _determinePosition() async {
    return await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high);
  }
}
