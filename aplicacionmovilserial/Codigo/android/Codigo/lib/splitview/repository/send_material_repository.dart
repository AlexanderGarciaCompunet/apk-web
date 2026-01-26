import 'package:http/http.dart' as http;

import 'package:almaviva_integration/splitview/repository/send_material_api.dart';

import 'package:almaviva_integration/splitview/repository/send_material_matrix_api.dart';

class SendMaterialRepository {
  final _sendMaterialCaptureAPI = SendMaterialCaptureAPI();
  final _sendMaterialMatrixAPI = SendMaterialMatrixAPI();

  Future<http.Response> sendMaterialRepository(String resp) =>
      _sendMaterialCaptureAPI.sendBarcodesCaptureAPI(resp);

  Future<http.Response> sendMaterialMatrixRepository(String resp) =>
      _sendMaterialMatrixAPI.sendMaterialMatrixAPI(resp);
}
