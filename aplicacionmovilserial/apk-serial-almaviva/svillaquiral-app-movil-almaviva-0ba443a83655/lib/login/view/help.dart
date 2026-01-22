import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';

import 'package:flutter/material.dart';

class HelpScreen extends StatelessWidget {
  const HelpScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return Scaffold(
        body: Container(
      color: Colors.white,
      width: size.width,
      height: size.height,
      child: Stack(
        children: <Widget>[
          Container(
            width: size.width,
            height: size.height,
            color: Colors.white38,
            child: MediaQuery.removePadding(
              removeTop: true,
              context: context,
              child: ListView(
                children: [
                  const HeaderTop(
                    color: Color.fromRGBO(255, 21, 31, 1),
                    path: 'lib/assets/Images/Logo_blanco.png',
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 50),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        BackButtonWidget(
                          type: 0,
                          callback: () => Navigator.pop(context),
                        ),
                        const SizedBox(
                          height: 30,
                        ),
                        const Text(
                          'Información General',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Color.fromRGBO(255, 21, 31, 1),
                              fontSize: 20,
                              fontWeight: FontWeight.w500),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Este es el menú de ayuda rápida de la aplicación móvil de toma masiva de seriales.Esta aplicación tiene como objetivo lograr la optimización del proceso de toma masiva de seriales para las referencias que posean esta característica (serializadas) con la integración al software de WMS (Warehouse Management System). ',
                          textAlign: TextAlign.justify,
                          style: TextStyle(color: Colors.black87, fontSize: 18),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Autenticación',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Color.fromRGBO(255, 21, 31, 1),
                              fontSize: 20,
                              fontWeight: FontWeight.w500),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Para ingresar a la aplicación debe digitar su usuario y contraseña del directorio activo. Si tiene problemas para iniciar sesión, comuníquese con la mesa de ayuda de Tecnología.',
                          textAlign: TextAlign.justify,
                          style: TextStyle(color: Colors.black87, fontSize: 18),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Captura de Codigos',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Color.fromRGBO(255, 21, 31, 1),
                              fontSize: 20,
                              fontWeight: FontWeight.w500),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Para hacer la captura de seriales debe ejecutar los siguientes pasos: \n \n 1.    Seleccione el almacén dónde realizará la tarea. \n \n 2.    Seleccione el tipo de operación, en este caso será “Ingreso” para listar las órdenes de recibo. \n \n 3.    En el listado de órdenes de recibo, seleccione el pedido sobre el que realizará la captura de seriales. \n \n 4.    Seleccione la referencia sobre la que leerá los seriales. El indicador de color significa el estado en el que se encuentra el conteo de esa referencia (rojo para pendiente, amarillo para en curso y verde para finalizado). El contador muestra las cantidades que se han capturado y las que se encuentran pendientes. \n \n 5.    Seleccione el modo de lectura:•Pallet:  En este modo de lectura deberá leer el LPN pallet e ingresar la cantidad de cajas que lo componen. Después deberá leer el SubLPN caja e ingresar la cantidad de unidades por caja, para pasar a la lectura de los seriales.•Caja: En este modo deberá leer el SubLPN caja e ingresar la cantidad de unidades por caja, para pasar a la lectura de los seriales.•Unidad: En este modo deberá ingresar la cantidad de unidades a leer, para pasar a la lectura de los seriales.\n \n 6.    Durante la lectura de seriales aparecerá la ventana de muestreo de seriales. En esta deberá seleccionar el serial que corresponda a cada tipo según el caso (MAC, IMEI, CHIP ID, entre otros).',
                          textAlign: TextAlign.justify,
                          style: TextStyle(color: Colors.black87, fontSize: 18),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    ));
  }
}
