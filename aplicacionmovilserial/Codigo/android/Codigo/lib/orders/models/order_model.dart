import 'package:hive/hive.dart';
part "order_model.g.dart";

@HiveType(typeId: 1)
class OrderModel extends HiveObject {
  @HiveField(0)
  int id;
  @HiveField(5)
  String orderId;
  @HiveField(1)
  String customerName;
  @HiveField(2)
  String documentType;
  @HiveField(3)
  String storeName;
  @HiveField(4)
  int customerId;
  @HiveField(6)
  int storeId;
  @HiveField(7)
  int status;

  OrderModel({
    required this.id,
    required this.orderId,
    required this.documentType,
    required this.customerName,
    required this.storeName,
    required this.customerId,
    required this.storeId,
    this.status = 0,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'],
      orderId: json['docnr'],
      documentType: json['type'].toString(),
      customerName: json['customer_name'],
      storeName: json['store_name'],
      customerId: json['customer_id'],
      storeId: json['store_id'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'order_id': orderId,
      'client_name': customerName,
      'documentType': documentType,
      'store_name': storeName,
      'customer_id': customerId,
      'store_id': storeId,
      'status': status
    };
  }
}
