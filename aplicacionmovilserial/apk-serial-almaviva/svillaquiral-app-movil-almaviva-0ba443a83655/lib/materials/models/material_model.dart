import 'package:hive/hive.dart';
part 'material_model.g.dart';

@HiveType(typeId: 2)
class MaterialModel extends HiveObject {
  @HiveField(0)
  int id;
  @HiveField(2)
  int materialId;
  @HiveField(3)
  int customerId;
  @HiveField(5)
  int orderId;
  @HiveField(4)
  int amount;
  @HiveField(6)
  String itemCode;
  @HiveField(7)
  String unitsType;
  @HiveField(8)
  String rcvsts;

  int currentQuantity;

  MaterialModel({
    required this.id,
    required this.materialId,
    required this.orderId,
    required this.customerId,
    required this.amount,
    required this.itemCode,
    required this.unitsType,
    required this.rcvsts,
    this.currentQuantity = 0,
  });
  factory MaterialModel.fromJson(Map<String, dynamic> json) {
    return MaterialModel(
        id: json['id'],
        orderId: json['document_id'],
        customerId: json['customer_id'],
        amount: int.parse(json['amount']),
        materialId: json['item_id'],
        itemCode: json['code'],
        unitsType: json['unit'] ?? '',
        rcvsts: json['rcvsts'] ?? '',
        currentQuantity: json['real_amount']);
  }
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'order_id': orderId,
      'customer_id': customerId,
      'amount': amount,
      'material_id': materialId,
      'code': itemCode,
      'rcvsts': rcvsts,
    };
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      // 'position_id': position_id,
      'order_id': orderId,
      'customer_id': customerId,
      'amount': amount,
      'material_id': materialId,
      'code': itemCode,
      'rcvsts': rcvsts
    };
  }
}
