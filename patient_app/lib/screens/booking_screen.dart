import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../models/unit_model.dart';
import '../services/api_service.dart';

class BookingScreen extends StatefulWidget {
  final UserModel user;
  final UnitModel unit;

  const BookingScreen({super.key, required this.user, required this.unit});

  @override
  State<BookingScreen> createState() => _BookingScreenState();
}

class _BookingScreenState extends State<BookingScreen> {
  bool _isLoading = false;
  Map<String, dynamic>? _bookingResult;

  Future<void> _handleBooking() async {
    setState(() => _isLoading = true);

    final result = await ApiService.createBooking(widget.user.id, widget.unit.id);

    setState(() {
      _isLoading = false;
      _bookingResult = result;
    });

    if (result['status'] != true) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(result['message'] ?? 'Booking failed'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Confirm Booking'),
        backgroundColor: const Color(0xFFFF0088),
        foregroundColor: Colors.white,
      ),
      body: _bookingResult != null && _bookingResult!['status'] == true
          ? _buildSuccessUI()
          : _buildBookingUI(),
    );
  }

  Widget _buildBookingUI() {
    return Padding(
      padding: const EdgeInsets.all(25.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Department Details',
            style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey),
          ),
          const SizedBox(height: 10),
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color(0xFFFF0088).withValues(alpha: 0.05),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: const Color(0xFFFF0088).withValues(alpha: 0.2)),
            ),
            child: Column(
              children: [
                _buildInfoRow(Icons.medical_services, 'Department', widget.unit.name),
                const Divider(height: 30),
                _buildInfoRow(Icons.person, 'Consulting Doctor', widget.unit.doctorName),
                const Divider(height: 30),
                _buildInfoRow(Icons.access_time, 'Timings', widget.unit.time),
              ],
            ),
          ),
          const SizedBox(height: 30),
          const Text(
            'Booking Information',
            style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey),
          ),
          const SizedBox(height: 10),
          Text(
            'Booking for: Tomorrow',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.grey[800]),
          ),
          const Spacer(),
          SizedBox(
            width: double.infinity,
            height: 60,
            child: ElevatedButton(
              onPressed: _isLoading ? null : _handleBooking,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFFF0088),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                elevation: 5,
              ),
              child: _isLoading
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text(
                      'BOOK TOKEN NOW',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, letterSpacing: 1),
                    ),
            ),
          ),
          const SizedBox(height: 20),
        ],
      ),
    );
  }

  Widget _buildSuccessUI() {
    final data = _bookingResult!['data'];
    return Padding(
      padding: const EdgeInsets.all(30.0),
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.check_circle, color: Colors.green, size: 100),
            const SizedBox(height: 20),
            const Text(
              'Booking Successful!',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 40),
            Container(
              padding: const EdgeInsets.all(30),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(25),
                boxShadow: [
                  BoxShadow(
                    color: Colors.grey.withValues(alpha: 0.2),
                    spreadRadius: 5,
                    blurRadius: 15,
                  )
                ],
              ),
              child: Column(
                children: [
                  const Text('YOUR TOKEN NUMBER', style: TextStyle(color: Colors.grey, letterSpacing: 2)),
                  const SizedBox(height: 10),
                  Text(
                    '${data['token_number']}',
                    style: const TextStyle(
                      fontSize: 80,
                      fontWeight: FontWeight.w900,
                      color: Color(0xFFFF0088),
                    ),
                  ),
                const Divider(height: 40),
                const Text('REPORTING TIME', style: TextStyle(color: Colors.grey, letterSpacing: 2)),
                const SizedBox(height: 10),
                Text(
                  '${data['slot_time']}',
                  style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ),
          const SizedBox(height: 50),
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Back to Home', style: TextStyle(color: Color(0xFFFF0088), fontSize: 18)),
          )
        ],
      ),
     ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, color: const Color(0xFFFF0088), size: 24),
        const SizedBox(width: 15),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: const TextStyle(color: Colors.grey, fontSize: 12)),
            Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          ],
        )
      ],
    );
  }
}
