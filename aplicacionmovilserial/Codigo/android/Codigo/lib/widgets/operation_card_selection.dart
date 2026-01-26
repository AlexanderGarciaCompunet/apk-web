import 'package:flutter/material.dart';
import 'package:almaviva_integration/config/app_colors.dart';

class OperationCardSelection extends StatelessWidget {
  final String imagePath;
  final String title;
  final String description;
  final VoidCallback callback;

  const OperationCardSelection(
      {Key? key,
      required this.imagePath,
      required this.title,
      required this.description,
      required this.callback})
      : super(key: key);
  @override
  Widget build(BuildContext context) {
    double cardheight = MediaQuery.of(context).size.height / 3.9;
    return InkWell(
      onTap: () {
        callback();
      },
      child: Stack(
        children: <Widget>[
          Container(
            width: 300,
            height: cardheight,
            decoration: const BoxDecoration(
                color: AppColors.primary,
                borderRadius: BorderRadius.all(Radius.circular(15))),
          ),
          Container(
            width: 300,
            height: cardheight,
            padding: const EdgeInsets.all(15.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                Text(
                  title,
                  style: const TextStyle(
                      color: Colors.white, fontSize: 20, fontWeight: FontWeight.w500),
                ),
                const Expanded(
                  child: SizedBox(),
                ),
                Text(
                  description,
                  style: const TextStyle(
                      color: Colors.white, fontSize: 15, fontWeight: FontWeight.w300),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }
}
