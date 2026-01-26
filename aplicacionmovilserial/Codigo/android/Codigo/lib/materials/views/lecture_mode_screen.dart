import 'package:almaviva_integration/materials/widgets/button_lecture_mode.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class LectureModeScreen extends StatelessWidget {
  const LectureModeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    return Scaffold(
        backgroundColor: Colors.white,
        body: SingleChildScrollView(
          child: Stack(
            children: <Widget>[
              Center(
                child: Container(
                  color: Colors.white38,
                  width: 300,
                  height: size.height,
                  padding: const EdgeInsets.only(top: 100, right: 10, left: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      BackButtonWidget(
                          type: 0,
                          callback: () {
                            Navigator.pop(context);
                          }),
                      const SizedBox(
                        height: 30,
                      ),
                      const Text(
                        'Modo de Lectura',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: AppColors.primary,
                            fontSize: 20,
                            fontWeight: FontWeight.w500),
                      ),
                      const SizedBox(
                        height: 30,
                      ),
                      const Text(
                        'Por favor, seleccionar el modo de lectura',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: Colors.black87, fontSize: 17, fontWeight: FontWeight.w100),
                      ),
                      const SizedBox(
                        height: 30,
                      ),
                      Column(
                        children: <Widget>[
                          Row(
                            children: <Widget>[
                              Flexible(
                                child: ButtonLectureMode(
                                  path: 'lib/assets/Images/apilar.png',
                                  title: 'Pallet',
                                  callback: () {
                                    _orderBloc.clearPalletBox();
                                    Navigator.pushNamed(
                                        context, BCRoutes.LecturePallet_screen.routeName);
                                  },
                                ),
                              ),
                              const SizedBox(
                                width: 10,
                              ),
                              Flexible(
                                child: ButtonLectureMode(
                                  path: 'lib/assets/Images/envio.png',
                                  title: 'Caja',
                                  callback: () {
                                    _orderBloc.clearPalletBox();
                                    Navigator.pushNamed(
                                        context, BCRoutes.LectureBoxScreen.routeName);
                                  },
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(
                            height: 10,
                          ),
                          ButtonLectureMode(
                            path: 'lib/assets/Images/abrir_caja.png',
                            title: 'Unidades',
                            callback: () {
                              _orderBloc.clearPalletBox();
                              Navigator.pushNamed(context, BCRoutes.UnitsCountScreen.routeName);
                            },
                          ),
                        ],
                      )
                    ],
                  ),
                ),
              ),
              const HeaderTop(
                color: AppColors.primary,
                path: 'lib/assets/images/Logo_Compunet_blanco.png',
              )
            ],
          ),
        ));
  }
}
