import 'package:flutter/material.dart';

class EmptyListInformation extends StatelessWidget {
  final String title;

  const EmptyListInformation({Key? key, required this.title}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        const SizedBox(
          width: 60,
          height: 60,
          child: Opacity(
            opacity: 0.7,
            child: Icon(
              Icons.info_outline,
              color: Colors.red,
            ),
          ),
        ),
        Text(
          title,
          style: const TextStyle(fontSize: 15, color: Colors.black26),
        )
      ],
    );
  }
}
