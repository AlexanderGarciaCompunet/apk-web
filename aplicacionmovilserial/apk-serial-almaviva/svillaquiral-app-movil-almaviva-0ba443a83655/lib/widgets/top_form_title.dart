import 'package:flutter/material.dart';

class TFTCustomPainter extends CustomPainter {
  final Color color;
  const TFTCustomPainter(this.color);
  @override
  void paint(Canvas canvas, Size size) {
    Paint paint_0 = Paint()
      ..color = color
      ..style = PaintingStyle.fill
      ..strokeWidth = 1;

    Path path_0 = Path();
    path_0.moveTo(0, size.height * 0.6458333);
    path_0.lineTo(0, 0);
    path_0.lineTo(size.width, 0);
    path_0.lineTo(size.width, size.height * 0.6683333);
    path_0.quadraticBezierTo(size.width * 0.7975000, size.height * 1.0904333,
        size.width * 0.5000000, size.height * 0.6666667);
    path_0.quadraticBezierTo(
        size.width * 0.1834375, size.height * 0.1200000, 0, size.height * 0.6458333);
    path_0.close();

    canvas.drawPath(path_0, paint_0);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) {
    return true;
  }
}
