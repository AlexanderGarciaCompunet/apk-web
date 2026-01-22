import 'dart:async';
import 'dart:convert';
import 'dart:math';

import 'package:flutter/cupertino.dart';
import 'package:http/http.dart';

import 'package:almaviva_integration/bloc/bloc_base.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/db/db.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/splitview/model/barcode_location.dart';
import 'package:almaviva_integration/splitview/repository/send_material_repository.dart';
// ignore: import_of_legacy_library_into_null_safe
import 'package:simple_cluster/simple_cluster.dart';

class BarcodeListBloc extends Bloc with ChangeNotifier {
  final SendMaterialRepository _sendMaterialRepository = SendMaterialRepository();

  List<BarcodeLocation> defaultBarcodes = [];
  //clustering

  Map<int, List<BarcodeLocation>> matrixBarcodes = {};
  Map<int, List<BarcodeLocation>> auxMatrix = {};

  //DBSCAN
  List<List<double>> points = [];

  //config material

  int quantityOfCodes = 3;
  int qtColumns = 2;
  double epsilon = 5;
  int minPoints = 2;
  int groups = 1;
  int instant = 1;
  double valueForChange = 5;
  int quantityUnits = 0;
  int totalQuantity = 0;
  double maxEpsilon = 0;
  double percent = 0;
  int orientation = 0;

  Stream<int> get messagePopUp => _messagePopUp.stream;

  final StreamController<int> _messagePopUp = StreamController();

  //actualizar variables iniciales con la configuración del servidor

  void initVariablesWithHive({qtUnits}) {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel materialConfigModel = box.get('currentMaterialConfig');
    epsilon = materialConfigModel.epsilon;
    groups = materialConfigModel.groups;
    qtColumns = materialConfigModel.qtColumns;
    quantityOfCodes = materialConfigModel.prefix.keys.length;
    quantityUnits = qtUnits;

    orientation = materialConfigModel.orientation;
    matrixBarcodes.clear();
    defaultBarcodes.clear();
    auxMatrix.clear();
  }

  //extraccion de los resultados de lectura de Hive
  void updateListTitles() {
    var box = HiveDB.getBoxWorkOrder();
    List<BarcodeLocation> results = box.get("result");
    if (defaultBarcodes.isNotEmpty) defaultBarcodes.clear();
    defaultBarcodes.addAll(results);

    MaterialConfigModel materialConfigModel = box.get('currentMaterialConfig');
    if (materialConfigModel.configId != 4) {
      separateValues(materialConfigModel);
    } else {
      notSpecialMaterial(materialConfigModel);
    }
    quantityOfCodesCaptured();
    notifyListeners();
  }

  //si lo valores estan al interior de un qr o data matrix y usa separador
  void separateValues(MaterialConfigModel materialConfigModel) {
    List<String> listOfCodes = withSeparator("");
    if (listOfCodes.isEmpty) {
      var key = materialConfigModel.prefix.keys.first;
      for (var barcode in defaultBarcodes) {
        List<String> barcodes = (barcode.barcode).split(RegExp(r'\s+'));
        if (materialConfigModel.prefix[key]!.isNotEmpty) {
          List<dynamic> strings = jsonDecode(materialConfigModel.prefix[key]!.trim());
          strings.addAll(barcodes);
          materialConfigModel.prefix[key] = jsonEncode(strings);
        } else {
          materialConfigModel.prefix[key] = jsonEncode(barcodes);
        }
        updateDefaultBarcodes(key, barcodes);
      }
    } else {
      var keys = materialConfigModel.prefix.keys.toList();

      for (int i = 0; i < keys.length; i++) {
        List<String> barcodes = (defaultBarcodes[i].barcode).split(RegExp(r'\s+'));
        materialConfigModel.prefix[keys[i]] = jsonEncode(barcodes);
        updateDefaultBarcodes(keys[i], barcodes);
      }
    }
  }

  //retorno de la lista con el separador
  List<String> withSeparator(String separator) {
    if (separator.isNotEmpty) {
      return defaultBarcodes.first.barcode.split(separator);
    } else {
      return [];
    }
  }

