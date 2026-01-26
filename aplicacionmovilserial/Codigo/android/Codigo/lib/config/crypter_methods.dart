import 'dart:convert';
import 'package:http/http.dart' as http;
// ignore: library_prefixes
import 'package:encrypt/encrypt.dart' as EC;

class Cypher {
  static String cypherToJson(String jsonToEncrypt) {
    final key = EC.Key.fromUtf8('my 32 length key................'); //32 chars
    final iv = EC.IV.fromUtf8('helloworldhellow');
    String encryptedString = encryptMyData(jsonToEncrypt, key, iv);

    return jsonEncode({"key": encryptedString});
  }

  static String decryptedFromJson(String jsonToEncrypt) {
    final key = EC.Key.fromUtf8('my 32 length key................'); //32 chars
    final iv = EC.IV.fromUtf8('helloworldhellow');
    try {
      Map<String, dynamic> response = jsonDecode(jsonToEncrypt);

      String decryptedData = decryptMyData(response["key"], key, iv);
      return decryptedData;
    } catch (e) {
      // ignore: avoid_print
      print(e);
    }
    return '';
  }

  static String decryptMyData(String text, EC.Key key, EC.IV iv) {
    final e = EC.Encrypter(EC.AES(key, mode: EC.AESMode.cbc));
    final decryptedData = e.decrypt(EC.Encrypted.fromBase64(text), iv: iv);
    return decryptedData;
  }

  static String encryptMyData(String text, EC.Key key, EC.IV iv) {
    final e = EC.Encrypter(EC.AES(key, mode: EC.AESMode.cbc));
    final encryptedData = e.encrypt(text, iv: iv);
    return encryptedData.base64;
  }

  static http.Response convertResponse(http.Response res) {
    String decryptedData = decryptedFromJson(res.body);

    return http.Response(decryptedData, res.statusCode);
  }

  static http.Response defaultResponseConvert(http.Response res) {
    final key = EC.Key.fromUtf8('my 32 length key................'); //32 chars
    final iv = EC.IV.fromUtf8('helloworldhellow');
    String encryptedString = encryptMyData(res.body, key, iv);

    encryptedString = jsonEncode({"key": encryptedString});
    return http.Response(encryptedString, res.statusCode);
  }
}
