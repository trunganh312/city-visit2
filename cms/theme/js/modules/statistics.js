"use strict";
/**
 * Format money
 */
Number.prototype.formatMoney = function (
  decimalLength = 0,
  decimal = ".",
  thousands = "."
) {
  let amount = this;
  try {
    let decimalCount = Math.abs(decimalLength);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    const i = parseInt(
      (amount = Math.abs(Number(amount) || 0).toFixed(decimalCount))
    ).toString();
    const j = i.length > 3 ? i.length % 3 : 0;

    return (
      negativeSign +
      (j ? i.substr(0, j) + thousands : "") +
      i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
      (decimalCount
        ? decimal +
        Math.abs(amount - i)
          .toFixed(decimalCount)
          .slice(2)
        : "")
    );
  } catch (e) {
    throw e;
  }
};

var Statistics = function (department) {
  this.chart = null;
  this.piceChart_1 = null;
  this.department = department;
  this.range = null;
  this.level = window.level;
  this.url = '';
  this.state = {
    boxData: window.boxData,
    viewMKT: 'confirm',
    prevType: null,
    // company: 0,
    // department: 0, khai bao truoc sau nay co the se dung den
    // team: 0,
    // admin: 0,
    id: 0,
    viewType: 0, // mode view hien tai cua bao cao
    refreshTime: 10, //10 phut cap nhat lai 1 lan
  };

  console.log(this);

};
/**
 * Tự động cập nhật dữ liệu theo time
 */
Statistics.prototype.autoUpdateData = function () {
  var self = this;
  var refreshTimeSeconds = this.state.refreshTime * 60,
    remainingTime = refreshTimeSeconds;
  var currentTime = 0;
  var timeFormat = null;
  self.actionfrom = null;
  
  setInterval(function () {
    currentTime = currentTime + 1;
    if (currentTime === refreshTimeSeconds) {
      currentTime = 0;
      self.updateChartData()
    }
    remainingTime = refreshTimeSeconds - currentTime;
    var h = Math.floor(remainingTime / 60);
    var s = Math.round(remainingTime % 60);
    timeFormat = format_number(h) + ":" + format_number(s);
  }, 1000);
};

/** Hàm tính % **/
var calculate_percent = function (target, total) {
    if (total == 0 || target == 0)
    return 0;
    
    var percent = (target / total) * 100;
    return Number.parseFloat(percent).toFixed(2);
}
/**
 * Hàm khởi tạo event của modules
 */
Statistics.prototype.run = function () {
  this.drawChart(); // Vẽ chart của thống kê
  this.changeDataChartFromSelectValue(); // Sự kiện khi select element trong filter chart change value
  this.autoUpdateData(); // Tự động cập nhật
  this.stickyFilter();
  this.changeViewMKT();
  var $this = this;
  
  $('#date_range').daterangepicker(
      {
        ranges   : {
          'Hôm nay' : [moment(), moment()],
          'Tuần này' : [moment().startOf('isoWeek'), moment()],
          'Tháng này'  : [moment().startOf('month'), moment().endOf('month')]
        },
        startDate: moment(),
        endDate  : moment(),
        locale: {
            customRangeLabel: "Theo khoảng",
            format: "DD/MM/YYYY",
            cancelLabel: "Xóa",
            applyLabel: "Chọn",
          },
      },
      function (start, end) {
        var t1 = start.format("DD/MM/YYYY");
        var t2 = end.format("DD/MM/YYYY");
        $this.range = t1 + " - " + t2;
        $(this).val($this.range);
        $this.actionfrom = null;      
        $this.updateChartData();
        
      }
    );
  /*
  $('input[name="date_range"]').daterangepicker({
    // timePicker: true,
    autoUpdateInput: true,
    locale: {
      format: "DD/MM/YYYY",
      cancelLabel: "Xóa",
      applyLabel: "Chọn"
    }
  },
    function (start, end) {
      var t1 = start.format("DD/MM/YYYY");
      var t2 = end.format("DD/MM/YYYY");
      $this.range = t1 + " - " + t2;
      $this.updateChartData();
    }
  );
  */
};
/**
 * Lấy dữ liệu input của filter report  
 *
 * @param   {string}  type  Kiểu input được định nghĩa xem các kiểu trong file /module/common/get_data_child.php
 * @param   {[type]}  id    id của record cần lấy
 *
 */
