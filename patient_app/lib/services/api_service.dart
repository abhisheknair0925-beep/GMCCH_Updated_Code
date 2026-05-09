import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/user_model.dart';
import '../models/unit_model.dart';

class ApiService {
  // Configurable Base URL
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  static Future<Map<String, dynamic>> login(String crno) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/user/login'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({'crno': crno}),
      );

      final data = json.decode(response.body);

      if (data['status'] == true) {
        return {
          'status': true,
          'user': UserModel.fromJson(data['user']),
        };
      } else {
        return {
          'status': false,
          'message': data['message'] ?? 'Invalid CR Number',
        };
      }
    } catch (e) {
      return {
        'status': false,
        'message': 'Network Error: Check your internet connection',
      };
    }
  }

  static Future<List<UnitModel>> getUnits() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/units'));
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        return data.map((item) => UnitModel.fromJson(item)).toList();
      }
      return [];
    } catch (e) {
      print('Units Fetch Error: $e');
      return [];
    }
  }

  static Future<Map<String, dynamic>> createBooking(int userId, int unitId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/booking/create'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'user_id': userId,
          'unit_id': unitId,
        }),
      );

      return json.decode(response.body);
    } catch (e) {
      return {
        'status': false,
        'message': 'Booking failed: Network Error',
      };
    }
  }

  static Future<List<dynamic>> getUserBookings(int userId) async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/booking/user/$userId'));
      if (response.statusCode == 200) {
        return json.decode(response.body);
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> cancelBooking(int bookingId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/booking/cancel'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'booking_id': bookingId,
        }),
      );

      return json.decode(response.body);
    } catch (e) {
      return {
        'status': false,
        'message': 'Cancellation failed: Network Error',
      };
    }
  }
}
