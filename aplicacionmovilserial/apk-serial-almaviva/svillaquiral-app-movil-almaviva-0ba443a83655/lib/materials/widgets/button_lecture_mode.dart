import 'package:almaviva_integration/orders/bloc/order_bloc.dart';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class ButtonLectureMode extends StatelessWidget {
  final String path;
  final String title;
  final VoidCallback callback;

  const ButtonLectureMode(
      {Key? key, required this.path, required this.title, required this.callback})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    final orderBloc = Provider.of<OrderBloc>(context);
    return GestureDetector(
      onTap: () {
        orderBloc.lectureMode = title;
        callback();
      },
      child: Container(
        padding: const EdgeInsets.all(30),
        decoration: const BoxDecoration(
            color: Color.fromRGBO(239, 239, 239, 1),
            borderRadius: BorderRadius.all(Radius.circular(9)),
            boxShadow: [BoxShadow(offset: Offset(3, 3), blurRadius: 6, color: Colors.black26)]),
        child: Center(
          child: Column(
            children: <Widget>[
              SizedBox(height: 50, child: Image.asset(path)),
              Text(
                title,
                style: const TextStyle(
                  fontSize: 17,
                  fontWeight: FontWeight.w300,
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
