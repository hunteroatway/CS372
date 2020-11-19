// Code is based off Google Maps API examples page.
// Code that was not required was removed.
// being used to help autofill a search of a location
// Google Maps API page: https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: -33.8688, lng: 151.2195 },
      zoom: 13,
    });
    const card = document.getElementById("pac-card");
    const input = document.getElementById("pac-input");
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
    const autocomplete = new google.maps.places.Autocomplete(input);
  
    const marker = new google.maps.Marker({
      map,
      anchorPoint: new google.maps.Point(0, -29),
    });
    autocomplete.addListener("place_changed", () => {
      marker.setVisible(false);
      const place = autocomplete.getPlace();
    
    // delim the autofilled location to be stored into the database
    var location = input.value.split(', ')
    console.log(location);
    });
}