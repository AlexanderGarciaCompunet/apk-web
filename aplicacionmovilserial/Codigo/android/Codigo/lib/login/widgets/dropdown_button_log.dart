import 'package:almaviva_integration/config/config_access.dart';
import 'package:almaviva_integration/login/bloc/authentication_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class DropdownLogin extends StatelessWidget {
  const DropdownLogin({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    var _authBloc = Provider.of<AuthenticationBloc>(context, listen: false);
    return Center(
      child: SizedBox(
        width: 180,
        child: DropdownButton(
            isExpanded: true,
            items:
                ConfigEndpointsAccess.listPathServer.map<DropdownMenuItem<String>>((String path) {
              return DropdownMenuItem<String>(
                value: path,
                child: Text(
                  path,
                  style: const TextStyle(
                      color: Colors.black, fontSize: 10, fontWeight: FontWeight.w200),
                ),
              );
            }).toList(),
            onChanged: (dynamic _value) {
              if (_value != null) {
                _authBloc.modifyServerAcces(_value.toString());
              }
            },
            hint: Consumer<AuthenticationBloc>(
              builder: (_, bloc, __) {
                return Text(
                  bloc.selected,
                  style: const TextStyle(
                      color: Colors.white, fontSize: 10, fontWeight: FontWeight.w200),
                );
              },
            )),
      ),
    );
  }
}
