// ignore_for_file: constant_identifier_names

enum BCRoutes {
  Login_screen,
  Ubication_screen,
  Operation_screen,
  Order_screen,
  Material_screen,
  LectureMode_screen,
  LecturePallet_screen,
  BoxCountScreen,
  Help,
  UnitsCountScreen,
  LectureBoxScreen,
  SplitViewUnitMatrixMaterial,
  ListBarcodeScreen,
  DatawedgeScanScreen,
  ListBarcodeDWScreen,
  TagsScreen,
}

extension RoutesValue on BCRoutes {
  String get routeName {
    switch (this) {
      case BCRoutes.Help:
        return '/bcHelp';
      case BCRoutes.SplitViewUnitMatrixMaterial:
        return '/bcSplitViewUnitMatrixMaterial';
      case BCRoutes.Login_screen:
        return '/bcLogin_screen';
      case BCRoutes.Ubication_screen:
        return '/bcUbication_screen';
      case BCRoutes.Operation_screen:
        return '/bcOperation_screen';
      case BCRoutes.Order_screen:
        return '/bcOrder_screen';
      case BCRoutes.Material_screen:
        return '/bcMaterial_screen';
      case BCRoutes.LectureMode_screen:
        return '/bcLectureMode_screen';
      case BCRoutes.LecturePallet_screen:
        return '/bcLecturePalletscreen';
      case BCRoutes.BoxCountScreen:
        return '/bcBoxPalletscreen';
      case BCRoutes.UnitsCountScreen:
        return '/bcUnitsCountScreen';
      case BCRoutes.LectureBoxScreen:
        return '/bcLectureBoxScreen';
      case BCRoutes.ListBarcodeScreen:
        return '/bcListBarcodeScreen';
      case BCRoutes.DatawedgeScanScreen:
        return '/bcDatawedgeScanScreen';
      case BCRoutes.ListBarcodeDWScreen:
        return '/bcListBarcodeDWScreen';
      case BCRoutes.TagsScreen:
        return '/bcTagsScreen';
      default:
        return '/bcLogin_screen';
    }
  }
}
