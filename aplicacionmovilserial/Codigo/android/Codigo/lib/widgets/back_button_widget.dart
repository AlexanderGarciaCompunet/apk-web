import 'package:flutter/material.dart';
import 'package:almaviva_integration/config/app_colors.dart';

class BackButtonWidget extends StatelessWidget {
  final int type;
  final VoidCallback callback;

  const BackButtonWidget({Key? key, required this.type, required this.callback}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      behavior: HitTestBehavior.translucent,
      onTap: () {
        callback();
      },
      child: const SizedBox(
        width: 30,
        height: 30,
        child: Center(
          child: Icon(
            Icons.arrow_back,
            color: AppColors.primary,
            size: 30,
          ),
        ),
      ),
    );
  }
}
