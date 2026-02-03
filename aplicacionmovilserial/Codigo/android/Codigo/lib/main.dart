import 'dart:async';

import 'package:almaviva_integration/splitview/view/datawedge_barcode_capture_material_matrix.dart';
import 'package:almaviva_integration/splitview/view/list_result_dw_screen%20.dart';
import 'package:almaviva_integration/tag/view/tags_screen.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import 'package:hive_flutter/hive_flutter.dart';
import 'package:path_provider/path_provider.dart';
import 'package:provider/provider.dart';

import 'package:scandit_flutter_datacapture_barcode/scandit_flutter_datacapture_barcode.dart';
import 'package:scandit_flutter_datacapture_core/scandit_flutter_datacapture_core.dart';

import 'package:almaviva_integration/box/views/box_screen.dart';
import 'package:almaviva_integration/login/view/login_screen_view.dart';
import 'package:almaviva_integration/materials/models/material_config_model.dart';
import 'package:almaviva_integration/materials/models/material_model.dart';
import 'package:almaviva_integration/materials/views/lecture_mode_screen.dart';
import 'package:almaviva_integration/materials/views/materials_screen.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/orders/models/order_model.dart';
import 'package:almaviva_integration/orders/views/orders_screen.dart';
import 'package:almaviva_integration/pallet/view/box_count_screen.dart';
import 'package:almaviva_integration/pallet/view/pallet_screen.dart';
import 'package:almaviva_integration/splitview/model/barcode_location.dart';

import 'package:almaviva_integration/splitview/view/list_result_screen.dart';
import 'package:almaviva_integration/splitview/view/unic_barcode_one_material_matrix.dart';
import 'package:almaviva_integration/units/views/unitsqt_screen.dart';
import 'package:almaviva_integration/user/model/user_model.dart';
import 'package:almaviva_integration/user/views/operation_screen.dart';
import 'package:almaviva_integration/user/views/ubication_screen.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';

import 'package:almaviva_integration/login/view/help.dart';
import 'package:almaviva_integration/login/services/auth_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ScanditFlutterDataCaptureBarcode.initialize();
  var path = getApplicationDocumentsDirectory();
  await Hive.initFlutter(path.toString());
  Hive.registerAdapter(UserModelAdapter());
  Hive.registerAdapter(OrderModelAdapter());
  Hive.registerAdapter(MaterialModelAdapter());
  Hive.registerAdapter(MaterialConfigModelAdapter());
  Hive.registerAdapter(BarcodeLocationAdapter());
  Hive.deleteBoxFromDisk('workorder');
  Hive.deleteBoxFromDisk('users');

  await Hive.openBox('users');
  await Hive.openBox('workorder');
  SystemChrome.setPreferredOrientations(
      [DeviceOrientation.portraitUp, DeviceOrientation.portraitDown]);
  runApp(const MyApp());
}

const String licenseKey =
    'Aeqwp9eBQ6x6FGqLkC+880omKuPUNfNTAl/LbtR8qrbqVzF3f3+uluk8eHcYRYi8s2ZDKDlTl0zHVehmujiZbwx4h8/+bBkASBeH0nFwNBuUbM6rBG0GGhZSceRSTb9K4VKJ7p4QN9nyPCIoqTtJEwYL1rcFD00U6qVnV0x9WjqIBDqjisIEC6t69jwNsUlZKL4v54O06aS6ikCIcAUCpFoAa03dutjLwZG4YOQelfXcqxLNdzCvGrsNwbgJ0pY9AVNheSYZJzMdUkED4EsJAYYBRuIAWnNdrhPdzZu9Mp9nbeYQ7RY2OBCNy65lM3w5yXdzLYyVCYHZPegcxtPwMuhtLxsaiZoaECpei2nhH2NrOpoKrUHPQ2iXtcDROIZZV4nlxguVpBnPt5lPNo3dKuhJa2DRDbXEtd0/bK5R4VYqB5PZaeWqB6Ff6Dg/UvGT7nbtX4+yuxa0ag0a6WLlz399GvmJ5Cfj7Uerif0K5F/utuqNV3kC68pjAxpYkClfWMolmjCE+tGExJfN7GccjGyuSpLlUsBVYcQcw93l4M6aZg4IWnHmjDuNbBWY0ScE72Rnj+BSrUwUaMRiUUpEf76TBUFl+Q53NwOROlHZzQ+4Ol6p5km9VwDMZs45iW1+yPQbuHcDlDqBWAWPyrlCbiGHvdQoCOpM5/V/5R6s3bJ6SD59GinyYZPyt0oSuCjRH3eGABnlWwRwcaqRlUgN5fglgLm+8MJcByXscTW9BlmB7EWqvZpHuwLZ98fN6wexX+4M4/U7zK6KaY8bCR8qNq2zTlNeCP3P95Sy7MGXByAfmv4lSn8Z+pxNP+sdunK9DvK7ijs=';

