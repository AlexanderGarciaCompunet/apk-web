import 'package:almaviva_integration/login/bloc/authentication_bloc.dart';
import 'package:almaviva_integration/login/widgets/dropdown_button_log.dart';
import 'package:almaviva_integration/login/widgets/form_login.dart';
import 'package:almaviva_integration/widgets/header_top_screen.dart';

import 'package:almaviva_integration/widgets/loading.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class LoginScreenView extends StatefulWidget {
  final VoidCallback initializeTimer;
  const LoginScreenView({
    Key? key,
    required this.initializeTimer,
  }) : super(key: key);

  @override
  _LoginScreenViewState createState() => _LoginScreenViewState();
}

class _LoginScreenViewState extends State<LoginScreenView> {
  final _authBloc = AuthenticationBloc();
  @override
  void dispose() {
    super.dispose();
  }

  void onChange() {
    _authBloc.onChangeLoading();
  }

  @override
  Widget build(BuildContext context) {
    double widthScreen = MediaQuery.of(context).size.width;
    double heightScreen = MediaQuery.of(context).size.height;

    return ChangeNotifierProvider(
        create: (context) => _authBloc,
        child: Consumer<AuthenticationBloc>(
          builder: (__, bloc, _) {
            return _authBloc.loading
                ? const Scaffold(
                    backgroundColor: Colors.white,
                    body: LoadingPage(),
                  )
                : Scaffold(
                    body: SingleChildScrollView(
                      child: Stack(
                        children: <Widget>[
                          Container(
                            decoration: BoxDecoration(
                                color: const Color.fromRGBO(255, 21, 31, 1),
                                image: DecorationImage(
                                  fit: BoxFit.cover,
                                  colorFilter: ColorFilter.mode(
                                      Colors.black.withOpacity(0.2), BlendMode.dstATop),
                                  image: const AssetImage("lib/assets/Images/mapa_america.png"),
                                )),
                            width: widthScreen,
                            height: heightScreen,
                            child: Container(
                                padding: const EdgeInsets.only(top: 80, right: 30, left: 30),
                                child: Padding(
                                  padding: const EdgeInsets.only(top: 50.0, right: 20, left: 20),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: <Widget>[
                                      Container(
                                        margin: const EdgeInsets.only(bottom: 20),
                                        width: 300,
                                        child: const Text("EXPERTOS \n EN LOGISTICA \n INTEGRADA",
                                            style: TextStyle(
                                              color: Colors.white,
                                              fontSize: 20,
                                            ),
                                            textAlign: TextAlign.right),
                                      ),
                                      const Expanded(child: SizedBox()),

                                      //
                                      FormLogin(
                                        initializeTimer: widget.initializeTimer,
                                        contextLogin: context,
                                        notifyProvider: onChange,
                                      ),

                                      const DropdownLogin(),
                                      const Expanded(child: SizedBox()),
                                    ],
                                  ),
                                )),
                          ),
                          const HeaderTop(
                            color: Colors.white,
                            path: 'lib/assets/Images/Logo_rojo.png',
                          )
                        ],
                      ),
                    ),
                  );
          },
        ));
  }
}
