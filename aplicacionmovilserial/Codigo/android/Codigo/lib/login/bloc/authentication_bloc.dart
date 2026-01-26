import 'dart:async';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/login/model/user_auth_model.dart';
import 'package:almaviva_integration/login/services/auth_service.dart';
import 'package:almaviva_integration/user/model/user_model.dart';
import 'package:flutter/widgets.dart';

// Componente de logica del negocio para autenticacion

class AuthenticationBloc extends Bloc with ChangeNotifier {
  late String _tokenAuth;
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

  //consulta de usuario usando AuthService (nuevo sistema)
  Future<int> consultUser() async {
    late UserModel userModel;

    // Llamar al nuevo AuthService
    LoginResponse response =
        await AuthService.login(_userForAuth.username, _userForAuth.password);

    if (response.success) {
      // Login exitoso
      _tokenAuth = response.token ?? '';
      var userProfile = response.profile;
      userModel = UserModel(
        idUser: 0,
        name: userProfile['name'] ?? '',
        lastname: userProfile['lastname'] ?? '',
      );
      var box = HiveDB.getBoxUser();
      box.put('user', userModel);
      box.put('token', _tokenAuth);

      return CodeError.AuthenticationSuccess.valueCode;
    } else {
      // Login fallido
      if (response.message.contains('Credenciales')) {
        return CodeError.AuthenticationFailed.valueCode;
      } else {
        return CodeError.ConnectionFailed.valueCode;
      }
    }
  }

  //verificación del usuario (parametros: Nombre de usuario, Contraseña)
  Future<int> verifyUser(String username, String password) async {
    _userForAuth = UserAuthModel(username: username, password: password);
    int response = await consultUser();
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
