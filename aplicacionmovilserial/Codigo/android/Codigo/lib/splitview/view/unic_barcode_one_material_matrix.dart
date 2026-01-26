import 'dart:ui';

import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/splitview/bloc/barcode_material_matrix_bloc.dart';
import 'package:almaviva_integration/splitview/widgets/local_counter.dart';

import 'package:almaviva_integration/widgets/back_button_widget.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/widgets/lecture_model_widget.dart'; 

import 'package:flutter/material.dart';

import 'package:permission_handler/permission_handler.dart';
import 'package:provider/provider.dart';
import 'package:flutter/widgets.dart' as widgets;

import 'package:scandit_flutter_datacapture_core/scandit_flutter_datacapture_core.dart';

// ignore: must_be_immutable
class UnicMatrixScanScreen extends StatefulWidget {
  DataCaptureContext dataCaptureContext;
  UnicMatrixScanScreen(this.dataCaptureContext, {Key? key}) : super(key: key);

  // Create data capture context using your license key.
  @override
  State<StatefulWidget> createState() =>
      // ignore: no_logic_in_create_state
      _UnicMatrixScanScreen(MatrixMaterialScanBloc(dataCaptureContext));
}

class _UnicMatrixScanScreen extends State<UnicMatrixScanScreen> with WidgetsBindingObserver {
  final MatrixMaterialScanBloc _bloc;
  // ignore: unused_field
  bool _isPermissionMessageVisible = false;
  bool _isCapturingEnabled = true;

  _UnicMatrixScanScreen(this._bloc);
  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      _bloc.onResume();
      _bloc.captureContext.addMode(_bloc.barcodeTracking);
      _bloc.switchCameraOn();
    } else if (state == AppLifecycleState.paused) {
      _bloc.captureContext.removeMode(_bloc.barcodeTracking);
      _bloc.switchCameraOff();
    }
  }

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance?.addObserver(this);
    asingMaterialQuantity();
    _bloc.messagePopUp.listen((event) {
      CodesErrorsCustom.genericAlertAction(event, context);
    });

    _bloc.isCapturing.listen((enabled) {
      setState(() {
        _isCapturingEnabled = enabled;
      });
    });
    _checkPermission();
  }

  void asingMaterialQuantity() {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    if (_orderBloc.lectureMode != "Unidades") {
      _bloc.materialQuantity = _orderBloc.box.quantityUnits;
      _bloc.updateMaterialCounter(_orderBloc.box.currentQuantity);
    } else {
      _bloc.materialQuantity = _orderBloc.unitsCount;
    }
  }

  @override
  Widget build(BuildContext context) {
    widgets.Size size = MediaQuery.of(context).size;
    return ChangeNotifierProvider(
      create: (context) => _bloc,
      child: Scaffold(
          body: Stack(
        children: <Widget>[
          Container(
            width: size.width,
            height: size.height,
            color: Colors.white38,
            child: MediaQuery.removePadding(
              context: context,
              removeTop: true,
              child: ListView(
                children: <Widget>[
                  const HeaderTop(
                    color: AppColors.primary,
                    path: 'lib/assets/images/Logo_Compunet_blanco.png',
                  ),
                  widgets.Row(
                    children: [
                      widgets.Container(
                          alignment: Alignment.bottomLeft,
                          margin: const widgets.EdgeInsets.only(left: 30),
                          child: BackButtonWidget(
                            type: 0,
                            callback: () {
                              if (_bloc.materialCounter < _bloc.materialQuantity) {
                                Navigator.pop(context);
                              }
                            },
                          )),
                      const Expanded(child: SizedBox()),
                      const LocalCounterMaterial(
                        typeCounter: 1,
                      ),
                      const SizedBox(
                        width: 10,
                      ),
                      const LectureModeWidget(),
                    ],
                  ),
                  const SizedBox(
                    height: 20,
                  ),
                  SizedBox.fromSize(
                    child: Stack(
                      children: <Widget>[
                        Positioned(
                          child: _bloc.captureView,
                        ),
                        Positioned(
                          child: _getTapToContinueOverlayWidget(),
                        ),
                      ],
                    ),
                    size: widgets.Size(370, size.height),

                    /// 1.21
                  ),
                  const SizedBox(
                    height: 30,
                  ),
                ],
              ),
            ),
          ),
          Container(
            alignment: Alignment.bottomCenter,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 20),
                  width: 80,
                  height: 80,
                  child: FloatingActionButton(
                    backgroundColor: AppColors.primary,
                    heroTag: "btresult",
                    elevation: 8,
                    child: const Icon(
                      Icons.check,
                      color: Colors.white,
                    ),
                    onPressed: () {
                      _bloc.finishOrder();

                      _bloc.switchCameraOff();
                      Navigator.pushNamed(context, BCRoutes.ListBarcodeScreen.routeName)
                          .then((value) {
                        _bloc.updateListTitles();
                        _bloc.switchCameraOff();
                        _bloc.switchCameraOn();
                        _bloc.resetScanResult();
                        _bloc.instant = 1;
                      });
                    },
                  ),
                ),
                SizedBox(
                  width: 80,
                  height: 80,
                  child: FloatingActionButton(
                    backgroundColor: AppColors.primary,
                    heroTag: "btcapture",
                    elevation: 8,
                    child: const Icon(
                      Icons.camera_alt_rounded,
                      color: Colors.white,
                    ),
                    onPressed: () {
                      _bloc.capturedEnable();
                    },
                  ),
                ),
              ],
            ),
          ),
        ],
      )),
    );
  }

  Widget _getTapToContinueOverlayWidget() {
    return Visibility(
      maintainSize: true,
      maintainAnimation: true,
      maintainState: true,
      child: GestureDetector(
        onTap: () {
          _bloc.resumeCapturing();
        },
        child: Center(
          child: ClipRect(
            child: BackdropFilter(
              filter: ImageFilter.blur(sigmaX: 10.0, sigmaY: 10.0),
              child: Container(
                decoration: BoxDecoration(color: Colors.black87.withOpacity(0.7)),
                child: const Center(
                  child: Text(
                    'Tap to continue',
                    style: TextStyle(color: Colors.white, fontSize: 16),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      visible: !_isCapturingEnabled,
    );
  }

  void _checkPermission() {
    if (mounted) {
      Permission.camera.request().isGranted.then((value) => setState(() {
            _isPermissionMessageVisible = !value;
            if (value) {
              _bloc.switchCameraOn();
            }
          }));
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance?.removeObserver(this);
    try {
      _bloc.dispose();
    } catch (e) {
      print(e);
    }
    super.dispose();
  }
}
