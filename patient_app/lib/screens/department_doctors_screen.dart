import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../models/unit_model.dart';
import '../services/api_service.dart';
import 'booking_screen.dart';

class DepartmentDoctorsScreen extends StatelessWidget {
  final String departmentName;
  final List<UnitModel> departmentUnits;
  final UserModel user;

  const DepartmentDoctorsScreen({
    super.key,
    required this.departmentName,
    required this.departmentUnits,
    required this.user,
  });

  /// Groups doctors by their set of OP days.
  /// Returns a list of maps: { 'days': 'Monday, Wednesday', 'doctors': [ {unit, name, qualification, photo} ] }
  List<Map<String, dynamic>> _buildOpDayGroups() {
    // Step 1: Group units by doctorId to collect each doctor's OP days and info
    final Map<int, Map<String, dynamic>> doctorMap = {};

    for (final unit in departmentUnits) {
      final doctorId = unit.doctorId ?? unit.id; // fallback to unit id
      if (!doctorMap.containsKey(doctorId)) {
        doctorMap[doctorId] = {
          'doctorId': doctorId,
          'name': unit.doctorName,
          'qualification': unit.doctorQualification ?? '',
          'photo': unit.doctorPhoto,
          'days': <String>[],
          'units': <UnitModel>[],
        };
      }
      if (unit.day != null && unit.day!.isNotEmpty) {
        doctorMap[doctorId]!['days'].add(unit.day!);
      }
      doctorMap[doctorId]!['units'].add(unit);
    }

    // Step 2: Group doctors that share the same set of OP days
    final Map<String, List<Map<String, dynamic>>> dayGroupMap = {};

    for (final doctor in doctorMap.values) {
      final days = List<String>.from(doctor['days']);
      // Sort days by weekday order for consistent display
      final dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      days.sort((a, b) => dayOrder.indexOf(a) - dayOrder.indexOf(b));
      final dayKey = days.isNotEmpty ? days.join(', ') : 'Not Scheduled';
      if (!dayGroupMap.containsKey(dayKey)) {
        dayGroupMap[dayKey] = [];
      }
      dayGroupMap[dayKey]!.add(doctor);
    }

    // Step 3: Convert to list
    return dayGroupMap.entries.map((entry) {
      return {
        'days': entry.key,
        'doctors': entry.value,
      };
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    final opDayGroups = _buildOpDayGroups();

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: const Text('MINI'),
        centerTitle: true,
        backgroundColor: const Color(0xFFFF0088),
        foregroundColor: Colors.white,
        elevation: 0,
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {},
            itemBuilder: (context) => [
              const PopupMenuItem(value: 'info', child: Text('Info')),
            ],
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Department Title
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 20),
              color: Colors.white,
              child: Text(
                departmentName.endsWith('Department') 
                    ? departmentName.replaceAll(' Department', '\nDepartment') 
                    : '$departmentName\nDepartment',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                  color: Colors.black87,
                  height: 1.3,
                ),
              ),
            ),

            // OP Day Sections
            ...opDayGroups.map((group) => _buildOpDaySection(context, group)),

