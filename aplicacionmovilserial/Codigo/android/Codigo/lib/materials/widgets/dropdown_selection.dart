import 'package:almaviva_integration/materials/bloc/sampling_material_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class DropdownSelection extends StatefulWidget {
  final String title;
  final String id;
  final List<String> list;

  const DropdownSelection({Key? key, required this.title, required this.list, required this.id})
      : super(key: key);

  @override
  _DropdownSelectionState createState() => _DropdownSelectionState();
}

class _DropdownSelectionState extends State<DropdownSelection> {
  String selectedItem = 'Seleccionar';
  @override
  Widget build(BuildContext context) {
    var _samplingMaterialBloc = Provider.of<SamplingMaterialBloc>(context, listen: false);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        const SizedBox(
          height: 15,
        ),
        Text(
          widget.title,
          textAlign: TextAlign.start,
          style: const TextStyle(color: Colors.black54, fontSize: 17, fontWeight: FontWeight.w700),
        ),
        const SizedBox(
          height: 15,
        ),
        Container(
          width: 250,
          decoration: BoxDecoration(
              color: Colors.black12,
              border: Border.all(color: Colors.black45, width: 2),
              borderRadius: const BorderRadius.all(Radius.circular(40))),
          padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 20),
          child: Theme(
            data: Theme.of(context).copyWith(
              canvasColor: Colors.grey,
            ),
            child: DropdownButton(
                isExpanded: true,
                items: widget.list.map<DropdownMenuItem<String>>((String serial) {
                  return DropdownMenuItem<String>(
                    value: serial,
                    child: Text(
                      serial,
                      style: const TextStyle(color: Colors.black54, fontSize: 15),
                    ),
                  );
                }).toList(),
                onChanged: (dynamic _value) {
                  setState(() {
                    if (_value != null) {
                      selectedItem = _value.toString();

                      _samplingMaterialBloc.addSelectionToPrefix(widget.id, _value.toString());
                      // selectedItemId = int.parse(_value.store_id.toString());
                    }
                  });
                },
                hint: Text(selectedItem)),
          ),
        ),
      ],
    );
  }
}
