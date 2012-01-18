<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/> 
		<title>Discover the Quinnipiac</title> 
		<style type="text/css"> img{ behavior: url(css/iepngfix.htc) } </style>

        <link rel="stylesheet" href="css/main.css" media="screen" />
        <script src="http://maps.google.com/maps?file=api&v=3&key=ABQIAAAA157vSCgjGgXUIvw5lV25QxTKJSA35ghpVcS6YOWwzzHODKu8DxRUvCwrhN3yUgR4L9lDPnHvxsa-YA" type="text/javascript"></script>
        <?php
        $op = $_GET["id"];
        if ($op) {
            echo "<script type='text/javascript'>var win=$op;</script>";
        } else {
            echo "<script type='text/javascript'>var win='none';</script>";
        }

        ?>
        <script type="text/javascript">

            // == shows all markers of a particular type, and ensures the checkbox is checked ==
            function showT(type) {
                for (var i=0; i<gmarkers.length; i++) {
                    if (gmarkers[i].mycategory == type) {
                        gmarkers[i].show();
                    }
                }
                // == check the checkbox ==
                document.getElementById(type+"box").checked = true;
            }

            // == hides all markers of a particular category, and ensures the checkbox is cleared ==
            function hideT(type) {
                for (var i=0; i<gmarkers.length; i++) {
                    if (gmarkers[i].mycategory == type) {
                        gmarkers[i].hide();
                    }
                }
                // == clear the checkbox ==
                document.getElementById(type+"box").checked = false;
                // == close the info window, in case its open on a marker that we just hid
                map.closeInfoWindow();
            }

            // == a checkbox has been clicked ==
            function boxclickT(box,type) {
                if (box.checked) {
                    showT(type);
                } else {
                    hideT(type);
                }
                // == rebuild the side bar
                // makeSidebar();
            }

            // == shows all layers of Ecosystem, and ensures the checkbox is checked ==
            function showR(group) {
                map.addOverlay(riveroutline);
                // == check the checkbox ==
                document.getElementById(group+"box").checked = true;
            }

            // == hides all markers of Ecosystem, and ensures the checkbox is cleared ==
            function hideR(group) {
                map.removeOverlay(riveroutline);
                // == clear the checkbox ==
		
                document.getElementById(group+"box").checked = false;
                // == close the info window, in case its open on a marker that we just hid
                map.closeInfoWindow();
            }

            // == a checkbox has been clicked ==
            function boxclickR(box,group) {
                if (box.checked) {
                    showR(group);
                } else {
                    hideR(group);
                }
                // == rebuild the side bar
                // makeSidebar();
            }
 
            var gmarkers = [];
            var gicons = [];
            var icons = [];
      
            //// create icons ///////
            GDownloadUrl("kml/example.xml", function(docA) {
                var xmlDocA = GXml.parse(docA);
                var icons = xmlDocA.documentElement.getElementsByTagName("marker");
						  
                for (var j = 0; j < icons.length; j++) {
                    var ca = icons[j].getAttribute("category");
                    var pi = icons[j].getAttribute("picture");
                    var final= ca+"-small_"+pi;
                    //// create icons ///////
                    gicons[""+final+""] = new GIcon(); 
                    gicons[""+final+""].image = "images/icons/small_"+pi+""; 
                    gicons[""+final+""].iconSize = new GSize(50, 50); 
                    gicons[""+final+""].iconAnchor = new GPoint(25, 25); 
                    gicons[""+final+""].infoWindowAnchor = new GPoint(5, 1); 
						 
                }
            });


            // A function to create the marker and set up the event window
            function createMarker(point,category,html,pic,wi,he) {
                var marker = new GMarker(point,gicons[category+"-small_"+pic]);
                // === Store the category and name info as a marker properties ===
                marker.mycategory = category;                             	    
                //marker.myname = name;
                GEvent.addListener(marker, "click", function() {
                    marker.openInfoWindowHtml(html);  
                });
                gmarkers.push(marker);
                return marker;
       
            }
        </script>
        <script type="text/javascript">
            function init(){
                if (GBrowserIsCompatible()) {
                    var map = new GMap2(document.getElementById("map"));
                    var mapTypeControl = new GLargeMapControl();
                    var rightTop = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,30));
                    map.addControl(mapTypeControl, rightTop);
                    var mapType = new GMapTypeControl();
                    map.removeMapType(G_HYBRID_MAP);
                    map.addMapType(G_PHYSICAL_MAP);
                    map.addControl(mapType);
                    map.setMapType(G_PHYSICAL_MAP);
                    map.setCenter(new GLatLng(41.362084,-72.906929), 11);
                    // ==== Create a KML Overlay ====
                    var riveroutline = new GGeoXml("http://www.considerthequinnipiac.com/map/kml/river.kml");
                    // Read the data
    	  
                    GDownloadUrl("kml/example.xml", function(doc) {
                        var xmlDoc = GXml.parse(doc);
                        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
						  
                        for (var i = 0; i < markers.length; i++) {
                            // obtain the attribues of each marker
                            var lat = parseFloat(markers[i].getAttribute("lat"));
                            var lon = parseFloat(markers[i].getAttribute("lon"));
                            var point = new GLatLng(lat,lon);
                            var category = markers[i].getAttribute("category");
                            var title = markers[i].getAttribute("title");
                            var des	= markers[i].getAttribute("description");
                            var pic = markers[i].getAttribute("picture");
                            var picture ="<img src='images/"+pic+"'><br />";
                            var wi= markers[i].getAttribute("width")+"px";
                            var h= markers[i].getAttribute("height");
                            var hh=h * 1.1;
                            var he=hh+"px";
						

						
                            // var name = markers[i].getAttribute("name");
                            var html = "<div class='iwContainer' style='width:"+wi+";height:"+he+"'><table><tr><td><span class=\"boxtitle\">"+title+"</span></td></tr><tr><td>"+picture+"</td></tr><tr><td><span class=\"boxdescription\">"+des+"</span></td></tr></table></div>";
						 
                            // create the marker
                            marker = createMarker(point,category,html,pic,wi,he);
                            map.addOverlay(marker);
						  
                        }
						
                        map.addOverlay(riveroutline);
						
                        if(win!='none'){
                            GEvent.trigger(gmarkers[win], "click");
                        }
						
						
                    });
					  
                }
    
                // display a warning if the browser was not compatible
                else {
                    alert("Sorry, the Google Maps API is not compatible with this browser");
                }
	
            }
        </script>

    </head>
    <body onload="init();">
        <div id="header" class="title">
            <img src="images/logo.png" title="Discover the Quinnipiac" alt="Discover the Quinnipiac" class="pic" onclick="window.location='http://www.considerthequinnipiac.com';"/>
        </div>
        <div id="map"></div>


        <div id="foot" class="footing">
            <table>  
                <tr>
                    <td>
                        <input type="checkbox"   id="Beautybox" onclick="boxclickT(this,'Beauty')" checked />&nbsp;Beauty
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="Birdsbox" onclick="boxclickT(this,'Birds')" checked />&nbsp;Birds
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="Boatsbox" onclick="boxclickT(this,'Boats')" checked />&nbsp;Boats
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="Communitybox" onclick="boxclickT(this,'Community')" checked />&nbsp;Community
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="IndustrialMouthbox" onclick="boxclickT(this,'IndustrialMouth')" checked />&nbsp;Industrial Mouth
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="TidalMarshbox" onclick="boxclickT(this,'TidalMarsh')" checked />&nbsp;Tidal Marsh
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="UpperRiverbox" onclick="boxclickT(this,'UpperRiver')" checked />&nbsp;Upper River
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="Riverbox" onclick="boxclickR(this,'River')" checked />&nbsp;River Outline
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="checkbox"   id="Canoebox" onclick="boxclickT(this,'Canoe')" checked />&nbsp;Canoe Launches
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>