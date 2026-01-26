// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'material_model.dart';

// **************************************************************************
// TypeAdapterGenerator
// **************************************************************************

class MaterialModelAdapter extends TypeAdapter<MaterialModel> {
  @override
  final int typeId = 2;

  @override
  MaterialModel read(BinaryReader reader) {
    final numOfFields = reader.readByte();
    final fields = <int, dynamic>{
      for (int i = 0; i < numOfFields; i++) reader.readByte(): reader.read(),
    };
    return MaterialModel(
      id: fields[0] as int,
      materialId: fields[2] as int,
      orderId: fields[5] as int,
      customerId: fields[3] as int,
      amount: fields[4] as int,
      itemCode: fields[6] as String,
      unitsType: fields[7] as String,
      rcvsts: fields[8] as String,
    );
  }

  @override
  void write(BinaryWriter writer, MaterialModel obj) {
    writer
      ..writeByte(8)
      ..writeByte(0)
      ..write(obj.id)
      ..writeByte(2)
      ..write(obj.materialId)
      ..writeByte(3)
      ..write(obj.customerId)
      ..writeByte(5)
      ..write(obj.orderId)
      ..writeByte(4)
      ..write(obj.amount)
      ..writeByte(6)
      ..write(obj.itemCode)
      ..writeByte(7)
      ..write(obj.unitsType)
      ..writeByte(8)
      ..write(obj.rcvsts);
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is MaterialModelAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}
