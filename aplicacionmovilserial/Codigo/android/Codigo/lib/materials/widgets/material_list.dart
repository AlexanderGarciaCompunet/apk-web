import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/materials/bloc/material_bloc.dart';

import 'package:almaviva_integration/materials/widgets/material_tile.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class MaterialList extends StatefulWidget {
  const MaterialList({Key? key}) : super(key: key);

  @override
  _MaterialListState createState() => _MaterialListState();
}

class _MaterialListState extends State<MaterialList> {
  @override
  void initState() {
    super.initState();

    WidgetsBinding.instance!.addPostFrameCallback((value) {
      consultCodeErrorsForAction();
    });
    enableListenerGetCount();
  }

  void enableListenerGetCount() {
    var materialBloc = Provider.of<MaterialBloc>(context, listen: false);
    Provider.of<OrderBloc>(context, listen: false).socket.on('getCount', (data) {
      print("hola estoy aqui $data");
      materialBloc.updateMaterialPosition(data);
    });
  }

  void consultCodeErrorsForAction() async {
    var materialBloc = Provider.of<MaterialBloc>(context, listen: false);
    int codeError = await materialBloc.getMyMaterials();

    if (codeError != 210) CodesErrorsCustom.alertLectureError(codeError, context);
  }

  @override
  Widget build(BuildContext context) {
    final orderBloc = Provider.of<OrderBloc>(context, listen: false);
    final materialBloc = Provider.of<MaterialBloc>(context, listen: false);

    return Consumer<MaterialBloc>(
      builder: (_, bloc, __) => materialBloc.materials.isNotEmpty
          ? Column(
              children: [
                const SizedBox(
                  height: 30,
                ),
                Expanded(child: Consumer<MaterialBloc>(
                  builder: (_, bloc, __) {
                    return ListView.builder(
                      itemCount: materialBloc.materials.length + 1,
                      itemBuilder: (context, index) {
                        if (index < orderBloc.orders.length || orderBloc.orders.isNotEmpty) {
                          if (index == materialBloc.materials.length) {
                            return const SizedBox(
                              height: 80,
                            );
                          } else {
                            return MaterialCard(
                                material: materialBloc.materials[index],
                                function: () {
                                  if (materialBloc.materials.isNotEmpty) {
                                    orderBloc.selectedMaterial(materialBloc.materials[index]);
                                    // orderBloc.materialConfigModel.prefix.clear();
                                    orderBloc.socket.off('updateOrder');
                                    orderBloc.socket.off('getCount');
                                    Navigator.pushNamed(
                                            context, BCRoutes.LectureMode_screen.routeName)
                                        .then((value) {
                                      enableListenerGetCount();
                                      materialBloc.materials.clear();
                                      consultCodeErrorsForAction();
                                      orderBloc.setUpSocketListener();
                                    });
                                  }
                                });
                          }
                        } else {
                          return const Text("");
                        }
                      },
                    );
                  },
                )),
              ],
            )
          : const Text(""),
    );
  }
}
