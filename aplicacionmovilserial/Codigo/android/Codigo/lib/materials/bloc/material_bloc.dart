// ignore_for_file: avoid_print

import 'dart:convert';

import 'package:almaviva_integration/bloc/bloc_base.dart';

import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';

import 'package:almaviva_integration/materials/models/material_model.dart';
import 'package:almaviva_integration/materials/repository/material_repository.dart';
import 'package:almaviva_integration/orders/models/order_model.dart';
import 'package:flutter/cupertino.dart';

import 'package:http/http.dart';

class MaterialBloc extends Bloc with ChangeNotifier {
  int counter = 0;
  late List<MaterialModel> materials = [];
  late List<MaterialModel> auxiliarMaterialList = [];
  final MaterialListServerRepository _materialListServerRepository = MaterialListServerRepository();

  void addOne() {
    counter = counter + 1;
    notifyListeners();
  }

  void removeOne() {
    if (counter > 0) {
      counter = counter - 1;
    }

    notifyListeners();
  }

  void updateMaterialPosition(dynamic materialToUpdate) {
    for (MaterialModel material in materials) {
      if (materialToUpdate['pos_id'] == material.id) {
        material.currentQuantity = materialToUpdate['value'];
        notifyListeners();
      }
    }
  }

  void searchByCodePosition(String code) {
    if (code.isNotEmpty) {
      auxiliarMaterialList = materials;
      materials = [];

      for (var material in auxiliarMaterialList) {
        if (material.itemCode.toLowerCase().contains(code.toLowerCase())) {
          materials.add(material);
        }
      }

      notifyListeners();
    } else {
      getMyMaterials();
    }
  }

  Future<int> getMyMaterials() async {
    final workOrder = HiveDB.getBoxWorkOrder();

    OrderModel currentOrder = workOrder.get("currentOrder");

    try {
      Response response =
          await _materialListServerRepository.materialListServerRepository(currentOrder.id);
      var jsonString = json.decode(response.body);
      print('Ejecutando Consulta MATERIALS: $jsonString');
      if (jsonString['status'] == "success") {
        materials.clear();
        for (var material in jsonString['data']) {
          await Future.delayed(const Duration(milliseconds: 100));
          if (material.keys.length != 0) materials.add(MaterialModel.fromJson(material));
        }
      }
      notifyListeners();
      return CodeError.ConnectionSucces.valueCode;
    } catch (e) {
      print('Error al obtener los materiales en el BLOC: $e');
    }
    return CodeError.ConnectionFailed.valueCode;
  }
}
