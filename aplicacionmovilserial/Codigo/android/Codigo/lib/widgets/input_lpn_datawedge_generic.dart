import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../splitview/bloc/barcode_lpn_dwcapture_bloc.dart';

class InputLpnDatawedgeGeneric extends StatefulWidget {
  final String title;
  final int type;

  const InputLpnDatawedgeGeneric({Key? key, required this.title, required this.type})
      : super(key: key);

  @override
  _InputLpnDatawedgeGenericState createState() => _InputLpnDatawedgeGenericState();
}

class _InputLpnDatawedgeGenericState extends State<InputLpnDatawedgeGeneric> {
  @override
  void initState() {
    final scanController = Provider.of<BarcodeDatawedgeBloc>(context, listen: false);
    scanController.applyConfiguration();
    scanController.listenOnlyOneEvent();

    super.initState();
  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    final scanController = Provider.of<BarcodeDatawedgeBloc>(context, listen: false);

    return Stack(
      children: [
        Container(
            height: 50,
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: const [
                BoxShadow(
                  color: Colors.black26,
                  offset: Offset(3, 3),
                  blurRadius: 10,
                )
              ],
              borderRadius: BorderRadius.circular(50.0),
            ),
            child: Padding(
                padding: const EdgeInsets.only(left: 25, right: 15, top: 5, bottom: 5),
                child: SizedBox(
                  height: 50,
                  child: TextFormField(
                    controller: scanController.editingController,
                    decoration: InputDecoration(
                        hintStyle: const TextStyle(
                            color: Colors.black87, fontSize: 15, fontWeight: FontWeight.w100),
                        border: InputBorder.none,
                        hintText: widget.title,
                        errorStyle: const TextStyle(color: Colors.transparent, height: 0)),
                    onSaved: (value) {
                      if (widget.type == 1) {
                        _orderBloc.pallet.lpnNumber = value.toString();
                      } else {
                        _orderBloc.box.lpnNumber = value.toString();
                      }
                      scanController.editingController.text = ''; //limpio el lpn input
                    },
                    validator: (value) {
                      if (value == null || value.trim().isEmpty) {
                        return 'error campo nulo';
                      }

                      return null;
                    },
                  ),
                ))),
        Align(
          alignment: Alignment.centerRight,
          child: GestureDetector(
            onTap: () {
              scanController.scan();
            },
            child: Container(
                padding: const EdgeInsets.only(
                  left: 20,
                ),
                margin: const EdgeInsets.only(
                  right: 20,
                ),
                decoration: const BoxDecoration(
                    border: Border(left: BorderSide(color: Colors.black, width: 1))),
                width: 50,
                height: 50,
                child: Center(
                  child: Image.asset(
                    'lib/assets/Images/barcode.png',
                  ),
                )),
          ),
        ),
      ],
    );
  }
}
