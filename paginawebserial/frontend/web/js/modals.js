var arr = [];

// $(document).ready(function () {
//   console.log("init funtuon ");
// });
$(".showModalButton").click(function (e) {
  e.preventDefault();

  if ($("#your-modal").data("bs.modal").isShown) {
    $("#your-modal").find("#modalContent").load($(this).attr("value"));
    $("#your-modal-label").html("<h4>" + $(this).attr("title") + "</h4>");
    $("#your-modal")
      .find(".modal-dialog")
      .addClass($(this).attr("size"))
      .removeClass("modal-md");
  } else {
    $("#your-modal")
      .modal("show")
      .find("#modalContent")
      .load($(this).attr("value"));
    $("#your-modal-label").html("<h4>" + $(this).attr("title") + "</h4>");
    $("#your-modal")
      .find(".modal-dialog")
      .addClass($(this).attr("size"))
      .removeClass("modal-md");
  }
});
$("button").click(function () {
  $("#mymodal").modal("show");
});
// MODO DEMO: Socket.io deshabilitado
var socket = null;
if (typeof io !== 'undefined') {
  var socketUrl = $("script[data-m][data-m!=null]").attr("data-m");
  if (socketUrl) {
    socket = io.connect(socketUrl, {
      // secure: true
    });
  }
}



// $('#data [label="Permission"] option').each(function () {
//   arr.push($(this).val())
//   console.log("askdjaksjready!");

// });
// console.log(arr);