Statistics.prototype.commonGetDataChild = function (type, id) {
  $.get(
    "/module/common/get_data_child.php?type=" + type + "&id=" + id + "&group_department=" + this.department
  ).done(res => {
    // $("#sale-" + type).attr("disabled", false);
    $("#sale-" + type).html(res);
  });
};

/**
 * Hàm control sự kiện của các selectbox
 *
 * @return  {[type]}  [return description]
 */
Statistics.prototype.changeDataChartFromSelectValue = function () {
  var self = this;
  $(".selectbox-change-data-chart").change(function (value) {
    var viewType = $(this).attr("data-type");
    var id = parseInt($(this).val());
    if (id == 0) {
      var prevEl = $(this).prev().prev();
      if (prevEl.length == 0) {
        id = 1; //
        viewType = 6; // View type = 6 là mặc định xem toàn bộ tập đoàn
      } else {
        id = prevEl.val();
        viewType = prevEl.attr("data-type");
      }
    }

    self.state.viewType = viewType;
    self.state.id = id;
    self.actionfrom = 'select';
    self.updateChartData();
  });
};

/**
 * Cập nhật tất cả các input select tính từ sau inout đang thao tác khi có sự thay đổi
 *
 * @param   {object}  element  jQuery Element
 *
 */
Statistics.prototype.resetAllInput = function (element) {
  var next = element.nextAll('select');
  if (next.length > 0) {
    next.each(function (index, _nextEl) {
      var firstOption = $(_nextEl).find('option');
      $(_nextEl).html(firstOption[0]);
    })
  }
}

/**
 * Set view type cho MKT
 */
Statistics.prototype.changeViewMKT = function (element) {
    var self = this;
    $('.change_view').change(function() {
        self.state.viewMKT = $(this).val();
        self.actionfrom = null;
        self.updateChartData();
    });
}

/**
 * updateChartData
 * render lai chart theo cac dieu kien da chon
 */
