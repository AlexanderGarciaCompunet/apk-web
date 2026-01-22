import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';

import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_lpn_dwcapture_bloc.dart';
import 'package:almaviva_integration/widgets/input_lpn_datawedge_generic.dart';

import 'package:almaviva_integration/widgets/primary_red_button.dart';
import 'package:almaviva_integration/widgets/quantity_input_widget.dart';

import 'package:flutter/material.dart';

import 'package:provider/provider.dart';

class FormGenericLPN extends StatefulWidget {
  final String title;
  final int type;
  final String quantityExplain;
  const FormGenericLPN(
      {Key? key, required this.title, required this.type, required this.quantityExplain})
      : super(key: key);

  @override
  _FormGenericLPNState createState() => _FormGenericLPNState();
}

class _FormGenericLPNState extends State<FormGenericLPN> {
  final scanController = BarcodeDatawedgeBloc();
  final MaterialBloc materialBloc = MaterialBloc();
  bool selectedItem = false;
  bool isSpecialBarcode = false;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => materialBloc),
        ChangeNotifierProvider(create: (_) => scanController)
      ],
      child: SizedBox(
        height: size.height / 2.1,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            Text(
              widget.title,
              style: const TextStyle(fontWeight: FontWeight.w700),
            ),
            const SizedBox(
              height: 10,
            ),
            Form(
              key: formKey,
              child: Column(
                children: [
                  // InputLpnGeneric(title: widget.title, type: widget.type),

                  InputLpnDatawedgeGeneric(title: widget.title, type: widget.type),
                  const SizedBox(
                    height: 20,
                  ),
                  QuantityInputWidget(
                    quantityExplain: widget.quantityExplain,
                    title: widget.title,
                    type: widget.type,
                  )
                ],
              ),
            ),
            const SizedBox(
              height: 5,
            ),
            (widget.type == 0)
                ? Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text("Tipo de lector:"),
                      ElevatedButton.icon(
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
                    ],
                  )
                : const Text(""),
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

  // ejecución del envio del formulario, validación y mensajes de error
  _lpnSave(BuildContext contextFunction) async {
    var _orderBloc = Provider.of<OrderBloc>(contextFunction, listen: false);
    if (formKey.currentState != null) {
      if (formKey.currentState!.validate()) {
        formKey.currentState!.save();

        int validateLPN = await _orderBloc.validateLPN(widget.type);

        if (await CodesErrorsCustom.alertLPN(validateLPN, contextFunction)) {
          if (widget.type == 1) {
            Navigator.pushNamed(contextFunction, BCRoutes.BoxCountScreen.routeName)
                .then((value) => scanController.listenOnlyOneEvent());
          } else {
            _actionForBox(contextFunction);
          }
        }
      } else {
        CodesErrorsCustom.showDialogToSend(
            "Error", "Campos vacíos, verifique las cantidades y el LPN", 0, contextFunction);
      }
    }

    return;
  }

  _actionForBox(BuildContext contextFunction) async {
    //TODO: retirar el comentario de material config method para solo redireccionar

    // var _orderBloc = Provider.of<OrderBloc>(contextFunction, listen: false);
    // int materialConfig = await _orderBloc.consultForMaterialConfig();
    // isSpecialBarcode = _orderBloc.specialBarcodeLecture();
    // print("El valor es ese: ${(selectedItem && !isSpecialBarcode)}}");
    // if (materialConfig == 210) {
    //   Navigator.pushNamed(
    //           contextFunction,
    //           (selectedItem && !isSpecialBarcode) // true :datawedge
    //               ? BCRoutes.DatawedgeScanScreen.routeName
    //               : BCRoutes.SplitViewUnitMatrixMaterial.routeName)
    //       // Navigator.pushNamed(contextFunction, BCRoutes.DatawedgeScanScreen.routeName)
    //       .then((value) => scanController.listenOnlyOneEvent());
    // } else {
    //   CodesErrorsCustom.alertLPN(materialConfig, contextFunction);
    // }
    var _orderBloc = Provider.of<OrderBloc>(contextFunction, listen: false);
    _orderBloc.updateTypeCapture(selectedItem);
    Navigator.pushNamed(contextFunction, BCRoutes.TagsScreen.routeName)
        .then((value) => scanController.listenOnlyOneEvent());
    ;
  }
}
