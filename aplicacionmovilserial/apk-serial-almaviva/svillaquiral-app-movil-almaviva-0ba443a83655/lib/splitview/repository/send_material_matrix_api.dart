import 'package:http/http.dart' as http;

import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/config/crypter_methods.dart';
import 'package:almaviva_integration/db/db.dart';

class SendMaterialMatrixAPI {
  Future<http.Response> sendMaterialMatrixAPI(String resp) async {
    String path = "serial-masters";
    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    final token = HiveDB.getBoxUser().get("token");
    print(resp);
    resp = Cypher.cypherToJson(resp);
    http.Response respHttp = await http
        .post(url,
            headers: {
              "Content-Type": "application/json",
              'Authorization': 'Bearer $token',
            },
            body: resp)
        .timeout(
      const Duration(seconds: 120),
      onTimeout: () {
        return Cypher.defaultResponseConvert(http.Response('error', 500));
        // Replace 500 with your http code.
      },
    );
    respHttp = Cypher.convertResponse(respHttp);

    if (respHttp.statusCode <= 500) {
      // Si la llamada al servidor fue exitosa, analiza el JSON
      return respHttp;
    } else {
      // Si la llamada no fue exitosa, lanza un error.
      throw Exception('Failed to load post');
    }
  }
}
