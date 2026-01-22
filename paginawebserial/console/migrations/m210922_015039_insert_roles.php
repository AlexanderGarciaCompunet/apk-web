<?php

use yii\db\Migration;

/**
 * Class m210922_015039_insert_roles
 */
class m210922_015039_insert_roles extends Migration
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
        [1, 'coordinator', 'Coordinador'], [2, 'materialMaster', 'Maestro de Materiales'],
        [1, 'operator', 'Operario'], [2, 'permission_customer', 'Permisos para clientes'],
        [2, 'ver_clientes', 'Permisos para materiales'],
        [2, 'permission_store', 'Permisos para almacén'], [2, 'permission_user', 'Permisos para usuarios'],
        [2, 'ver_almacenes', 'Permisos para almacén'], [2, 'ver_usuarios', 'Permisos para usuarios'],
        [2, 'update_store', 'Permisos para almacén'], [2, 'renombrar_rol', 'Permisos para usuarios'],
        [2, 'permission_role', 'Permisos para roles'], [2, 'permission_order', 'Permisos para pedidos'],
        [2, 'ver_roles', 'Permisos para roles'], [2, 'ver_ordenes', 'Permisos para pedidos'],
        [2, 'update_role', 'Permisos para roles'], [2, 'update_order', 'Permisos para pedidos'],
        [2, 'permission_script', 'Permisos para scripts'], [2, 'permission_info', 'Permisos para información general'],
        [2, 'permission_item', 'Permisos para materiales'],
        [2, 'ver_materiales', 'Permisos para materiales'], [2, 'assign_user_store', 'Permisos para asignar usuarios a almacen'],
        [2, 'update_index', 'Permisos para materiales'], [2, 'update_item', 'Permisos para materiales'],
        [2, 'ver_bodegas', 'Permisos bodega'], [2, 'importación_de_datos', 'Permisos para importar data del wms'],

        [2, 'permisos_basicos', 'Permisos basicos'], [2, '/site/logout', null], [2, '/site/about', null], [2, '/site/index', null],
        [2, 'ver_scripts', 'Permisos scripts'], [2, '/scripts/index', null], [2, '/store/view-users', null],
        [2, '/order/import', null], [2, '/store/import', null], [2, '/item/import', null], [2, '/customer/import', null],
      ]
    );

    $this->batchInsert(
      'auth_item_child',
      ['parent', 'child'],
      [
        ['ver_almacenes', '/store/index'], ['ver_almacenes', '/store/view'],
        ['ver_ordenes', '/order/index'], ['ver_ordenes', '/order/view'],
        ['ver_usuarios', '/user/index'], ['ver_usuarios', '/user/view'],
        ['ver_materiales', '/item/index'], ['ver_materiales', '/item/view'],
        ['ver_bodegas', '/warehouse/index'],
        ['ver_clientes', '/customer/index'],
        ['ver_scripts', '/scripts/index'],
        ['permisos_basicos', '/site/logout'], ['permisos_basicos', '/site/about '], ['permisos_basicos', '/site/index '],

        ['importación_de_datos', '/order/import'], ['importación_de_datos', '/store/import'], ['importación_de_datos', '/item/import'],
        ['importación_de_datos', '/customer/import'],

     
        ['renombrar_rol', '/user/update'],
        ['renombrar_rol', '/user/create'],
        ['operator', 'ver_materiales'], ['operator', 'ver_ordenes'], ['operator', 'ver_almacenes'],

        ['coordinator', 'update_store'], ['coordinator', 'update_order'],
        ['coordinator', 'renombrar_rol'], ['coordinator', 'update_item'],
        ['coordinator', 'permission_customer'], ['coordinator', 'permission_store'],
        ['coordinator', 'permission_user  '], ['coordinator', 'permission_role'],
        ['coordinator', 'permission_order'], ['coordinator', 'permission_script'],
        ['coordinator', 'permission_info'], ['coordinator', 'permission_item'],
        ['coordinator', 'ver_bodegas'], ['coordinator', 'ver_almacenes'], ['coordinator', 'ver_ordenes'],
        ['coordinator', 'ver_usuarios'], ['coordinator', 'ver_materiales'], ['coordinator', 'ver_clientes'],
        ['coordinator', 'ver_scripts'],

        ['materialMaster', 'permission_customer'], ['materialMaster', 'permission_order'],
        ['materialMaster', 'ver_clientes'], ['materialMaster', 'ver_ordenes'],

        ['operator', 'permisos_basicos'], ['coordinator', 'permisos_basicos'], ['materialMaster', 'permisos_basicos'],
        ['assign_user_store', '/store/view-users'],
        ['sysadmin', 'importación_de_datos'],

      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {

    $this->delete('auth_item_child', 'parent like "view_%"');
    $this->delete('auth_item_child', 'parent like "update_%"');
    $this->delete('auth_item_child', 'parent like "coordinator_%"');
    $this->delete('auth_item_child', 'parent like "operator_%"');
    $this->delete('auth_item_child', 'parent like "assign_user_store"');
    $this->delete('auth_item_child', 'parent like "basic"');
    $this->delete('auth_item_child', 'parent like "import_data"');

    $this->delete('auth_item', 'name like "view_%"');
    $this->delete('auth_item', 'name like "update_%"');

    $this->delete('auth_item', ['name' => 'permission_customer']);
    $this->delete('auth_item', ['name' => 'permission_user']);
    $this->delete('auth_item', ['name' => 'permission_role']);
    $this->delete('auth_item', ['name' => 'permission_order']);
    $this->delete('auth_item', ['name' => 'permission_script']);
    $this->delete('auth_item', ['name' => 'permission_info']);
    $this->delete('auth_item', ['name' => 'permission_item']);
    $this->delete('auth_item', ['name' => 'permission_store']);
    $this->delete('auth_item', ['name' => 'materialMaster']);
    $this->delete('auth_item', ['name' => 'importación_de_datos']);
    $this->delete('auth_item', ['name' => 'coordinator']);
    $this->delete('auth_item', ['name' => 'operator']);
    $this->delete('auth_item', ['name' => 'assign_user_store']);
    $this->delete('auth_item', ['name' => 'permisos_basicos']);
    $this->delete('auth_item', ['name' => '/store/import']);
    $this->delete('auth_item', ['name' => '/customer/import']);
    $this->delete('auth_item', ['name' => '/order/import']);
    $this->delete('auth_item', ['name' => '/item/import']);
    $this->delete('auth_item', ['name' => '/store/view-users']);
    $this->delete('auth_item', ['name' => '/site/logout']);
    $this->delete('auth_item', ['name' => '/site/index']);
    $this->delete('auth_item', ['name' => '/site/about']);
    $this->delete('auth_item', ['name' => '/site/scripts']);
    $this->delete('auth_item', ['name' => '/scripts/index']);
    $this->delete('auth_item', ['name' => 'ver_bodegas']);
  }
}
