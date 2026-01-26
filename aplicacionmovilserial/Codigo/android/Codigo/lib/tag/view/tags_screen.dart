import 'package:almaviva_integration/tag/bloc/tag_bloc.dart';
import 'package:almaviva_integration/tag/widgets/list_tags.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:flutter/cupertino.dart';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config/actions_errors_code.dart';
import '../../orders/bloc/order_bloc.dart';
import '../../route/barcode_capture_routes.dart';
import '../../widgets/back_button_widget.dart';
import '../../widgets/header_top_screen.dart';

// ignore: must_be_immutable
class TagScreen extends StatefulWidget {
  const TagScreen({Key? key}) : super(key: key);

  @override
  State<TagScreen> createState() => _TagScreenState();
}

class _TagScreenState extends State<TagScreen> {
  TagBloc tagBloc = TagBloc();

  @override
  void initState() {
    _getTags();
    super.initState();
  }

  Future<int> _getTags() async {
    int codeError = await tagBloc.getMyTags();
    if (codeError == 220) CodesErrorsCustom.alertLectureError(codeError, context);
    if (codeError == 204) CodesErrorsCustom.alertLectureError(codeError, context);
    return codeError;
  }

  @override
  Widget build(BuildContext context) {
    Size size = MediaQuery.of(context).size;

    return ChangeNotifierProvider(
      create: (_) => tagBloc,
      child: Scaffold(
          backgroundColor: Colors.white,
          body: Stack(
            children: <Widget>[
              Center(
                child: Container(
                  color: Colors.white38,
                  width: 280,
                  height: size.height,
                  padding: const EdgeInsets.only(top: 90, right: 10, left: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      const SizedBox(
                        height: 30,
                      ),
                      Row(
                        children: [
                          BackButtonWidget(
                              type: 0,
                              callback: () {
                                _showPopUp(
                                    'Advertencia',
                                    'Esta apunto de abandonar la lectura de la caja, desea confirmar',
                                    true,
                                    context);
                              }),
                          const Expanded(child: SizedBox()),
                          const Text(
                            'Selección de etiqueta',
                            textAlign: TextAlign.start,
                            style: TextStyle(
                                color: AppColors.primary,
                                fontSize: 20,
                                fontWeight: FontWeight.w500),
                          ),
                          const Expanded(child: SizedBox()),
                        ],
                      ),
                      const SizedBox(
                        height: 40,
                      ),
                      SizedBox(
                        height: size.height * 1.9 / 3,
                        width: 350,
                        child: Consumer<TagBloc>(
                          builder: (_, bloc, __) {
                            if (bloc.tags.isNotEmpty) {
                              return CustomScrollView(
                                slivers: [
                                  SliverList(
                                    delegate: SliverChildBuilderDelegate(
                                      (context, index) {
                                        if (index < bloc.tags.length && bloc.tags.isNotEmpty) {
                                          return ListOfTags(
                                            color: index % 2 == 0
                                                ? AppColors.primary
                                                : Colors.black54,
                                            tag: tagBloc.tags[index],
                                          );
                                        }
                                      },
                                      childCount: tagBloc.tags.length,
                                    ),
                                  ),
                                ],
                              );
                            } else {
                              return const Center(
                                child: SizedBox(
                                    height: 30,
                                    width: 30,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 5,
                                      color: AppColors.primary,
                                    )),
                              );
                            }
                          },
                        ),
                      ),
                      const SizedBox(
                        height: 20,
                      ),
                    ],
                  ),
                ),
              ),
              const HeaderTop(
                color: AppColors.primary,
                path: 'lib/assets/images/Logo_Compunet_blanco.png',
              )
            ],
          )),
    );
  }

  _showPopUp(String title, String content, bool actions, BuildContext contextFunction) {
    return showDialog(
        context: contextFunction,
        builder: (BuildContext context) => CupertinoAlertDialog(
              title: Text(
                title,
                style: const TextStyle(
                    fontWeight: FontWeight.w500, color: AppColors.primary),
              ),
              content: Text(content),
              actions: actions
                  ? [
                      TextButton(
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          child: const Text(
                            "Cancelar",
                            style: TextStyle(
                                fontWeight: FontWeight.w500,
                                color: Color.fromARGB(255, 78, 78, 78)),
                          )),
                      TextButton(
                          onPressed: () {
                            var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
                            if (_orderBloc.lectureMode == "Unidades" &&
                                _orderBloc.lectureMode == "Caja") {
                              Navigator.popUntil(context,
                                  ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
                              return;
                            }
                            if (_orderBloc.incrementBoxCounter()) {
                              Navigator.popUntil(context,
                                  ModalRoute.withName(BCRoutes.LectureMode_screen.routeName));
                              return;
                            }
                            Navigator.popUntil(
                                context, ModalRoute.withName(BCRoutes.BoxCountScreen.routeName));
                          },
                          child: const Text(
                            "Confirmar",
                            style: TextStyle(
                                fontWeight: FontWeight.w500, color: AppColors.primary),
                          )),
                    ]
                  : [],
            ));
  }
}
