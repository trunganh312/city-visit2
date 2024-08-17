let marker;
let map;
let infoWindow;
let currentInfoWindow = null;

async function initMap(hotels, hotelCurrent) {
  // Request needed libraries.
  const { Map } = await google.maps.importLibrary("maps");

  // Khởi tạo tọa độ ban đầu
  const myLatlng = {
    lat: Number(hotels[0].hot_lat) || info.hot_lat,
    lng: Number(hotels[0].hot_lng) || info.hot_lng,
  };

  // Tạo 1 đối tượng map
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 10,
    center: myLatlng,
    mapTypeControlOptions: {
      mapTypeIds: ["roadmap", "hybrid"],
    },
  });

  // Lặp qua mảng hotels
  hotels.forEach((hotel) => {
    let marker = new google.maps.Marker({
      position: {
        lat: Number(hotel.hot_lat),
        lng: Number(hotel.hot_lng),
      },
      map: map,
      title: hotel.hot_name, // Set marker title
    });

    let infoWindow = new google.maps.InfoWindow({
      content: `
        <div class="card transition-3d-hover shadow-hover-2 h-100 w-100">
                        <div class="position-relative">
                            <a href="http://cityvisit.local/hotel/${hotel.hot_slug}.html" class="d-block gradient-overlay-half-bg-gradient-v5">
                                <img class="card-img-top" src="http://uploads.cityvisit.local/hotel_cover/${hotel.hot_page_cover}" alt="Image Description" />
                            </a>
                        </div>
                        <div class="card-body px-4 pt-2 pb-3" style="display: flex;flex-direction: column;
                            ">
                            <div style="flex: 1">
                            <a href="http://cityvisit.local/hotel/${hotel.hot_slug}.html" class="card-title font-size-17 font-weight-medium text-dark">${hotel.hot_name}</a>
                            </div>
                            <div class="mt-2">
                                <span class="mr-1 font-size-14 text-gray-1">Chỉ từ</span>
                                <span class="font-weight-bold">${hotel.hot_price} VNĐ</span>
                                <span class="font-size-14 text-gray-1"> / đêm</span>
                            </div>
                        </div>
                    </div
      `,
    });

    marker.addListener("click", () => {
      // Check xem currentInfoWindow có bằng null không, nếu bằng null thì show infoWindow của marker đó
      if (currentInfoWindow) {
        currentInfoWindow.close();
      }
      infoWindow.open(map, marker);
      currentInfoWindow = infoWindow;
    });

    // Hiển thị InfoWindow của marker mặc định khi bản đồ được tải
    if (hotelCurrent && hotel.hot_id === hotelCurrent.hot_id) {
      infoWindow.open(map, marker);
      currentInfoWindow = infoWindow;
    }
  });
}
