import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/widgets/primary_red_button.dart';
import 'package:almaviva_integration/widgets/quantity_input_widget.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class QuantityUnits extends StatefulWidget {
  final String title;
  final String quantityExplain;
  final int type;

  const QuantityUnits(
      {Key? key, required this.title, required this.quantityExplain, required this.type})
      : super(key: key);
  @override
  _QuantityUnitsState createState() => _QuantityUnitsState();
}

class _QuantityUnitsState extends State<QuantityUnits> {
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final MaterialBloc materialBloc = MaterialBloc();
  bool selectedItem = false;
  bool isSpecialBarcode = false;
  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    return ChangeNotifierProvider(
      create: (context) => materialBloc,
      child: SizedBox(
        height: size.height / 2.3,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: <Widget>[
            const SizedBox(
              height: 10,
            ),
            Form(
              key: formKey,
              child: Column(
                children: [
                  QuantityInputWidget(
                    quantityExplain: widget.quantityExplain,
                    title: widget.title,
                    type: widget.type,
                  )
                ],
              ),
            ),
            Align(
              alignment: Alignment.centerRight,
              child: ElevatedButton.icon(
                icon: Icon(selectedItem ? Icons.flip_outlined : Icons.camera),
                label: Text(
                  selectedItem ? "Escaner" : "Cámara",
                  style: const TextStyle(color: Colors.white),
                ),
                style: ElevatedButton.styleFrom(
                  primary: Colors.red,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18.0)),
                ),
                onPressed: () {
                  print("selected Item: $selectedItem");
                  setState(() {
                    selectedItem = !selectedItem;
                  });
                },
              ),
            ),
            PrimaryRedButton(
              title: "Continuar",
              callback: () {
                _lpnSave(context);
              },
            )
          ],
        ),
      ),
    );
  }

  _lpnSave(BuildContext contextFunction) async {
    if (formKey.currentState != null) {
      if (formKey.currentState!.validate()) {
        formKey.currentState!.save();
        //TODO: quitar comentario de consultMaterialConfig

        var _orderBloc = Provider.of<OrderBloc>(contextFunction, listen: false);
        _orderBloc.updateTypeCapture(selectedItem);
        // int materialConfig = await _orderBloc.consultForMaterialConfig();
        // isSpecialBarcode = _orderBloc.specialBarcodeLecture();
        // // if (_orderBloc.materialConfigIsNotEmpty()) {
        // if (materialConfig == 210) {
        //   Navigator.pushNamed(
        //           contextFunction,
        //           (selectedItem && !isSpecialBarcode)
        //               ? BCRoutes.DatawedgeScanScreen.routeName
        //               : BCRoutes.SplitViewUnitMatrixMaterial.routeName)
        //       .then((value) {
        //     if (value != false) {
        //       Navigator.popUntil(
        //           context, ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
        //     }
        //   });
        // } else {
        //   CodesErrorsCustom.alertLPN(materialConfig, contextFunction);
        // }

        Navigator.pushNamed(contextFunction, BCRoutes.TagsScreen.routeName);
      } else {
        CodesErrorsCustom.showDialogToSend(
            "Error", "Campos vacíos, verifique las cantidades", 0, contextFunction);
      }
    }

    return;
  }
}
