<?
include('../../Core/Config/require_cms.php');

//Lấy ra các nhóm Admin
$list_group_admin   =  $Model->getListData('admin_group', 'adgr_id, adgr_name', '', 'adgr_name');
