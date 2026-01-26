import 'package:almaviva_integration/pallet/repository/send_pallet_server_api.dart';
import 'package:http/http.dart' as http;

class SendPalletMasterRepository {
  final _sendPalletMastertoServerApi = SendPalletMastertoServerApi();

  Future<http.Response> sendPalletMasterRepository(String pallet) =>
      _sendPalletMastertoServerApi.sendPalletMastertoServerApi(pallet);
}
