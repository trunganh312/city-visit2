<?
// routes.php
return [
    'GET' => [
        '/api/hotels' => 'HotelController@getHotels',
        '/api/hotels/{id}' => 'HotelController@getHotelDetails',
        '/api/amenity' => 'HotelController@getAmenities',
        '/api/districts' => 'DistrictController@getAllDistricts',
        '/api/citys' => 'CityController@getAllCitys'
    ],
    'POST' => [
        '/api/hotels' => 'HotelController@addHotel'
    ],
    'PUT' => [
        '/api/hotels/{id}' => 'HotelController@updateHotel',
        '/api/hotels/updateStatus' => 'HotelController@updateStatus'
    ],
    'DELETE' => [
        '/api/hotels/{id}' => 'HotelController@deleteHotel'
    ]
];
