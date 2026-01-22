import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:flutter/material.dart';

class SamplingMaterialBloc extends Bloc with ChangeNotifier {
  late MaterialConfigModel materialConfigModel =
      MaterialConfigModel(customerId: 0, orderId: 0, materialId: 0, prefix: {});

  void addSelectionToPrefix(String id, String selection) {
    if (!materialConfigModel.prefix.containsValue(selection)) {
      materialConfigModel.prefix[id] = selection;
    }
  }

  bool validateSamplingMaterial(MaterialConfigModel material) {
    return materialConfigModel.prefix.values.length == material.prefix.values.length ? true : false;
  }
}
