import 'dart:async';
import 'dart:convert';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/login/model/user_auth_model.dart';
import 'package:almaviva_integration/login/repository/auth_repository.dart';
import 'package:almaviva_integration/user/model/user_model.dart';
import 'package:flutter/widgets.dart';

// import 'package:device_info_plus/device_info_plus.dart';
import 'package:http/http.dart';

// Componente de logica del negocio para autenticacion

class AuthenticationBloc extends Bloc with ChangeNotifier {
  late String _tokenAuth;
  final VerifyUsersAuth _verifyUsersAuth = VerifyUsersAuth();
  late UserAuthModel _userForAuth;
  String selected = ConfigEndpointsAccess.pathServerAccess;
  bool loading = false;
  void modifyServerAcces(String serverAccess) {
    selected = serverAccess;
    ConfigEndpointsAccess.ipAddress = serverAccess;
    ConfigEndpointsAccess.modifyServerAccess(serverAccess);
    notifyListeners();
  }

  void onChangeLoading() {
    if (loading == true) {
      loading = false;
    } else {
      loading = true;
    }
    notifyListeners();
  }

  //consulta de usuario, conectado a traves de auth_repository (Servicio de authenticación conectado a la api)
  Future<int> consultUser() async {
    late UserModel userModel;
    String jsonUser = json.encode(_userForAuth);
    //la consulta obtiene una respuesta, si esta es positiva el usuario podra acceder al home,
    //si es negativa el usuario recibe un mensaje de error
    Response response = await _verifyUsersAuth.verifyUsersAuth(jsonUser);
    print(response.body);
    if (response.statusCode == 500) CodeError.ConnectionFailed.valueCode;
    if (response.statusCode == 200) {
      var jsonString = json.decode(response.body.toString());

      if (jsonString['status'] == 'error') {
        return CodeError.AuthenticationFailed.valueCode;
      } else {
        //cargar el usuario al usuario logeado a share preferences

        _tokenAuth = jsonString['data']['token'];
        var userProfile = jsonString['data']['profile'];
        userModel =
            UserModel(idUser: 0, name: userProfile['name'], lastname: userProfile['lastname']);
        var box = HiveDB.getBoxUser();

        // var deviceInfo = await _deviceInfo();
        box.put('user', userModel);
        // box.put('device', deviceInfo);
        box.put('token', _tokenAuth);

        return CodeError.AuthenticationSuccess.valueCode;
      }
    }
    return CodeError.ConnectionFailed.valueCode;
  }

  //verificación del ususario (parametros: Nombre de usuario, Contraseña)
  Future<int> verifyUser(String username, String password) async {
    _userForAuth = UserAuthModel(username: username, password: password);
    int response = await consultUser();
    // int response = CodeError.AuthenticationSuccess.valueCode;
    await Future.delayed(const Duration(milliseconds: 1000));
    return response;
  }

  //extracción de información del dispositivo (paquete device info plus)
  // Future<String> _deviceInfo() async {
  //   DeviceInfoPlugin deviceInfo = DeviceInfoPlugin();
  //   AndroidDeviceInfo androidInfo = await deviceInfo.androidInfo;
  //   return json.encode(androidInfo.toString());
  // }

}
