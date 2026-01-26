import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';

class MaterialTileCounterSplit extends StatelessWidget {
  const MaterialTileCounterSplit({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 60,
      padding: const EdgeInsets.symmetric(horizontal: 30),
      decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: const BorderRadius.all(Radius.circular(20)),
          border: Border.all(color: Colors.yellow, width: 1.5)),
      child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Text(
                  "Referencia:",
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w500),
                ),
                ValueListenableBuilder(
                  valueListenable: Hive.box('workorder').listenable(keys: ['currentMaterial']),
                  builder: (_, dynamic box, __) {
                    return Text(
                      '${box.get("currentMaterial").id}',
                      style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w100),
                    );
                  },
                ),
              ],
            ),
            Row(
              children: const [
                Text(
                  "Contador Global:",
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w500),
                ),
                Text(
                  '0/200',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w100),
                )
              ],
            ),
          ]),
    );
  }
}
