import 'package:almaviva_integration/login/repository/auth_server_api.dart';
import 'package:http/http.dart' as http;

class VerifyUsersAuth {
  final _verifyUser = VerifytoServerAPI();

  Future<http.Response> verifyUsersAuth(String req) => _verifyUser.verifytoServerAPI(req);
}
