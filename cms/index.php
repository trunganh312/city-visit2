<?
include 'core/config/require_cms.php';
$page_title =   WEBSITE_NAME . ' - Trang chủ CMS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?=$Layout->loadHead($page_title)?>
    <style>
        .main_listing {
            padding: 10px;
            border: none;
        }
        .progress {
            height: 30px;
        }
        .group_chanel {
            text-align: center;
            font-weight: 500;
        }
        .fa-thumbs-down {
            color: #f00;
            font-size: 16px;
        }
        .fa-thumbs-up {
            color: #179f01;
            font-size: 16px;
        }
        .table .col_icon {
            width: 70px;
        }
        .col_percent {
            width: 40%;
        }
    </style>
</head>
<body class="sidebar-mini page_statistic">
	<?
	$Layout->header($page_title);
    ?>
    <?
	$Layout->footer();
	?>
</body>
</html>