<?php

use mdm\admin\components\MenuHelper;

/* @var $directoryAsset string */

// MODO DEMO: Menú estático igual al proyecto original
$items = [
  [
    'label' => 'Clientes',
    'url' => ['/customer/index'],
    'icon' => 'fas fa-building',
  ],
  [
    'label' => 'Almacenes',
    'url' => ['/warehouse/index'],
    'icon' => 'fas fa-warehouse',
  ],
  [
    'label' => 'Bodegas',
    'url' => ['/store/index'],
    'icon' => 'fas fa-store',
  ],
  [
    'label' => 'Usuarios',
    'url' => ['/user/index'],
    'icon' => 'fas fa-users',
  ],
  [
    'label' => 'Roles',
    'url' => ['/admin/assignment'],
    'icon' => 'fas fa-user-tag',
  ],
  [
    'label' => 'Pedidos',
    'url' => ['/order/index'],
    'icon' => 'fas fa-file-alt',
  ],
  [
    'label' => 'Materiales',
    'url' => ['/item/index'],
    'icon' => 'fas fa-plus-square',
  ],
  [
    'label' => 'Etiquetas',
    'url' => ['/tag-config/index'],
    'icon' => 'fas fa-tags',
  ],
  [
    'label' => 'Scripts',
    'url' => ['/scripts/index'],
    'icon' => 'fas fa-file-code',
  ],
  [
    'label' => 'Información General',
    'url' => ['/site/about'],
    'icon' => 'fas fa-info-circle',
  ],
  [
    'label' => 'Cerrar Sesión',
    'url' => ['/site/logout'],
    'icon' => 'fas fa-sign-out-alt',
  ],
];

// Código original RBAC comentado para referencia:
// $callback = function ($menu) {
//   $data = $menu['data'];
//   return [
//     'label' => $menu['name'],
//     'url' => [$menu['route']],
//     'option' => $data,
//     'icon' => $menu['data'],
//     'items' => $menu['children'],
//   ];
// };
// $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, 1, $callback, true);
//se agrega el css en la view
\frontend\assets\LeftAsset::register($this);


use \frontend\assets\FontsAsset;

FontsAsset::register($this);

?>

<aside class="main-sidebar  sidebar-light-primary elevation-3">
  <a href="<?= Yii::$app->homeUrl ?>" class="brand-link d-flex justify-content-center">
    <img src="<?= Yii::$app->request->baseUrl ?>/img/logo_compunet_color.png" alt="COMPUNET Logo" style="width: 140px; height: auto; padding: 10px;">
  </a>

  <?php
    $identity = Yii::$app->user->identity;
    $userThumbnail = ($identity && $identity->profile) ? $identity->profile->customThumbnail : '';
    $userFullname = $identity ? $identity->fullname : 'Usuario';
    $userUsername = $identity ? $identity->username : '';
  ?>
  <section class="sidebar">
    <a href="#" class="user-panel  d-flex justify-content-center">
      <!-- Sidebar user panel -->
      <div class="image">
        <img src="<?= $userThumbnail; ?>" class="img-circle " alt="User Image">
      </div>
      <div class="info">
        <span class="d-block"><?= $userFullname ?></span>
        <span class="d-block"><?= $userUsername ?></span>
      </div>
      <!-- Sidebar Menu -->
    </a>
  </section>
  <nav class="mt-3">
    <?= seisvalt\widgets\Menu::widget(
      [
        'options' => [
          'class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview', 'role' => 'menu', 'data-accordion' => false,
        ],
        'items' => $items,
      ]
    ) ?>
  </nav>
</aside>