final GlobalKey<ScaffoldMessengerState> snackbarKey =
    GlobalKey<ScaffoldMessengerState>();

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return const AppRoot();
  }
}

class AppRoot extends StatefulWidget {
  const AppRoot({Key? key}) : super(key: key);

  @override
  _AppRootState createState() => _AppRootState();
}

class _AppRootState extends State<AppRoot> {
  final navigatorKey = GlobalKey<NavigatorState>();
  late DataCaptureContext contextCapture;
  Timer _timer = Timer(const Duration(hours: 1), () {});

  @override
  void initState() {
    super.initState();

    _initializeTimer();
  }

  @override
  void dispose() {
    _timer.cancel(); // Cancelar el timer para evitar múltiples timers activos
    Hive.deleteBoxFromDisk('workorder');
    Hive.deleteBoxFromDisk('users');
    Hive.close();

    super.dispose();
  }

  void _initializeTimer() {
    _timer = Timer(const Duration(hours: 2), () {
      _logOutUser();
    });
  }

  void _logOutUser() {
    const SnackBar snackBar =
        SnackBar(content: Text("Sesión Cerrada, vuelve a ingresar"));
    snackbarKey.currentState?.showSnackBar(snackBar);
    navigatorKey.currentState!.pushAndRemoveUntil(
        MaterialPageRoute(
            builder: (context) => LoginScreenView(
                  initializeTimer: _initializeTimer,
                )),
        (Route<dynamic> route) => false);

    _timer.cancel();
  }

  void _handleUserInteraction([_]) {
    if (!_timer.isActive) {
      return;
    }

    _timer.cancel();
    _initializeTimer();
  }

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (context) => OrderBloc()),
      ],
      child: GestureDetector(
        behavior: HitTestBehavior.translucent,
        onTap: _handleUserInteraction,
        onPanDown: _handleUserInteraction,
        onScaleStart: _handleUserInteraction,
        child: MaterialApp(
          navigatorKey: navigatorKey,
          debugShowCheckedModeBanner: false,
          title: 'Compunet',
          theme: ThemeData(fontFamily: 'MuseoSans'),
          scaffoldMessengerKey: snackbarKey,
          home: AuthWrapper(
            initializeTimer: _initializeTimer,
            navigatorKey: navigatorKey,
            logOutCallback: _logOutUser,
          ),
          routes: {
            BCRoutes.Login_screen.routeName: (context) => LoginScreenView(
                  initializeTimer: _initializeTimer,
                ),
            BCRoutes.Ubication_screen.routeName: (context) => UbicationScreen(
                  callback: () => _logOutUser(),
                ),
            BCRoutes.Operation_screen.routeName: (context) => OperationScreen(
                  callback: () => _logOutUser(),
                ),
            BCRoutes.Order_screen.routeName: (context) => OrderScreen(
                  callback: () => _logOutUser(),
                ),
            BCRoutes.Material_screen.routeName: (context) =>
                const MaterialScreen(),
            BCRoutes.LectureMode_screen.routeName: (context) =>
                const LectureModeScreen(),
            BCRoutes.LecturePallet_screen.routeName: (context) =>
                const LecturePalletScreen(),
            BCRoutes.BoxCountScreen.routeName: (context) =>
                const BoxCountScreen(),
            BCRoutes.Help.routeName: (context) => const HelpScreen(),
            BCRoutes.UnitsCountScreen.routeName: (context) =>
                const UnitsCountScreen(),
            BCRoutes.LectureBoxScreen.routeName: (context) =>
                const LectureBoxScreen(),
            BCRoutes.SplitViewUnitMatrixMaterial.routeName: (context) =>
                UnicMatrixScanScreen(
                    DataCaptureContext.forLicenseKey(licenseKey)),
            BCRoutes.ListBarcodeScreen.routeName: (context) =>
                const ListBarcodeScreen(),
            BCRoutes.DatawedgeScanScreen.routeName: (context) =>
                const DatawedgeScanScreen(),
            BCRoutes.ListBarcodeDWScreen.routeName: ((context) =>
                const ListBarcodeDWScreen()),
            BCRoutes.TagsScreen.routeName: (context) => TagScreen(),
          },
        ),
      ),
    );
  }
}

