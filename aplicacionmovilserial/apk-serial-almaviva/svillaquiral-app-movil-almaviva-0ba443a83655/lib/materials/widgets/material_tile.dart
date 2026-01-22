import 'package:almaviva_integration/materials/bloc/material_bloc.dart';
import 'package:almaviva_integration/materials/models/material_model.dart';
import 'package:provider/provider.dart';
import 'package:flutter/material.dart';

class MaterialCard extends StatelessWidget {
  final MaterialModel material;
  final VoidCallback function;

  const MaterialCard({Key? key, required this.material, required this.function}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    return Consumer<MaterialBloc>(builder: (__, _bloc, _) {
      return Container(
        height: 100,
        margin: const EdgeInsets.only(bottom: 20),
        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 10),
        decoration: BoxDecoration(
            border: Border.all(color: setColorWithQuantity(), width: 2),
            boxShadow: const [
              BoxShadow(color: Colors.black26, offset: Offset(3, 3), blurRadius: 4)
            ],
            color: Colors.white,
            borderRadius: const BorderRadius.all(Radius.circular(15))),
        child: Center(
          child: ListTile(
            contentPadding: const EdgeInsets.symmetric(horizontal: 10),
            onTap: () {
              function();
            },
            trailing: Image.asset(
              'lib/assets/Images/barcode.png',
              alignment: Alignment.topCenter,
              width: 40,
            ),
            title: Padding(
              padding: const EdgeInsets.only(top: 10),
              child: Row(
                children: [
                  const Text('Referencia:',
                      style: TextStyle(
                          color: Colors.black, fontSize: 14, fontWeight: FontWeight.w500)),
                  Text(material.itemCode,
                      style: const TextStyle(
                          color: Colors.black, fontSize: 14, fontWeight: FontWeight.w100)),
                ],
              ),
            ),
            subtitle: Padding(
              padding: const EdgeInsets.only(bottom: 10),
              child: Column(
                children: [
                  Row(
                    children: [
                      const Text('Status:',
                          style: TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w500)),
                      Text(material.rcvsts,
                          style: const TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w100)),
                      const Text('\t - \t ',
                          style: TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w700)),
                      const Text('Unit:',
                          style: TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w500)),
                      Text(material.unitsType,
                          style: const TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w100)),
                    ],
                  ),
                  Row(
                    children: [
                      const Text('Cantidad:',
                          style: TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w500)),
                      Text('${material.currentQuantity}/${material.amount}',
                          style: const TextStyle(
                              color: Colors.black, fontSize: 13.5, fontWeight: FontWeight.w100))
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      );
    });
  }

  Color setColorWithQuantity() {
    int amount = material.amount;
    if (0 == material.currentQuantity) {
      return Colors.red;
    } else if (material.currentQuantity > 0 && material.currentQuantity < amount) {
      return Colors.yellow;
    } else {
      return Colors.green;
    }
  }
}