  //Actualizar la vista con los codigos separados
  void updateDefaultBarcodes(String key, List<String> barcodes) {
    int keyFromString = int.parse(key);

    List<BarcodeLocation> barcodeToScreen = [];
    for (var barcode in barcodes) {
      barcodeToScreen.add(BarcodeLocation(barcode, {}, 0));
    }
    if (matrixBarcodes.containsKey(keyFromString)) {
      barcodeToScreen.addAll(matrixBarcodes[keyFromString]!);
      matrixBarcodes[keyFromString] = barcodeToScreen;
    } else {
      matrixBarcodes[keyFromString] = barcodeToScreen;
    }
  }

  //ejecucion en multiples codigos de material
  void notSpecialMaterial(MaterialConfigModel materialConfigModel) {
    if (materialConfigModel.prefix.keys.length > 1) {
      executeSimpleClustering();
    } else {
      var key = materialConfigModel.prefix.keys.first;
      matrixBarcodes[int.parse(key)] = defaultBarcodes;
      materialConfigModel.prefix[key] =
          jsonEncode(defaultBarcodes.map((barcode) => barcode.barcode).toList());
    }
  }

  void clearBarcodes() {
    var box = HiveDB.getBoxWorkOrder();
    matrixBarcodes.clear();
    box.put('result', matrixBarcodes);
    notifyListeners();
  }

  void discardBarcode(int indexOfList, int index) {
    var box = HiveDB.getBoxWorkOrder();
    List<BarcodeLocation> results = box.get("result");
    results
        .removeWhere((barcode) => barcode.barcode == matrixBarcodes[indexOfList]![index].barcode);
    matrixBarcodes[indexOfList]!.removeAt(index);
    box.put('result', results);
    // updateListTitles();
    quantityOfCodesCaptured();
    notifyListeners();
  }

  void quantityOfCodesCaptured() {
    totalQuantity = 0;
    for (var key in matrixBarcodes.keys) {
      totalQuantity += matrixBarcodes[key]!.length;
    }
  }
  //clustering

  void executeSimpleClustering() {
    print("Execute Simple Clustering");

    List<List<int>> clusterOutput = [];
    double epsilonPrueba = epsilon;
    int minPointsPrueba = minPoints;
    double valueOfEpsilon = epsilon;
    int cont = 0;
    bool discount = true;

    while (valueOfEpsilon != 0 && cont < 100) {
      Map<String, dynamic> resultClustering =
          simpleClustering(valuesForClustering(), epsilonPrueba, minPointsPrueba);
      clusterOutput = resultClustering["clusterOutput"];

      int noise = resultClustering["noise"];

      valueOfEpsilon = verifyClusters(clusterOutput, noise, epsilonPrueba, discount);
      if (epsilonPrueba == 0) {
        print("no puede seguir reduciendo");
        epsilonPrueba = epsilon;
        discount = false;
      }
      epsilonPrueba += valueOfEpsilon;

      cont += 1;
    }

    epsilon = maxEpsilon;
    Map<String, dynamic> resultClustering =
        simpleClustering(valuesForClustering(), epsilon, minPointsPrueba);
    clusterOutput = resultClustering["clusterOutput"];
    print("epsilon: $maxEpsilon $percent $clusterOutput");
    saveBarcodesInGroups(clusterOutput);
    printMatrixBarcodes();
    // sortColumns();
    sortColumnsByTwoCriteria();
    calculateAngleinterCodes();
    renameTagForGroups();

    sortColumnsByOtherCordinate();
    sortColumnsByCoordinates();

    printMatrixBarcodes();
  }

  double verifyClusters(List<List<int>> clusterOutput, int noise, double epsilon, bool discount) {
    // analizedCluster(clusterOutput);
    //cambio aqui con verify Cluster Length
    if (!analizedCluster(clusterOutput, epsilon)) {
      if (valueForChange == 0 && noise == 0) {
        return 0;
      }

      if (clusterOutput.length < qtColumns && epsilon > 0 && discount) {
        return -valueForChange;
      }

      return valueForChange;
    } else {
      return 0;
    }
  }

