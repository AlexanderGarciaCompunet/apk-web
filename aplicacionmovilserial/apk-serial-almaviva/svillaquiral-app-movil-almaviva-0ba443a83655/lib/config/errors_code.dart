// ignore_for_file: constant_identifier_names

enum CodeError {
  AuthenticationSuccess,
  AuthenticationFailed,
  ConnectionFailed,
  ConnectionSucces,
  ResourceNotFound,
  NewReceived,
  Reprocess,
  Completed,
  NewMaterial
}

extension Value on CodeError {
  // code 120 == Recibido Nuevo
  // code 121 == Reproceso
  // code 122 == ya existe completado
  // code 220 == rechazado o conexión fallida
  // code 210 == petición valida y contestada
  // este Bloc es el encargado de la manipulación de toda la información del contedo de la orden y compartir la información con el servidor

  int get valueCode {
    switch (this) {
      case CodeError.Completed:
        return 122;
      case CodeError.Reprocess:
        return 121;
      case CodeError.NewReceived:
        return 120;
      case CodeError.NewMaterial:
        return 130;
      case CodeError.ConnectionFailed:
        return 220;
      case CodeError.ConnectionSucces:
        return 210;
      case CodeError.AuthenticationSuccess:
        return 201;
      case CodeError.AuthenticationFailed:
        return 203;
      case CodeError.ResourceNotFound:
        return 204;

      default:
        return 0;
    }
  }
}
