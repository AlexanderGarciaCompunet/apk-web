import 'package:flutter/material.dart';

import 'package:almaviva_integration/widgets/form_generic_lpn.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';

import 'package:provider/provider.dart';

class BoxCountScreen extends StatefulWidget {
  const BoxCountScreen({Key? key}) : super(key: key);

  @override
  _BoxCountScreen createState() => _BoxCountScreen();
}

class _BoxCountScreen extends State<BoxCountScreen> {
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final editingController = TextEditingController();
  final materialBloc = MaterialBloc();
  @override
  Widget build(BuildContext context) {
    final orderBloc = Provider.of<OrderBloc>(context, listen: false);
    Size size = MediaQuery.of(context).size;

    return ChangeNotifierProvider(
      create: (context) => materialBloc,
      child: Scaffold(
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
                          },
                        ),
                        const SizedBox(
                          height: 10,
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text(
                              'Lectura Caja',
                              textAlign: TextAlign.start,
                              style: TextStyle(
                                  color: AppColors.primary,
                                  fontSize: 20,
                                  fontWeight: FontWeight.w500),
                            ),
                            Consumer<OrderBloc>(
                              builder: (_, bloc, __) {
                                return Stack(children: <Widget>[
                                  SizedBox(
                                      width: 60,
                                      height: 60,
                                      child: CircularProgressIndicator(
                                        backgroundColor: Colors.grey,
                                        value: orderBloc.pallet.percentOfUnits,
                                        strokeWidth: 13,
                                        color: AppColors.primary,
                                      )),
                                  SizedBox(
                                      width: 60,
                                      height: 60,
                                      child: Center(
                                          child: Text(
                                              ' ${orderBloc.pallet.currentQuantity}/${orderBloc.pallet.quantityUnits}'))),
                                ]);
                              },
                            )
                          ],
                        ),
                        const SizedBox(
                          height: 30,
                        ),
                        const Text(
                          'Debe registrar el sublpn de la caja a leer',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Colors.black87, fontSize: 17, fontWeight: FontWeight.w200),
                        ),
                        const SizedBox(
                          height: 30,
                        ),
                        const FormGenericLPN(
                            title: 'SubLPN', type: 0, quantityExplain: 'Unidades por caja')
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
          )),
    );
  }
}
