import 'package:almaviva_integration/config/config_access.dart';

import 'package:http/http.dart' as http;

class VerifytoServerAPI {
  Future<http.Response> verifytoServerAPI(String req) async {
    String path = "auth/login";
    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    // MODO DEMO - Sin cifrado
    // req = Cypher.cypherToJson(req);
    try {
      http.Response respHttp = await http
          .post(url,
              headers: {
                "Content-Type": "application/json",
              },
              body: req)
          .timeout(
        const Duration(seconds: 40),
        onTimeout: () {
          return http.Response('error', 500);
          // Replace 500 with your http code.
        },
      );
      // MODO DEMO - Sin descifrado
      // respHttp = Cypher.convertResponse(respHttp);
      if (respHttp.statusCode >= 200 && respHttp.statusCode <= 300) {
        // Si la llamada al servidor fue exitosa, analiza el JSON

        return respHttp;
      }
      return respHttp;
    } catch (e) {
      throw Exception('Failed to load post');
    }
  }
}