Statistics.prototype.updateChartData = function () {
  var self = this;
  $("#submit_loading").show();
  var date_range = $('#date_range').val();
  
  if (self.range != 'undefined' && self.range != null) {
    date_range  =   self.range
  }
  
  var group = parseInt(this.department);
  
  var action = (group == 1) ? 'saleReport' : 'marketingReport';
  
  var url = "id=" +
    self.state.id +
    "&type=" +
    self.state.viewType +
    "&view=" +
    self.state.viewMKT +
    "&dateRange=" + date_range;
  
  $.get(
    "/?json=1&controller=statistic&action=" + action + "&" + url
  ).done(function (res) {
    res = JSON.parse(res);
    var data = res.data.boxData;
    
    self.state.boxData = data;
    self.url  =   url;
    self.range = date_range;
    
    $("#doanh-so").text(data.money_total ? parseInt(data.money_total).formatMoney(0, ",", ",") : 0);
    $("#doanh-thu").text(data.revenue_total ? parseInt(data.revenue_total).formatMoney(0, ",", ",") : 0);
    
    //Nếu xem báo cáo của Sale
    if (action == 'saleReport') {
        
        //Set lại href của các link liên quan
        $("#doanh-so").parents('.inner').find('.thickbox').attr('href', `/module/statistic/listing_order_all_sale.php?` + url + `&TB_iframe=true&width=1100&height=600`);
        $('#btn_export_sale').attr('href', "/module/statistic/export_list_sale.php?" + url);
        
        $("#data-new").text(format_number(data.data_new));
        $("#rate-new").text(calculate_percent(data.order_new, data.data_new));
        $("#rate-old").text(calculate_percent(data.order_old, data.data_old));
        $("#rate-cskh").text(calculate_percent(data.order_cskh, data.data_cskh));
        $("#rate-cancel").text(calculate_percent(data.order_cancel, data.order_confirmed));
        $("#rate-return").text(calculate_percent(data.order_return, data.order_shipped));
        
    } else {
        //Nếu xem báo cáo của MKT
        
        //Set lại href của các link liên quan
        $("#doanh-so").parents('.inner').find('.thickbox').attr('href', `/module/statistic/listing_order_all_sale.php?department=MKT&` + url + `&TB_iframe=true&width=1100&height=600`);
        $('#btn_export_sale').attr('href', "/module/statistic/export_list_sale.php?group=mkt&" + url);
        
        //Nếu xem theo report ngày đẩy Data
        if (self.state.viewMKT == 'confirm') {
            
            $("#data-box-3").text(format_number(data.data_new));
            $("#data-box-4").text(format_number(data.data_old)).next().html('');
            $("#data-box-5").text(format_number(data.order_total)).next().html('');
            $("#data-box-6").text(calculate_percent(data.order_total, data.data_total)).next().html('%');
            $("#data-box-7").text(calculate_percent(data.order_total, data.data_connected));
            $("#data-box-8").text(calculate_percent(data.order_old, data.data_old));
            
            $("#data-box-3-txt").text('Data mới');
            $("#data-box-4-txt").text('Data cào');
            $("#data-box-5-txt").text('Tổng đơn hàng');
            $("#data-box-6-txt").text('Tỷ lệ chốt');
            $("#data-box-7-txt").text('Tỷ lệ chốt/Kết nối');
            $("#data-box-8-txt").text('Tỷ lệ chốt cào');
            
        } else {
            
            //Nếu xem theo ngày tạo Data
            $("#data-box-3").text(format_number(data.data_new));
            $("#data-box-4").text(calculate_percent(data.order_new, data.data_new)).next().html('%');
            $("#data-box-5").text(calculate_percent(data.order_new, data.data_connected)).next().html('%');
            $("#data-box-6").text(format_number(data.budget_total)).next().html('đ');
            $("#data-box-7").text(calculate_percent(data.budget_total, data.money_total));
            $("#data-box-8").text(calculate_percent(data.budget_total, data.revenue_total));
            
            $("#data-box-3-txt").text('Data mới');
            $("#data-box-4-txt").text('Tỷ lệ chốt mới');
            $("#data-box-5-txt").text('Tỷ lệ chốt/Kết nối');
            $("#data-box-6-txt").text('Chi phí');
            $("#data-box-7-txt").text('Chi phí/Doanh số');
            $("#data-box-8-txt").text('Chi phí/Doanh thu');
            
        }
        
    }
    
    $("#submit_loading").hide();
    self.chart.data = self.formatDatasets(res.labelChart, res.data.chartData);
    var piceData = self.formatDatasets(res.labelChart, res.data.chartData, 'pie');
    self.piceChart_1.data = {
      datasets: [{
        data: piceData.datasets.data_money,
        backgroundColor: piceData.chartColor
      }],
      labels: piceData.labels
    }
    self.piceChart_2.data = {
      datasets: [{
        data: piceData.datasets.data_revenue,
        backgroundColor: piceData.chartColor
      }],
      // These labels appear in the legend and in the tooltips when hovering different arcs
      labels: piceData.labels
    }
    self.chart.update();
    self.piceChart_1.update(); // Update chart doanh số
    self.piceChart_2.update(); // Update chart doanh thu
    
    //Load lại bảng dữ liệu chi tiết
    if (action == 'saleReport') {
        self.renderDataTableSale(res);
    } else {
        if (self.state.viewMKT == 'confirm') {
            self.renderDataTableMKT(res);
        } else {
            self.renderDataTableMKTCreate(res);
        }
        
    }
    
    //Load option cho các selectbox tương ứng
    if (self.actionfrom != 'undefined' && self.actionfrom == 'select') {
        var html_select = '<option value="0">-- Chọn ' + res.data.labelSelect + ' --</option>';   //HTML để cho append vào các select box tương ứng
        
        $.each(res.data.option, function (index, value) {
            html_select +=  '<option value="' + value.id + '">' + value.name + '</option>';
          });
         $('#sale-' + self.state.viewType).html(html_select);
         
         //Clear các option cấp con
         for (var i = 0; i < self.state.viewType; i++) {
            $('#sale-' + i).html('<option value="0">-- Chọn ' + $('#sale-' + i).attr('label-name') + ' --</option>');
         }
    }
  });
};

