import 'package:almaviva_integration/orders/repository/orders_server_api.dart';
import 'package:http/http.dart' as http;

class OrdersListServerRepository {
  final _ordersListServerApi = OrdersListServerApi();

  Future<http.Response> ordersListServerRepository(int type, int store) =>
      _ordersListServerApi.ordersListServerApi(type, store);
}
