import 'package:almaviva_integration/materials/repository/material_server_api.dart';

import 'package:http/http.dart' as http;

class MaterialListServerRepository {
  final _materialListServerApi = MaterialListServerApi();

  Future<http.Response> materialListServerRepository(int orderId) =>
      _materialListServerApi.materialListServerApi(orderId);
}
