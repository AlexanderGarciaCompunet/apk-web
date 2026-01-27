import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:http/http.dart' as http;

class MaterialConfigServerApi {
  Future<http.Response> materialConfigServerApi(int itemId) async {
    String path = "serial-rules/search?item=$itemId";

    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    final token = HiveDB.getBoxUser().get("token");
    http.Response respHttp = await http.get(
      url,
      headers: {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      },
    ).timeout(
      const Duration(seconds: 20),
      onTimeout: () {
        return http.Response('error', 500);
        // Replace 500 with your http code.
      },
    );
    // MODO DEMO - Sin descifrado
    // respHttp = Cypher.convertResponse(respHttp);
    if (respHttp.statusCode < 300) {
      // Si la llamada al servidor fue exitosa, analiza el JSON

      return respHttp;
    } else {
      // Si la llamada no fue exitosa, lanza un error.
      throw Exception('Failed to load post');
    }
  }
}
