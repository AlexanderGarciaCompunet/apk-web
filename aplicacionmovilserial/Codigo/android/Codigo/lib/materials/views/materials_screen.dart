import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';

import 'package:almaviva_integration/materials/widgets/material_list.dart';
import 'package:almaviva_integration/materials/widgets/search_widget.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';

import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:provider/provider.dart';

class MaterialScreen extends StatefulWidget {
  const MaterialScreen({
    Key? key,
  }) : super(key: key);

  @override
  State<StatefulWidget> createState() {
    return _MaterialScreen();
  }
}

class _MaterialScreen extends State<MaterialScreen> {
  final MaterialBloc _materialBloc = MaterialBloc();
  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    return ChangeNotifierProvider(
      create: (context) => _materialBloc,
      child: Scaffold(
        body: MediaQuery.removePadding(
          context: context,
          removeTop: true,
          child: ListView(children: <Widget>[
            Stack(
              children: <Widget>[
                SizedBox(
                  height: size.height,
                  child: Center(
                    child: Container(
                      color: Colors.white38,
                      width: 300,
                      padding: const EdgeInsets.only(top: 90, right: 10, left: 10),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: <Widget>[
                          Row(
                            children: [
                              BackButtonWidget(
                                  type: 0,
                                  callback: () {
                                    Provider.of<OrderBloc>(context, listen: false)
                                        .socket
                                        .off('getCount');
                                    Navigator.pop(context);
                                  }),
                              const SearchGuideWidget()
                            ],
                          ),
                          const SizedBox(
                            height: 30,
                          ),
                          const Text(
                            'Información del Pedido',
                            textAlign: TextAlign.start,
                            style: TextStyle(
                                color: AppColors.primary,
                                fontSize: 21,
                                fontWeight: FontWeight.w500),
                          ),
                          const SizedBox(
                            height: 20,
                          ),
                          ValueListenableBuilder(
                            valueListenable:
                                Hive.box('workorder').listenable(keys: ['currentOrder']),
                            builder: (_, dynamic box, __) {
                              return Column(
                                children: <Widget>[
                                  Row(
                                    children: [
                                      const Text(
                                        'Pedido: ',
                                        textAlign: TextAlign.start,
                                        style: TextStyle(
                                            color: Colors.black87,
                                            fontSize: 17,
                                            fontWeight: FontWeight.w500),
                                      ),
                                      Text(
                                        '${box!.get('currentOrder').orderId}',
                                        textAlign: TextAlign.start,
                                        style: const TextStyle(
                                            color: Colors.black87,
                                            fontSize: 17,
                                            fontWeight: FontWeight.w100),
                                      ),
                                    ],
                                  ),
                                  Row(
                                    children: [
                                      const Text(
                                        'Cliente:',
                                        textAlign: TextAlign.start,
                                        style: TextStyle(
                                            color: Colors.black87,
                                            fontSize: 17,
                                            fontWeight: FontWeight.w500),
                                      ),
                                      SizedBox(
                                        width: size.width * 0.6,
                                        child: Text(
                                          '${box!.get('currentOrder').customerName}',
                                          overflow: TextOverflow.ellipsis,
                                          textAlign: TextAlign.start,
                                          style: const TextStyle(
                                              color: Colors.black87,
                                              fontSize: 15,
                                              fontWeight: FontWeight.w100),
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              );
                            },
                          ),
                          const SizedBox(
                            height: 20,
                          ),
                          Expanded(
                            child: Stack(
                              children: [
                                const MaterialList(),
                                Align(
                                  alignment: Alignment.bottomCenter,
                                  child: Container(
                                      margin: const EdgeInsets.symmetric(vertical: 20),
                                      width: 300.0,
                                      height: 45.0,
                                      decoration: const BoxDecoration(
                                          color: AppColors.primary,
                                          borderRadius: BorderRadius.all(Radius.circular(58))),
                                      child: RawMaterialButton(
                                        elevation: 0.0,
                                        child: const Text(
                                          'Terminar',
                                          style: TextStyle(color: Colors.white, fontSize: 20),
                                        ),
                                        onPressed: () {
                                          _orderBloc.finishOrder().then((value) {
                                            if (value == CodeError.ConnectionFailed.valueCode) {
                                              CodesErrorsCustom.showDialogToSend(
                                                  "Error",
                                                  "ha sucedido algo, intentalo de nuevo",
                                                  0,
                                                  context);
                                            } else {
                                              _orderBloc.emitDisconectionOrder();
                                              _orderBloc.socket.off('getCount');

                                              Navigator.pop(context);
                                            }
                                          });
                                        },
                                      )),
                                ),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                  ),
                ),
                const HeaderTop(
                  color: AppColors.primary,
                  path: 'lib/assets/images/Logo_Compunet_blanco.png',
                )
              ],
            ),
          ]),
        ),
      ),
    );
  }
}