var colors = [
  "rgba(35,183,229,1)",
  "rgba(220, 54, 69,1)",
  "rgba(39, 194, 76,1)",
  "#ffc107",
  "#e83e8c",
  "#3c8dbc",
  "#fd7e14",
  "#007bff",
  "#8bc34a",
  "#9e9e9e",
  "#673ab7"
]
/** 
 * format data return theo format cua chartjs
 */
Statistics.prototype.formatDatasets = function (labels, datasets, type) {
  var self = this;
  
  var _return = {
    labels: [],
    datasets: [],
    chartColor: []
  }
  // set chart labels
  $.each(labels, function (key, title) {
    _return.labels.push(title);
    _return.chartColor.push(colors[key]);
  });

  var chartDatasetValue = {
    data_money: [],
    data_revenue: [],
    piceColor: []
  };

  $.each(datasets, function (key, value) {
    if (type == 'pie') {
      var percent_money = calculate_percent(value.money_total, self.state.boxData.money_total);
      var percent_revenue = calculate_percent(value.revenue_total, self.state.boxData.revenue_total);
      chartDatasetValue.data_money.push(percent_money);
      chartDatasetValue.data_revenue.push(percent_revenue);
    } else {
      chartDatasetValue.data_money.push(parseInt(value.money_total));
      chartDatasetValue.data_revenue.push(parseInt(value.revenue_total));
    }
  });
  
  if (type == 'pie') {
    _return.datasets = chartDatasetValue;
  } else {
    _return.datasets = [{
      label: "Doanh số",
      backgroundColor: "rgba(35,183,229,1)",
      borderColor: "rgb(35,183,229)",
      borderWidth: 1,
      data: chartDatasetValue.data_money // list doanh so của nhân viên ứng với mục labels
    },
    {
      label: "Doanh thu",
      backgroundColor: "rgba(220, 54, 69,1)",
      borderColor: "rgb(220, 54, 69)",
      borderWidth: 1,
      data: chartDatasetValue.data_revenue // list doanh thu của nhân viên ứng với mục labels
    }];
  }
  
  return _return;

}

/**
 * render chartjs hien thi doanh thu va doanh so
 * cac bien cua chart duoc lay từ file module/statistic/views/sale.php
 * cac biến này được lấy để sử dụng lần đâu tiên
 */
