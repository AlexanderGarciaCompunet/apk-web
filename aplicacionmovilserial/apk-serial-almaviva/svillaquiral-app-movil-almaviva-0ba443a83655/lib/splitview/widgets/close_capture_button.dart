import 'package:flutter/material.dart';

class MaterialCloseButton extends StatelessWidget {
  final VoidCallback callbackFinish;

  const MaterialCloseButton({Key? key, required this.callbackFinish}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Align(
      alignment: Alignment.bottomCenter,
      child: Container(
          margin: const EdgeInsets.symmetric(vertical: 5),
          width: 300.0,
          height: 50.0,
          decoration: const BoxDecoration(
              color: Color.fromRGBO(255, 21, 31, 1),
              borderRadius: BorderRadius.all(Radius.circular(58))),
          child: RawMaterialButton(
            elevation: 0.0,
            child: const Text(
              'Terminar',
              style: TextStyle(color: Colors.white, fontSize: 20),
            ),
            onPressed: () {
              callbackFinish();
            },
          )),
    );
  }
}
