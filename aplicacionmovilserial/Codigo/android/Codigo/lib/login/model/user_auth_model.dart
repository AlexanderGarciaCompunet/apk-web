class UserAuthModel {
  String username;

  String password;
  UserAuthModel({required this.username, required this.password});

  factory UserAuthModel.fromJson(Map<String, dynamic> json) {
    return UserAuthModel(
      username: json['username'],
      password: json['password'],
    );
  }
  Map<String, dynamic> toJson() {
    return {'username': username, 'password': password};
  }

  Map<String, dynamic> toMap() {
    return {'username': username, 'password': password};
  }
}
