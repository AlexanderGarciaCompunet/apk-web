import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/user/bloc/store_bloc.dart';

import 'package:almaviva_integration/widgets/dropdown_stores_generic.dart';

import 'package:almaviva_integration/widgets/header_top_screen.dart';
import 'package:almaviva_integration/widgets/primary_red_button.dart';

import 'package:flutter/cupertino.dart';

import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';

import 'package:provider/provider.dart';

class UbicationScreen extends StatelessWidget {
  final StoreBloc _storeBloc = StoreBloc();
  final VoidCallback callback;

  UbicationScreen({Key? key, required this.callback}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return ChangeNotifierProvider(
      create: (context) => _storeBloc,
      child: Scaffold(
          backgroundColor: Colors.white,
          body: Stack(
            children: <Widget>[
              Center(
                child: Container(
                  color: Colors.white38,
                  width: 280,
                  height: size.height,
                  padding: const EdgeInsets.only(
                    top: 120,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      Row(
                        children: [
                          ValueListenableBuilder(
                            valueListenable: Hive.box('users').listenable(keys: ['user']),
                            builder: (__, Box box, _) {
                              return Text(
                                'Bienvenido, ${box.get('user').name ?? ''}',
                                textAlign: TextAlign.start,
                                style: const TextStyle(
                                    color: Color.fromRGBO(255, 21, 31, 1),
                                    fontSize: 20,
                                    fontWeight: FontWeight.w500),
                              );
                            },
                          ),
                          const Expanded(child: SizedBox()),
                          InkWell(
                            onTap: () => callback(),
                            child: const SizedBox(
                              child: Icon(
                                Icons.logout_sharp,
                                color: Colors.red,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(
                        height: 30,
                      ),
                      const Text(
                        'Por favor, seleccionar su ubicación antes de continuar',
                        textAlign: TextAlign.start,
                        style: TextStyle(
                            color: Colors.black87, fontSize: 18, fontWeight: FontWeight.w100),
                      ),
                      const Expanded(child: SizedBox()),
                      const DropdownGenericStores(title: 'Almacén:', typeDW: 1),
                      const Expanded(child: SizedBox()),
                      PrimaryRedButton(
                          title: "Siguiente",
                          callback: () {
                            if (_storeBloc.selectedStore.name != 'Seleccionar') {
                              _storeBloc.saveSelectedStore();
                              Navigator.pushNamed(context, BCRoutes.Operation_screen.routeName);
                            } else {
                              showDialog(
                                context: context,
                                builder: (context) {
                                  return const CupertinoAlertDialog(
                                    title: Text('Información'),
                                    content: Text('Parece que no has seleccionado nada'),
                                  );
                                },
                              );
                            }
                          }),
                    ],
                  ),
                ),
              ),
              const HeaderTop(
                color: Color.fromRGBO(255, 21, 31, 1),
                path: 'lib/assets/Images/Logo_blanco.png',
              )
            ],
          )),
    );
  }
}
