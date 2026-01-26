import 'dart:async';
import 'package:flutter/material.dart' as material;

import 'package:scandit_flutter_datacapture_barcode/scandit_flutter_datacapture_barcode.dart';
import 'package:scandit_flutter_datacapture_barcode/scandit_flutter_datacapture_barcode_tracking.dart';
import 'package:scandit_flutter_datacapture_core/scandit_flutter_datacapture_core.dart';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/splitview/model/barcode_location.dart';

class MatrixMaterialScanBloc extends Bloc
    with material.ChangeNotifier
    implements BarcodeTrackingListener {
  late DataCaptureContext captureContext;
  // Use the world-facing (back) camera.
  final Camera? _camera = Camera.defaultCamera;
  TorchSwitchControl torchSwitchControl = TorchSwitchControl();
  late BarcodeTracking barcodeTracking;
  late DataCaptureView captureView;

  List<BarcodeLocation> scanResults = [];
  List<String> scanResultString = [];
  List<BarcodeLocation> repeatResultsScan = [];

  int instant = 1;
  bool newInstant = false;

  Timer? _timer;
  static Feedback feedback = Feedback.defaultFeedback;

  int materialCounter = 0;
  late int realMaterialCounter = 0;
  late int materialQuantity;
  int seconds = 5;

  final StreamController<bool> _isCapturingStreamController = StreamController();

  Stream<bool> get isCapturing => _isCapturingStreamController.stream;

  Stream<int> get messagePopUp => _messagePopUp.stream;

  final StreamController<int> _messagePopUp = StreamController();

  //Repository's

  MatrixMaterialScanBloc(this.captureContext) {
    init();
  }
  BarcodeTrackingSettings get barcodeTrackingSettings {
    var captureSettings = BarcodeTrackingSettings();

    // The settings instance initially has all types of barcodes (symbologies) disabled. For the purpose of this
    // sample we enable a very generous set of symbologies. In your own app ensure that you only enable the
    // symbologies that your app requires as every additional enabled symbology has an impact on processing times.
    captureSettings.enableSymbologies(symbologiesEnabled());
    barcodeTracking = BarcodeTracking.forContext(captureContext, captureSettings)
      ..addListener(this);

    captureView = DataCaptureView.forContext(captureContext);

    captureView.addOverlay(
        BarcodeTrackingBasicOverlay.withBarcodeTrackingForView(barcodeTracking, captureView));

    if (_camera != null) {
      captureContext.setFrameSource(_camera!);
    }

    _camera?.switchToDesiredState(FrameSourceState.on);

    return captureSettings;
  }

  void onResume() {
    captureViewSetSettings();
  }

  void captureViewSetSettings() {
    var _viewfinder = RectangularViewfinder.withStyleAndLineStyle(
        RectangularViewfinderStyle.legacy, RectangularViewfinderLineStyle.light);
    _viewfinder.setSize(
        SizeWithUnit(DoubleWithUnit(20.0, MeasureUnit.dip), DoubleWithUnit(60.0, MeasureUnit.dip)));
    captureView.addOverlay(
      BarcodeTrackingAdvancedOverlay.withBarcodeTrackingForView(barcodeTracking, captureView)
        ..shouldShowScanAreaGuides = true,
    );

    captureView = DataCaptureView.forContext(captureContext);
    captureView.pointOfInterest = PointWithUnit(
        DoubleWithUnit(0.5, MeasureUnit.fraction), DoubleWithUnit(0.5, MeasureUnit.fraction));

    captureView.addControl(torchSwitchControl);

    BarcodeTrackingBasicOverlay.withBarcodeTrackingForView(barcodeTracking, captureView);
  }

  void init() {
    var cameraSettings = BarcodeTracking.recommendedCameraSettings;
    cameraSettings.preferredResolution = VideoResolution.fullHd;
    cameraSettings.focusGestureStrategy = FocusGestureStrategy.manual;
    _camera?.applySettings(cameraSettings);
    barcodeTracking = BarcodeTracking.forContext(captureContext, barcodeTrackingSettings)
      ..addListener(this);
    captureViewSetSettings();
    captureContext.setFrameSource(_camera!);
    switchCameraOn();
    barcodeTracking.isEnabled = false;
  }

  bool specialBarcodeLecture() {
    MaterialConfigModel materialConfigModel = HiveDB.getBoxWorkOrder().get('currentMaterialConfig');
    int typeOfSymbol = materialConfigModel.configId;
    if (typeOfSymbol == 1 || typeOfSymbol == 2 || typeOfSymbol == 3) {
      return true;
    }
    return false;
  }

  Set<Symbology> symbologiesEnabled() {
    MaterialConfigModel materialConfigModel = HiveDB.getBoxWorkOrder().get('currentMaterialConfig');
    int typeOfSymbol = materialConfigModel.configId;
    switch (typeOfSymbol) {
      case 1:
        return {
          Symbology.qr,
        };
      case 2:
        return {
          Symbology.dataMatrix,
        };
      case 3:
        return {Symbology.pdf417};

      default:
        return {
          Symbology.ean8,
          Symbology.ean13Upca,
          Symbology.upce,
          Symbology.code39,
          Symbology.code128,
          Symbology.interleavedTwoOfFive
        };
    }
  }

  int returnQuantityOfCodes() {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel material = box.get("currentMaterialConfig");
    return material.prefix.keys.length;
  }

  void defineTimeOfCapture() {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel material = box.get("currentMaterialConfig");
    seconds = material.secondsForCaputre;
  }

  void updateMaterialCounter(int currentQuantity) {
    materialCounter = realMaterialCounter = currentQuantity;
    notifyListeners();
  }

  void captureBarcodeEnable() {
    if (_camera!.desiredState == FrameSourceState.on) {
      switchCameraOff();
      _isCapturingStreamController.sink.add(false);
    } else {
      switchCameraOn();
      _isCapturingStreamController.sink.add(true);
    }
    notifyListeners();
  }

  void resumeCapturing() {
    _isCapturingStreamController.sink.add(true);
    switchCameraOn();
  }

  void switchCameraOff() {
    _camera!.switchToDesiredState(FrameSourceState.off);
  }

  void switchCameraOn() {
    _resetPauseCameraTimer();
    _isCapturingStreamController.sink.add(true);
    _camera!.switchToDesiredState(FrameSourceState.on);
  }

  void _resetPauseCameraTimer() {
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 30), (timer) {
      _isCapturingStreamController.sink.add(false);
      _camera!.switchToDesiredState(FrameSourceState.off);
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    _messagePopUp.close();
    _isCapturingStreamController.close();
    barcodeTracking.removeListener(this);
    barcodeTracking.isEnabled = false;
    _camera?.switchToDesiredState(FrameSourceState.off);
    captureContext.removeAllModes();

    super.dispose();
  }

  @override
  void didUpdateSession(BarcodeTracking barcodeTracking, BarcodeTrackingSession session) {
    for (final trackedBarcode in session.addedTrackedBarcodes) {
      if (!repeatScanResultContains(trackedBarcode.barcode.data!)) {
        _resetPauseCameraTimer();
        feedback.emit();

        repeatResultsScan.add(BarcodeLocation(
            trackedBarcode.barcode.data!, trackedBarcode.location.topLeft.toMap(), 0));
      }
      // if (!scanResultString.contains(trackedBarcode.barcode.data)) {
      //   scanResultString.add(trackedBarcode.barcode.data!);
      //   scanResults.add(BarcodeLocation(
      //       trackedBarcode.barcode.data!, trackedBarcode.location.topLeft.toMap(), 0));
      // }
    }
  }

  bool repeatScanResultContains(String barcodeToFind) {
    for (var barcodeRepet in repeatResultsScan) {
      if (barcodeRepet.barcode == barcodeToFind) {
        return true;
      }
    }
    return false;
  }

  void saveRepeatResultScan() async {
    for (var barcode in repeatResultsScan) {
      if (searchInToResults(barcode.barcode)) {
        newInstant = true;
      }
    }
    updateNewInstant();
    for (var barcode in repeatResultsScan) {
      barcode.type = instant.toDouble();
    }
    for (var barcode in repeatResultsScan) {
      if (!scanResultString.contains(barcode.barcode)) {
        scanResults.add(barcode);
        scanResultString.add(barcode.barcode);
        materialCounter++;

        notifyListeners();
      }
    }
  }

  void capturedEnable() {
    repeatResultsScan.clear();
    newInstant = false;

    barcodeTracking.isEnabled = true;

    Future.delayed(Duration(seconds: seconds)).whenComplete(() {
      barcodeTracking.isEnabled = false;
      saveRepeatResultScan();
      _messagePopUp.sink.add(130);
    });
  }

  void updateNewInstant() {
    if (!newInstant) {
      instant++;
    }
    notifyListeners();
  }

  void updateListTitles() {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel material = box.get('currentMaterialConfig');
    scanResults = [];

    materialCounter = realMaterialCounter;
    notifyListeners();
  }

  void resetScanResult() {
    var box = HiveDB.getBoxWorkOrder();
    box.put("result", []);
    scanResultString.clear();
    scanResults.clear();
    notifyListeners();
  }
  // captura por instantes

  void analyzeCapturedCodes(TrackedBarcode trackedBarcode) {
    if (scanResultString.contains(trackedBarcode.barcode.data)) {
      newInstant = false;
      notifyListeners();
    }
  }

  bool searchInToResults(String barcodeToFind) {
    for (var barcode in scanResults) {
      if (barcode.barcode == barcodeToFind) {
        return true;
      }
    }
    return false;
  }

  finishOrder() {
    var box = HiveDB.getBoxWorkOrder();
    printScanResults();
    box.put("result", scanResults);
  }

  printScanResults() {
    for (var result in scanResults) {
      print(result.toJson());
    }
  }
}
