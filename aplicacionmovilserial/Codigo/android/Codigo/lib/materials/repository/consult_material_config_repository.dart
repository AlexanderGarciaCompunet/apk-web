import 'package:almaviva_integration/materials/repository/consult_material_config_api.dart';

import 'package:http/http.dart' as http;

class MaterialConfigRepository {
  final MaterialConfigServerApi _materialConfigServerApi = MaterialConfigServerApi();
  Future<http.Response> materialConfigRepository(int itemId) =>
      _materialConfigServerApi.materialConfigServerApi(itemId);
}
