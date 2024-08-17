<?
// Lấy ra thông tin khách sạn tại đà nẵng
$sql = "SELECT h.*,
        c.cit_name,
        d.dis_name,d.dis_address_map
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        WHERE 1=1 AND h.hot_city_id =" . CITY_ID;

$hotels = $DB->query($sql)->toArray();

$hotelsJson = json_encode($hotels);
$hotelJson = json_encode($hotels[0]);

?>

<div class="pb-4 mb-2">
    <a href="#ontargetModal" class="d-block border rounded" data-modal-target="#ontargetModal" data-modal-effect="fadein">
        <img src="/public/img/map-markers/map.jpg" alt="" width="100%" />
    </a>
    <!-- On Target Modal -->
    <? include('../../components/show_map.php') ?>
</div>

<?=
// Load map với list dât hotel 
$Layout->loadMapInit($hotelsJson, $hotelJson);
?>