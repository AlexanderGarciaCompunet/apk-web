// ignore_for_file: avoid_print

import 'dart:convert';
import 'dart:developer';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';

import 'package:almaviva_integration/orders/repository/finish_order_repository.dart';

import 'package:almaviva_integration/pallet/models/lpn_model.dart';
import 'package:almaviva_integration/materials/models/material_model.dart';

import 'package:almaviva_integration/orders/models/order_model.dart';
import 'package:almaviva_integration/orders/repository/orders_list_repository.dart';
import 'package:almaviva_integration/pallet/repository/send_pallet_repository.dart';

import 'package:flutter/cupertino.dart';
// ignore: library_prefixes
import 'package:socket_io_client/socket_io_client.dart' as IO;
import 'package:http/http.dart';

// este Bloc es el encargado de la manipulación de toda la información del contedo de la orden y compartir la información con el servidor
class OrderBloc extends Bloc with ChangeNotifier {
  final OrdersListServerRepository _ordersListServerRepository = OrdersListServerRepository();
  final SendPalletMasterRepository _sendPalletMasterRepository = SendPalletMasterRepository();
  final SendOrderServerRepository _sendOrderServerRepository = SendOrderServerRepository();

  late LpnModel pallet = LpnModel();
  late LpnModel box = LpnModel();
  late int unitsCount = 0;
  //real time
  Map<String, dynamic> daata = <String, dynamic>{};
  int total = 0;
  late IO.Socket socket;
  //modo de lectura
  late String lectureMode;
  late List<String> newMaterial = [];
  late List<OrderModel> orders = [];
  late List<OrderModel> auxiliarList = [];
  bool selectedTypeCapture = false;

  //limpiando la inforación de las variables pallet y box
  void clearPalletBox() {
    pallet = LpnModel();
    box = LpnModel();
    unitsCount = 0;
  }

  //socket de actualización de orden
  void setUpSocketListener() {
    socket.on('updateOrder', (inOrder) {
      print("inorder $inOrder");
      dynamic orderUpdate = json.decode(inOrder);

      for (OrderModel order in orders) {
        if (orderUpdate['idOrder'] == order.id) {
          orderUpdate['status'] > 0 ? order.status = 1 : order.status = 0;

          notifyListeners();
        }
      }
    });
  }

  //emicion de orden
  void emitUpdateOrders() {
    socket.emit('updateOrders');
  }

  //Emición de conexión a una orden con estatus 1 (en proceso)
  void emitConnectionOrder(OrderModel orderModel) {
    socket.emit('newOperator', {"idOrder": orderModel.id, "status": 1});
  }

  //Emición de desconexión a una orden con estatus 0 (terminada)
  void emitDisconectionOrder() {
    final boxWorkOrder = HiveDB.getBoxWorkOrder();
    OrderModel orderModel = boxWorkOrder.get('currentOrder');
    socket.emit('newOperator', {"idOrder": orderModel.id, "status": 0});
  }

  // actualizar tipo de captura
  void updateTypeCapture(bool typeCapture) {
    selectedTypeCapture = typeCapture;
    notifyListeners();
  }

  //incremento del contador de unidades sea por pallet o por caja retorna true si se encuentra completado
  bool incrementBoxCounter() {
    if (box.status == 120) pallet.currentQuantity++;
    print("AUNSIGO : ${pallet.currentQuantity}");
    updatePercentforUnits();
    notifyListeners();
    return isCompleteThePallet();
  }

  //verificación de si se completó el pallet
  bool isCompleteThePallet() {
    if (pallet.currentQuantity >= pallet.quantityUnits) {
      pallet = box = LpnModel();

      return true;
    }

    return false;
  }

  //actualización del porcentaje de unidades
  void updatePercentforUnits() {
    pallet.percentOfUnits = double.parse(
        ((100 * pallet.currentQuantity / pallet.quantityUnits) / 100).toStringAsFixed(2));
  }

  //Sekección de orden de trabajo
  void selectedOrder(OrderModel orderModel) {
    final boxWorkOrder = HiveDB.getBoxWorkOrder();
    boxWorkOrder.put('currentOrder', orderModel);

    notifyListeners();
  }

  //Selección de material dentro de la orden
  void selectedMaterial(MaterialModel materialModel) {
    final boxWorkOrder = HiveDB.getBoxWorkOrder();
    boxWorkOrder.put('currentMaterial', materialModel);

    notifyListeners();
  }

  //actualización de las ordenes que llegan del servidor
  Future<int> consultForNewOrder() async {
    orders.clear();
    final box = HiveDB.getBoxUser();
    final type = box.get('operation');
    final store = box.get('store');

    try {
      Response response = await _ordersListServerRepository.ordersListServerRepository(type, store);

      var jsonString = json.decode(response.body);

      print('Ejecutando consulta ORDERS ${jsonString}');
      if (jsonString['status'] == "success") {
        for (var order in jsonString['data']) {
          await Future.delayed(const Duration(milliseconds: 100));
          orders.add(OrderModel.fromJson(order));
        }
        notifyListeners();

        return CodeError.ConnectionSucces.valueCode;
      } else {
        orders.clear();
        notifyListeners();
        return CodeError.ResourceNotFound.valueCode;
      }
    } on Exception catch (e) {
      print("error al ejecutar la consulta ordenes");
    }
    return CodeError.ConnectionFailed.valueCode;
  }

