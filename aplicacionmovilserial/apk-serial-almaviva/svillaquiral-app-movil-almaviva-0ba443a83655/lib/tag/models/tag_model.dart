class TagModel {
  String name;
  String description;
  int idTag;
  int qtColumns;
  int groups;
  int orientation;
  double epsilon;
  Map<String, dynamic> serialty;
  int time;
  int configId;
  int typeCapture;

  TagModel({
    required this.name,
    required this.description,
    required this.idTag,
    this.qtColumns = 2,
    this.groups = 0,
    this.orientation = 0,
    this.epsilon = 100,
    required this.serialty,
    this.time = 5,
    this.configId = 4,
    this.typeCapture = 0,
  });
  factory TagModel.fromJson(Map<String, dynamic> json) {
    return TagModel(
      name: json['name'],
      description: json['description'],
      idTag: json['idTag'],
      qtColumns: json['qtColumns'],
      groups: json['groups'],
      orientation: json['orientation'],
      epsilon: json['epsilon'].toDouble(),
      serialty: json['serialty'],
      time: json['time'],
      configId: json['configId'],
      typeCapture: json['typeCapture'],
    );
  }
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'description': description,
      'idTag': idTag,
      'qtColumns': qtColumns,
      'groups': groups,
      'orientation': orientation,
      'epsilon': epsilon,
      'serialty': serialty,
      'time': time,
      'configId': configId,
      'typeCapture': typeCapture,
    };
  }
}
