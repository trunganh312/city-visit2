<? include '../../config/require_web.php'; ?>

<!DOCTYPE html>
<html lang="en">


<head>
    <?=
    $Layout->loadHead('Không tìm thấy trang');
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
            <p class="lead">
                Vui lòng quay trang trước đó
            </p>
            <btn style="cursor: pointer;" onclick="window.history.back();" class="btn btn-primary">Quay lại</btn>
        </div>
    </div>
</body>

</html>