// Booking form api
$(document).ready(function () {
  $("#bookingForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "/ajax/booking.php", // URL xử lý form, file PHP
      data: $(this).serialize(), // Lấy tất cả dữ liệu từ form
      success: function (response) {
        const result = JSON.parse(response);
        // Hiển thị alert sau khi xử lý thành công
        alert(result.message);
        window.location.href = "/views/list/index.php";
      },
      error: function () {
        // Hiển thị thông báo lỗi nếu có
        alert("Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại.");
      },
    });
  });
});

// Review api
$(document).ready(function () {
  $("#reviewForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "/ajax/review.php", // URL xử lý form, file PHP
      data: $(this).serialize(), // Lấy tất cả dữ liệu từ form
      success: function (response) {
        const result = JSON.parse(response);
        // Hiển thị alert sau khi xử lý thành công
        alert(result.message);
        location.reload();
      },
      error: function () {
        // Hiển thị thông báo lỗi nếu có
        alert("Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại.");
      },
    });
  });
});
