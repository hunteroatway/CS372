// need a second location script to get the second autofill box found on the sign up page
// using locationIQ API to autocomplete the locations
// store values for the map
var map2 = L.map('map2',{
    // center at Regina
    center: [50.4452, -104.6189], 
    zoom: 12, // default values needed
    scrollWheelZoom: true, // default values needed
  });
  
  // set the geocoder options
  var geocoderOptionss = {
    markers: false, // avoid putting a marker on an invisible map
    attribution: null, // set to null as map invisible
    expanded: true, // start the box open
    panToPoint: false, // set to null as map invisible
    placeholder: 'Set Your Location',
    focus: true,
    params: {               
      dedupe: 1, // avoid duplicate values
      tag: 'place:city,place:town,place:village', // used to limit to only places. Remove buisness/ attractions from results
    }
  }
  
  // initalize the geocoder
  var geocoder2 = new L.control.geocoder('pk.6bbc944b7fcc9bef50be322773aaeaa2', geocoderOptionss).addTo(map2).on('select', function(res2){
    //get the values
    updateValues2(res2.feature.feature.address.name, res2.feature.feature.address.state, res2.feature.feature.address.country);
  });
  
  // get the search div
  var searchBox = document.getElementById("search-boxSU");
  //get geocoder container
  var geocoderContainer = geocoder2.getContainer();
  //append container to searchbox to create spot to search
  searchBox.appendChild(geocoderContainer);
  
  function updateValues2(city, province, country){
  
    //get the input fields
    var cityID = document.getElementById("citySU");
    var provinceID = document.getElementById("provinceSU");
    var countryID = document.getElementById("countrySU");
  
    // change the value to what was got from auto complete
    cityID.value = city;
    provinceID.value = province;
    countryID.value = country;
  
  }