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
  
function loadKmlFile(file, map) {
    GDownloadUrl(baseXmlDir + file, function(doc) {
        var xmlDoc = GXml.parse(doc);
        var markers = xmlDoc.documentElement.getElementsByTagName('marker');
       
        for(var i=0; i < markers.length; i++) {
            var lat = parseFloat(markers[i].getAttribute("lat"));
            var lon = parseFloat(markers[i].getAttribute("lon"));
            var point = new GLatLng(lat, lon);
            var category = markers[i].getAttribute("category");
            var title = markers[i].getAttribute("title");
            var des = markers[i].getAttribute("description");
            var pic = markers[i].getAttribute("picture");
            var picture = "<img src='/sites/map_files/images/" + pic + "'><br />";
            var wi = markers[i].getAttribute("width") + "px";
            var h = markers[i].getAttribute("height");
            var hh = h * 1.1;
            var he = hh + "px";



            // var name = markers[i].getAttribute("name");
            var html = "<div class='iwContainer' style='width:" + wi + ";height:" + he + "'><table><tr><td><span class=\"boxtitle\">" + title + "</span></td></tr><tr><td>" + picture + "</td></tr><tr><td><span class=\"boxdescription\">" + des + "</span></td></tr></table></div>";

            // create the marker
            marker = createMarker(point, category, html, pic, wi, he);
            map.addOverlay(marker);
        }
    });
}

function loadIcons(file) {
    GDownloadUrl(baseXmlDir + file, function(docA) {
        var xmlDocA = GXml.parse(docA);
        var icons = xmlDocA.documentElement.getElementsByTagName("marker");
				  
        for (var j = 0; j < icons.length; j++) {
            var ca = icons[j].getAttribute("category");
            var pi = icons[j].getAttribute("picture");
            var final= ca+"-small_"+pi;
            //// create icons ///////
            gicons[""+final+""] = new GIcon(); 
            gicons[""+final+""].image = "/sites/map_files/images/icons/small_"+pi+""; 
            gicons[""+final+""].iconSize = new GSize(50, 50); 
            gicons[""+final+""].iconAnchor = new GPoint(25, 25); 
            gicons[""+final+""].infoWindowAnchor = new GPoint(5, 1); 
				 
        }
    });
}

function init() {
    if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map_canvas"));
        var mapTypeControl = new GLargeMapControl();
        var rightTop = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10, 30));
        map.addControl(mapTypeControl, rightTop);
        var mapType = new GMapTypeControl();
        map.removeMapType(G_HYBRID_MAP);
        map.addMapType(G_PHYSICAL_MAP);
        map.addControl(mapType);
        map.setMapType(G_PHYSICAL_MAP);
        map.setCenter(new GLatLng(41.48029017775285,-72.85858154296875), 11);
        // ==== Create a KML Overlay ====
        var riveroutline = new GGeoXml("http://thequinnipiacriver.com/sites/map_files/kml/river.kml");
        // Read the data
        //loadKmlFile("/sites/map_files/kml/auto/example.xml", map);
        for(var i = 0; i < kmlFiles.length; i++) {
            loadKmlFile(kmlFiles[i], map);
        }
        
        map.addOverlay(riveroutline);


    }

    // display a warning if the browser was not compatible
    else {
        alert("Sorry, the Google Maps API is not compatible with this browser");
    }

}

var gmarkers = [];
var gicons = [];
var icons = [];
var win='none';
var baseXmlDir = "/sites/map_files/kml/auto/";

jQuery(document).ready(function() {
    var myOptions = {
    // center: new google.maps.LatLng(41.330548965310406,-72.88433074951172),
    //zoom: 13,
    // mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    // var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    //// create icons ///////
    /*
    GDownloadUrl("/sites/map_files/kml/auto/example.xml", function(docA) {
        var xmlDocA = GXml.parse(docA);
        var icons = xmlDocA.documentElement.getElementsByTagName("marker");
				  
        for (var j = 0; j < icons.length; j++) {
            var ca = icons[j].getAttribute("category");
            var pi = icons[j].getAttribute("picture");
            var final= ca+"-small_"+pi;
            //// create icons ///////
            gicons[""+final+""] = new GIcon(); 
            gicons[""+final+""].image = "/sites/map_files/images/icons/small_"+pi+""; 
            gicons[""+final+""].iconSize = new GSize(50, 50); 
            gicons[""+final+""].iconAnchor = new GPoint(25, 25); 
            gicons[""+final+""].infoWindowAnchor = new GPoint(5, 1); 
				 
        }
    });
*/
    for(var i = 0; i < kmlFiles.length; i++) {
        loadIcons(kmlFiles[i]);
    }
    init();
  
});