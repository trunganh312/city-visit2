let marker;
let map;
let infoWindow;

async function initMap(lat, lng) {
  // Request needed libraries.
  const myLatlng = {
    lat: lat || 21.0277644,
    lng: lng || 105.8341598,
  };

  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 13,
    center: myLatlng,
    mapTypeControlOptions: {
      mapTypeIds: ["roadmap", "hybrid"],
    },
  });

  marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    draggable: true,
  });

  infoWindow = new google.maps.InfoWindow({
    content: "",
    pixelOffset: new google.maps.Size(0, -30),
  });

  // Attach events to the map and marker
  attachMapEvents();
  attachMarkerEvents();

  // Search functionality
  const input = document.getElementById("pac-input");
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  const autocomplete = new google.maps.places.Autocomplete(input, {
    fields: ["place_id", "geometry", "name", "formatted_address"],
  });

  autocomplete.bindTo("bounds", map);
  autocomplete.addListener("place_changed", function () {
    infoWindow.close();
    const place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    const { lat, lng } = place.geometry.location.toJSON();
    map.setCenter({
      lat,
      lng,
    });
    map.setZoom(15);

    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var contentString = `
          <div class="info-window">
                    <div class="coordinates">Lat: ${lat}, Lng: ${lng}</div>
                </div>
        `;
    infoWindow.setContent(contentString);
    infoWindow.setPosition(place.geometry.location);
    infoWindow.open(map, marker);

    setCoordinates(lat, lng);
  });
}

function attachMapEvents() {
  map.addListener("click", (mapsMouseEvent) => {
    const { lat, lng } = mapsMouseEvent.latLng.toJSON();
    var contentString = `
          <div class="info-window">
                    <div class="coordinates">Lat: ${lat}, Lng: ${lng}</div>
                </div>
        `;

    infoWindow.close();
    infoWindow.setContent(contentString);
    infoWindow.setPosition(mapsMouseEvent.latLng);
    infoWindow.open(map);

    setCoordinates(lat, lng);

    if (marker) {
      marker.setMap(null);
    }

    marker = new google.maps.Marker({
      position: mapsMouseEvent.latLng,
      map: map,
      draggable: true,
    });

    attachMarkerEvents();
  });
}

function attachMarkerEvents() {
  if (!marker) return;

  marker.addListener("drag", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    var contentString = `
          <div class="info-window">
                    <div class="coordinates">Lat: ${lat}, Lng: ${lng}</div>
                </div>
        `;

    infoWindow.setContent(contentString);
    infoWindow.setPosition(event.latLng);
    infoWindow.open(map, marker);

    setCoordinates(lat, lng);
  });

  marker.addListener("dragend", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    var contentString = `
          <div class="info-window">
                    <div class="coordinates">Lat: ${lat}, Lng: ${lng}</div>
                </div>
        `;

    infoWindow.setContent(contentString);
    infoWindow.setPosition(event.latLng);
    infoWindow.open(map, marker);

    setCoordinates(lat, lng);
  });

  marker.addListener("mouseover", (event) => {
    const { lat, lng } = event.latLng.toJSON();
    var contentString = `
          <div class="info-window">
                    <div class="coordinates">Lat: ${lat}, Lng: ${lng}</div>
                </div>
        `;
    infoWindow.setContent(contentString);
    infoWindow.setPosition(event.latLng);
    infoWindow.open(map, marker);

    setCoordinates(lat, lng);
  });
}

function setCoordinates(lat, lng) {
  const citLatInput = document.getElementById("cit_lat_center");
  const citLngInput = document.getElementById("cit_lng_center");
  const hotLatInput = document.getElementById("hot_lat");
  const hotLngInput = document.getElementById("hot_lng");
  const disLatInput = document.getElementById("dis_lat_center");
  const disLngInput = document.getElementById("dis_lng_center");

  if (citLatInput) {
    citLatInput.value = lat;
  }
  if (citLngInput) {
    citLngInput.value = lng;
  }
  if (hotLatInput) {
    hotLatInput.value = lat;
  }
  if (hotLngInput) {
    hotLngInput.value = lng;
  }
  if (disLatInput) {
    disLatInput.value = lat;
  }
  if (disLngInput) {
    disLngInput.value = lng;
  }
}
