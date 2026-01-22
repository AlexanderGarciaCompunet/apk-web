<?php

use yii\db\Migration;

/**
 * Class m211012_214527_create_admin_user
 */
class m211012_214527_create_admin_user extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->batchInsert(
      'auth_item',
      ['type', 'name', 'description'],
      [
        [2, 'manager', 'manager']
      ]
    );

    $this->batchInsert(
      'auth_item_child',
      ['parent', 'child'],
      [
        ['manager', 'update_store'], ['manager', 'update_order'],
        ['manager', 'update_item'],
        ['manager', 'permission_customer'], ['manager', 'permission_store'],
        ['manager', 'permission_user  '], ['manager', 'permission_role'],
        ['manager', 'permission_order'], ['manager', 'permission_script'],
        ['manager', 'permission_info'], ['manager', 'permission_item'],
        ['manager', 'ver_bodegas'], ['manager', 'ver_almacenes'], ['manager', 'ver_ordenes'],
        ['manager', 'ver_usuarios'], ['manager', 'ver_materiales'], ['manager', 'ver_clientes'],
        ['manager', 'ver_scripts'],
        ['manager', 'permisos_basicos'],
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->delete('auth_item_child', 'parent like "manager"');
    $this->delete('auth_item', ['name' => 'manager']);
  }
}
