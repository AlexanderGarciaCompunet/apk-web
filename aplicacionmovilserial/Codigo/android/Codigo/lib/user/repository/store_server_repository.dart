import 'package:almaviva_integration/user/repository/store_server_api.dart';
import 'package:http/http.dart' as http;

class StoreConsultRepository {
  final _storeServerApi = StoreServerAPI();

  Future<http.Response> storeConsultRepository() => _storeServerApi.storeServerAPI();
}
