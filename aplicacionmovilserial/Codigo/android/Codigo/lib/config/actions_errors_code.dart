import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:almaviva_integration/config/app_colors.dart';

class CodesErrorsCustom {
  // code 120 == Recibido Nuevo
  // code 121 == Reproceso
  // code 122 == ya existe completado
  // code 220 == rechazado o conexión fallida
  // code 210 == petición valida y contestada
  // code 130 == Configuracion necesaria

  static Future<bool> alertLectureError(int error, BuildContext contextFunction) async {
    switch (error) {
      case 123:
        return await showDialogToSend("Enviando Seriales",
                    "Aguarde un momento mientras se envia la información", 3, contextFunction)
                .then((value) => value) ??
            false;
      case 121:
        return await showDialogToSend("Serial Repetido",
                    "Uno de los seriales que intenta enviar ya fue leído", 1, contextFunction)
                .then((value) => value) ??
            false;

      case 120:
        return await showDialogToSend("Enviado", "Seriales enviados con exíto", 0, contextFunction)
                .then((value) => value) ??
            true;
      case 122:
        return await showDialogToSend("Repetido", "El serial que intenta ingresar ya fue capturado",
                    1, contextFunction)
                .then((value) => value) ??
            false;
      default:
        return await showDialogToSend("Error de Conexión",
                    "Se ha agotado el tiempo de espera de la petición", 2, contextFunction)
                .then((value) => value) ??
            false;
    }
  }

  static Future<bool> genericAlertAction(int error, BuildContext contextFunction) async {
    switch (error) {
      case 130:
        await showDialogToSend("Información", "Termino la captura", 2, contextFunction)
            .then((value) => value);
        break;
      case 131:
        break;
      default:
        return await showDialogToSend(
                "Error de Conexión", "Parece que hay problemas con la conexión", 2, contextFunction)
            .then((value) => value);
    }
    return true;
  }

  static Future<bool> alertLPN(int validateLPN, BuildContext contextFunction) async {
    switch (validateLPN) {
      case 121:
        return await showDialogToSend(
                    "Existente", "LPN Existente, continuará la lectura", 0, contextFunction)
                .then((value) => value) ??
            true;

      case 120:
        break;
      case 122:
        return await showDialogToSend("Completado",
                    "LPN que intenta ingresar ya se encuentra completo", 1, contextFunction)
                .then((value) => value) ??
            false;

      case 123:
        return await showDialogToSend(
                    "Invalido",
                    "LPN que intenta ingresar es invalido, verifiquelo y vuelva a intantar",
                    1,
                    contextFunction)
                .then((value) => value) ??
            false;

      default:
        return await showDialogToSend("Error de Conexión",
                    "Parece que hay problemas con la conexión", 2, contextFunction)
                .then((value) => value) ??
            false;
    }
    return true;
  }

  static showDialogToSend(String title, String content, int type, BuildContext contextFunction) {
    return showDialog(
        barrierDismissible: type == 2 ? true : false,
        context: contextFunction,
        builder: (BuildContext context) {
          switch (type) {
            case 0:
              return CupertinoAlertDialog(
                  title: Text(
                    title,
                    style: const TextStyle(
                        fontWeight: FontWeight.w500, color: AppColors.primary),
                  ),
                  content: Text(content),
                  actions: [
                    TextButton(
                        onPressed: () {
                          Navigator.pop(context, true);
                        },
                        child: const Text(
                          "Aceptar",
                          style: TextStyle(
                              fontWeight: FontWeight.w500, color: AppColors.primary),
                        )),
                  ]);
            case 1:
              return CupertinoAlertDialog(
                  title: Text(
                    title,
                    style: const TextStyle(
                        fontWeight: FontWeight.w500, color: AppColors.primary),
                  ),
                  content: Text(content),
                  actions: [
                    TextButton(
                        onPressed: () {
                          Navigator.pop(context, false);
                        },
                        child: const Text(
                          "Aceptar",
                          style: TextStyle(
                              fontWeight: FontWeight.w500, color: AppColors.primary),
                        )),
                  ]);
            case 2:
              return CupertinoAlertDialog(
                  title: Text(
                    title,
                    style: const TextStyle(
                        fontWeight: FontWeight.w500, color: AppColors.primary),
                  ),
                  content: Text(content));
            case 3:
              return CupertinoAlertDialog(
                  title: Text(
                    title,
                    style: const TextStyle(
                      fontSize: 20,
                      color: AppColors.primary,
                    ),
                  ),
                  content: SizedBox(
                    height: 150,
                    child: Column(
                      children: [
                        const Expanded(child: SizedBox()),
                        const Center(
                          child: SizedBox(
                              height: 80,
                              width: 80,
                              child: CircularProgressIndicator(
                                strokeWidth: 10,
                                color: AppColors.primary,
                              )),
                        ),
                        const Expanded(child: SizedBox()),
                        Text(
                          content,
                          style: const TextStyle(color: Color(0xFF044494), fontSize: 15),
                        )
                      ],
                    ),
                  ));

            default:
              return CupertinoAlertDialog(
                  title: Text(
                    title,
                    style: const TextStyle(
                        fontWeight: FontWeight.w500, color: AppColors.primary),
                  ),
                  content: Text(content));
          }
        });
  }
}
