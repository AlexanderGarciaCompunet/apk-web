import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:almaviva_integration/config/config_access.dart';

class AuthService {
  // Obtener URL base del servidor desde la configuración
  static String getBaseUrl() {
    // Remover la última "/" si existe
    String url = ConfigEndpointsAccess.pathServerAccess;
    if (url.endsWith('/')) {
      url = url.substring(0, url.length - 1);
    }
    return url;
  }

  // Guardar token localmente
  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Obtener token guardado
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Realizar login
  static Future<LoginResponse> login(String email, String password) async {
    try {
      final baseUrl = getBaseUrl();
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'username': email, 'password': password}),
      )
          .timeout(
        const Duration(seconds: 30),
        onTimeout: () {
          throw Exception(
            'Timeout en la petición. El servidor tardó demasiado.',
          );
        },
      );

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);

        // Validar que la respuesta tenga la estructura esperada
        if (json['status'] == 'success' && json['data'] != null) {
          final token = json['data']['token'];
          final userId = json['data']['user_id'];
          final profile = json['data']['profile'];

          // Guardar el token
          await saveToken(token);

          return LoginResponse(
            success: true,
            token: token,
            userId: userId,
            profile: profile,
            message: 'Login exitoso',
          );
        } else {
          return LoginResponse(
            success: false,
            message: json['message'] ?? 'Error en la respuesta del servidor',
          );
        }
      } else if (response.statusCode == 401) {
        return LoginResponse(
          success: false,
          message: 'Credenciales incorrectas',
        );
      } else {
        return LoginResponse(
          success: false,
          message: 'Error: ${response.statusCode}',
        );
      }
    } catch (e) {
      return LoginResponse(success: false, message: 'Error de conexión: $e');
    }
  }

  // Cerrar sesión
  static Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // Verificar si hay sesión activa
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }
}

class LoginResponse {
  final bool success;
  final String? token;
  final dynamic userId;
  final dynamic profile;
  final String message;

  LoginResponse({
    required this.success,
    this.token,
    this.userId,
    this.profile,
    required this.message,
  });
}
