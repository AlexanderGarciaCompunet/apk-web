import 'package:hive_flutter/hive_flutter.dart';
part 'material_config_model.g.dart';

@HiveType(typeId: 3)
class MaterialConfigModel {
  @HiveField(0)
  int posId;
  @HiveField(1)
  int customerId;
  @HiveField(2)
  int materialId;
  @HiveField(3)
  int orderId;
  @HiveField(4)
  int lpnId;
  @HiveField(5)
  int sublpnId;
  @HiveField(6)
  late Map<String, String> prefix;
  @HiveField(7)
  int configId;
  @HiveField(8)
  int qtColumns;
  @HiveField(9)
  int groups;
  @HiveField(10)
  double epsilon;
  @HiveField(11)
  int quantity;
  @HiveField(12)
  int orientation;
  @HiveField(13)
  int secondsForCaputre;
  @HiveField(14)
  int typeCapture;
  @HiveField(15)
  int configLabelId;

  MaterialConfigModel({
    this.posId = 0,
    required this.customerId,
    required this.materialId,
    required this.orderId,
    required this.prefix,
    this.lpnId = 0,
    this.sublpnId = 0,
    this.configId = 0,
    this.groups = 0,
    this.epsilon = 100,
    this.qtColumns = 1,
    this.quantity = 0,
    this.orientation = 0,
    this.secondsForCaputre = 0,
    this.typeCapture = 0,
    this.configLabelId = 0,
  });
  factory MaterialConfigModel.fromJson(Map<String, dynamic> json) {
    return MaterialConfigModel(
        customerId: json['customer_id'],
        materialId: json['item_id'],
        prefix: json['serials'],
        orderId: json['document_id'],
        lpnId: json['lpn_id'],
        sublpnId: json['sublpn_id'],
        configId: json['config_id'],
        epsilon: json['epsilon'],
        qtColumns: json['columns'],
        groups: json['groups'],
        orientation: json['orientation'],
        secondsForCaputre: json['seconds'],
        typeCapture: json['typeCapture'],
        configLabelId: json['config_label_id']);
  }
  Map<String, dynamic> toJson() {
    return {
      'pos_id': posId,
      'item_id': materialId,
      'serials': prefix,
      'document_id': orderId,
      'customer_id': customerId,
      'lpn_id': lpnId,
      'lpn_pos_id': sublpnId,
      'config_id': configId,
      'quantity': quantity,
      'seconds': secondsForCaputre,
      'groups': groups,
      'typeCapture': typeCapture,
      'orientation': orientation,
      'config_label_id': configLabelId
    };
  }
}