Statistics.prototype.drawChart = function () {
  var data = this.formatDatasets(window.chartData.labels, chartData.datasets);
  
  this.chart = new Chart(document.getElementById("chart").getContext("2d"), {
    type: "bar",
    data: data,
    options: {
      plugins: {
        labels: false  // disable plugin 'p1' for this instance
      },
      reponsive: true,
      maintainAspectRatio: false,
      tooltipTemplate: "<%= addCommas(value) %>",
      legend: {
        position: "top"
      },
      scales: {
        yAxes: [
          {
            ticks: {
              callback: function (value) {
                return value.formatMoney(0, ",", ",") + " đ";
              }
            }
          }
        ]
      },
      tooltips: {
        callbacks: {
          label: function (tooltipItem, chart) {
            var datasetLabel =
              chart.datasets[tooltipItem.datasetIndex].label || "";
            return (
              datasetLabel +
              " " +
              tooltipItem.yLabel.formatMoney(0, ",", ",") +
              " đ"
            );
          }
        }
      },
      title: {
        display: false,
        text: "Biểu đồ doanh số",
        fontSize: 18
      }
    }
  });

  var piceData = this.formatDatasets(window.chartData.labels, chartData.datasets, 'pie');
  var pieOptions = {
    legend: {
      position: "right"
    },
    tooltips: {
      callbacks: {
        label: function (item, chart) {
          return chart.labels[item.index] + " : " + Math.round(chart.datasets[0].data[item.index]) + '%';
        }
      }
    },
    // plugins link : https://github.com/emn178/chartjs-plugin-labels
    plugins: {
      labels: [{
        color: 'white',
        render: 'percentage',
        fontColor: "white"
      }]
    }
  }
  this.piceChart_1 = new Chart(document.getElementById('pie-1').getContext('2d'), {
    type: 'pie',
    data: {
      datasets: [{
        data: piceData.datasets.data_money,
        backgroundColor: piceData.chartColor
      }],

      // These labels appear in the legend and in the tooltips when hovering different arcs
      labels: piceData.labels
    },
    options: pieOptions
  })

  this.piceChart_2 = new Chart(document.getElementById('pie-2').getContext('2d'), {
    type: 'pie',
    data: {
      datasets: [{
        data: piceData.datasets.data_revenue,
        backgroundColor: piceData.chartColor
      }],

      // These labels appear in the legend and in the tooltips when hovering different arcs
      labels: piceData.labels
    },
    options: pieOptions
  })
};

/**
 * Load ajax lại data của table sale
 */
Statistics.prototype.renderDataTableSale = function (res) {
    var self = this;
    var url = "&type=" + self.state.viewType + "&dateRange=" + self.range;  //URL để click hiển thị popup chi tiết
    
  var htmlString = '';
  $.each(res.data.data, function (index, value) {
    htmlString += `<tr class="text-right tr_data">
        <td class="text-left th_break_right">${res.listName[value.id]}</td>
        <td><a class="thickbox" href="/module/statistic/list_data_process.php?data_type=new&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Data mới được xử lý của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.data_new)}</a></td>
        <td><a class="thickbox" href="/module/statistic/list_data_process.php?data_type=old&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Data cào được xử lý của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.data_old)}</a></td>
        <td class="th_break_right"><a class="thickbox" href="/module/statistic/list_data_process.php?data_type=cskh&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Data CSKH được xử lý của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.data_cskh)}</a></td>
        <td><a class="thickbox" href="/module/statistic/list_order_process.php?order_type=new&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Đơn mới của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.order_new)}</a></td>
        <td><a class="thickbox" href="/module/statistic/list_order_process.php?order_type=old&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Đơn cào của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.order_old)}</a></td>
        <td><a class="thickbox" href="/module/statistic/list_order_process.php?order_type=cskh&id=` + value.id + url + `&TB_iframe=true&width=900&height=600" title="Các Đơn CSKH của ` + res.listName[value.id] + ` từ: ` + self.range + `">${format_number(value.order_cskh)}</a></td>
        <td>${format_number(value.order_shipped)}</td>
        <td class="th_break_right">${format_number(value.order_success)}</td>
        <td>${format_number(value.money_new)}</td>
        <td>${format_number(value.money_old)}</td>
        <td>${format_number(value.money_cskh)}</td>
        <td>${format_number(value.money_total)}</td>
        <td class="th_break_right">${format_number(value.revenue_total)}</td>
        <td>${calculate_percent(value.order_new, value.data_new)}%</td>
        <td>${calculate_percent(value.order_old, value.data_old)}%</td>
        <td>${calculate_percent(value.order_cskh, value.data_cskh)}%</td>
        <td>${calculate_percent(value.order_cancel, value.order_confirmed)}%</td>
        <td class="th_break_right">${calculate_percent(value.order_return, value.order_shipped)}%</td>
    </tr>`;
    
  });
  
  var boxData = res.data.boxData;
  var htmlFooterString = `<tr class="text-right tr_total">
                  <td class="th_break_right">Tổng số:</td>
                  <td>${format_number(boxData.data_new)}</td>
                    <td>${format_number(boxData.data_old)}</td>
                    <td class="th_break_right">${format_number(boxData.data_cskh)}</td>
                    <td>${format_number(boxData.order_new)}</td>
                    <td>${format_number(boxData.order_old)}</td>
                    <td>${format_number(boxData.order_cskh)}</td>
                    <td>${format_number(boxData.order_shipped)}</td>
                    <td class="th_break_right">${format_number(boxData.order_success)}</td>
                    <td>${format_number(boxData.money_new)}</td>
                    <td>${format_number(boxData.money_old)}</td>
                    <td>${format_number(boxData.money_cskh)}</td>
                    <td>${format_number(boxData.money_total)}</td>
                    <td class="th_break_right">${format_number(boxData.revenue_total)}</td>
                    <td>${calculate_percent(boxData.order_new, boxData.data_new)}%</td>
                    <td>${calculate_percent(boxData.order_old, boxData.data_old)}%</td>
                    <td>${calculate_percent(boxData.order_old, boxData.data_cskh)}%</td>
                    <td>${calculate_percent(boxData.order_cancel, boxData.order_confirmed)}%</td>
                    <td class="th_break_right">${calculate_percent(boxData.order_return, boxData.order_shipped)}%</td>
                </tr>`;

  $('#data-detail').html(htmlString);
  $('#table-footer').html(htmlFooterString);
}


