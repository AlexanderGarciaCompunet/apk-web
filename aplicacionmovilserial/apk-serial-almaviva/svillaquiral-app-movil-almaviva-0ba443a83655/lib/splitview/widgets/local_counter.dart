import 'package:almaviva_integration/splitview/bloc/barcode_material_matrix_bloc.dart';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../bloc/barcode_lpn_dwcapture_bloc.dart';

class LocalCounterMaterial extends StatelessWidget {
  final int typeCounter;

  const LocalCounterMaterial({Key? key, required this.typeCounter}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
        width: 100,
        height: 30,
        decoration: const BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(20)),
            color: Color.fromRGBO(136, 136, 136, 1)),
        child: typeCounter == 0 ? counterDWProvider() : counterProvider());
  }

  Widget counterDWProvider() {
    return Center(child: Consumer<BarcodeDatawedgeBloc>(
      builder: (_, bloc, __) {
        return Text(
          '${bloc.listOfCodes.length}',
          style: const TextStyle(color: Colors.white, fontSize: 12),
        );
      },
    ));
  }

  Widget counterProvider() {
    return Center(child: Consumer<MatrixMaterialScanBloc>(
      builder: (_, bloc, __) {
        return Text(
          '${bloc.materialCounter}',
          style: const TextStyle(color: Colors.white, fontSize: 12),
        );
      },
    ));
  }
}
