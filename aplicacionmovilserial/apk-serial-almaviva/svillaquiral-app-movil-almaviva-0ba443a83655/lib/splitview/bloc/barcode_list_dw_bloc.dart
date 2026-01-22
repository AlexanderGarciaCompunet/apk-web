import 'dart:async';
import 'dart:convert';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/splitview/repository/datawedge_scan_active_repository.dart';
import 'package:almaviva_integration/splitview/repository/send_material_repository.dart';
import 'package:flutter/cupertino.dart';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:http/http.dart';

class BarcodeListDatawedgeBloc extends Bloc with ChangeNotifier {
  final DatawedgeServiceRepository _datawedgeService = DatawedgeServiceRepository();
  final SendMaterialRepository _sendMaterialRepository = SendMaterialRepository();
  Map<int, List<String>> matrixBarcodes = {};
  int totalQuantity = 0;
  int quantityUnits = 0;
  int quantityOfCodes = 0;

  Stream<int> get messagePopUp => _messagePopUp.stream;
  final StreamController<int> _messagePopUp = StreamController();

  void establishMatrixOfCodes(int units) {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel materialConfigModel = box.get('currentMaterialConfig');
    var results = box.get('result');

    _datawedgeService.establishMatrixOfCodes(materialConfigModel.prefix);
    _datawedgeService.establishGroups(materialConfigModel.groups);

    _datawedgeService.establishTotalQuantityByColumn(units);
    matrixBarcodes = _datawedgeService.analyzeBarcodeList(results);
    quantityUnits = units;
    quantityOfCodes = matrixBarcodes.keys.length;
    matrixBarcodes.forEach((key, value) => totalQuantity += value.length);
    notifyListeners();
  }

  void clearHive() {
    var box = HiveDB.getBoxWorkOrder();
    var result = box.get('result');
    MaterialConfigModel materialConfigModel = box.get("currentMaterialConfig");
    for (var key in materialConfigModel.prefix.keys) materialConfigModel.prefix[key] = "";

    result.clear();
    box.put("result", result);
    box.put("currentMaterialConfig", materialConfigModel);
  }

  void updateMaterialConfigToSend() {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel materialConfigModel = box.get("currentMaterialConfig");
    for (var key in materialConfigModel.prefix.keys) {
      materialConfigModel.prefix[key] = jsonEncode(matrixBarcodes[int.parse(key)]);
    }
    sendMaterialsToServer(materialConfigModel);
  }

  void sendMaterialsToServer(MaterialConfigModel material) async {
    if (!material.prefix.containsValue("")) {
      _messagePopUp.sink.add(123);

      try {
        Response response = await _sendMaterialRepository
            .sendMaterialMatrixRepository(jsonEncode(material.toJson()));
        if (response.statusCode >= 300) {
          _messagePopUp.sink.add(CodeError.ConnectionFailed.valueCode);
          return;
        }

        var jsonString = json.decode(response.body);

        if (jsonString["message"] == "122") _messagePopUp.sink.add(122);
        if (jsonString["message"] == "120") _messagePopUp.sink.add(120);
      } catch (e) {
        _messagePopUp.sink.add(CodeError.ConnectionFailed.valueCode);
      }
    }
  }

  void discardBarcode(int indexOfList, int index) {
    var box = HiveDB.getBoxWorkOrder();
    var results = box.get("result");
    results.removeWhere((barcode) => barcode == matrixBarcodes[indexOfList]![index]);
    matrixBarcodes[indexOfList]!.removeAt(index);

    if (totalQuantity >= 1) totalQuantity -= 1;
    box.put('result', results);
    // updateListTitles();
    notifyListeners();
  }

  void clearBarcodes() {
    var box = HiveDB.getBoxWorkOrder();
    matrixBarcodes.clear();
    box.put('result', matrixBarcodes);
    notifyListeners();
  }

  @override
  void dispose() {
    _messagePopUp.close();
    super.dispose();
  }
}