/**
 * Load ajax lại data của table MKT xem report theo ngày đẩy Data
 */
Statistics.prototype.renderDataTableMKT = function (res) {
  
  var htmlTitle =   `<tr class="top_th">
                            <th rowspan="2">Tên</th>
                            <th colspan="4">Data</th>
                            <th colspan="3">Đơn hàng</th>
                            <th colspan="4">Tỷ lệ</th>
                            <th colspan="3">Doanh số (1000VNĐ)</th>
                            <th colspan="3">Tiền (1000VNĐ)</th>
                            <th rowspan="2">Doanh thu (1000VNĐ)</th>
                            <th colspan="3">Chi phí</th>
                        </tr>
                        <tr class="bottom_th">
                            <th>Tổng</th>
                            <th>Mới</th>
                            <th>Cào</th>
                            <th class="th_break_right">Kết nối</th>
                            <th>Tổng đơn</th>
                            <th>Đơn mới</th>
                            <th class="th_break_right">Đơn cào</th>
                            <th>KN/Tổng</th>
                            <th>Chốt mới</th>
                            <th>Chốt/KN</th>
                            <th class="th_break_right">Cào</th>
                            <th>Tổng</th>
                            <th>DS mới</th>
                            <th class="th_break_right">DS cào</th>
                            <th>Đang đi</th>
                            <th>Hủy</th>
                            <th class="th_break_right">Hoàn</th>
                            <th>Tổng</th>
                            <th>CP/DS</th>
                            <th class="th_break_right">CP/DT</th>
                        </tr>`;
  
  var htmlString = '';
  $.each(res.data.data, function (index, value) {
    htmlString += `<tr class="text-right tr_data">
        <td class="text-left th_break_right">${res.listName[value.id]}</td>
        <td>${format_number(value.data_total)}</td>
        <td>${format_number(value.data_new)}</td>
        <td>${format_number(value.data_old)}</td>
        <td class="th_break_right">${format_number(value.data_connected)}</td>
        <td>${format_number(value.order_total)}</td>
        <td>${format_number(value.order_new)}</td>
        <td class="th_break_right">${format_number(value.order_old)}</td>
        <td>${calculate_percent(value.data_connected, value.data_total)}%</td>
        <td>${calculate_percent(value.order_new, value.data_new)}%</td>
        <td>${calculate_percent(value.order_total, value.data_connected)}%</td>
        <td class="th_break_right">${calculate_percent(value.order_old, value.data_old)}%</td>
        <td>${format_number(value.money_total / 1000)}</td>
        <td>${format_number(value.money_new / 1000)}</td>
        <td class="th_break_right">${format_number(value.money_old / 1000)}</td>
        <td>${format_number(value.money_shipping / 1000)}</td>
        <td>${format_number(value.money_cancel / 1000)}</td>
        <td class="th_break_right">${format_number(value.money_return / 1000)}</td>
        <td class="th_break_right">${format_number(value.revenue_total / 1000)}</td>
        <td>${format_number(value.budget_total)}</td>
        <td>${calculate_percent(value.budget_total, value.money_total)}%</td>
        <td class="th_break_right">${calculate_percent(value.budget_total, value.revenue_total)}%</td>
    </tr>`;
  });
    var boxData = res.data.boxData;
  var htmlFooterString = `<tr class="text-right tr_total">
                    <td class="th_break_right">Tổng số:</td>
                    <td>${format_number(boxData.data_total)}</td>
                    <td>${format_number(boxData.data_new)}</td>
                    <td>${format_number(boxData.data_old)}</td>
                    <td class="th_break_right">${format_number(boxData.data_connected)}</td>
                    <td>${format_number(boxData.order_total)}</td>
                    <td>${format_number(boxData.order_new)}</td>
                    <td class="th_break_right">${format_number(boxData.order_old)}</td>
                    <td>${calculate_percent(boxData.data_connected, boxData.data_total)}%</td>
                    <td>${calculate_percent(boxData.order_new, boxData.data_new)}%</td>
                    <td>${calculate_percent(boxData.order_total, boxData.data_connected)}%</td>
                    <td class="th_break_right">${calculate_percent(boxData.order_old, boxData.data_old)}%</td>
                    <td>${format_number(boxData.money_total / 1000)}</td>
                    <td>${format_number(boxData.money_new / 1000)}</td>
                    <td class="th_break_right">${format_number(boxData.money_old / 1000)}</td>
                    <td>${format_number(boxData.money_shipping / 1000)}</td>
                    <td>${format_number(boxData.money_cancel / 1000)}</td>
                    <td class="th_break_right">${format_number(boxData.money_return / 1000)}</td>
                    <td class="th_break_right">${format_number(boxData.revenue_total / 1000)}</td>
                    <td>${format_number(boxData.budget_total)}</td>
                    <td>${calculate_percent(boxData.budget_total, boxData.money_total)}%</td>
                    <td class="th_break_right">${calculate_percent(boxData.budget_total, boxData.revenue_total)}%</td>
                </tr>`;
    
    $('#data-title').html(htmlTitle);
    $('#data-detail').html(htmlString);
    $('#table-footer').html(htmlFooterString);
}

