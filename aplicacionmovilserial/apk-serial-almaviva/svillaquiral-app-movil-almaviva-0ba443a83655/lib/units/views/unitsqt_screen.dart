import 'package:almaviva_integration/units/widgets/quantity_units.dart';

import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:flutter/material.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';

import 'package:provider/provider.dart';

class UnitsCountScreen extends StatefulWidget {
  const UnitsCountScreen({Key? key}) : super(key: key);

  @override
  _UnitsCountScreen createState() => _UnitsCountScreen();
}

class _UnitsCountScreen extends State<UnitsCountScreen> {
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final editingController = TextEditingController();
  final materialBloc = MaterialBloc();
  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return ChangeNotifierProvider(
      create: (context) => materialBloc,
      child: Scaffold(
          backgroundColor: Colors.white,
          body: SingleChildScrollView(
            child: Stack(
              children: <Widget>[
                Center(
                  child: Container(
                    color: Colors.white38,
                    width: 300,
                    height: size.height,
                    padding: const EdgeInsets.only(top: 100, right: 10, left: 10),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        BackButtonWidget(
                          type: 0,
                          callback: () {
                            Navigator.pop(context);
                          },
                        ),
                        const SizedBox(
                          height: 10,
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: const [
                            Text(
                              'Lectura por Unidades',
                              textAlign: TextAlign.start,
                              style: TextStyle(
                                  color: Color.fromRGBO(255, 21, 31, 1),
                                  fontSize: 20,
                                  fontWeight: FontWeight.w500),
                            ),
                          ],
                        ),
                        const SizedBox(
                          height: 60,
                        ),
                        const Text(
                          'Debe ingresar la cantidad de unidades a leer',
                          textAlign: TextAlign.start,
                          style: TextStyle(
                              color: Colors.black87, fontSize: 17, fontWeight: FontWeight.w200),
                        ),
                        const SizedBox(
                          height: 50,
                        ),
                        const QuantityUnits(
                          title: "Unidades",
                          quantityExplain: 'Cantidad de unidades:',
                          type: 2,
                        )
                      ],
                    ),
                  ),
                ),
                const HeaderTop(
                  color: Color.fromRGBO(255, 21, 31, 1),
                  path: 'lib/assets/Images/Logo_blanco.png',
                )
              ],
            ),
          )),
    );
  }
}
