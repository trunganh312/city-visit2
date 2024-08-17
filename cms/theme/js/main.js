$(function () {
  $(".main_content").append(
    '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
  );
  load_editor(".texteditor");

  $(".datepicker").daterangepicker({
    singleDatePicker: true,
    minDate: typeof _minDate != "undefined" ? _minDate : "",
    locale: {
      format: "DD/MM/YYYY",
      cancelLabel: "Xóa",
      applyLabel: "Chọn",
    },
  });

  $(document).on("focus", ".timepicker", function () {
    $(this).daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,
      locale: {
        format: "DD/MM/YYYY HH:mm",
        cancelLabel: "Xóa",
        applyLabel: "Chọn",
      },
    });
  });

  $(".date_range").daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    minDate: typeof _minDate != "undefined" ? _minDate : "01/01/2021",
    locale: {
      customRangeLabel: "Theo khoảng",
      format: "DD/MM/YYYY",
      cancelLabel: "Xóa",
      applyLabel: "Chọn",
    },
  });

  $(".date_ranges").daterangepicker({
    ranges: {
      "Hôm nay": [moment(), moment()],
      "Hôm qua": [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Tuần này": [moment().startOf("isoWeek"), moment()],
      "Tuần trước": [
        moment().subtract(1, "isoWeek").startOf("isoWeek"),
        moment().subtract(1, "isoWeek").endOf("isoWeek"),
      ],
      "Tháng này": [moment().startOf("month"), moment().endOf("month")],
      "Tháng trước": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
    autoUpdateInput: false,
    autoApply: true,
    locale: {
      customRangeLabel: "Theo khoảng",
      format: "DD/MM/YYYY",
      cancelLabel: "Xóa",
      applyLabel: "Chọn",
    },
  });

  $(".date_range, .date_ranges").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("DD/MM/YYYY") + " - " + picker.endDate.format("DD/MM/YYYY"),
    );
  });
  $(".date_range, .date_ranges").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
  });

  $(".date_time_range").daterangepicker({
    timePicker: true,
    timePickerIncrement: 1,
    locale: {
      format: "DD/MM/YYYY HH:mm",
      cancelLabel: "Xóa",
      applyLabel: "Chọn",
    },
  });

  $(document).on("keyup", ".number", function () {
    $(this).val(format_number($(this).val()));
  });

  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="select2"]').select2({
    placeholder: function () {
      $(this).data("placeholder");
    },
    language: {
      noResults: function () {
        return "";
      },
    },
  });

  /** --- Load quận huyện, xã phường --- **/
  $("#city").change(function () {
    $("#district").load("/module/common/get_select_child.php?type=district&id=" + $(this).val());
  });

  $("#district").change(function () {
    $("#ward,#wards").load("/module/common/get_select_child.php?type=ward&id=" + $(this).val());
  });

  /** Gõ search tự động **/
  $(".search_auto").autoComplete({
    minChars: 2,
    source: function (term, response) {
      $.getJSON(
        "/module/common/search_auto.php",
        { q: term, type: $(".search_auto:focus").data("target") },
        function (data) {
          response(data);
        },
      );
    },
    renderItem: function (item, search) {
      return (
        '<div class="autocomplete-suggestion"><span class="img-responsive" data-id="' +
        item.id +
        '">' +
        item.name +
        "</span></div>"
      );
    },
    onSelect: function (e, term, item) {
      e.preventDefault();
      var _module = $(".search_auto:focus").data("target");
      var _type = $(".search_auto:focus").data("type");
      var _name = _module;
      if ($(".search_auto:focus").data("name")) _name = $(".search_auto:focus").data("name");
      //Nếu là search để add nhiều item kiểu như add các địa danh vào tour
      if (_type == "multi") {
        $("#list_" + _module + " ul").append(
          '<li><i class="fa fa-times" aria-hidden="true" onclick="remove_item_search(this);"></i>' +
            item.find("span").text() +
            '<input type="hidden" name="' +
            _name +
            '[]" value="' +
            item.find("span").data("id") +
            '"></li>',
        );
      } else {
        //Nếu search 1 item thì put luôn value vào input
        $("#" + _module + "_id").val(item.find("span").data("id"));
        $("#search_" + _module).val(item.find("span").text());
      }
    },
  });

  /** --- Sort element --- **/
  $(".sortable").sortable();

  $(".dropdown-toggle").dropdown({
    display: "static",
  });

  /** Copy content **/
  $(".copy_button").click(function (e) {
    e.preventDefault();
    var copyText = $(this).parents(".copy_box").eq(0).find(".copy_input").select();
    document.execCommand("copy");
    $(".tooltip-inner").html("Copied");
  });

  $(".max_char").keyup(function () {
    var _count = $(this).val().length;
    $(this).next().find(".count_char").text(_count);
  });
});

