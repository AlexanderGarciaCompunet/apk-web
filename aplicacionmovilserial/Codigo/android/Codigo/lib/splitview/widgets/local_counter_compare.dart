import 'package:almaviva_integration/splitview/bloc/barcode_list_bloc.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_list_dw_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class LocalCounterCompareMaterial extends StatelessWidget {
  final int type;
  const LocalCounterCompareMaterial({
    Key? key,
    required this.type,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
        width: 100,
        height: 30,
        decoration: const BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(20)),
            color: Color.fromRGBO(136, 136, 136, 1)),
        child: counterProvider());
  }

  Widget counterProvider() {
    return Center(
        child: type == 0
            ? Consumer<BarcodeListBloc>(
                builder: (_, bloc, __) {
                  return Text(
                    '${bloc.totalQuantity}/${bloc.quantityUnits * bloc.quantityOfCodes}',
                    style: const TextStyle(color: Colors.white, fontSize: 12),
                  );
                },
              )
            : Consumer<BarcodeListDatawedgeBloc>(
                builder: (_, bloc, __) {
                  return Text(
                    '${bloc.totalQuantity}/${bloc.quantityUnits * bloc.quantityOfCodes}', //TO DO: implementar el total de unidades y de codigos comparación
                    style: const TextStyle(color: Colors.white, fontSize: 12),
                  );
                },
              ));
  }
}