  //busqueda de las ordenes por codigo
  void searchByCodePosition(String code) {
    if (code.isNotEmpty) {
      auxiliarList = orders;
      orders = [];
      for (var order in auxiliarList) {
        if (order.orderId.toString().toLowerCase().contains(code.toLowerCase())) {
          orders.add(order);
        }
      }

      notifyListeners();
    } else {
      consultForNewOrder();
    }
  }

  //validando el estado del LPN o SUBLPN
  Future<int> validateLPN(int type) async {
    //if (box.lpnNumber.length == 15 && box.lpnNumber.startsWith("SUB")) {
    // if (lpnRulesValidate(type)) {
    int sendLPN = type == 1 ? await sendLpnPallet() : await sendLpnBox();
    switch (sendLPN) {
      case 122:
        return 122;
      case 121:
        print("Reproceso");
        return 121;
      case 120:
        print("Nuevo");
        return 120;
      case 220:
        print("Fallo de conexión");
        return CodeError.ConnectionFailed.valueCode;
    }
    // }
    print("LPN invalido verifiquelo y vuelva a intentarlo");
    return 123;
    // }
  }

  //Reglas de validación de lpn o sublpn
  bool lpnRulesValidate(int type) {
    if (type == 1) {
      if (pallet.lpnNumber.startsWith("BG") && !pallet.lpnNumber.contains(RegExp(r"-([0-9])\w+"))) {
        return true;
      }
    } else {
      if (box.lpnNumber.startsWith("BG") && box.lpnNumber.contains(RegExp(r"-([0-9])\w+"))) {
        return true;
      }
    }
    return false;
  }

  //actualización de la base de datos local  con el LPN (pallet )
  String updatePalletToJson() {
    final workOrder = HiveDB.getBoxWorkOrder();
    MaterialModel currentMaterial = workOrder.get("currentMaterial");
    OrderModel currentOrder = workOrder.get("currentOrder");
    pallet.setPallet(
        customerId: currentOrder.customerId,
        currentQuantity: 0,
        orderId: currentOrder.id,
        type: 1,
        materialId: currentMaterial.materialId,
        percent: 0);

    String palletJson = json.encode(pallet.toJson());
    return palletJson;
  }

  //actualización de la base de datos local  con el SUBLPN (caja )
  String updateBoxToJson() {
    final workOrder = HiveDB.getBoxWorkOrder();
    MaterialModel currentMaterial = workOrder.get("currentMaterial");
    OrderModel currentOrder = workOrder.get("currentOrder");
    box.setPallet(
        customerId: currentOrder.customerId,
        currentQuantity: 0,
        orderId: currentOrder.id,
        type: 2,
        materialId: currentMaterial.materialId,
        percent: 0);

    box.lpnsup = pallet.lpnId;
    String boxJson = json.encode(box.toJson());

    return boxJson;
  }

  //envio del LPN (pallet )
  Future<int> sendLpnPallet() async {
    String palletJson = updatePalletToJson();
    print("esta es el envio ${palletJson}");
    Response response = await _sendPalletMasterRepository.sendPalletMasterRepository(palletJson);
    print("esta es la respuesta ${response.body.toString()}");

    if (response.statusCode > 300) {
      return CodeError.ConnectionFailed.valueCode;
    } //si la peticion sale mal

    var jsonString = json.decode(response.body);

    if (jsonString['message'] == 122) {
      return 122;
    } else if (jsonString['message'] == 121) {
      pallet.lpnId = jsonString["data"]['id'];
      pallet.quantityUnits = jsonString["data"]['itemcnt'];
      pallet.currentQuantity = jsonString["data"]['real_amount'];
      updatePercentforUnits();
      return 121;
    } else {
      pallet.lpnId = jsonString["data"]['id'];
      return 120;
    }
  }

  //envio del SUBLPN (caja )
  Future<int> sendLpnBox() async {
    String boxJson = updateBoxToJson();

    Response response = await _sendPalletMasterRepository.sendPalletMasterRepository(boxJson);

    if (response.statusCode > 300) return CodeError.ConnectionFailed.valueCode;

    var jsonString = json.decode(response.body);

    if (jsonString['message'] == 122) {
      return 122;
    } else if (jsonString['message'] == 121) {
      box.lpnId = jsonString["data"]['id'];
      box.quantityUnits = jsonString["data"]['itemcnt'];
      box.currentQuantity = jsonString["data"]['real_amount'];
      box.status = jsonString['message'];

      return 121;
    } else {
      box.lpnId = jsonString["data"]['id'];
      box.status = jsonString['message'];
      return 120;
    }
  }

  //cierre de orden
  Future<int> finishOrder() async {
    final workOrder = HiveDB.getBoxWorkOrder();
    OrderModel currentOrder = workOrder.get("currentOrder");
    try {
      Response response =
          await _sendOrderServerRepository.sendOrderServerRepository(currentOrder.id);
      log(response.body);
      if (response.statusCode > 300) return CodeError.ConnectionFailed.valueCode;
    } catch (e) {
      print("Fallo la consulta fin orden:$e");
    }

    return CodeError.ConnectionSucces.valueCode;
  }
}
