import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../models/unit_model.dart';
import '../services/api_service.dart';
import 'booking_screen.dart';
import 'my_bookings_screen.dart';

class HomeScreen extends StatefulWidget {
  final UserModel user;

  const HomeScreen({super.key, required this.user});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late Future<List<UnitModel>> _unitsFuture;

  @override
  void initState() {
    super.initState();
    _unitsFuture = ApiService.getUnits();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: const Text('Select Department'),
        centerTitle: true,
        backgroundColor: const Color(0xFFFF0088),
        foregroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => Navigator.pop(context),
          )
        ],
      ),
      body: Column(
        children: [
          // Greeting Header
          Container(
            padding: const EdgeInsets.all(20),
            width: double.infinity,
            decoration: const BoxDecoration(
              color: Color(0xFFFF0088),
              borderRadius: BorderRadius.only(
                bottomLeft: Radius.circular(30),
                bottomRight: Radius.circular(30),
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Hello, ${widget.user.name}',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 5),
                const Text(
                  'Choose a department to book your token',
                  style: TextStyle(color: Colors.white70, fontSize: 14),
                ),
              ],
            ),
          ),

          const SizedBox(height: 10),

          // Units List
          Expanded(
            child: FutureBuilder<List<UnitModel>>(
              future: _unitsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator(color: Color(0xFFFF0088)));
                } else if (snapshot.hasError || !snapshot.hasData || snapshot.data!.isEmpty) {
                  return _buildErrorState();
                }

                final units = snapshot.data!;

                return ListView.builder(
                  padding: const EdgeInsets.all(15),
                  itemCount: units.length,
                  itemBuilder: (context, index) {
                    return _buildUnitCard(context, units[index]);
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildUnitCard(BuildContext context, UnitModel unit) {
    return Card(
      elevation: 2,
      margin: const EdgeInsets.only(bottom: 15),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
      child: ListTile(
        contentPadding: const EdgeInsets.all(15),
        leading: Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: const Color(0xFFFF0088).withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: const Icon(Icons.medical_services, color: Color(0xFFFF0088), size: 30),
        ),
        title: Text(
          unit.name,
          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 5),
            Row(
              children: [
                const Icon(Icons.person, size: 14, color: Colors.grey),
                const SizedBox(width: 5),
                Text(unit.doctorName, style: const TextStyle(color: Colors.grey)),
              ],
            ),
            const SizedBox(height: 3),
            Row(
              children: [
                const Icon(Icons.access_time, size: 14, color: Colors.grey),
                const SizedBox(width: 5),
                Text(unit.time, style: const TextStyle(color: Colors.grey)),
              ],
            ),
          ],
        ),
        trailing: const Icon(Icons.arrow_forward_ios, size: 16, color: Colors.grey),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => BookingScreen(
                user: widget.user,
                unit: unit,
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildErrorState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.error_outline, size: 60, color: Colors.grey[400]),
          const SizedBox(height: 10),
          const Text('Could not load departments', style: TextStyle(color: Colors.grey)),
          TextButton(
            onPressed: () {
              setState(() {
                _unitsFuture = ApiService.getUnits();
              });
            },
            child: const Text('Retry', style: TextStyle(color: Color(0xFFFF0088))),
          )
        ],
      ),
    );
  }
}
