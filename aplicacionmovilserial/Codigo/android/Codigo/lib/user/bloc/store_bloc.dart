import 'dart:async';
import 'dart:convert';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/user/model/store_model.dart';
import 'package:almaviva_integration/user/repository/labels_server_repository.dart';
import 'package:almaviva_integration/user/repository/store_server_repository.dart';
import 'package:flutter/widgets.dart';
import 'package:http/http.dart';

class StoreBloc extends Bloc with ChangeNotifier {
  StoreModel selectedStore = StoreModel(storeId: 0, name: 'Seleccionar');
  late List<StoreModel> stores = [];

  final StoreConsultRepository _userStore = StoreConsultRepository();
  final LabelsConsultRepository _labelsConsultRepository = LabelsConsultRepository();

  final StreamController<int> _notifyLoseConnection = StreamController();
  Stream<int> get notifyLoseConnection => _notifyLoseConnection.stream;

  void setSelecttedItem(String selectedItemUser, int selectedItemIdUser) {
    selectedStore.storeId = selectedItemIdUser;
    selectedStore.name = selectedItemUser;
    notifyListeners();
  }

  void saveSelectedStore() {
    final box = HiveDB.getBoxUser();
    box.put('store', selectedStore.storeId);
  }

  void consultStore() async {
    Response response = await _userStore.storeConsultRepository();
    if (response.statusCode <= 300) {
      //se decodifica la respuesta
      var jsonString = json.decode(response.body);

      stores.clear();
      for (var store in jsonString) {
        stores.add(StoreModel.fromJson(store));
      }
      updateLabels();
      notifyListeners();
    } else {
      _notifyLoseConnection.sink.add(220);
    }
  }

  //array de etiquetas de seriales
  Future<void> updateLabels() async {
    Response response = await _labelsConsultRepository.labelsConsultRepository();

    if (response.statusCode <= 300) {
      var jsonString = json.decode(response.body);

      final box = HiveDB.getBoxWorkOrder();
      box.put('labels', jsonString);
    } else {
      _notifyLoseConnection.sink.add(220);
    }
  }

  @override
  void dispose() {
    _notifyLoseConnection.close();
    super.dispose();
  }
}
