// ignore_for_file: unnecessary_const

import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/config/errors_code.dart';
import 'package:almaviva_integration/login/bloc/authentication_bloc.dart';
import 'package:almaviva_integration/route/barcode_capture_routes.dart';
import 'package:almaviva_integration/widgets/primary_red_button.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class FormLogin extends StatefulWidget {
  final VoidCallback initializeTimer;
  final BuildContext contextLogin;
  final VoidCallback notifyProvider;

  const FormLogin(
      {Key? key,
      required this.initializeTimer,
      required this.contextLogin,
      required this.notifyProvider})
      : super(key: key);

  @override
  _FormLoginState createState() => _FormLoginState();
}

class _FormLoginState extends State<FormLogin> {
  String userValue = '';

  String passwordValue = '';

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        Container(
          width: 300,
          padding: const EdgeInsets.only(left: 10),
          child: const Text("Inicio de Sesión",
              style: TextStyle(color: Colors.white, fontSize: 23, fontFamily: 'DTLArgoTRegular'),
              textAlign: TextAlign.left),
        ),
        const SizedBox(height: 20),
        //carga de formulario
        Form(
          key: formKey,
          child: Column(
            children: <Widget>[
              Stack(
                children: [
                  Container(
                      height: 50,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(50.0),
                      ),
                      child: Padding(
                          padding: const EdgeInsets.only(left: 25, right: 15, top: 5, bottom: 5),
                          child: SizedBox(
                            height: 50,
                            child: TextFormField(
                              decoration: const InputDecoration(
                                  border: InputBorder.none,
                                  hintText: 'Usuario',
                                  errorStyle: TextStyle(color: Colors.transparent, height: 0)),
                              onSaved: (value) {
                                if (value != null) {
                                  userValue = value;
                                }
                              },
                              validator: (value) {
                                if (value == null || value.trim().isEmpty) {
                                  return 'error campo nulo';
                                }

                                return null;
                              },
                            ),
                          ))),
                ],
              ),
              const SizedBox(height: 20),
              Stack(
                children: [
                  Container(
                      height: 50,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(50.0),
                      ),
                      child: Padding(
                          padding: const EdgeInsets.only(left: 25, right: 15, top: 5),
                          child: TextFormField(
                            obscureText: true,
                            decoration: const InputDecoration(
                                border: InputBorder.none,
                                hintText: 'Contraseña',
                                errorStyle: TextStyle(color: Colors.transparent, height: 0)),
                            onSaved: (value) {
                              if (value != null) {
                                passwordValue = value;
                              }
                            },
                            validator: (value) {
                              if (value == null || value.trim().isEmpty) {
                                return 'error campo nulo';
                              }
                              return null;
                            },
                          ))),
                ],
              ),
              const SizedBox(height: 20),
              Column(
                children: <Widget>[
                  PrimaryRedButton(
                    title: 'Iniciar',
                    callback: () {
                      _loginPassed(context);
                    },
                  ),
                  const SizedBox(height: 20),
                  Container(
                      padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 15),
                      child: GestureDetector(
                        onTap: () {
                          Navigator.pushNamed(context, BCRoutes.Help.routeName);
                        },
                        child: const Text("¿Necesitas ayuda?",
                            style: TextStyle(
                                color: Colors.white,
                                fontSize: 15,
                                fontWeight: FontWeight.w100,
                                fontFamily: 'MuseoSans'),
                            textAlign: TextAlign.right),
                      )),
                ],
              )
            ],
          ),
        ),
      ],
    );
  }

  _loginPassed(BuildContext contextFunction) async {
    if (formKey.currentState != null) {
      if (formKey.currentState!.validate()) {
        formKey.currentState!.save();
        Provider.of<AuthenticationBloc>(context, listen: false).onChangeLoading();
        int authenticationValue = await Provider.of<AuthenticationBloc>(context, listen: false)
            .verifyUser(userValue, passwordValue);

        switch (authenticationValue) {
          //correcta
          case 201:
            correctAuth();
            break;
          //incorrecta
          case 203:
            incorrectAuth();
            break;
          case 220:
            internalError();
            break;
          default:
        }
      } else {
        CodesErrorsCustom.showDialogToSend(
            "Campos Vacíos.", "Diligencie todos los campos.", 2, widget.contextLogin);
      }
    }

    return;
  }

  void correctAuth() {
    widget.initializeTimer();
    Navigator.pushNamedAndRemoveUntil(
        widget.contextLogin, BCRoutes.Ubication_screen.routeName, (route) => false);
  }

  void incorrectAuth() {
    widget.notifyProvider();
    CodesErrorsCustom.showDialogToSend("Error en Autenticación.",
        "Usuario y/o Contraseña incorrectos. Por favor, intente de nuevo.", 2, widget.contextLogin);
  }

  void internalError() {
    widget.notifyProvider();

    CodesErrorsCustom.showDialogToSend(
        "Error Interno", "Pongase en contacto con el proveedor", 2, widget.contextLogin);
  }
}
