$(function () {
  /* Xử lý upload ảnh */
  mediaGenTable();

  //Upload ảnh khi chọn file
  $("#media-upload input[type=file]").change(async function () {
    process_status();
    var totalfiles = $(this)[0].files.length;
    var fd = new FormData();
    // Check file selected or not
    for (var index = 0; index < totalfiles; index++) {
      fd.append("files", $(this)[0].files[index]);
      fd.append("tempup", media_db.temp);
      fd.append("group", $(this).data("type"));

      await $.ajax({
        url: "/module/common/uploadify.php",
        type: "POST",
        data: fd,
        contentType: false,
        processData: false,
        dataType: "json",
      }).then(function (res) {
        media_db.images = Object.assign(media_db.images, res.data);
        if (res.msg != "") {
          $("#error_upload").append(res.msg + "<br>");
        }
      });
    }
    $("#media-upload input[type=file]").val(null);
    mediaGenTable();
    process_status(false);
  });

  /*Move drag image*/
  $("#media-upload").sortable({
    items: "li:not(.select)",
  });
});

//Hàm render ra table ảnh
function mediaGenTable() {
  var html_str = [];
  for (var i in media_db.images) {
    const row = media_db.images[i];
    $(`#media-upload li[data-key=${i}]`).remove();
    var temp = media_db.template.replace("{key}", i);
    temp = temp.replace("{src}", row.src);
    temp = temp.replace("{name}", row.name);
    temp = temp.replace("{id}", row.id);
    html_str.push(temp);
  }
  $("#media-upload").append(html_str.join(""));
}

/*Process status*/
function process_status(v = true) {
  if (v) $("#processbar").show();
  else $("#processbar").hide();
}

//Xóa các ảnh cũ
function delete_image(id) {
  var _id = id;
  var _del = confirm("Xác nhận xóa ảnh?");
  if (_del) {
    process_status();
    $.post(
      "delete_image.php",
      { pic_id: _id },
      function (data) {
        if (data.ok === 0) {
          alert(data.error);
          return false;
        } else {
          $(`#media-upload li[data-key=k${_id}]`).remove();
        }
        process_status(false);
      },
      "json",
    );
  }
}

/** --- Xóa các ảnh tạm vừa up --- **/
function delete_image_temp(id) {
  var _id = id;
  var _del = confirm("Xác nhận xóa ảnh?");
  if (_del) {
    process_status();
    $.post(
      "/module/common/delete_image_temp.php",
      { pic_id: _id },
      function (data) {
        if (data.ok === 0) {
          alert(data.error);
          return false;
        } else {
          delete media_db.images[`k${_id}`];
          $(`#media-upload li[data-key=k${_id}]`).remove();
        }
        process_status(false);
      },
      "json",
    );
  }
}
