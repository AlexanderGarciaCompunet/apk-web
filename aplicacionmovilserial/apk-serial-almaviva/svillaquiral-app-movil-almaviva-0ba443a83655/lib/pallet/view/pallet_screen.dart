import 'package:flutter/material.dart';

import 'package:permission_handler/permission_handler.dart';

import 'package:almaviva_integration/widgets/form_generic_lpn.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';

class LecturePalletScreen extends StatefulWidget {
  const LecturePalletScreen({Key? key}) : super(key: key);

  @override
  _LecturePalletScreenState createState() => _LecturePalletScreenState();
}

class _LecturePalletScreenState extends State<LecturePalletScreen> {
  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    WidgetsBinding.instance?.addPostFrameCallback((_) async {
      var permissionSatus = await Permission.camera.request();

      if (permissionSatus != PermissionStatus.granted) {}
    });

    return Scaffold(
        backgroundColor: Colors.white,
        body: SingleChildScrollView(
          child: Stack(
            children: <Widget>[
              Center(
                child: Container(
                  color: Colors.white38,
                  width: 280,
                  height: size.height,
                  padding: const EdgeInsets.only(top: 100, right: 10, left: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      BackButtonWidget(
                        type: 0,
                        callback: () {
                          Navigator.pop(context);
                        },
                      ),
                      const SizedBox(
                        height: 30,
                      ),
                      const Text(
                        'Lectura por Pallet',
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
                        'Debe registrar el LPN del pallet antes de leer:',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: Colors.black87, fontSize: 18, fontWeight: FontWeight.w200),
                      ),
                      const SizedBox(
                        height: 30,
                      ),
                      const FormGenericLPN(
                        title: 'LPN Pallet',
                        type: 1,
                        quantityExplain: 'Cajas por pallet: ',
                      )
                    ],
                  ),
                ),
              ),
              const HeaderTop(
                color: Color.fromRGBO(255, 21, 31, 1),
                path: 'lib/assets/Images/Logo_blanco.png',
              )
            ],
          ),
        ));
  }
}
