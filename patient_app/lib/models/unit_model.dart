class UnitModel {
  final int id;
  final String name;
  final String time;
  final String doctorName;

  UnitModel({
    required this.id,
    required this.name,
    required this.time,
    required this.doctorName,
  });

  factory UnitModel.fromJson(Map<String, dynamic> json) {
    return UnitModel(
      id: json['id'],
      name: json['name'],
      time: json['time'] ?? 'N/A',
      doctorName: json['doctor'] != null ? json['doctor']['name'] : 'On Duty',
    );
  }
}
