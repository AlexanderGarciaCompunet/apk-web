import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:http/http.dart' as http;

class MaterialListServerApi {
  Future<http.Response> materialListServerApi(int idOrder) async {
    String path = "document-positions/search?id=$idOrder";

    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    final token = HiveDB.getBoxUser().get("token");

    http.Response respHttp = await http.get(
      url,
      headers: {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
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