/**
 * Load ajax lại data của table MKT xem report theo ngày tạo Data
 */
Statistics.prototype.renderDataTableMKTCreate = function (res) {
  
  var htmlTitle =   `<tr class="top_th">
                            <th rowspan="2">Tên</th>
                            <th rowspan="2">Data</th>
                            <th rowspan="2">Đơn hàng</th>
                            <th colspan="2">Tỷ lệ</th>
                            <th colspan="4">Doanh số (VNĐ)</th>
                            <th rowspan="2">Doanh thu</th>
                            <th colspan="3">Chi phí</th>
                        </tr>
                        <tr class="bottom_th">
                            <th>Chốt mới</th>
                            <th class="th_break_right">Chốt/KN</th>
                            <th>Tổng</th>
                            <th>Đang đi</th>
                            <th>Hủy</th>
                            <th class="th_break_right">Hoàn</th>
                            <th>Tổng</th>
                            <th>CP/DS</th>
                            <th class="th_break_right">CP/DT</th>
                        </tr>`;
  var htmlString = '';
  $.each(res.data.data, function (index, value) {
    htmlString += `<tr class="text-right tr_data">
        <td class="text-left th_break_right">${res.listName[value.id]}</td>
        <td class="th_break_right">${format_number(value.data_new)}</td>
        <td class="th_break_right">${format_number(value.order_new)}</td>
        <td>${calculate_percent(value.order_new, value.data_new)}%</td>
        <td class="th_break_right">${calculate_percent(value.order_new, value.data_connected)}%</td>
        <td>${format_number(value.money_total)}</td>
        <td>${format_number(value.money_shipping)}</td>
        <td>${format_number(value.money_cancel)}</td>
        <td class="th_break_right">${format_number(value.money_return)}</td>
        <td class="th_break_right">${format_number(value.revenue_total)}</td>
        <td>${format_number(value.budget_total)}</td>
        <td>${calculate_percent(value.budget_total, value.money_total)}%</td>
        <td class="th_break_right">${calculate_percent(value.budget_total, value.revenue_total)}%</td>
    </tr>`;
  });
    var boxData = res.data.boxData;
  var htmlFooterString = `<tr class="text-right tr_total">
                    <td class="text-left th_break_right">Tổng số</td>
                    <td class="th_break_right">${format_number(boxData.data_new)}</td>
                    <td class="th_break_right">${format_number(boxData.order_new)}</td>
                    <td>${calculate_percent(boxData.order_new, boxData.data_new)}%</td>
                    <td class="th_break_right">${calculate_percent(boxData.order_new, boxData.data_connected)}%</td>
                    <td>${format_number(boxData.money_total)}</td>
                    <td>${format_number(boxData.money_shipping)}</td>
                    <td>${format_number(boxData.money_cancel)}</td>
                    <td class="th_break_right">${format_number(boxData.money_return)}</td>
                    <td class="th_break_right">${format_number(boxData.revenue_total)}</td>
                    <td>${format_number(boxData.budget_total)}</td>
                    <td>${calculate_percent(boxData.budget_total, boxData.money_total)}%</td>
                    <td class="th_break_right">${calculate_percent(boxData.budget_total, boxData.revenue_total)}%</td>
                </tr>`;

    $('#data-title').html(htmlTitle);
    $('#data-detail').html(htmlString);
    $('#table-footer').html(htmlFooterString);
}

