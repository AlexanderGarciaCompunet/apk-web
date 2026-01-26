import 'package:almaviva_integration/orders/models/order_model.dart';
import 'package:almaviva_integration/config/app_colors.dart';
import 'package:flutter/material.dart';

class CardOrder extends StatelessWidget {
  final OrderModel order;
  final VoidCallback function;

  const CardOrder({Key? key, required this.order, required this.function}) : super(key: key);
  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      child: Container(
        height: 140,
        padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 5),
        decoration: const BoxDecoration(
            boxShadow: [BoxShadow(color: Colors.black26, offset: Offset(3, 3), blurRadius: 4)],
            color: AppColors.primary,
            borderRadius: BorderRadius.all(Radius.circular(20))),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            ListTile(
                leading: Container(
                  width: 50,
                  height: 50,
                  decoration: const BoxDecoration(
                      borderRadius: BorderRadius.all(Radius.circular(100)), color: Colors.white),
                  child: Center(
                      child: Image.asset(
                    'lib/assets/Images/firma.png',
                    fit: BoxFit.fill,
                    width: 30,
                  )),
                ),
                title: Text(order.orderId.toString(),
                    style: const TextStyle(color: Colors.white, fontSize: 13)),
                subtitle: Text('${order.customerName} ',
                    maxLines: 2, style: const TextStyle(color: Colors.white, fontSize: 11))),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                order.status == 0
                    ? const Text('')
                    : Container(
                        margin: const EdgeInsets.only(right: 20, top: 15),
                        decoration: BoxDecoration(
                            color: Colors.white, borderRadius: BorderRadius.circular(20)),
                        width: 35,
                        height: 35,
                        child: const Center(
                            child: Icon(
                          Icons.person_add,
                          size: 13,
                        )),
                      ),
                Container(
                  decoration: const BoxDecoration(
                      color: Colors.white, borderRadius: BorderRadius.all(Radius.circular(20))),
                  width: 120,
                  height: 35,
                  margin: const EdgeInsets.only(right: 20, top: 15),
                  child: TextButton(
                    onPressed: () {
                      function();
                    },
                    child: const Text('Completar', style: TextStyle(color: AppColors.primary)),
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
