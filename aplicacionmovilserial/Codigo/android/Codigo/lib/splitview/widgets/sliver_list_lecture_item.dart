import 'package:almaviva_integration/splitview/bloc/barcode_lpn_dwcapture_bloc.dart';
import 'package:almaviva_integration/splitview/widgets/tile_barcode_captured.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class ResultOfCaptures extends StatelessWidget {
  const ResultOfCaptures({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final scanController = Provider.of<BarcodeDatawedgeBloc>(context);
    return SliverPadding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 40),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (context, index) {
            if (scanController.listOfCodes.isNotEmpty) {
              var item =
                  TileBarcodeCaptured(title: "${index + 1}. ${scanController.listOfCodes[index]}");
              return Dismissible(
                  key: UniqueKey(),
                  onDismissed: (direction) {
                    scanController.discardBarcode(index);
                  },
                  child: item);
            }
            return null;
          },
          childCount: scanController.listOfCodes.length,
        ),
      ),
    );
  }
}
