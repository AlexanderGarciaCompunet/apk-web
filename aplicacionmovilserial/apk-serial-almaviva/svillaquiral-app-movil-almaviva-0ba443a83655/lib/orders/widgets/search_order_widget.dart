import 'package:almaviva_integration/orders/bloc/order_bloc.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class SearchOrderWidget extends StatefulWidget {
  const SearchOrderWidget({Key? key}) : super(key: key);

  @override
  State<SearchOrderWidget> createState() => _SearchGuideWidgetState();
}

class _SearchGuideWidgetState extends State<SearchOrderWidget> with SingleTickerProviderStateMixin {
  late Animation<double> animation;
  late AnimationController animationController;
  TextEditingController editingController = TextEditingController();
  bool isForward = false;
  @override
  void initState() {
    animationController =
        AnimationController(duration: const Duration(milliseconds: 900), vsync: this);
    final curvedAnimation = CurvedAnimation(parent: animationController, curve: Curves.easeOutExpo);
    animation = Tween<double>(begin: 0, end: 190).animate(curvedAnimation)
      ..addListener(() {
        setState(() {});
      });
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    var _orderBloc = Provider.of<OrderBloc>(context, listen: false);
    var size = MediaQuery.of(context).size;
    return SizedBox(
      width: size.width / 1.45,
      height: 50,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          Container(
            width: animation.value,
            decoration: const BoxDecoration(
                color: Colors.white,
                boxShadow: [
                  BoxShadow(
                    color: Color.fromARGB(71, 0, 0, 0),
                    blurRadius: 6,
                    offset: Offset(3, 3),
                  ),
                ],
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(50),
                  bottomLeft: Radius.circular(50),
                )),
            child: Padding(
              padding: const EdgeInsets.only(left: 20, bottom: 5),
              child: TextField(
                controller: editingController,
                onChanged: (value) {
                  _orderBloc.searchByCodePosition(editingController.text);
                },
                onEditingComplete: () {
                  _orderBloc.searchByCodePosition(editingController.text);
                  editingController.text = '';
                },
                cursorColor: Colors.cyan,
                style: const TextStyle(color: Colors.black38),
                decoration: const InputDecoration(border: InputBorder.none),
              ),
            ),
          ),
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(
                boxShadow: const [
                  BoxShadow(
                    color: Color.fromARGB(71, 0, 0, 0),
                    blurRadius: 6,
                    offset: Offset(3, 3),
                  ),
                ],
                color: Colors.white,
                borderRadius: animation.value > 1
                    ? const BorderRadius.only(
                        topLeft: Radius.circular(0),
                        bottomLeft: Radius.circular(0),
                        topRight: Radius.circular(50),
                        bottomRight: Radius.circular(50),
                      )
                    : BorderRadius.circular(50)),
            child: IconButton(
                onPressed: () {
                  if (!isForward) {
                    animationController.forward();
                    isForward = true;
                  } else {
                    animationController.reverse();
                    isForward = false;
                  }
                },
                icon: const Icon(
                  Icons.search,
                  color: Colors.black26,
                )),
          )
        ],
      ),
    );
  }
}
