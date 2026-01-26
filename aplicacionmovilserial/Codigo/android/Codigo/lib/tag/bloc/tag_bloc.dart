import 'dart:convert';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/tag/models/tag_model.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart';

import '../../config/errors_code.dart';
import '../../db/db.dart';
import '../../materials/models/material_model.dart';
import '../../orders/models/order_model.dart';
import '../repository/tags_repository.dart';

class TagBloc extends Bloc with ChangeNotifier {
  final _tagRepository = TagRepository();

  List<TagModel> tags = [];
  // traer todas las etiquetas
  Future<int> getMyTags() async {
    final workOrder = HiveDB.getBoxWorkOrder();
    MaterialModel currentMaterial = workOrder.get("currentMaterial");

    try {
      Response response = await _tagRepository.tagListServerRepository(
          currentMaterial.materialId, currentMaterial.customerId);
      var jsonString = json.decode(response.body);
      print('Ejecutando Consulta ETIQUETAS: $jsonString');
      if (jsonString['status'] == "error") return CodeError.ResourceNotFound.valueCode;
      if (jsonString['status'] == "success") {
        tags.clear();
        for (var tag in jsonString['data']) {
          await Future.delayed(const Duration(milliseconds: 100));
          if (tag.keys.length != 0) tags.add(TagModel.fromJson(tag));
        }
        notifyListeners();
        return CodeError.ConnectionSucces.valueCode;
      }
    } catch (e) {
      print('Error al obtener las etiquetas en el BLOC: $e');
    }
    return CodeError.ConnectionFailed.valueCode;
  }

  void selectTagForLecture({boxId, palletId, tag, quantity}) {
    final workOrder = HiveDB.getBoxWorkOrder();
    MaterialModel currentMaterial = workOrder.get("currentMaterial");
    OrderModel currentOrder = workOrder.get("currentOrder");
    var _materialConfigModel = updateMaterialConfig(
        currentOrder: currentOrder, currentMaterial: currentMaterial, tag: tag);
    _materialConfigModel.quantity = quantity;
    _materialConfigModel.lpnId = palletId;
    _materialConfigModel.sublpnId = boxId;
    workOrder.put("currentMaterialConfig", _materialConfigModel);
  }

  MaterialConfigModel updateMaterialConfig({
    required OrderModel currentOrder,
    required MaterialModel currentMaterial,
    required TagModel tag,
  }) {
    var struct = parseStruct(tag.serialty);

    return MaterialConfigModel(
      posId: currentMaterial.id,
      customerId: currentMaterial.customerId,
      orderId: currentOrder.id,
      materialId: currentMaterial.materialId,
      configId: tag.configId,
      epsilon: tag.epsilon,
      groups: tag.groups,
      qtColumns: tag.qtColumns,
      orientation: tag.orientation,
      prefix: struct,
      secondsForCaputre: tag.time,
      typeCapture: tag.typeCapture,
      configLabelId: tag.idTag,
    );
  }

  Map<String, String> parseStruct(Map<String, dynamic> prefix) {
    Map<String, String> prefix2 = prefix.map((key, value) => MapEntry<String, String>(key, ""));

    return prefix2;
  }

  bool specialBarcodeLecture() {
    MaterialConfigModel materialConfigModel = HiveDB.getBoxWorkOrder().get('currentMaterialConfig');
    int typeOfSymbol = materialConfigModel.configId;
    if (typeOfSymbol == 1 || typeOfSymbol == 2 || typeOfSymbol == 3) {
      return true;
    }
    return false;
  }

  String getTypeTag(int configId) {
    switch (configId) {
      case 1:
        return 'QR';
      case 2:
        return 'Datamatrix';
      case 3:
        return 'PDF417';
      default:
        return 'Código de Barras';
    }
  }

  @override
  void dispose() {
    super.dispose();
  }
}
