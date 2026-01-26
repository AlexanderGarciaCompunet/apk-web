import 'package:almaviva_integration/splitview/repository/datawedge_scan_active_api.dart';

class DatawedgeServiceRepository {
  final DatawedgeService _datawedgeService = DatawedgeService();
  Future<void> activeSessionScanner() => _datawedgeService.activeSessionScanner();
  Future<void> printVersion() => _datawedgeService.printVersion();
  Future<void> createProfile(String profileName) => _datawedgeService.createProfile(profileName);
  void listenScanResult() => _datawedgeService.listenScanResult();
  void closeStreamController() => _datawedgeService.closeStreamController();
  Stream<Map<int, List<String>>> get eventOnDatawedge => _datawedgeService.eventOnDatawedge;
  Stream<List<String>> get eventListOnDatawedge => _datawedgeService.eventListOnDatawedge;

  Future<void> modifySettings({numberOfCodes, timer, reportInstantly, beamWidth, aimType}) =>
      _datawedgeService.modifySettings(
          numberOfCodes: numberOfCodes,
          timer: timer,
          reportInstantly: reportInstantly,
          beamWidth: beamWidth,
          aimType: aimType);
  void establishMatrixOfCodes(Map<String, String> matrixReference) =>
      _datawedgeService.establishMatrixOfCodes(matrixReference);
  void stablishTotalQuantity(int quantity) => _datawedgeService.establishTotalQuantity(quantity);
  void resetReportInstantlyVariables() => _datawedgeService.resetReportInstantlyVariables();
  Future<void> stopSessionScanner() => _datawedgeService.stopSessionScanner();
  void changeStateOfSubscription() => _datawedgeService.changeStateOfSubscription();
  void closeListeners() => _datawedgeService.closeListeners();
  Map<int, List<String>> analyzeBarcodeList(List<dynamic> barcodes) =>
      _datawedgeService.analyzeBarcodeList(barcodes);
  void establishGroups(int groups) => _datawedgeService.establishGroups(groups);
  void establishTotalQuantityByColumn(int quantity) =>
      _datawedgeService.establishTotalQuantityByColumn(quantity);
}