// Widget que verifica si hay sesión activa
class AuthWrapper extends StatefulWidget {
  final VoidCallback initializeTimer;
  final GlobalKey<NavigatorState> navigatorKey;
  final VoidCallback logOutCallback;

  const AuthWrapper({
    super.key,
    required this.initializeTimer,
    required this.navigatorKey,
    required this.logOutCallback,
  });

  @override
  State<AuthWrapper> createState() => _AuthWrapperState();
}

class _AuthWrapperState extends State<AuthWrapper> {
  bool? _isLoggedIn;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _checkLoginStatus();
  }

  Future<void> _checkLoginStatus() async {
    final isLoggedIn = await AuthService.isLoggedIn();
    if (mounted) {
      setState(() {
        _isLoggedIn = isLoggedIn;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      );
    }

    return _buildContent();
  }

  Widget _buildContent() {
    // Si hay sesión activa, mostrar home, sino login
    if (_isLoggedIn == true) {
          // Mostrar la primera pantalla del home (Ubication)
          return ChangeNotifierProvider(
            create: (context) => OrderBloc(),
            child: GestureDetector(
              behavior: HitTestBehavior.translucent,
              onTap: () => widget.initializeTimer(),
              onPanDown: (_) => widget.initializeTimer(),
              onScaleStart: (_) => widget.initializeTimer(),
              child: MaterialApp(
                navigatorKey: widget.navigatorKey,
                debugShowCheckedModeBanner: false,
                title: 'Compunet',
                theme: ThemeData(fontFamily: 'MuseoSans'),
                home: UbicationScreen(callback: widget.logOutCallback),
                routes: {
                  BCRoutes.Login_screen.routeName: (context) => LoginScreenView(
                        initializeTimer: widget.initializeTimer,
                      ),
                  BCRoutes.Ubication_screen.routeName: (context) =>
                      UbicationScreen(
                        callback: () => widget.logOutCallback(),
                      ),
                  BCRoutes.Operation_screen.routeName: (context) =>
                      OperationScreen(
                        callback: () => widget.logOutCallback(),
                      ),
                  BCRoutes.Order_screen.routeName: (context) => OrderScreen(
                        callback: () => widget.logOutCallback(),
                      ),
                  BCRoutes.Material_screen.routeName: (context) =>
                      const MaterialScreen(),
                  BCRoutes.LectureMode_screen.routeName: (context) =>
                      const LectureModeScreen(),
                  BCRoutes.LecturePallet_screen.routeName: (context) =>
                      const LecturePalletScreen(),
                  BCRoutes.BoxCountScreen.routeName: (context) =>
                      const BoxCountScreen(),
                  BCRoutes.Help.routeName: (context) => const HelpScreen(),
                  BCRoutes.UnitsCountScreen.routeName: (context) =>
                      const UnitsCountScreen(),
                  BCRoutes.LectureBoxScreen.routeName: (context) =>
                      const LectureBoxScreen(),
                  BCRoutes.SplitViewUnitMatrixMaterial.routeName: (context) =>
                      UnicMatrixScanScreen(
                          DataCaptureContext.forLicenseKey(licenseKey)),
                  BCRoutes.ListBarcodeScreen.routeName: (context) =>
                      const ListBarcodeScreen(),
                  BCRoutes.DatawedgeScanScreen.routeName: (context) =>
                      const DatawedgeScanScreen(),
                  BCRoutes.ListBarcodeDWScreen.routeName: ((context) =>
                      const ListBarcodeDWScreen()),
                  BCRoutes.TagsScreen.routeName: (context) => TagScreen(),
                },
              ),
            ),
          );
    } else {
      // No hay sesión, mostrar login
      return LoginScreenView(
        initializeTimer: widget.initializeTimer,
      );
    }
  }
}
