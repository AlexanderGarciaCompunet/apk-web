import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:http/http.dart' as http;

class SendPalletMastertoServerApi {
  Future<http.Response> sendPalletMastertoServerApi(String pallet) async {
    String path = "lpn-masters";

    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    final token = HiveDB.getBoxUser().get("token");
    // MODO DEMO - Sin cifrado
    // pallet = Cypher.cypherToJson(pallet);
    http.Response respHttp = await http
        .post(url,
            headers: {
              "Content-Type": "application/json",
              'Authorization': 'Bearer $token',
            },
            body: pallet)
        .timeout(
      const Duration(seconds: 20),
      onTimeout: () {
        return http.Response('error', 500);
        // Replace 500 with your http code.
      },
    );
    // MODO DEMO - Sin descifrado
    // respHttp = Cypher.convertResponse(respHttp);
    print(respHttp.body);
    if (respHttp.statusCode <= 500) {
      // Si la llamada al servidor fue exitosa, analiza el JSON
      return respHttp;
    } else {
      // Si la llamada no fue exitosa, lanza un error.
      throw Exception('Failed to load post');
    }
  }
}
