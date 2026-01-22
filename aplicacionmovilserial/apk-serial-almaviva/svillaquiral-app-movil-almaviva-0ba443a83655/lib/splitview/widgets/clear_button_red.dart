import 'package:almaviva_integration/splitview/bloc/barcode_list_bloc.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_list_dw_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

// ignore: must_be_immutable
class ClearButtonRed extends StatelessWidget {
  final int type;
  ClearButtonRed({Key? key, required this.type}) : super(key: key);

  var _bloc;
  @override
  Widget build(BuildContext context) {
    if (type == 0) {
      _bloc = Provider.of<BarcodeListBloc>(context, listen: false);
    } else {
      _bloc = Provider.of<BarcodeListDatawedgeBloc>(context, listen: false);
    }
    return Container(
      width: 50,
      height: 50,
      decoration: const BoxDecoration(
          color: Color.fromRGBO(255, 21, 31, 1),
          borderRadius: BorderRadius.all(Radius.circular(30))),
      child: Center(
        child: TextButton(
          onPressed: () {
            _bloc.clearBarcodes();
          },
          child: const Icon(
            Icons.delete,
            color: Colors.white,
          ),
        ),
      ),
    );
  }
}