  bool analizedCluster(List<List<int>> clusterOutput, double epsilon) {
    int clExpected = groups == 0
        ? quantityUnits
        : quantityUnits ~/ quantityOfCodes; //cantidad esperada dentro de cada cluster
    double percentOfParticipation = 100 / qtColumns; // porcentaje ideal de participación
    List<double> percentOfCluster = []; //porcentaje de clusterización
    double percentAccum = 0; //porcentaje total
    for (var cluster in clusterOutput) {
      percentOfCluster.add(100 * cluster.length / clExpected);
    }
    for (var percent in percentOfCluster) {
      percentAccum += calculateRealParticipation(percentOfParticipation, percent);
    }

    if (percentAccum >= percent && qtColumns == clusterOutput.length) {
      print("lo actualicee, $qtColumns ${clusterOutput.length}");
      percent = percentAccum;
      maxEpsilon = epsilon;
    }
    print("Se calculo el porcentaje: $percentAccum $epsilon ${clusterOutput.length}");
    if (percentAccum > 80 && percentAccum <= 100) {
      return false;
    }
    return false;
  }

  double calculateRealParticipation(double percentOfParticipation, double percentOfCluster) {
    return percentOfCluster * percentOfParticipation / 100;
  }

  Map<String, dynamic> simpleClustering(List<List<double>> dataset, double epsilon, int minPoints) {
    DBSCAN dbscan = DBSCAN(
      epsilon: epsilon, // 200 para dos columnas y grupos 180 arris
      minPoints: minPoints,
    );

    List<List<int>> clusterOutput = dbscan.run(dataset);

    return {"clusterOutput": clusterOutput, "noise": dbscan.noise.length};
  }

  void saveBarcodesInGroups(List<List<int>> clusterOutput) {
    if (matrixBarcodes.isNotEmpty) matrixBarcodes.clear();
    for (var cluster in clusterOutput) {
      int index = clusterOutput.indexOf(cluster);
      for (var barcode in cluster) {
        if (matrixBarcodes.keys.contains(index)) {
          matrixBarcodes[index]!.add(defaultBarcodes[barcode]);
        } else {
          matrixBarcodes[index] = [defaultBarcodes[barcode]];
        }
      }
    }
  }

  void printBarcodes(List<List<int>> clusterOutput) {
    for (var cluster in clusterOutput) {
      print("_________");
      for (var barcode in cluster) {
        print("${defaultBarcodes[barcode].barcode} ${defaultBarcodes[barcode].location}");
      }
    }
  }

  List<List<double>> valuesForClustering() {
    points.clear();
    for (var barcode in defaultBarcodes) {
      points.add(barcode.location.values.toList().cast<double>());
    }

    return points;
  }

  void sortColumnsByOtherCordinate() {
    var codesEntries;
    if (orientation == 0) {
      codesEntries = matrixBarcodes.entries.toList()
        ..sort((m1, m2) => (m1.value.first.location["x"]).compareTo(m2.value.first.location["x"]));
    } else {
      codesEntries = matrixBarcodes.entries.toList()
        ..sort((m1, m2) => (m1.value.first.location["y"]).compareTo(m2.value.first.location["y"]));
    }

    matrixBarcodes
      ..clear()
      ..addEntries(codesEntries);

    printMatrixBarcodes();
  }

  void calculateAngleinterCodes() {
    print("--------------------- inicio de analisis de angulos -----------------------");
    if (matrixBarcodes.keys.isNotEmpty) {
      var listOfKeys = matrixBarcodes.keys.toList();
      var pivotKey = listOfKeys.first;
      for (var key in matrixBarcodes.keys) {
        if (matrixBarcodes[pivotKey]!.length > matrixBarcodes[key]!.length) {
          pivotKey = key;
        }
      }
      print("privot key es : $pivotKey");
      for (int i = 0; i < listOfKeys.length; i++) {
        if (listOfKeys[i] != pivotKey &&
            matrixBarcodes[pivotKey]!.isNotEmpty &&
            matrixBarcodes[listOfKeys[i]]!.isNotEmpty) {
          compareTwoColumnsReverse(pivotKey, listOfKeys[i]);
        }
      }
    }
  }

