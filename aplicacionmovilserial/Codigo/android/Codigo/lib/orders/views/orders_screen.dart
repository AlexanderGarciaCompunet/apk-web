import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/orders/widgets/order_list.dart';
import 'package:almaviva_integration/orders/widgets/search_order_widget.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class OrderScreen extends StatelessWidget {
  final VoidCallback callback;

  const OrderScreen({Key? key, required this.callback}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    return Scaffold(
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
                    width: size.width / 1.3,
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
                              callback: () {
                                Provider.of<OrderBloc>(context, listen: false)
                                    .socket
                                    .off('updateOrder');
                                Navigator.pop(context);
                              },
                            ),
                            const Expanded(child: SizedBox()),
                            InkWell(
                              onTap: () => callback(),
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
                          'Ingresos',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: AppColors.primary,
                              fontSize: 21,
                              fontWeight: FontWeight.w500),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Text(
                          'Listado de documentos de ingreso disponibles:',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Colors.black87, fontSize: 17, fontWeight: FontWeight.w200),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        Row(
                          children: const [Expanded(child: SizedBox()), SearchOrderWidget()],
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        const Expanded(
                          child: ListOrder(),
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
    );
  }
}
