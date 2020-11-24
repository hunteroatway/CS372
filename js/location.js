// using locationIQ API to autocomplete the locations
// store values for the map
var map = L.map('map',{
  // center at Regina
  center: [50.4452, -104.6189], 
  zoom: 12, // default values needed
  scrollWheelZoom: true, // default values needed
});

// set the geocoder options
var geocoderOptions = {
  markers: false, // avoid putting a marker on an invisible map
  attribution: null, // set to null as map invisible
  expanded: true, // start the box open
  panToPoint: false, // set to null as map invisible
  placeholder: 'Search Location',
  focus: true,
  params: {               
    dedupe: 1, // avoid duplicate values
    tag: 'place:*', // used to limit to only places. Remove buisness/ attractions from results
  }
}

// initalize the geocoder
var geocoder = new L.control.geocoder('pk.6bbc944b7fcc9bef50be322773aaeaa2', geocoderOptions).addTo(map).on('select', function(res){
  //get the values
  updateValues(res.feature.feature.address.name, res.feature.feature.address.state, res.feature.feature.address.country);
});

// get the search div
var searchBox = document.getElementById("search-box");
//get geocoder container
var geocoderContainer = geocoder.getContainer();
//append container to searchbox to create spot to search
searchBox.appendChild(geocoderContainer);

function updateValues(city, province, country){

  //get the input fields
  var cityID = document.getElementById("city");
  var provinceID = document.getElementById("province");
  var countryID = document.getElementById("country");

  // change the value to what was got from auto complete
  cityID.value = city;
  provinceID.value = province;
  countryID.value = country;

}