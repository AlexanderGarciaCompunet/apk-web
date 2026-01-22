import 'package:hive/hive.dart';
part 'user_model.g.dart';

@HiveType(typeId: 0)
class UserModel extends HiveObject {
  @HiveField(0)
  int idUser;
  @HiveField(1)
  String name;
  @HiveField(2)
  String lastname;

  UserModel({
    required this.idUser,
    required this.name,
    required this.lastname,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      idUser: json['id_user'],
      name: json['name'],
      lastname: json['lastname'],
    );
  }
  Map<String, dynamic> toJson() {
    return {
      'id_user': idUser,
      'name': name,
      'lastname': lastname,
    };
  }

  Map<String, dynamic> toMap() {
    return {
      'id_user': idUser,
      'name': name,
      'lastname': lastname,
    };
  }
}
