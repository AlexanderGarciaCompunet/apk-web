class StoreModel {
  int storeId;
  String name;

  StoreModel({
    required this.storeId,
    required this.name,
  });

  factory StoreModel.fromJson(Map<String, dynamic> json) {
    return StoreModel(
      storeId: json['id'],
      name: json['name'],
    );
  }
  Map<String, dynamic> toJson() {
    return {
      'id': storeId,
      'name': name,
    };
  }
}
