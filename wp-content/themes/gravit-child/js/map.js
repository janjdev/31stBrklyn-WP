function initialize() {



  // Create an array of styles.

  var styles = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#cf0000"},{"lightness":17}]}]



  // Create a new StyledMapType object, passing it the array of styles,

  // as well as the name to be displayed on the map type control.

  var styledMap = new google.maps.StyledMapType(styles,

    {name: "31st&BRKLYN"});



  // Create a map object, and include the MapTypeId to add

  // to the map type control.

  var mapOptions = {

    zoom: 11,

    center: new google.maps.LatLng(39.069881,-94.557626),

    mapTypeControlOptions: {

      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']

    }

  };

  var map;

  if(document.getElementById('map_brklyn')){
  map = new google.maps.Map(document.getElementById("map_brklyn"), mapOptions);
}

  var map2 = new google.maps.Map(document.getElementById("footer_map_brklyn"),

    mapOptions);



  //Associate the styled map with the MapTypeId and set it to display.
if(document.getElementById('map_brklyn')){
  map.mapTypes.set('map_style', styledMap);

  map.setMapTypeId('map_style');
}
  map2.mapTypes.set('map_style', styledMap);

  map2.setMapTypeId('map_style');

}