//Check email valid
function isEmail(email) {
  var re =
    /^(\w|[^_]\.[^_]|[\-])+(([^_])(\@){1}([^_]))(([a-z]|[\d]|[_]|[\-])+|([^_]\.[^_])*)+\.[a-z]{2,3}$/i;
  return re.test(email);
}

//Check field blank
function checkblank(str) {
  if ($.trim(str) == "") return true;
  return false;
}

//Format number 1234567.89 to 1,234,567.89
function format_number(n) {
  if (!n) return "";
  if (n == "-") return n;

  var number = n.toString().replace(/,/g, "");
  number = Number(number);

  if (isNaN(number)) return "";

  var parts = number.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  return parts.join(".");
}

//Lấy giá trị của một số khi số đó có chứa các dấu phẩy phân tách phần nghìn
function get_number(number_str) {
  if (!number_str) return "0";
  return number_str.toString().replace(/,/g, "");
}

/** --- Xóa ảnh khi thêm/sửa SP --- **/
function delete_picture(id, type) {
  var _id = id;
  var _del = confirm("Xác nhận xóa ảnh?");
  var _type = type ? type : "temp";

  //Tùy xóa ảnh hiện tại hay là ảnh đang up thêm
  var file_delete = _type == "official" ? "delete_picture.php" : "delete_picture_temp.php";

  if (_del) {
    $.post(
      file_delete,
      { pic_id: _id },
      function (data) {
        if (data.ok === 0) {
          alert(data.error);
          return false;
        } else {
          $("#pic_" + _id).remove();
        }
      },
      "json",
    );
  }
}

/** --- Update giá trị của 1 selebox --- **/
function ajax_change_value(id, value, type) {
  var _id = id ? id : 0;
  var _value = value ? value : 0;
  var _type = type ? type : "";

  $.ajax({
    type: "POST",
    url: "change_value.php",
    data: { record_id: _id, value: _value, type: _type },
  });
}

/** --- Validate form khi submit --- **/
function check_form() {
  toggle_overlay();

  var count = 0;
  $(".required").each(function () {
    var value = $(this).val();

    if (checkblank(value)) {
      //Tùy theo là input text hay là select mà show câu chữ phù hợp
      var call = $(this).hasClass("control_select") ? "chọn" : "nhập";
      var el = $(this);
      //Nếu là select2 thì phải next() thêm
      if ($(this).hasClass("control_select2")) el = $(this).next();

      el.addClass("is_blank")
        .next()
        .addClass("error")
        .html("Vui lòng " + call + " " + $(this).attr("data-required"));

      count++;
    } else {
      //Nếu có nhập dữ liệu thì remove các class lỗi (nếu trước đó bị lỗi)
      if ($(this).hasClass("control_select2")) {
        $(this).next().removeClass("is_blank").next().removeClass("error").html("");
      } else {
        $(this).removeClass("is_blank");
        $(this).next().removeClass("error").html("");
      }
    }
  });

  if (count > 0) {
    toggle_overlay();
    return false;
  }

  document.form_data.submit();
}

