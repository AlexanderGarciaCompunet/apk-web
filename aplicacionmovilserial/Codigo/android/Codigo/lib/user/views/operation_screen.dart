import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';

import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/widgets/operation_card_selection.dart';

import 'package:flutter/material.dart';

class OperationScreen extends StatefulWidget {
  final VoidCallback callback;
  const OperationScreen({Key? key, required this.callback}) : super(key: key);

  @override
  _OperationScreenState createState() => _OperationScreenState();
}

class _OperationScreenState extends State<OperationScreen> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return Scaffold(
        backgroundColor: Colors.white,
        body: Stack(
          children: <Widget>[
            Center(
              child: Container(
                color: Colors.white38,
                width: 280,
                height: size.height,
                padding: const EdgeInsets.only(top: 90, right: 10, left: 10),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    const SizedBox(
                      height: 30,
                    ),
                    Row(
                      children: [
                        BackButtonWidget(
                          type: 0,
                          callback: () => Navigator.pop(context),
                        ),
                        const Expanded(child: SizedBox()),
                        InkWell(
                          onTap: () => widget.callback(),
                          child: const SizedBox(
                            child: Icon(
                              Icons.logout_sharp,
                              color: AppColors.primary,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(
                      height: 30,
                    ),
                    const Text(
                      'Tipo de Operación',
                      textAlign: TextAlign.start,
                      style: TextStyle(
                          color: AppColors.primary,
                          fontSize: 20,
                          fontWeight: FontWeight.w500),
                    ),
                    const SizedBox(
                      height: 20,
                    ),
                    const Text('Por favor, seleccionar el tipo de operación que realizará',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: Colors.black87, fontSize: 17, fontWeight: FontWeight.w200)),
                    const SizedBox(
                      height: 40,
                    ),
                    OperationCardSelection(
                        imagePath:
                            'https://www.grupovalora.es/wp-content/uploads/2016/02/Valora-Soluciones-Logisticas.jpg',
                        title: 'Recibo',
                        description:
                            'Seleccione esta opción para ver un listado de los documentos de recibo de mercancía.',
                        callback: () {
                          HiveDB.getBoxUser().put('operation', 1);
                          Navigator.pushNamed(context, BCRoutes.Order_screen.routeName);
                        }),
                    const SizedBox(
                      height: 20,
                    ),
                    OperationCardSelection(
                        imagePath:
                            'https://img.yapo.cl/images/80/8035799805.jpg',
                        title: 'Pedido',
                        description:
                            'Seleccione esta opción para ver un listado de los documentos de pedidos.',
                        callback: () {
                          HiveDB.getBoxUser().put('operation', 2);
                          Navigator.pushNamed(context, BCRoutes.Order_screen.routeName);
                        }),
                  ],
                ),
              ),
            ),
            const HeaderTop(
              color: AppColors.primary,
              path: 'lib/assets/images/Logo_Compunet_blanco.png',
            )
          ],
        ));
  }
}
