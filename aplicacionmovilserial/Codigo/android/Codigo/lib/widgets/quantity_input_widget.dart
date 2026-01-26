import 'package:almaviva_integration/materials/bloc/material_bloc.dart';
import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';

class QuantityInputWidget extends StatefulWidget {
  final String quantityExplain;
  final String title;
  final int type;

  const QuantityInputWidget(
      {Key? key, required this.quantityExplain, required this.title, required this.type})
      : super(key: key);

  @override
  _QuantityInputWidgetState createState() => _QuantityInputWidgetState();
}

class _QuantityInputWidgetState extends State<QuantityInputWidget> {
  final editingController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final _materialBloc = Provider.of<MaterialBloc>(context, listen: false);
    final orderBloc = Provider.of<OrderBloc>(context, listen: false);
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(widget.quantityExplain),
        SizedBox(
          width: 100,
          child: Stack(
            children: [
              Container(
                  width: 100,
                  height: 40,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    boxShadow: const [
                      BoxShadow(
                        color: Colors.black26,
                        offset: Offset(3, 3),
                        blurRadius: 10,
                      )
                    ],
                    borderRadius: BorderRadius.circular(50.0),
                  ),
                  child: Padding(
                      padding: const EdgeInsets.only(left: 40, right: 40, top: 0, bottom: 0),
                      child: SizedBox(
                          height: 40,
                          child: TextFormField(
                            keyboardType: TextInputType.number,
                            controller: editingController,
                            inputFormatters: <TextInputFormatter>[
                              FilteringTextInputFormatter.digitsOnly
                            ],
                            expands: false,
                            decoration: const InputDecoration(
                                border: InputBorder.none,
                                hintText: '0',
                                errorStyle: TextStyle(color: Colors.transparent, height: 0)),
                            onChanged: (value) {
                              if (value != '') {
                                _materialBloc.counter = int.parse(value);
                              }
                            },
                            onSaved: (value) {
                              if (widget.type == 1) {
                                orderBloc.pallet.quantityUnits = int.parse(value ?? '0');
                              } else if (widget.type == 0) {
                                orderBloc.box.quantityUnits = int.parse(value.toString());
                              } else {
                                orderBloc.unitsCount = int.parse(value.toString());
                              }
                            },
                            validator: (value) {
                              if (value == null || value.trim().isEmpty || value == '0') {
                                return 'error campo nulo';
                              }

                              return null;
                            },
                          )))),
              Align(
                alignment: Alignment.centerRight,
                child: Consumer<MaterialBloc>(
                  builder: (__, value, _) {
                    return GestureDetector(
                      onTap: () {
                        _materialBloc.addOne();
                        editingController.text = _materialBloc.counter.toString();
                      },
                      child: const SizedBox(
                        height: 40,
                        child: Padding(
                          padding: EdgeInsets.only(right: 10.0),
                          child: Icon(Icons.add),
                        ),
                      ),
                    );
                  },
                ),
              ),
              Align(
                  alignment: Alignment.centerLeft,
                  child: GestureDetector(
                    onTap: () {
                      _materialBloc.removeOne();
                      editingController.text = _materialBloc.counter.toString();
                    },
                    child: const SizedBox(
                      height: 40,
                      child: Padding(
                        padding: EdgeInsets.only(left: 10.0),
                        child: Icon(Icons.remove),
                      ),
                    ),
                  ))
            ],
          ),
        ),
      ],
    );
  }
}
