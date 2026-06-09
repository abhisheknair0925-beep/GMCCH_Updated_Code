class UnitModel {
  final int id;
  final String name;
  final String time;
  final String? day;
  final int? doctorId;
  final String doctorName;
  final String? doctorQualification;
  final String? doctorPhoto;
  final String? doctorDepartment;

  UnitModel({
    required this.id,
    required this.name,
    required this.time,
    this.day,
    this.doctorId,
    required this.doctorName,
    this.doctorQualification,
    this.doctorPhoto,
    this.doctorDepartment,
  });

  factory UnitModel.fromJson(Map<String, dynamic> json) {
    final doctor = json['doctor'] as Map<String, dynamic>?;
    return UnitModel(
      id: json['id'],
      name: json['name'],
      time: json['time'] ?? 'N/A',
      day: json['day'],
      doctorId: json['doctor_id'],
      doctorName: doctor != null ? (doctor['name'] ?? 'On Duty') : 'On Duty',
      doctorQualification: doctor?['qualification'],
      doctorPhoto: doctor?['photo'],
      doctorDepartment: doctor?['department'],
    );
  }
}
