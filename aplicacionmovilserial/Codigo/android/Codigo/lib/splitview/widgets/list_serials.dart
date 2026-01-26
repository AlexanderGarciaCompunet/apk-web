import 'package:almaviva_integration/splitview/bloc/barcode_list_bloc.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_list_dw_bloc.dart';

import 'package:almaviva_integration/splitview/widgets/tile_barcode_captured.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class ListOfSerials extends StatelessWidget {
  final int indexOfList;
  final int type;

  const ListOfSerials({Key? key, required this.indexOfList, required this.type}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return type == 0 ? _consumerBarcodeListBloc() : _consumerBarcodeListDWBloc();
  }

  Widget _consumerBarcodeListBloc() {
    return Consumer<BarcodeListBloc>(
      builder: (_, _bloc, __) {
        if (_bloc.matrixBarcodes.isNotEmpty && _bloc.matrixBarcodes.keys.contains(indexOfList)) {
          return ListView.builder(
            physics: const ClampingScrollPhysics(),
            shrinkWrap: true,
            padding: const EdgeInsets.all(0),
            itemCount: _bloc.matrixBarcodes[indexOfList]!.length,
            itemBuilder: (context, index) {
              var item = TileBarcodeCaptured(
                  title: "${index + 1}. ${_bloc.matrixBarcodes[indexOfList]![index].barcode}");
              return Dismissible(
                  key: UniqueKey(),
                  onDismissed: (direction) {
                    _bloc.discardBarcode(indexOfList, index);
                  },
                  child: item);
            },
          );
        } else {
          return Column(children: const [Text("Vacío")]);
        }
      },
    );
  }

  Widget _consumerBarcodeListDWBloc() {
    return Consumer<BarcodeListDatawedgeBloc>(
      builder: (_, _bloc, __) {
        if (_bloc.matrixBarcodes.isNotEmpty && _bloc.matrixBarcodes.keys.contains(indexOfList)) {
          return ListView.builder(
            physics: const ClampingScrollPhysics(),
            shrinkWrap: true,
            padding: const EdgeInsets.all(0),
            itemCount: _bloc.matrixBarcodes[indexOfList]!.length,
            itemBuilder: (context, index) {
              var item = TileBarcodeCaptured(
                  title: "${index + 1}. ${_bloc.matrixBarcodes[indexOfList]![index]}");
              return Dismissible(
                  key: UniqueKey(),
                  onDismissed: (direction) {
                    _bloc.discardBarcode(indexOfList, index);
                  },
                  child: item);
            },
          );
        } else {
          return Column(children: const [Text("Vacío")]);
        }
      },
    );
  }
}
