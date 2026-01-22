class LpnModel {
  late int lpnId;
  late String lpnNumber;
  late int customerId;
  late int quantityUnits;
  late int currentQuantity;
  late double percentOfUnits;
  late int materialId;
  late int orderId;
  late int status;
  late int type;
  late int lpnsup;

  LpnModel({
    this.status = 0,
    this.lpnNumber = '',
    this.percentOfUnits = 0.0,
    this.quantityUnits = 0,
    this.customerId = 0,
    this.currentQuantity = 0,
    this.materialId = 0,
    this.orderId = 0,
    this.type = 0,
    this.lpnId = 0,
    this.lpnsup = 0,
  });

  factory LpnModel.fromJson(Map<String, dynamic> json) {
    return LpnModel(
        status: int.parse(json['status']),
        lpnNumber: json['lpnNumber'],
        quantityUnits: int.parse(json['quantityUnits']),
        customerId: json['customer_id']);
  }

  Map<String, dynamic> toJson() {
    return {
      'lpnnr': lpnNumber,
      'status': status,
      'item_id': materialId,
      'document_id': orderId,
      'itemcnt': quantityUnits,
      'lpnty': type,
      'customer_id': customerId,
      'currentQuantity': currentQuantity,
      'lpnsup': lpnsup
    };
  }

  void setPallet({
    required int customerId,
    required int currentQuantity,
    required int orderId,
    required int type,
    required int materialId,
    required double percent,
  }) {
    percentOfUnits = percent;
    this.materialId = materialId;
    this.customerId = customerId;
    this.currentQuantity = currentQuantity;
    status = status;
    this.orderId = orderId;
    this.type = type;
  }
}
