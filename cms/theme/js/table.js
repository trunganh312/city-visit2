/** --- Checkbox Active/Deactive --- **/
function table_tick(e) {
  $(e)
    .load($(e).attr("href"))
    .html('<img class="loading_small" src="/theme/image/loading_24.gif" />');
  return false;
}

//Check all
function check_all(obj) {
  if ($(obj).is(":checked")) {
    $(".icb").attr("checked", "checked");
  } else {
    $(".icb").removeAttr("checked");
  }
}
//Active field
function active_field(obj) {
  $(obj).html('<img class="loading_small" src="/theme/image/loading_24.gif" />').load(obj.href);
  //$(obj).load(obj.href);
  return false;
}
//Delete one record
function delete_one(record_id) {
  var url_delete = "delete.php";
  if (typeof file_delete != "undefined") url_delete = file_delete;

  if (confirm("Bạn có chắc chắn muốn xóa bản ghi này?")) {
    $.ajax({
      type: "POST",
      url: url_delete,
      data: { id: record_id },
      success: function (data) {
        if (data != "Error") {
          $("#tr_" + record_id).remove();
        }
      },
    });
  }
}
//Delete all selected
function delete_all() {
  if (confirm("Bạn có chắc chắn muốn xóa hết các bản ghi đã chọn?")) {
    var list_id = "0";
    var arr_id = new Array();
    var url_delete = "delete.php";
    if (typeof file_delete != "undefined") url_delete = file_delete;

    $(".icb").each(function () {
      if ($(this).is(":checked")) {
        list_id += "," + $(this).val();
        arr_id.push($(this).val());
      }
    });

    if (list_id != "0") {
      $.ajax({
        type: "POST",
        url: url_delete,
        data: { record_id: list_id },
        success: function (data) {
          if (data != "Error") {
            for (var i = 0; i < arr_id.length; i++) {
              $("#tr_" + arr_id[i]).remove();
            }
          }
        },
      });
    } //end if
  } //end confirm
}