            const SizedBox(height: 30),
          ],
        ),
      ),
    );
  }

  Widget _buildOpDaySection(BuildContext context, Map<String, dynamic> group) {
    final String days = group['days'];
    final List<Map<String, dynamic>> doctors = group['doctors'];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // OP Day Banner (Full Width, Solid Color)
        Container(
          width: double.infinity,
          margin: const EdgeInsets.only(top: 16),
          padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 20),
          color: const Color(0xFFFF0088),
          child: Text(
            'OP.   $days',
            style: const TextStyle(
              color: Colors.white,
              fontSize: 18,
              fontWeight: FontWeight.bold,
              letterSpacing: 0.5,
            ),
          ),
        ),

        // "Book Token" button
        Align(
          alignment: Alignment.centerRight,
          child: Padding(
            padding: const EdgeInsets.only(right: 20, top: 8, bottom: 4),
            child: TextButton(
              onPressed: () {
                // If there's only one doctor, navigate directly to booking
                // If multiple doctors, show selection dialog
                if (doctors.length == 1) {
                  final units = doctors[0]['units'] as List<UnitModel>;
                  if (units.isNotEmpty) {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BookingScreen(
                          user: user,
                          unit: units.first,
                        ),
                      ),
                    );
                  }
                } else {
                  _showDoctorSelectionDialog(context, doctors);
                }
              },
              child: const Text(
                'Book Token',
                style: TextStyle(
                  color: Colors.black87,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ),

        // Grouped Doctor Container (Full Width, No Card Margins)
        Container(
          color: Colors.white,
          width: double.infinity,
          padding: const EdgeInsets.symmetric(vertical: 4),
          child: Column(
            children: [
              for (int i = 0; i < doctors.length; i++) ...[
                _buildDoctorCard(context, doctors[i]),
                if (i < doctors.length - 1)
                  const Divider(height: 1, indent: 20, endIndent: 20, color: Color(0xFFEEEEEE)),
              ]
            ],
          ),
        ),
      ],
    );
  }

  void _showDoctorSelectionDialog(BuildContext context, List<Map<String, dynamic>> doctors) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) {
        return Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Select Doctor',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 16),
              ...doctors.map((doctor) {
                final units = doctor['units'] as List<UnitModel>;
                return ListTile(
                  leading: _buildDoctorAvatar(doctor, width: 44, height: 50),
                  title: Text(
                    doctor['name'],
                    style: const TextStyle(fontWeight: FontWeight.w600),
                  ),
                  subtitle: Text(
                    doctor['qualification'] ?? '',
                    style: const TextStyle(fontSize: 12, color: Colors.grey),
                  ),
                  trailing: const Icon(Icons.arrow_forward_ios, size: 16, color: Color(0xFFFF0088)),
                  onTap: () {
                    Navigator.pop(ctx);
                    if (units.isNotEmpty) {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => BookingScreen(
                            user: user,
                            unit: units.first,
                          ),
                        ),
                      );
                    }
                  },
                );
              }),
              const SizedBox(height: 10),
            ],
          ),
        );
      },
    );
  }

  Widget _buildDoctorCard(BuildContext context, Map<String, dynamic> doctor) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          // Doctor Photo (Square/Rectangular)
          _buildDoctorAvatar(doctor, width: 80, height: 90),
          const SizedBox(width: 20),

          // Doctor Info
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  doctor['name'] ?? '',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                    color: Colors.black87,
                  ),
                ),
                if (doctor['qualification'] != null &&
                    doctor['qualification'].toString().isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(top: 6),
                    child: Text(
                      doctor['qualification'],
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.grey[600],
                        height: 1.3,
                      ),
                    ),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDoctorAvatar(Map<String, dynamic> doctor, {double width = 80, double height = 90}) {
    final photoUrl = ApiService.getDoctorPhotoUrl(doctor['photo']);

    if (photoUrl != null) {
      return ClipRRect(
        borderRadius: BorderRadius.circular(4),
        child: Container(
          width: width,
          height: height,
          color: Colors.grey[200],
          child: Image.network(
            photoUrl,
            fit: BoxFit.cover,
            errorBuilder: (context, error, stackTrace) => _buildPlaceholderAvatar(doctor, width, height),
          ),
        ),
      );
    }
    return _buildPlaceholderAvatar(doctor, width, height);
  }

  Widget _buildPlaceholderAvatar(Map<String, dynamic> doctor, double width, double height) {
    final name = (doctor['name'] as String?) ?? '';
    final initials = name
        .split(' ')
        .where((w) => w.isNotEmpty)
        .take(2)
        .map((w) => w[0].toUpperCase())
        .join();

    return ClipRRect(
      borderRadius: BorderRadius.circular(4),
      child: Container(
        width: width,
        height: height,
        color: const Color(0xFFFF0088).withValues(alpha: 0.15),
        alignment: Alignment.center,
        child: Text(
          initials,
          style: TextStyle(
            color: const Color(0xFFFF0088),
            fontWeight: FontWeight.bold,
            fontSize: width * 0.35,
          ),
        ),
      ),
    );
  }
}
