<?php

use yii\db\Migration;

/**
 * Class m210722_211408_insert_data_menu_table
 */
class m210722_211408_insert_data_menu_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    //Url Basicas

    $this->batchInsert(
      'auth_item',
      ['type', 'name'],
      [
        [2, '/customer/index'], [2, '/customer/create'],
        [2, '/customer/update'], [2, '/customer/delete'], [2, '/customer/view'],
        [2, '/warehouse/index'], [2, '/warehouse/create'],
        [2, '/warehouse/update'], [2, '/warehouse/delete'], [2, '/warehouse/view'],
        [2, '/item/index'], [2, '/item/create'],
        [2, '/item/update'], [2, '/item/delete'], [2, '/item/view'],
        [2, '/company/index'], [2, '/company/create'],
        [2, '/company/update'], [2, '/company/delete'],
        [2, '/store/index'], [2, '/store/create'],
        [2, '/store/update'], [2, '/store/delete'], [2, '/store/view'],
        [2, '/order/index'], [2, '/order/create'],
        [2, '/order/update'], [2, '/order/delete'],
        [2, '/order/view'],
        [2, '/user/index'], [2, '/user/create'],
        [2, '/user/update'], [2, '/user/delete'],
        [2, '/user/view'],
      ]
    );

    $this->batchInsert(
      'menu',
      ['name', 'parent', 'route', 'order', 'data'],
      [
        ['lateral', null, null, 1, 'coffee'],
        ['Clientes', 1, '/customer/index', 1, 'building'],
        [ 'Almacenes', 1, '/store/index', 2, 'house-user'],
        [ 'Bodegas', 1, '/warehouse/index', 3, 'warehouse'],
        ['Usuarios', 1, '/user/index', 4, 'users'],
        [ 'Roles', 1, '/admin/role/index', 5, 'user-tag'],
        [ 'Pedidos', 1, '/order/index', 6, 'file-alt'],
        [ 'Materiales', 1, '/item/index', 7, 'plus-square'],
        [ 'Etiquetas', 1, '/tag-config/index', 8, 'tags'],
        [ 'Scripts', 1, '/site/scripts', 9, 'file-prescription'],
        [ 'Configuración', 1, null, 10, 'cogs'],
        [ 'Información General', 1, '/site/about', 11, 'info-circle'],
        ['Cerrar Sesión', 1, '/site/logout', 12, 'sign-out-alt'],
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->delete('menu', 'id >= 0');
    $this->delete('auth_item', 'name like "/customer/%"');
    $this->delete('auth_item', 'name like "/warehouse/%"');
    $this->delete('auth_item', 'name like "/item/%"');
    $this->delete('auth_item', 'name like "/company/%"');
    $this->delete('auth_item', 'name like "/store/%"');
    $this->delete('auth_item', 'name like "/order/%"');
    $this->delete('auth_item', 'name like "/user/%"');
    return true;
  }
}
