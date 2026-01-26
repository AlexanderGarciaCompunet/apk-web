import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/config/crypter_methods.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:http/http.dart' as http;

class OrdersListServerApi {
  Future<http.Response> ordersListServerApi(int type, int store) async {
    String path = "document-headers/search?type=$type&page=1&store=$store&per-page=100";
    Uri url = Uri.parse(ConfigEndpointsAccess.pathServerAccess + path);
    final token = HiveDB.getBoxUser().get("token");

    http.Response respHttp = await http.get(
      url,
      headers: {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      },
    );
    respHttp = Cypher.convertResponse(respHttp);
    print("OrdersListServerApi: " + respHttp.body);
    if (respHttp.statusCode < 300) {
      // Si la llamada al servidor fue exitosa, analiza el JSON
      return respHttp;
    } else {
      // Si la llamada no fue exitosa, lanza un error.
      throw Exception('Failed to load post');
    }
  }
}
