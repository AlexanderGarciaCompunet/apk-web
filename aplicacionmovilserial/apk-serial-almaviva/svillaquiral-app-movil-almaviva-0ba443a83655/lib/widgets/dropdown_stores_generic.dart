// ignore_for_file: prefer_typing_uninitialized_variables

import 'package:almaviva_integration/config/actions_errors_code.dart';
import 'package:almaviva_integration/user/bloc/store_bloc.dart';
import 'package:almaviva_integration/user/model/store_model.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class DropdownGenericStores extends StatefulWidget {
  final String title;

  final int typeDW;

  const DropdownGenericStores({Key? key, required this.title, required this.typeDW})
      : super(key: key);

  @override
  _DropdownGenericStoresState createState() => _DropdownGenericStoresState();
}

class _DropdownGenericStoresState extends State<DropdownGenericStores> {
  var _storeBloc;
  @override
  void initState() {
    _storeBloc = Provider.of<StoreBloc>(context, listen: false);
    _storeBloc.notifyLoseConnection.listen((notification) {
      if (notification == 220) {
        CodesErrorsCustom.alertLectureError(220, context);
      }
    });
    _storeBloc.consultStore();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        const SizedBox(
          height: 15,
        ),
        Text(
          widget.title,
          textAlign: TextAlign.start,
          style: const TextStyle(color: Colors.black54, fontSize: 17, fontWeight: FontWeight.w500),
        ),
        const SizedBox(
          height: 15,
        ),
        Container(
          width: 300,
          decoration: BoxDecoration(
              color: Colors.black12,
              border: Border.all(color: Colors.black45, width: 2),
              borderRadius: const BorderRadius.all(Radius.circular(40))),
          padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 20),
          child: Theme(
            data: Theme.of(context).copyWith(
              canvasColor: Colors.grey,
            ),
            child: Consumer<StoreBloc>(
              builder: (context, value, child) {
                return DropdownButton(
                    isExpanded: true,
                    items: _storeBloc.stores.map<DropdownMenuItem<StoreModel>>((StoreModel store) {
                      return DropdownMenuItem<StoreModel>(
                        value: store,
                        child: Text(
                          store.name,
                          style: const TextStyle(
                              color: Colors.black54, fontSize: 15, fontWeight: FontWeight.w200),
                        ),
                      );
                    }).toList(),
                    onChanged: (dynamic _value) {
                      if (_value != null) {
                        if (widget.typeDW == 1) {
                          _storeBloc.setSelecttedItem(
                              _value.name.toString(), int.parse(_value.storeId.toString()));
                        } else {
                          // selectedItem0 = _value.toString();
                        }
                      }
                    },
                    hint: Consumer<StoreBloc>(
                      builder: (_, value, __) {
                        return Text(
                          widget.typeDW == 1 ? _storeBloc.selectedStore.name : '',
                          style: const TextStyle(
                              color: Colors.black54, fontSize: 15, fontWeight: FontWeight.w300),
                        );
                      },
                    ));
              },
            ),
          ),
        ),
      ],
    );
  }
}
