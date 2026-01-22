import 'package:flutter/material.dart';

class LoadingPage extends StatelessWidget {
  const LoadingPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(40.0),
      child: Center(
          child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          SizedBox(width: 200, child: Image.asset('lib/assets/Images/Logo_rojo.png')),
          const SizedBox(
            height: 20,
          ),
          const SizedBox(
              width: 300,
              child: LinearProgressIndicator(
                color: Colors.redAccent,
                minHeight: 1,
              )),
        ],
      )),
    );
  }
}
