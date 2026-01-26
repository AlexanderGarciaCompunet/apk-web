import 'package:almaviva_integration/tag/models/tag_model.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../orders/bloc/order_bloc.dart';
import '../../route/barcode_capture_routes.dart';
import '../bloc/tag_bloc.dart';

class ListOfTags extends StatefulWidget {
  final TagModel tag;

  final Color color;
  const ListOfTags({Key? key, required this.tag, required this.color}) : super(key: key);

  @override
  State<ListOfTags> createState() => _ListOfTagsState();
}

class _ListOfTagsState extends State<ListOfTags> {
  bool _customTileExpanded = false;
  bool _textColor = false;

  _navigatorHandler(selectedItem, isSpecialBarcode) {
    Navigator.pushNamed(
        context,
        (selectedItem && !isSpecialBarcode)
            ? BCRoutes.DatawedgeScanScreen.routeName
            : BCRoutes.SplitViewUnitMatrixMaterial.routeName);
  }

  @override
  Widget build(BuildContext context) {
    var orderBloc = Provider.of<OrderBloc>(context, listen: false);
    var tagBloc = Provider.of<TagBloc>(context, listen: false);
    return Card(
      color: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
      ),
      clipBehavior: Clip.antiAlias,
      margin: const EdgeInsets.all(5),
      child: ExpansionTile(
        title: Text(
          widget.tag.name,
          style: TextStyle(
            color: _textColor ? widget.color : Colors.white,
            fontSize: 17,
            fontWeight: FontWeight.w500,
          ),
        ),
        subtitle: Text(
          tagBloc.getTypeTag(widget.tag.configId),
          style: TextStyle(
            color: _textColor ? widget.color : Colors.white,
            fontSize: 14,
            fontWeight: FontWeight.w500,
          ),
        ),
        trailing: Icon(
          _customTileExpanded ? Icons.arrow_drop_up_rounded : Icons.arrow_drop_down_sharp,
          color: _customTileExpanded ? AppColors.primary : Colors.white,
        ),
        backgroundColor: Colors.transparent,
        collapsedBackgroundColor: widget.color,
        childrenPadding: const EdgeInsets.all(20),
        // textColor: Color(0xFFeb5b25),
        children: [
          Column(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _titleWidget('Columnas:', ' ${widget.tag.qtColumns}'),
              _titleWidget('Orientación:', widget.tag.orientation == 0 ? 'Vertical' : 'Horizontal'),
              _titleWidget('Tipo de Código:', tagBloc.getTypeTag(widget.tag.configId)),
              _titleWidget('Grupos:', ' ${widget.tag.groups == 0 ? 'No' : 'Sí'}'),
              _titleWidget('Lector:', widget.tag.typeCapture == 0 ? 'Escaner' : 'Cámara'),
              _titleWidget('Estructura:', ' ${widget.tag.serialty.values.join(', ')}'),
              Align(
                alignment: Alignment.centerRight,
                child: GestureDetector(
                  onTap: () {
                    tagBloc.selectTagForLecture(
                        boxId: orderBloc.box.lpnId,
                        palletId: orderBloc.pallet.lpnId,
                        tag: widget.tag,
                        quantity: orderBloc.unitsCount == 0
                            ? orderBloc.box.currentQuantity * widget.tag.serialty.keys.length
                            : orderBloc.unitsCount);
                    _navigatorHandler(
                        orderBloc.selectedTypeCapture, tagBloc.specialBarcodeLecture());
                  },
                  child: Container(
                      decoration: BoxDecoration(
                          color: widget.color,
                          borderRadius: const BorderRadius.all(Radius.circular(20))),
                      width: 30,
                      height: 30,
                      child: const Icon(Icons.chevron_right_outlined, color: Colors.white)),
                ),
              )
            ],
          ),
        ],
        onExpansionChanged: (bool expanded) {
          setState(() {
            _customTileExpanded = expanded;
            _textColor = expanded;
          });
        },
      ),
    );
  }

  Widget _titleWidget(String title, String value) {
    return SizedBox(
        child: Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
        ),
        SizedBox(
          width: 100,
          child: Text(
            value,
            style: const TextStyle(
              fontSize: 12,
            ),
          ),
        )
      ],
    ));
  }
}