  void compareTwoColumnsReverse(int pivotKey, int compareToKey) {
    List<BarcodeLocation> x3 = matrixBarcodes[pivotKey]!;
    List<BarcodeLocation> x2 = matrixBarcodes[compareToKey]!;

    //calculo segun la orientación
    double barcodeLocationl1 = orientation == 0 ? x3.first.location["y"] : x3.first.location["x"];
    double barcodeLocationl2 = orientation == 0 ? x2.first.location["y"] : x2.first.location["x"];
    //distancia
    double distance = barcodeLocationl1 - barcodeLocationl2;
    double maxInColumn = 0;
    // dos posiciones una indice segundo  valor
    Map<int, List<double>> matrixOfIndex = {};
    //barcodes pivot
    for (int i = 0; i < x2.length; i++) {
      double locationOfPivot = orientation == 0 ? x2[i].location["x"] : x2[i].location["y"];
      int idxPivot = i;
      int idxCompare = 5000;
      maxInColumn = 0;
      for (int j = 0; j < x3.length; j++) {
        double locationBarcodeToCompare =
            orientation == 0 ? x3[j].location["x"] : x3[j].location["y"];
        double valueOfResult =
            atan(distance.abs() / (locationOfPivot - locationBarcodeToCompare).abs()) * 180 / pi;

        // print(
        //     "valor resultante: ${valueOfResult.abs()} distancia:   $distance codigo x2:  $locationBarcodeToCompare codigo x3: $locationOfPivot ");

        if (valueOfResult > maxInColumn) {
          maxInColumn = valueOfResult;
          idxCompare = j;
        }

        print(
            "pivote: $pivotKey - comparado $compareToKey - $locationOfPivot - $idxPivot - $idxCompare - $locationBarcodeToCompare - $maxInColumn");
      }

      if (matrixOfIndex.containsKey(idxCompare)) {
        if (matrixOfIndex[idxCompare]![1] < maxInColumn) {
          matrixOfIndex[idxCompare] = [idxPivot.toDouble(), maxInColumn];
        }
      } else {
        bool flag = false;
        for (var list in matrixOfIndex.values) {
          if (list[0] == idxPivot) {
            flag = true;
            break;
          }
        }
        if (flag != true && idxCompare != 5000) {
          matrixOfIndex[idxCompare] = [idxPivot.toDouble(), maxInColumn];
        }
      }
    }
    print("resultados del analisis: ");
    for (var idx in matrixOfIndex.keys) {
      print("$idx ${matrixOfIndex[idx]}");
    }
    cutExtraCodes(pivotKey, compareToKey, matrixOfIndex);
  }

  void cutExtraCodes(int pivotKey, int compareToKey, Map<int, List<double>> matrixOfIndex) {
    List<BarcodeLocation> listOfCodes = [];
    List<BarcodeLocation> compareToList = [];
    for (var key in matrixOfIndex.keys) {
      listOfCodes.add(matrixBarcodes[pivotKey]![key]);
    }
    print(matrixOfIndex);
    for (var listKey in matrixOfIndex.values) {
      compareToList.add(matrixBarcodes[compareToKey]![listKey[0].toInt()]);
    }
    matrixBarcodes[pivotKey] = listOfCodes;
    matrixBarcodes[compareToKey] = compareToList;
  }

  void sortColumnsByCoordinates() {
    print("ordenando las columnas");
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel materialConfigModel = box.get('currentMaterialConfig');

    List<int> listOfKeys = matrixBarcodes.keys.toList();
    List<String> realKeys = materialConfigModel.prefix.keys.toList();

    print("$listOfKeys-------------------$realKeys");

    auxMatrix.clear();

    if (groups == 0) {
      orientation == 0
          ? listOfKeys.sort((key2, key1) {
              var r = matrixBarcodes[key1]!
                  .first
                  .location["y"]
                  .compareTo(matrixBarcodes[key2]!.first.location["y"]);
              return r;
            })
          : listOfKeys.sort((key1, key2) {
              var r = matrixBarcodes[key1]!
                  .first
                  .location["x"]
                  .compareTo(matrixBarcodes[key2]!.first.location["x"]);
              return r;
            });
    }
    print("$listOfKeys");
    for (int i = 0; i < listOfKeys.length; i += 1) {
      print("${realKeys[i]} ${listOfKeys[i]}");
      auxMatrix[int.parse(realKeys[i])] = matrixBarcodes[listOfKeys[i]]!;
    }
    matrixBarcodes.clear();
    matrixBarcodes = auxMatrix;
    printMatrixBarcodes();
  }

