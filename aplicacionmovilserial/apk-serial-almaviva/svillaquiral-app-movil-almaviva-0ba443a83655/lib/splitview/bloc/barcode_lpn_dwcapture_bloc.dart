import 'dart:async';

import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/splitview/repository/datawedge_scan_active_repository.dart';
import 'package:flutter/cupertino.dart';

import 'package:almaviva_integration/bloc/bloc_base.dart';

// ignore: import_of_legacy_library_into_null_safe

class BarcodeDatawedgeBloc extends Bloc with ChangeNotifier {
  final DatawedgeServiceRepository _datawedgeService = DatawedgeServiceRepository();
  StreamSubscription? subscription;
  TextEditingController editingController = TextEditingController();
  List<String> listOfCodes = [];
  int qtUnits = 0;

  void setConfigScanner(units) {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel materialConfigModel = box.get('currentMaterialConfig');
    qtUnits = (materialConfigModel.prefix.keys.length * units).toInt();
    _datawedgeService
        .stablishTotalQuantity((materialConfigModel.prefix.keys.length * units).toInt());
    _datawedgeService.modifySettings(
        numberOfCodes: materialConfigModel.prefix.keys.length * units,
        timer: 20000,
        reportInstantly: true,
        beamWidth: 0,
        aimType: 7);
  }

  void listenOnAnyEvent() {
    _datawedgeService.createProfile("DataWedgeFlutterDemo");
    _datawedgeService.listenScanResult();
    subscription = _datawedgeService.eventListOnDatawedge.listen((event) {
      listOfCodes = event;
      print("evento: $listOfCodes");
      notifyListeners();
    });
  }

  void listenOnlyOneEvent() {
    _datawedgeService.createProfile("DataWedgeFlutterDemo");
    _datawedgeService.listenScanResult();
    try {
      subscription = _datawedgeService.eventListOnDatawedge.listen((event) {
        editingController.text = event.last;
        stopSessionScanner();
        notifyListeners();
      });
    } catch (e) {
      print("error: $e");
    }
  }

  String get getSannedValue => editingController.text;

  void scan() {
    _datawedgeService.activeSessionScanner();
  }

  void applyConfiguration() {
    _datawedgeService.modifySettings(
        numberOfCodes: 2, timer: 5000, reportInstantly: true, beamWidth: 0, aimType: 7);
  }

  void stopSessionScanner() {
    print("se detuvo");
    _datawedgeService.stopSessionScanner();
  }

  void unListenOnEvent() {
    print("subcription cancel");
    subscription?.cancel();
  }

  void resumeSubscription() {
    subscription?.resume();
  }

  void stablishTotalQuantity() {
    _datawedgeService.stablishTotalQuantity(2);
  }

  void saveResults() {
    var box = HiveDB.getBoxWorkOrder();
    print(listOfCodes.length);

    box.put("result", listOfCodes);
    print("guarde");
  }

  bool verifyQuantityUnits() {
    if (qtUnits != listOfCodes.length) {
      return false;
    }
    return true;
  }

  void clearListOfCodes() {
    listOfCodes.clear();
    _datawedgeService.resetReportInstantlyVariables();
    notifyListeners();
  }

  void discardBarcode(index) {
    listOfCodes.removeAt(index);

    // updateListTitles();
    notifyListeners();
  }

  @override
  void dispose() {
    unListenOnEvent();
    super.dispose();
  }
}
