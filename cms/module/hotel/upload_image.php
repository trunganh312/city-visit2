<?
include('./config_module.php');
$Admin->checkPermission('admin_create');
/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Đăng ảnh khách sạn';
/** --- End of Khai báo một số biến cơ bản --- **/
$hotel_id = getValue('hotel_id');

// Lấy ra danh sách ảnh của hotel 
$images = $DB->query("SELECT * FROM hotel_image WHERE hti_hotel_id = " . $hotel_id)->toArray();

$imageJson = json_encode($images);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
    <style rel="stylesheet">
        html,
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #fafafa;
            line-height: 1.6;
        }

        .lead {
            font-size: 1.5rem;
            font-weight: 300;
        }

        .container {
            margin: 0;
        }
    </style>
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
    <?
    $Layout->header($page_title);
    ?>
    <a href="/module/hotel/list.php" class="ml-2">Quay lại</a>
    <div class="container">
        <ul>
            <?=
            $Layout->loadRoomType();
            ?>
        </ul>

    </div>
    <?
    $Layout->loadFooter();
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../theme/js/jquery.uploader.min.js"></script>
    <script type="application/javascript">
        const images = <?= $imageJson ?>;
        const imageArr = images.map(img => {
            return {
                name: img.hti_type_image,
                url: `http://uploads.cityvisit.local/hotel_images/${img.hti_name}`
            }
        })
        let ajaxConfig = {
            ajaxRequester: function(config, uploadFile, pCall, sCall, eCall) {
                let formData = new FormData();
                formData.append('file', uploadFile.file);
                formData.append('id', uploadFile.id);
                formData.append('type', uploadFile.type_image);
                formData.append('hotel_id', <?= $hotel_id ?>); // Thêm id khách sạn vào form data
                $.ajax({
                    url: 'upload_handler.php', // Đường dẫn tới script PHP xử lý tải lên
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        let xhr = new window.XMLHttpRequest();
                        // Theo dõi tiến trình tải lên
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                let percentComplete = evt.loaded / evt.total * 100;
                                pCall(Math.round(percentComplete));
                            }
                        }, false);

                        return xhr;
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.status === 'success') {
                            sCall(res.url); // Gọi sCall với URL trả về
                        } else {
                            eCall(res.message);
                        }
                    },
                    error: function() {
                        eCall('Có lỗi xảy ra khi tải lên file.');
                    }
                });
            }
        };
        [1, 2, 3, 4, 5, 6, 7].forEach(item => {
            $("#demo" + item).uploader({
                multiple: true,
                defaultValue: imageArr.filter(img => img.name == item) || [],
                ajaxConfig: ajaxConfig,
                type: document.getElementById("demo" + item).value
            });
        })
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1VDDWMRSTH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "G-1VDDWMRSTH");
    </script>
    <script>
        try {
            fetch(
                    new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", {
                        method: "HEAD",
                        mode: "no-cors",
                    }),
                )
                .then(function(response) {
                    return true;
                })
                .catch(function(e) {
                    var carbonScript = document.createElement("script");
                    carbonScript.src =
                        "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
                    carbonScript.id = "_carbonads_js";
                    document.getElementById("carbon-block").appendChild(carbonScript);
                });
        } catch (error) {
            console.log(error);
        }
    </script>
</body>

</html>