  void renameTagForGroups() {
    if (quantityOfCodes > 0 && groups == 1) {
      auxMatrix = {};
      for (var pivot in matrixBarcodes.keys) {
        createNewTag(matrixBarcodes[pivot]!);
      }
      matrixBarcodes.clear();

      matrixBarcodes.addAll(auxMatrix);
    }
  }

  void createNewTag(List<BarcodeLocation> listOfCodes) {
    int count = 0;

    for (var barcode in listOfCodes) {
      if (count == quantityOfCodes) count = 0;
      if (!auxMatrix.keys.contains(count)) auxMatrix[count] = [];
      print("$count ${barcode.barcode}");
      auxMatrix[count]!.add(barcode);
      count++;
    }
  }

  void sortColumnsByTwoCriteria() {
    print("Ordenamiento por dos criterios");

    if (matrixBarcodes.keys.isNotEmpty && groups == 1) {
      print("entre aqui y organice ... ");
      for (var pivot in matrixBarcodes.keys) {
        matrixBarcodes[pivot]!.sort((m1, m2) {
          var r = m1.type.compareTo(m2.type);
          if (r != 0) return r;
          return orientation == 0
              ? m1.location["x"].compareTo(m2.location["x"])
              : m1.location["y"].compareTo(m2.location["y"]);
        });
      }
    } else {
      print("HOLAAAAAA"); //se arregla cambiando coordenadas
      for (var pivot in matrixBarcodes.keys) {
        matrixBarcodes[pivot]!.sort((m1, m2) => orientation == 0
            ? m1.location["x"].compareTo(m2.location["x"])
            : m1.location["y"].compareTo(m2.location["y"]));
      }
    }

    printMatrixBarcodes();
  }

  void printMatrixBarcodes() {
    print("Imprimiendo");
    for (var key in matrixBarcodes.keys) {
      print("--------------------------");
      for (var barcode in matrixBarcodes[key]!) {
        print("${barcode.barcode} ${barcode.location} ");
      }
    }
  }

  void finishAndCloseOrder() {
    var box = HiveDB.getBoxWorkOrder();
    MaterialConfigModel material = box.get("currentMaterialConfig");

    // si el material tiene más  de una clave
    if (material.prefix.keys.length > 1) {
      //reconstruccion de las listas con las claves respectivas
      for (var key in material.prefix.keys) {
        List<String> barcodes = [];
        try {
          for (var barcode in matrixBarcodes[int.parse(key)]!) {
            barcodes.add(barcode.barcode);
          }
        } catch (e) {
          print("error en la clave $key");
        }

        material.prefix[key.toString()] = jsonEncode(barcodes);
      }
      sendMaterialsToServer(material);
    } else if (material.configId == 4) {
      //material con una unica clave lectura matrix scan
      String key = material.prefix.keys.first;
      List<String> barcodes = [];
      for (var barcode in defaultBarcodes) {
        barcodes.add(barcode.barcode);
      }
      material.prefix[key] = jsonEncode(barcodes);

      sendMaterialsToServer(material);
    } else {
      for (var key in material.prefix.keys) {
        List<String> barcodes = [];

        for (var barcode in matrixBarcodes[int.parse(key)]!) {
          print("el auxiliar esta asi: ${barcode}");
          barcodes.add(barcode.barcode);
        }
        material.prefix[key] = jsonEncode(barcodes);
      }

      sendMaterialsToServer(material);
    }
    // sortOutBarcodes();
  }

  void sendMaterialsToServer(MaterialConfigModel material) async {
    print("este es el log: ${material.toJson()}");
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

  void clearHive() {
    var box = HiveDB.getBoxWorkOrder();

    MaterialConfigModel materialConfigModel = box.get("currentMaterialConfig");
    for (var key in materialConfigModel.prefix.keys) {
      materialConfigModel.prefix[key] = "";
    }
    var result = box.get('result');
    result.clear();
    box.put("result", result);
    box.put("currentMaterialConfig", materialConfigModel);
  }

  @override
  void dispose() {
    _messagePopUp.close();
    super.dispose();
  }
}
