<?php 
include('../core/config/require_cms.php');
$Admin->checkPermission('manage_files');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>Thư viện ảnh - CMS</title>
	<script src="ckfinder.js"></script>
	<style>
		.ui-header.ui-bar-b{
			display: none !important;
		}
		</style>
</head>
<body>
	<script>
	CKFinder.start({
		language: 'vi',
		skin: 'neko'
	});
</script>

</body>
</html>

