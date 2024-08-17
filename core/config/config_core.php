<?
//Một số biến dùng chung cho toàn bộ cả website cả CMS
$DB     =   new Database;
$Router =   new Router;
$Image  =   new Image;
$Model  =   new Model;

/** --- Bảng configuration --- **/
$cfg_website    =   $DB->query("SELECT * FROM configuration WHERE con_id = 1")->getOne();
if (empty($cfg_website)) {
    exit('<p style="text-align:center">Website đang được bảo trì, vui lòng quay lại sau ít phút! Rất xin lỗi quý khách vì sự bất tiện này!</p>');
}

?>