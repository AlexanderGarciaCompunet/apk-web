// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'material_config_model.dart';

// **************************************************************************
// TypeAdapterGenerator
// **************************************************************************

class MaterialConfigModelAdapter extends TypeAdapter<MaterialConfigModel> {
  @override
  final int typeId = 3;

  @override
  MaterialConfigModel read(BinaryReader reader) {
    final numOfFields = reader.readByte();
    final fields = <int, dynamic>{
      for (int i = 0; i < numOfFields; i++) reader.readByte(): reader.read(),
    };
    return MaterialConfigModel(
      posId: fields[0] as int,
      customerId: fields[1] as int,
      materialId: fields[2] as int,
      orderId: fields[3] as int,
      prefix: (fields[6] as Map).cast<String, String>(),
      lpnId: fields[4] as int,
      sublpnId: fields[5] as int,
      configId: fields[7] as int,
      groups: fields[9] as int,
      epsilon: fields[10] as double,
      qtColumns: fields[8] as int,
      quantity: fields[11] as int,
      orientation: fields[12] as int,
      secondsForCaputre: fields[13] as int,
      typeCapture: fields[14] as int,
      configLabelId: fields[15] as int,
    );
  }

  @override
  void write(BinaryWriter writer, MaterialConfigModel obj) {
    writer
      ..writeByte(16)
      ..writeByte(0)
      ..write(obj.posId)
      ..writeByte(1)
      ..write(obj.customerId)
      ..writeByte(2)
      ..write(obj.materialId)
      ..writeByte(3)
      ..write(obj.orderId)
      ..writeByte(4)
      ..write(obj.lpnId)
      ..writeByte(5)
      ..write(obj.sublpnId)
      ..writeByte(6)
      ..write(obj.prefix)
      ..writeByte(7)
      ..write(obj.configId)
      ..writeByte(8)
      ..write(obj.qtColumns)
      ..writeByte(9)
      ..write(obj.groups)
      ..writeByte(10)
      ..write(obj.epsilon)
      ..writeByte(11)
      ..write(obj.quantity)
      ..writeByte(12)
      ..write(obj.orientation)
      ..writeByte(13)
      ..write(obj.secondsForCaputre)
      ..writeByte(14)
      ..write(obj.typeCapture)
      ..writeByte(15)
      ..write(obj.configLabelId);
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is MaterialConfigModelAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}
