import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_list_bloc.dart';
import 'package:almaviva_integration/splitview/widgets/clear_button_red.dart';
import 'package:almaviva_integration/splitview/widgets/list_serials.dart';
import 'package:almaviva_integration/splitview/widgets/local_counter_compare.dart';
import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/widgets/primary_red_button.dart';
import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:provider/provider.dart';

class ListBarcodeScreen extends StatefulWidget {
  const ListBarcodeScreen({Key? key}) : super(key: key);

  @override
  State<ListBarcodeScreen> createState() => _ListBarcodeScreenState();
}

class _ListBarcodeScreenState extends State<ListBarcodeScreen> {
  BarcodeListBloc barcodeListBloc = BarcodeListBloc();

  @override
  void initState() {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    barcodeListBloc.initVariablesWithHive(
        qtUnits: _orderBloc.unitsCount == 0 ? _orderBloc.box.quantityUnits : _orderBloc.unitsCount);
    barcodeListBloc.updateListTitles();
    barcodeListBloc.messagePopUp.listen((codeError) {
      codeError == 123
          ? CodesErrorsCustom.alertLectureError(codeError, context)
          : _actionForEventPopUp(codeError);
    });
    super.initState();
  }

  void _actionForEventPopUp(int codeError) async {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    CodesErrorsCustom.alertLectureError(codeError, context).then((value) {
      if (_orderBloc.lectureMode == "Unidades") {
        Navigator.popUntil(context, ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
      } else if (_orderBloc.lectureMode == "Pallet") {
        if (_orderBloc.incrementBoxCounter()) {
          Navigator.popUntil(context, ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
        } else {
          Navigator.popUntil(context, ModalRoute.withName(BCRoutes.BoxCountScreen.routeName));
        }
      } else {
        Navigator.popUntil(context, ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return ChangeNotifierProvider(
      create: (context) => barcodeListBloc,
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
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          BackButtonWidget(
                            type: 0,
                            callback: () {
                              Navigator.pop(context, false);
                              barcodeListBloc.clearHive();
                            },
                          ),
                          const LocalCounterCompareMaterial(
                            type: 0,
                          ),
                          ClearButtonRed(
                            type: 0,
                          ),
                        ],
                      ),
                      const SizedBox(
                        height: 20,
                      ),
                      const Text(
                        'Resultados de Captura',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: AppColors.primary,
                            fontSize: 20,
                            fontWeight: FontWeight.w500),
                      ),
                      const SizedBox(
                        height: 20,
                      ),
                      Expanded(
                          child: CustomScrollView(
                        slivers: [
                          SliverList(
                              delegate: SliverChildBuilderDelegate(
                            (context, index) {
                              if (barcodeListBloc.matrixBarcodes.keys.isNotEmpty) {
                                return Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: <Widget>[
                                    ValueListenableBuilder(
                                      builder: (_, dynamic box, __) {
                                        var value =
                                            barcodeListBloc.matrixBarcodes.keys.toList()[index];
                                        return Container(
                                          margin: const EdgeInsets.all(8.0),
                                          padding: const EdgeInsets.all(8.0),
                                          child: Text(
                                            '${box.get('labels')[value - 1]['description']}:',
                                            style:
                                                const TextStyle(color: Colors.grey, fontSize: 15),
                                          ),
                                        );
                                      },
                                      valueListenable:
                                          Hive.box('workorder').listenable(keys: ['labels']),
                                    ),
                                    ListOfSerials(
                                      indexOfList:
                                          barcodeListBloc.matrixBarcodes.keys.toList()[index],
                                      type: 0,
                                    )
                                  ],
                                );
                              } else {
                                return const Text("Listas Vacías");
                              }
                            },
                            childCount: barcodeListBloc.matrixBarcodes.keys.length,
                          ))
                        ],
                      )),
                      PrimaryRedButton(
                          title: 'Terminar',
                          callback: () {
                            barcodeListBloc.finishAndCloseOrder();
                          }),
                      const SizedBox(
                        height: 5,
                      ),
                    ],
                  ),
                ),
              ),
              const HeaderTop(
                color: AppColors.primary,
                path: 'lib/assets/images/Logo_Compunet_blanco.png',
              )
            ],
          ),
        ),
      ),
    );
  }
}
