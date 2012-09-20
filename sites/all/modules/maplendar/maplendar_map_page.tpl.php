<style>
  #map {
    height: 500px;
  }
  .maplendar_marker_div {
    border: 2px solid #333;
    border-radius: 5px;
    padding: 5px;
    background: white;
    height: 50px;
    width: 50px;
  }
  
  .maplendar_image {
    margin: 0;
    padding: 0;
    height: 50px;
    width: 50px;
  }
  
  .maplendar_marker_div .maplendar_image img {
    width: 50px;
    height: 50px;
    margin: 0;
    padding: 0;
  }
  
  .maplendar_marker_div .maplendar_name {
    margin-top: 2px;
    font-size: 9px;
    line-height: 10px;
    text-align: center;
    width: 50px;
  }
</style>

<div id="map"></div>

<script type="text/javascript">
  var map = L.map('map').setView([51.505, -0.09], 13);

  L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
  }).addTo(map);
  
  var myIcon = L.divIcon({
    iconSize: [50, 70],
    iconAnchor: [50, 0],
    className: 'maplendar_marker_div', 
    html: "<div class='maplendar_image'><img src='http://www.insomniacmania.com/sites/default/files/news/17661/scoop_17661_6931.jpg' /></div><div class='maplendar_name'>Leighton Whiting</div>"
  });
  
  L.marker([51.5, -0.09], {icon: myIcon}).addTo(map).bindPopup("<b>Hello world!</b><br />I am a popup.");

</script>
