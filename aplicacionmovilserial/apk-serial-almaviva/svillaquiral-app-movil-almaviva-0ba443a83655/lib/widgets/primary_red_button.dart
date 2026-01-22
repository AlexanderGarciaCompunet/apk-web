import 'package:flutter/material.dart';

class PrimaryRedButton extends StatelessWidget {
  final String title;
  final VoidCallback callback;

  const PrimaryRedButton({Key? key, required this.title, required this.callback}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        children: <Widget>[
          Container(
            margin: const EdgeInsets.symmetric(vertical: 20),
            width: 280,
            child: ClipRRect(
              borderRadius: BorderRadius.circular(50),
              child: Stack(
                children: <Widget>[
                  Positioned.fill(
                    child: Container(
                      decoration: const BoxDecoration(color: Color.fromRGBO(255, 21, 31, 1)),
                    ),
                  ),
                  SizedBox(
                    width: 300,
                    height: 45,
                    child: TextButton(
                      style: TextButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 36),
                        primary: Colors.white,
                        textStyle: const TextStyle(fontSize: 20),
                      ),
                      onPressed: () => callback(),
                      child: Text(title),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
