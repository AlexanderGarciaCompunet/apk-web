import 'package:almaviva_integration/user/repository/labels_server_api.dart';
import 'package:http/http.dart' as http;

class LabelsConsultRepository {
  final _labelsServerApi = LabelsServerAPI();

  Future<http.Response> labelsConsultRepository() => _labelsServerApi.labelsServerAPI();
}
