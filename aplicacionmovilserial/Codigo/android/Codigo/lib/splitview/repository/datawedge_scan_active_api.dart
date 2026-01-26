import 'dart:async';
import 'dart:convert';

import 'package:flutter/services.dart';

class DatawedgeService {
  final MethodChannel _methodChannel = const MethodChannel('com.compunet.almaviva/command');
  EventChannel scanChannel = const EventChannel("com.compunet.almaviva/scan");
  StreamSubscription? _streamSubscription;

  final StreamController<Map<int, List<String>>> _streamController =
      StreamController<Map<int, List<String>>>();

  final StreamController<List<String>> _streamListCodes = StreamController<List<String>>();

  Map<int, List<String>> matrixOfCodes = {1: [], 2: [], 3: [], 4: []};
  //variables
  int totalQuantity = 0;

  List<String> listOfCodes = [];
  int quantityUnitsByColumn = 0;
  int groups = 0;

  Stream<Map<int, List<String>>> get eventOnDatawedge => _streamController.stream;

  Stream<List<String>> get eventListOnDatawedge => _streamListCodes.stream;

  Future<void> _sendDataWedgeCommand(String command, String parameter) async {
    try {
      String argumentAsJson = jsonEncode({"command": command, "parameter": parameter});

      await _methodChannel.invokeMethod('sendDataWedgeCommandStringParameter', argumentAsJson);
    } on PlatformException {
      //  Error invoking Android method
    }
  }

  Future<void> printVersion() async {
    try {
      final result = await _methodChannel.invokeMethod("version");
      // ignore: avoid_print
      print(result);
    } catch (e) {
      print("Error: $e");
    }
  }

  Future<void> createProfile(String profileName) async {
    try {
      await _methodChannel.invokeMethod('createDataWedgeProfile', profileName);
    } on PlatformException {
      //  Error invoking Android method
    }
  }

  void changeStateOfSubscription() {
    _streamSubscription!.isPaused ? _streamSubscription!.resume() : _streamSubscription!.pause();
  }

  void listenScanResult() {
    _streamSubscription = scanChannel.receiveBroadcastStream().listen((event) {
      var eventDecode = json.decode(event);

      if (eventDecode["data"].length <= 1) {
        // if (cont <= totalQuantity) {
        print("si estoy imprimiendo:${eventDecode["data"]}");
        if (!listOfCodes.contains(eventDecode["data"].last.toString()))
          listOfCodes.add(eventDecode["data"].last.toString());

        _streamListCodes.sink.add(listOfCodes);

        // }
        if (listOfCodes.length == totalQuantity) {
          _sendDataWedgeCommand("com.symbol.datawedge.api.SOFT_SCAN_TRIGGER", "STOP_SCANNING");
          // analyzeBarcodeList(listOfCodes);
        }
      }
      // else {
      //   analyzeBarcodeList(eventDecode["data"].map((e) => e.toString()).toList());
      // }
    });
  }

  void resetReportInstantlyVariables() {
    listOfCodes = [];
  }

  void closeStreamController() {
    _streamController.close();
    _streamListCodes.close();
  }

  Future<void> stopSessionScanner() async {
    try {
      _sendDataWedgeCommand("com.symbol.datawedge.api.SOFT_SCAN_TRIGGER", "STOP_SCANNING");
    } catch (e) {
      print("Error :C : $e");
    }
  }

  Future<void> activeSessionScanner() async {
    try {
      _sendDataWedgeCommand("com.symbol.datawedge.api.SOFT_SCAN_TRIGGER", "START_SCANNING");
    } catch (e) {
      print("Error :C : $e");
    }
  }

  Future<void> modifySettings({numberOfCodes, timer, reportInstantly, beamWidth, aimType}) async {
    // context: Context, numberOfBarcodesPerScan: Int, vbReportInstantly: Boolean, timer : Int, Beam_Width:Int}
    print("reporte $reportInstantly");
    try {
      String argumentAsJson = jsonEncode({
        "numberOfCodes": numberOfCodes,
        "timer": timer,
        "reportInstantly": reportInstantly,
        "aim_type": aimType,
        "beamWidth": beamWidth
      });

      var result = await _methodChannel.invokeMethod('setConfigDataWedge', argumentAsJson);
      print(result);
    } on PlatformException {
      //  Error invoking Android method
    }
  }

  void establishMatrixOfCodes(Map<String, String> matrixReference) {
    matrixOfCodes = {};
    for (var key in matrixReference.keys) {
      matrixOfCodes[int.parse(key)] = [];
    }
  }

  void establishTotalQuantity(int quantity) {
    totalQuantity = quantity;
  }

  void establishGroups(int groups) {
    this.groups = groups;
  }

  void establishTotalQuantityByColumn(int quantity) {
    quantityUnitsByColumn = quantity;
  }

  Map<int, List<String>> analyzeBarcodeList(List<dynamic> barcodes) {
    int keysLenght = matrixOfCodes.keys.length;

    matrixOfCodes = groups == 0
        ? separateListOfCodesByColumn(barcodes, quantityUnitsByColumn)
        : separateListOfCodes(barcodes, keysLenght);

    _streamController.sink.add(matrixOfCodes);

    resetReportInstantlyVariables();
    return matrixOfCodes;
  }

  Map<int, List<String>> separateListOfCodesByColumn(
      List<dynamic> listOfCodes, int quantityOfCodes) {
    print("entre por columnas $listOfCodes");
    Map<int, List<String>> auxMatrix = {};
    List<int> keysOfMatrix = matrixOfCodes.keys.toList();
    int count = 0, auxCount = 0;
    int value = keysOfMatrix[count];

    for (var barcode in listOfCodes) {
      if (count == quantityOfCodes) {
        auxCount++;
        count = 0;
      }
      value = keysOfMatrix[auxCount];
      if (!auxMatrix.keys.contains(value)) auxMatrix[value] = [];
      print("$value ${barcode}");
      auxMatrix[value]!.add(barcode);
      print("matriz auxiliar: $auxMatrix");
      count++;
    }
    return auxMatrix;
  }

  Map<int, List<String>> separateListOfCodes(List<dynamic> listOfCodes, int quantityOfCodes) {
    print("Cantidad: $quantityOfCodes");
    Map<int, List<String>> auxMatrix = {};
    List<int> keysOfMatrix = matrixOfCodes.keys.toList();
    int count = 0;
    int value = keysOfMatrix[count];

    for (var barcode in listOfCodes) {
      if (count == quantityOfCodes) {
        count = 0;
      }
      value = keysOfMatrix[count];
      if (!auxMatrix.keys.contains(value)) auxMatrix[value] = [];
      print("$value ${barcode}");
      auxMatrix[value]!.add(barcode);
      count++;
    }
    return auxMatrix;
  }

  void closeListeners() {
    _streamSubscription?.cancel();
  }
}
