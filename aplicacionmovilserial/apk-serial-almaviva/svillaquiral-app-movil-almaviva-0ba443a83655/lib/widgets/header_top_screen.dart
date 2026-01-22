import 'package:almaviva_integration/widgets/top_form_title.dart';
import 'package:flutter/material.dart';

class HeaderTop extends StatelessWidget {
  final String path;
  final Color color;
  const HeaderTop({
    Key? key,
    required this.path,
    required this.color,
  }) : super(key: key);
  @override
  Widget build(BuildContext context) {
    double widthScreen = MediaQuery.of(context).size.width;
    double heightScreen = MediaQuery.of(context).size.width;
    return Stack(
      children: <Widget>[
        CustomPaint(
          size: Size(
              widthScreen,
              (heightScreen * 0.25)
                  .toDouble()), //You can Replace [WIDTH] with your desired width for Custom Paint and height will be calculated automatically
          painter: TFTCustomPainter(color),
        ),
        Padding(
          padding: const EdgeInsets.all(19.0),
          child: Align(
            alignment: Alignment.topRight,
            child: SizedBox(
              width: 155,
              child: Image.asset(path),
            ),
          ),
        )
      ],
    );
  }
}
