import 'package:almaviva_integration/orders/repository/finish_order_server_api.dart';

import 'package:http/http.dart' as http;

class SendOrderServerRepository {
  final _sendOrderServerApi = SendOrderServerApi();

  Future<http.Response> sendOrderServerRepository(int orderId) =>
      _sendOrderServerApi.sendOrderServerApi(orderId);
}