//Set editable cho field text
function set_editable(e, url, data, type) {
  e.editable(url, {
    indicator: "<img src='/theme/image/loading_fb.gif' />",
    type: type ? type : "text",
    submit: "Lưu",
    cancel: "Hủy",
    tooltip: "Click để sửa",
    placeholder: "",
    inputcssclass: "form-control",
    cancelcssclass: "btn btn-block btn-danger btn-xs btn-flat",
    submitcssclass: "btn btn-block btn-success btn-xs btn-flat",
    width: "70%",
    submitdata: function () {
      data_submit = {};
      $.each(data, function (k, v) {
        data_submit[v] = e.data(v);
      });
      return data_submit;
    },
  });
}

/** Show or Hide overlay **/
function toggle_overlay() {
  $(".main_content").toggleClass("overlay-wrapper");
}

/*Process status*/
function process_status(v = true) {
  if (v) $("#processbar").show();
  else $("#processbar").hide();
}

// tao url mới và add thêm params
function URL_add_parameter(url, param, value) {
  var hash = {};
  var parser = document.createElement("a");

  parser.href = url;

  var parameters = parser.search.split(/\?|&/);

  for (var i = 0; i < parameters.length; i++) {
    if (!parameters[i]) continue;

    var ary = parameters[i].split("=");
    hash[ary[0]] = ary[1];
  }

  hash[param] = value;

  var list = [];
  Object.keys(hash).forEach(function (key) {
    list.push(key + "=" + hash[key]);
  });

  parser.search = "?" + list.join("&");
  return parser.href;
}

//Load Editor
function load_editor(el) {
  var editor_box = $(el).summernote({
    height: typeof editor_height != "undefined" ? editor_height : 200,
    callbacks: {
      onImageUpload: function (files) {
        for (let i = 0; i < files.length; i++) {
          //Up ảnh từ Editor lưu trực tiếp trên server rồi chèn link ảnh vào nội dung
          let out = new FormData();
          out.append("file", files[i], files[i].name);

          $.ajax({
            method: "POST",
            url: "../common/upload_editor.php",
            contentType: false,
            cache: false,
            processData: false,
            data: out,
            success: function (img) {
              editor_box.summernote("insertImage", img);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              alert(textStatus + " " + errorThrown);
            },
          });
        }
      },
    },
  });
}

/** Gõ search auto check multi **/
function search_multi(type) {
  var loading = $("#search_" + type)
    .parent()
    .find(".img_loading_form");
  var kw = $("#search_" + type).val();

  if (kw.length >= 2) {
    loading.show();
    $.get("/module/common/search_multi.php?q=" + kw + "&type=" + type, function (data) {
      $("#list_" + type + " ul").append(data);
    });
    loading.hide();
  } else {
    alert("Từ khóa tìm kiếm phải có ít nhất 2 ký tự");
  }
}

// Show checkeditor
const toolbarOptions = [
  ["bold", "italic", "underline", "strike"], // toggled buttons
  ["blockquote", "code-block"],
  ["link", "image", "video", "formula"],

  [
    {
      header: 1,
    },
    {
      header: 2,
    },
  ], // custom button values
  [
    {
      list: "ordered",
    },
    {
      list: "bullet",
    },
    {
      list: "check",
    },
  ],
  [
    {
      script: "sub",
    },
    {
      script: "super",
    },
  ], // superscript/subscript
  [
    {
      indent: "-1",
    },
    {
      indent: "+1",
    },
  ], // outdent/indent
  [
    {
      direction: "rtl",
    },
  ], // text direction

  [
    {
      size: ["small", false, "large", "huge"],
    },
  ], // custom dropdown
  [
    {
      header: [1, 2, 3, 4, 5, 6, false],
    },
  ],

  [
    {
      color: [],
    },
    {
      background: [],
    },
  ], // dropdown with defaults from theme
  [
    {
      font: [],
    },
  ],
  [
    {
      align: [],
    },
  ],

  ["clean"], // remove formatting button
];
var options = {
  theme: "snow",
  modules: {
    toolbar: toolbarOptions,
  },
};

// Hàm chèn text vào ô textarea
function insertText(id) {
  console.log(123);
  editor.on("text-change", function () {
    document.querySelector(id).innerText = document.querySelector("#editor").children[0].innerHTML;
  });
}
