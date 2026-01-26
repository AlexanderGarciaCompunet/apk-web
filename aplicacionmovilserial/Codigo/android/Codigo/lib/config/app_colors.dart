import 'package:flutter/material.dart';

/// Colores de la marca Compunet
/// Para actualizar los colores de la app, modifica los valores aquí
class AppColors {
  // Color principal - Morado Compunet RGB(48, 52, 131)
  static const Color primary = Color.fromRGBO(48, 52, 131, 1);

  // Color de acento - Verde Compunet RGB(187, 213, 49)
  static const Color accent = Color.fromRGBO(187, 213, 49, 1);

  // Blanco
  static const Color white = Colors.white;

  // Color principal con opacidad (para fondos, overlays, etc.)
  static Color primaryWithOpacity(double opacity) {
    return Color.fromRGBO(48, 52, 131, opacity);
  }

  // Color de acento con opacidad
  static Color accentWithOpacity(double opacity) {
    return Color.fromRGBO(187, 213, 49, opacity);
  }

  // Colores para estados
  static const Color error = Color.fromRGBO(220, 53, 69, 1);    // Rojo para errores
  static const Color success = Color.fromRGBO(40, 167, 69, 1);  // Verde para éxito
  static const Color warning = Color.fromRGBO(255, 193, 7, 1);  // Amarillo para advertencias

  // Colores de texto
  static const Color textPrimary = Color.fromRGBO(33, 37, 41, 1);
  static const Color textSecondary = Color.fromRGBO(108, 117, 125, 1);
  static const Color textLight = Colors.white;

  // Colores de fondo
  static const Color background = Colors.white;
  static const Color backgroundSecondary = Color.fromRGBO(248, 249, 250, 1);
}
