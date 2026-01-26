// ignore_for_file: library_prefixes

import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';

import 'package:almaviva_integration/orders/widgets/card_order.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';

import 'package:almaviva_integration/widgets/empty_list_information.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:socket_io_client/socket_io_client.dart' as IO;

class ListOrder extends StatefulWidget {
  const ListOrder({Key? key}) : super(key: key);

  @override
  _ListOrder createState() => _ListOrder();
}

class _ListOrder extends State<ListOrder> {
  late int type;
  @override
  void initState() {
    super.initState();
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    WidgetsBinding.instance!.addPostFrameCallback((value) => consultCodeErrorsForAction());
    _orderBloc.socket = IO.io('http://${ConfigEndpointsAccess.ipAddress}:8081',
        IO.OptionBuilder().setTransports(['websocket']).setExtraHeaders({'foo': 'bar'}).build());

    _orderBloc.socket.connect();
    _orderBloc.setUpSocketListener();
    _orderBloc.emitUpdateOrders();
  }

  void consultCodeErrorsForAction() async {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    int codeError = await _orderBloc.consultForNewOrder();
    if (codeError == CodeError.ConnectionFailed.valueCode)
      CodesErrorsCustom.alertLectureError(codeError, context);
  }

  @override
  Widget build(BuildContext context) {
    final orderBloc = Provider.of<OrderBloc>(context, listen: false);
    return Column(
      children: [
        Expanded(
          child: RefreshIndicator(
            onRefresh: () {
              consultCodeErrorsForAction();
              return Future.delayed(const Duration(seconds: 1));
            },
            child: Consumer<OrderBloc>(
              builder: (_, bloc, __) {
                return ListView.builder(
                  itemCount: orderBloc.orders.length + 1,
                  itemBuilder: (_, index) {
                    if (orderBloc.orders.isEmpty) {
                      return const Center(
                        child: EmptyListInformation(title: 'Lista de Pedidos Vacía'),
                      );
                    }
                    if (index < orderBloc.orders.length) {
                      if (orderBloc.orders.isNotEmpty) {
                        if (index == orderBloc.orders.length) {
                          return const SizedBox(
                            height: 80,
                          );
                        } else {
                          return CardOrder(
                              order: orderBloc.orders[index],
                              function: () {
                                if (orderBloc.orders.isNotEmpty) {
                                  orderBloc.emitConnectionOrder(orderBloc.orders[index]);
                                  orderBloc.selectedOrder(orderBloc.orders[index]);
                                  Navigator.pushNamed(context, BCRoutes.Material_screen.routeName)
                                      .then((value) {
                                    consultCodeErrorsForAction();
                                    orderBloc.emitDisconectionOrder();
                                    Future.delayed(const Duration(seconds: 1))
                                        .then((value) => orderBloc.emitUpdateOrders());
                                  });
                                }
                              });
                        }
                      }
                    }
                    return SizedBox();
                  },
                );
              },
            ),
          ),
        ),
      ],
    );
  }
}
