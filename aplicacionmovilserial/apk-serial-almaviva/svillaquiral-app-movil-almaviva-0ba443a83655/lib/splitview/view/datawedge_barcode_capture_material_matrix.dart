import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_lpn_dwcapture_bloc.dart';
import 'package:almaviva_integration/splitview/widgets/sliver_list_lecture_item.dart';

import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/widgets/lecture_model_widget.dart';
import 'package:flutter/cupertino.dart';

import 'package:flutter/material.dart';

import 'package:provider/provider.dart';
import 'package:flutter/widgets.dart' as widgets;

import '../widgets/local_counter.dart';

// ignore: must_be_immutable
class DatawedgeScanScreen extends StatefulWidget {
  const DatawedgeScanScreen({Key? key}) : super(key: key);

  @override
  State<StatefulWidget> createState() =>
      // ignore: no_logic_in_create_state
      _DatawedgeScanScreen();
}

class _DatawedgeScanScreen extends State<DatawedgeScanScreen> with WidgetsBindingObserver {
  final scanController = BarcodeDatawedgeBloc();

  @override
  void initState() {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);

    scanController.applyConfiguration();
    scanController.setConfigScanner(
        _orderBloc.unitsCount == 0 ? _orderBloc.box.quantityUnits : _orderBloc.unitsCount);
    scanController.listenOnAnyEvent();
    super.initState();
  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    widgets.Size size = MediaQuery.of(context).size;

    return Scaffold(
        body: ChangeNotifierProvider(
      create: (context) => scanController,
      child: Stack(
        children: <Widget>[
          Container(
            width: size.width,
            height: size.height,
            color: Colors.white38,
            child: MediaQuery.removePadding(
              context: context,
              removeTop: true,
              child: Column(
                children: <Widget>[
                  const HeaderTop(
                    color: Color.fromRGBO(255, 21, 31, 1),
                    path: 'lib/assets/Images/Logo_blanco.png',
                  ),
                  widgets.Row(
                    children: [
                      widgets.Container(
                          alignment: Alignment.bottomLeft,
                          margin: const widgets.EdgeInsets.only(left: 30),
                          child: BackButtonWidget(
                            type: 0,
                            callback: () {
                              _showPopUp(
                                  'Advertencia',
                                  'Esta apunto de abandonar el conteo, desea confirmar',
                                  true,
                                  context);
                            },
                          )),
                      const Expanded(child: SizedBox()),
                      const LocalCounterMaterial(
                        typeCounter: 0,
                      ),
                      const SizedBox(
                        width: 10,
                      ),
                      const LectureModeWidget(),
                    ],
                  ),
                  const Expanded(
                    child: CustomScrollView(
                      physics: BouncingScrollPhysics(),
                      slivers: [ResultOfCaptures()],
                    ),
                  ),
                  const SizedBox(
                    height: 20,
                  ),
                  const SizedBox(
                    height: 30,
                  ),
                ],
              ),
            ),
          ),
          Container(
            margin: const EdgeInsets.all(20),
            alignment: Alignment.bottomCenter,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                SizedBox(
                  width: 60,
                  height: 60,
                  child: FloatingActionButton(
                    backgroundColor: const Color.fromRGBO(255, 21, 31, 1),
                    heroTag: "btresult",
                    elevation: 8,
                    child: const Icon(
                      Icons.check,
                      color: Colors.white,
                    ),
                    onPressed: () {
                      scanController.stopSessionScanner();
                      if (scanController.verifyQuantityUnits()) {
                        scanController.saveResults();
                        Navigator.pushNamed(context, BCRoutes.ListBarcodeDWScreen.routeName);
                      } else {
                        _showPopUp(
                            'Advertencia',
                            'Inconsistencia en la lectura, por favor vuelva a leer',
                            false,
                            context);
                        scanController.clearListOfCodes();
                      }
                    },
                  ),
                ),
                const SizedBox(
                  width: 10,
                ),
                SizedBox(
                  width: 60,
                  height: 60,
                  child: FloatingActionButton(
                    backgroundColor: const Color.fromRGBO(255, 21, 31, 1),
                    heroTag: "btcapture",
                    elevation: 8,
                    child: const Icon(
                      Icons.camera_alt_rounded,
                      color: Colors.white,
                    ),
                    onPressed: () {
                      scanController.scan();
                    },
                  ),
                ),
                const SizedBox(
                  width: 10,
                ),
                SizedBox(
                  width: 60,
                  height: 60,
                  child: FloatingActionButton(
                    backgroundColor: const Color.fromRGBO(255, 21, 31, 1),
                    heroTag: "btdelete",
                    elevation: 8,
                    child: const Icon(
                      Icons.delete,
                      color: Colors.white,
                    ),
                    onPressed: () {
                      scanController.clearListOfCodes();
                    },
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    ));
  }

  _showPopUp(String title, String content, bool actions, BuildContext contextFunction) {
    return showDialog(
        context: contextFunction,
        builder: (BuildContext context) => CupertinoAlertDialog(
              title: Text(title,
                  style: const TextStyle(
                      fontWeight: FontWeight.w500, color: Color.fromRGBO(216, 21, 34, 1))),
              content: Text(content),
              actions: actions
                  ? [
                      TextButton(
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          child: const Text("Cancelar",
                              style: TextStyle(
                                  fontWeight: FontWeight.w500,
                                  color: Color.fromARGB(255, 78, 78, 78)))),
                      TextButton(
                          onPressed: () {
                            var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
                            if (_orderBloc.lectureMode == "Unidades" &&
                                _orderBloc.lectureMode == "Caja") {
                              Navigator.popUntil(context,
                                  ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
                              return;
                            }
                            if (_orderBloc.incrementBoxCounter()) {
                              Navigator.popUntil(context,
                                  ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
                              return;
                            }
                            Navigator.popUntil(
                                context, ModalRoute.withName(BCRoutes.BoxCountScreen.routeName));
                          },
                          child: const Text("Confirmar",
                              style: TextStyle(
                                  fontWeight: FontWeight.w500,
                                  color: Color.fromRGBO(216, 21, 34, 1)))),
                    ]
                  : [],
            ));
  }
}