/**
 * Sticky filter trên mobile khi scroll
 *
 * @return  {void}
 */
Statistics.prototype.stickyFilter = function () {
  $(window).scroll(function (e) {
    var offset = $('.content-wrapper .content-header #sale-revenue').offset(); // điểm offset sẽ sticky đến
    var top = $(window).scrollTop(); // lấy độ cao hiện tại của scroll
    var top_check = top + 100;
    if (top_check > offset.top) {
      $('.content-wrapper .content-header .header-filter').addClass('sticky-mobile');
      if ($('.btn-extend').length == 0) {
        $('.content-wrapper .content-header .header-filter').append('<button class="btn-extend"><span>Mở rộng bộ lọc</span> <i class="fa fa-angle-double-down"></i></button>')
      }
    } else {
      $('.content-wrapper .content-header .header-filter').removeClass('sticky-mobile');
    }
  });

  $(document).on('click', '.btn-extend', function () {
    $('.content-wrapper .content-header .header-filter').toggleClass('filter-open')
    if ($('.content-wrapper .content-header .header-filter').hasClass('filter-open')) {
      $('.btn-extend').html('Thu gọn <i class="fa fa-angle-double-up"></i>');
    } else {
      $('.btn-extend').html('Mở rộng bộ lọc <i class="fa fa-angle-double-down"></i>');
    }
  })
}

if(typeof groupDepartment !== 'undefined') {
  var _Statistics = new Statistics(groupDepartment);

  _Statistics.run();
}
