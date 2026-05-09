class UserModel {
  final int id;
  final String name;
  final String? crno;

  UserModel({
    required this.id,
    required this.name,
    this.crno,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      crno: json['crno'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'crno': crno,
    };
  }
}
