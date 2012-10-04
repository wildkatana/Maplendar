Maplendar
=========

A school project integrating Google Calendar and Leaflet Maps to help you keep track of family and friends

Ben Andrus
Colin Craghead
Leighton Whiting

Dev Instructions:

The only files you should be editing are in the sites/all/modules/maplendar directory:

maplendar.module
  Contains the php logic and other page and endpoint functions. This won't need to be edited much.
maplendar_map_page.tpl.php
  This is the most important file, since it has the html/js/css for generating the map pages
maplendar.install
  This contains the schema for the database tables we will be using