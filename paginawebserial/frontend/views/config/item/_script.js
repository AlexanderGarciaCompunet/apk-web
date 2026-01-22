var listItems = {
  "name": [
    "assign_user_store",
    "basic",
    "coordinator",
    "import_data",
    "manager",
    "materialMaster",
    "operator",
    "permission_admin",
    "permission_customer",
    "permission_info",
    "permission_item",
    "permission_order",
    "permission_role",
    "permission_script",
    "permission_store",
    "permission_user",
    "sysadmin",
    "update_index",
    "update_item",
    "update_order",
    "update_role",
    "update_store",
    "update_user",
    "view_customer",
    "view_item",
    "view_order",
    "view_role",
    "view_scripts",
    "view_store",
    "view_user",
    "warehouse_index",
  ],
  "description": [
    "Permisos para asignar usuarios a almacen",
    "Permisos basicos",
    "Coordinador",
    "Permisos para importar data del wms",
    "manager",
    "Maestro de Materiales",
    "Operario",
    "Permisos del administrador",
    "Permisos para clientes",
    "Permisos para información general",
    "Permisos para materiales",
    "Permisos para pedidos",
    "Permisos para roles",
    "Permisos para scripts",
    "Permisos para almacén",
    "Permisos para usuarios",
    "SuperAdministrador del sistema",
    "Permisos para materiales",
    "Permisos para materiales",
    "Permisos para pedidos",
    "Permisos para roles",
    "Permisos para almacén",
    "Permisos para usuarios",
    "Permisos para materiales",
    "Permisos para materiales",
    "Permisos para pedidos",
    "Permisos para roles",
    "Permisos scripts",
    "Permisos para almacén",
    "Permisos para usuarios",
    "Permisos bodega",
  ],
}
function checkItem(name) {
  listItems.forEach(function (item) {
    if (item == name) {
      return true;
    }
  }
  );
  return false;


}
function updateItems(r) {
  _opts.items.available = r.available;
  _opts.items.assigned = r.assigned;
  search('available');
  search('assigned');
}

$('.btn-assign').click(function () {
  var $this = $(this);
  var target = $this.data('target');
  var items = $('select.list[data-target="' + target + '"]').val();

  if (items && items.length) {
    $.post($this.attr('href'), { items: items }, function (r) {
      updateItems(r);
    });
  }
  return false;
});

$('.search[data-target]').keyup(function () {
  search($(this).data('target'));
});

function search(target) {
  var i = 0;

  var $list = $('select.list[data-target="' + target + '"]');
  $list.html('');
  var q = $('.search[data-target="' + target + '"]').val();

  var groups = {
    role: [$('<optgroup label="Roles">'), false],
    permission: [$('<optgroup label="Permisos">'), false],
    route: [],
  };
  $.each(_opts.items[target], function (name, group) {
    i = i + 1;
    if (name.indexOf(q) >= 0) {
      if ([group][0] == "permission") {
        $('<option title="' + listItems[i - 1] + '">').text(name).val(name).appendTo(groups[group][0]);
        console.log(i);
      } else {
        $('<option> ').text(name).val(name).appendTo(groups[group][0]);
      }
      groups[group][1] = true;
    }
  });
  $.each(groups, function () {
    if (this[1]) {
      $list.append(this[0]);
    }
  });
}

// initial
search('available');
search('assigned');





console.log(listItems['name'][0]);
