import 'package:hive/hive.dart';

class HiveDB {
  static Box getBoxUser() => Hive.box('users');
  static Box getBoxWorkOrder() => Hive.box('workorder');
}
