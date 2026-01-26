import 'package:flutter/material.dart';
import 'package:almaviva_integration/config/app_colors.dart';

class PrimaryRedButton extends StatelessWidget {
  final String title;
  final VoidCallback callback;

  const PrimaryRedButton({Key? key, required this.title, required this.callback}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        children: <Widget>[
          Container(
            margin: const EdgeInsets.symmetric(vertical: 20),
            width: 280,
            child: ClipRRect(
              borderRadius: BorderRadius.circular(50),
              child: Stack(
                children: <Widget>[
                  Positioned.fill(
                    child: Container(
                      decoration: const BoxDecoration(color: AppColors.primary),
                    ),
                  ),
                  SizedBox(
                    width: 300,
                    height: 45,
                    child: TextButton(
                      style: TextButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 36),
                        foregroundColor: Colors.white,
                        textStyle: const TextStyle(fontSize: 20),
                      ),
                      onPressed: () => callback(),
                      child: Text(title),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
