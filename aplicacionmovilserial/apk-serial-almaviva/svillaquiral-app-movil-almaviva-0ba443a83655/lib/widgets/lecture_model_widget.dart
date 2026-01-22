import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class LectureModeWidget extends StatelessWidget {
  const LectureModeWidget({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    return Container(
      width: 150,
      height: 30,
      margin: const EdgeInsets.only(right: 10),
      decoration: const BoxDecoration(
        borderRadius: BorderRadius.all(Radius.circular(20)),
        color: Color.fromRGBO(255, 21, 31, 1),
      ),
      child: Center(
          child: Text(
        'Modo de Lectura: ${_orderBloc.lectureMode}',
        style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 10),
      )),
    );
  }
}
