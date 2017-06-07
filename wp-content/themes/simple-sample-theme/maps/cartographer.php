<?php
    /*
     * Mapmaker file.
     * Author: Andy Devlin
     * Description: This file makes the map
     *
     */
?>

<?php
//Get values from ACF
//Get the developer API Key
    $apiKey = get_field('api_key', 'option');
//instantiate an empty array that will hold a map at each index.
    $adMaps = array();
//loop through all of the maps
    if (have_rows('map', 'option')):
        $mapCounter = 0;
        $mapTotal = count(have_rows('map', 'option'));
        while (have_rows('map', 'option')): the_row();
            //Increment map counter
            $mapCounter++;
            //set map defaults
//        $theMapType = 'ROADMAP';
//        $theMapMaxZoom = 5;
//        $theMapMinZoom = 10;
//        $theMapUI = true;
//        $theMapBackgroundColor = 'dimgray';
//        $mapZoom = 10;
//        $mapScroll = false;
//        $mapStyleJSON = '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}]';
//        $mapCenter = '33.7593143,-84.39285'; //centennial
            //Get map values
            $mapCenter = get_sub_field('map_center', 'option'); //get lat, long coordinates for the center of the map
            $mapZoom = get_sub_field('map_zoom_level', 'option'); //get the zoom level for the initial map display
            $mapStyleJSON = get_sub_field('map_styles', 'option'); // get hte json that sets up the styles for the map
            $mapID = get_sub_field('div_id', 'option'); //get the unique HTML id of the div that will display the map
            $mapName = get_sub_field('map_title', 'option');
            $theMapType = get_sub_field('map_type', 'option');
            $theMapMaxZoom = get_sub_field('map_max_zoom', 'option');
            $theMapMinZoom = get_sub_field('map_min_zoom', 'option');
            $theMapUI = get_sub_field('map_ui', 'option');
            $theMapBackgroundColor = get_sub_field('map_background_color', 'option');
            $mapScrollWheel = get_sub_field('scroll_wheel', 'option');
            $mapDraggable = get_sub_field('draggable', 'option');
            $resetBoundsByLocation = get_sub_field('reorient_map_based_on_locations', 'option');
            $showAllLocations = get_sub_field('show_all_locations', 'option');

            //js variable names
            //$functionName = 'mapInit' . $mapName;
            //start creating the array
            $adMaps[$mapCounter - 1] = array(
                'map_name' => $mapName,
                'map_styles' => $mapStyleJSON,
                'map_zoom' => $mapZoom,
                'map_center' => $mapCenter,
                'map_id' => $mapID,
                'map_max_zoom' => $theMapMaxZoom,
                'map_min_zoom' => $theMapMinZoom,
                'map_type' => $theMapType,
                'map_ui' => $theMapUI,
                'map_scrollwheel' => $mapScrollWheel,
                'map_draggable' => $mapDraggable,
                'map_background_color' => $theMapBackgroundColor,
                'reset_bounds' => $resetBoundsByLocation,
                'show_all_locations' => $showAllLocations,
                'location_groups' => array()
            );
            //loop through the location groups that are associated with this map
            if (have_rows('location_group', 'option')):
                //instantiate the location group counter and grab the total number
                $groupCount = 0;
                $groupTotal = count(have_rows('location_group', 'option'));
                while (have_rows('location_group', 'option')): the_row();
                    //increment the location group counter
                    $groupCount++;

                    //Get location group values
                    $groupName = get_sub_field('group_name', 'option');

                    //grab the static location boolean value
                    $staticLocation = get_sub_field('static_locations', 'option');

                    //if the location group is static, the trigger should be ignored by the application logic
                    //this is the (hopefully valid) jQuery selector that when clicked will trigger the display of this location group
                    $theTrigger = get_sub_field('group_trigger', 'option');

                    //this boolean value will be appplied to individual markers within the location group and if true will prevent click event listeners from being attached to these locations
                    $allowClickablePins = get_sub_field('allow_clickable_pins', 'option');

                    //TODO: incorporate field options to allow a higher degree of customization for what displays in the infowindow
                    //for now we will just capture the values of this field and hope it doens't cause anything to break
                    $infoWindowFields = get_sub_field('infowindow_display_fields', 'option');

                    //grab the animation value from acf and make it uppercase
                    $theAnimation = strtoupper(get_sub_field('animation', 'option'));
                    //if the animation is empty set the default to drop because that's always fun
                    if (empty($theAnimation)) {
                        //set default animation type
                        $theAnimation = 'DROP';
                    }

                    //set default pin value
                    $pinColor = 'ffdead'; //a bland hex color
                    $thePin = 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|' . $pinColor; //add the hex color to the end of a google hosted image
                    //get the pin values from acf flx content
                    if (have_rows('pin_type')): //flexible content repeater
                        while (have_rows('pin_type', 'option')):
                            the_row();
                            if (get_row_layout() === 'basic_pin')://layout1
                                //get layout1 values
                                $iconURL = get_sub_field('icon_url', 'option');
                                $pinColor = get_sub_field('pin_color', 'option');
                                $thePin = $iconURL . substr($pinColor, 1); //remove the # from the hexcode
                            endif;
                            if (get_row_layout() === 'custom_icon')://layout2
                                //get layout 2 values
                                $thePin = get_sub_field('the_icon', 'option');
                            endif;
                        endwhile;
                    endif; //end pin flexible content conditional
                    //set values for json ouput
                    //TODO: clean up the syntax for adding data
                    $adMaps[$mapCounter - 1]['location_groups'][$groupCount - 1] = array(
                        'group_name' => $groupName,
                        'pin_color' => $pinColor,
                        'pin_icon' => $thePin,
                        'trigger' => $theTrigger,
                        'animation' => $theAnimation,
                        'static' => $staticLocation,
                        'clickable' => $allowClickablePins,
                        'info_window_fields' => $infoWindowFields,
                        'locations' => array()
                    );

                    //Start Looping through the locations within each group
                    if (have_rows('map_location', 'option')):
                        $locationCount = 0;
                        $locationTotal = count(have_rows('map_location', 'option'));
                        $locationGroup = array();
                        while (have_rows('map_location', 'option')): the_row();
                            //increment the location counter
                            $locationCount++;
                            //category instance specific data
                            $locationCategory = 'locationGroup' . $groupCount;

                            //Create a unique ID for this location
                            $uniqueID = $mapID . '_' . $locationCategory . '_' . $locationCount;

                            //retrieve the location name
                            $locationName = esc_html(get_sub_field('location_name', 'option'));
                            //retrieve the address string
                            $locationAddress = get_sub_field('location_address', 'option');
                            //retireve the latlong string
                            $locationLatlong = get_sub_field('location_lat_long', 'option');

                            //TODO: Add extra fields to each location so additional info can be displyed in the infowindow on click
                            //$locationLink = get_sub_field('location_link', 'option);
                            //$customText = get_sub_field('custom_text', 'option');
                            //generate a google maps link based on the location latlong and the location name as a search parameter
                            //might not always be accurate...
                            $googleMapsLink = 'http://maps.google.com/maps?z=15&t=m&ll=' . $locationLatlong . '&q=' . $locationName;

                            //add this information for json output
                            $adMaps[$mapCounter - 1]['location_groups'][$groupCount - 1]['locations'][$locationCount - 1] = array(
                                'name' => $locationName,
                                'address' => $locationAddress,
                                'latlong' => $locationLatlong,
                                'googlemapslink' => $googleMapsLink,
                                'category' => $groupName,
                                'unique_id' => $uniqueID,
                                'google_marker' => array()
                            );

                            //use phps built in json function to encode the array you just created
                            $jsOutput = json_encode($adMaps);

                        //if you need to test your output...
                        //echo '<script>console.log(' . $jsOutput . ');</script>';
                        endwhile; //end location loop
                    endif; //end location conditional
                endwhile; //end location group loop
            endif; //end location group conditional
        endwhile; //end maps loop
    endif; //end maps conditional
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
<script>
    var adMaps = <?php echo $jsOutput; ?>;//the holder for all of the map JSON data
    var adMarkers = [];
    console.table(adMaps);



    function adMapsInit() {


        //||||||||||||||||||||||||||||||||||||||||||||
        //||||||||||| DATA/OBJECT CREATION |||||||||||
        //||||||||||||||||||||||||||||||||||||||||||||
        //TODO: Move this out of the adMapsInit function and have some of the data already formatted prior/without needing to call google maps

        //begin looping through the JSON map data
        adMaps.map(function (x) {
            //where x represents a map and it's associated data
            //set the default location as a backup
            var defaultLocation = x.map_center;
            var myMap = createGoogleMap(x);
            //start looping through the location groups
            x.location_groups.map(function (y) {
                //where y represents a location group and it's associated data
                //create the infowindow for this group?
                var largeInfowindow = new google.maps.InfoWindow();
                //summon the markerGroup object that we will fill out with properties and a locations array
                var currentMarkerGroup = createLocationGroup(y);
                //loop through the individual locations in the map group
                y.locations.map(function (z) {
                    //create the google marker object
                    //where z represents a location and it's associated data
                    var markerObject = createGoogleMarker(z, currentMarkerGroup);
                    //console.info('This marker object was just created:');
                    //console.table(markerObject);
                    //if a lat long object was created and then a google marker was created...
                    if (markerObject !== 'ad-error') {
                        //add this marker to the marker group
                        currentMarkerGroup.markers.push(markerObject);
                        //if the marker is clickable, add an event listener for the infowindow
                        if (markerObject.clickable) {
                            markerObject.addListener('click', function () {
                                //populate ingfowindow
                                populateInfoWindow(markerObject, largeInfowindow, z, myMap);
                            });
                        }
                    }
                });
                //add this marker group to the group container
                adMarkers.push(currentMarkerGroup);
            });//done looping through the location groups
            //print what you created to the console
            console.info('This is the data set for all maps.');
            console.table(adMarkers);

            //|||||||||||||||||||||||||||||||||||||
            //||||||||||| DISPLAY LOGIC |||||||||||
            //|||||||||||||||||||||||||||||||||||||
            //
            //since we are still in the map loop, check the display properties of this map and then display the map locations accordingly
            //Display Options
            //1. Show all Locations
            //2. Show one group at a time, and display these groups after their asssociated trigger is activated
            //// a. Reset map bounds based on locations
            //// b. Just show locations like normal
            //
            //1.Show all Locations
            if (x.show_all_locations === true) {
                console.log(x.map_name + ' will show all locations...');
                //a. Reset map bounds based on locatioon coordinates
                if (x.reset_bounds === true) {
                    console.log('... and ' + x.map_name + ' will reset the bounds of the map based on the coordinates of the locations it contains');
                    adMarkers.map(function (group) {
                        showAndExtendMap(group, myMap);
                    });
                }//enda

                //b. Just show all locations like normal
                if (x.reset_bounds === false) {
                    console.log('... and ' + x.map_name + ' will not reset the initial map bounds set by the initial zoom.');
                    adMarkers.map(function (group) {
                        showMarkers(group, myMap);
                    });
                }//endb
            }//end1
            //2. Show one group at a time, and display these groups after their asssociated trigger is activated
            if (x.show_all_locations === false) {
                console.log(x.map_name + ' will show one location at a time...');
                //show static locations first
                var staticLocations = [];
                adMarkers.map(function (group) {
                    if (group.static === true) {//check the static property of the markerGroup
                        console.log('found a static group! ' + group.category);
                        staticLocations.push(group);
                    }
                });


                //a. Reset map bounds based on locatioon coordinates
                if (x.reset_bounds === true) {
                    console.log('... and ' + x.map_name + ' will reset the bounds of the map based on the coordinates of the locations it contains');
                    //Start out the map with the static locations displayed
                    staticLocations.map(function (staticGroup) {
                        showAndExtendMap(staticGroup, myMap);
                    });
                    //loop through all of the marker groups and set upo an event listener
                    adMarkers.map(function (group) {
                        jQuery(group.trigger).click(function () {
                            showAndExtendMap(group, myMap);
                        });//end group event listener

                    });
                }//enda

                //b. Just show all locations like normal
                if (x.reset_bounds ===false) {
                    console.log('... and ' + x.map_name + ' will not reset the initial map bounds set by the initial zoom.');
                    //Start out the map with the static locations displayed
                    staticLocations.map(function (staticGroup) {
                        showAndExtendMap(staticGroup, myMap);
                    });
                    //loop through all of the marker groups and set upo an event listener
                    adMarkers.map(function (group) {
                        jQuery(group.trigger).click(function () {
                            showMarkers(group, myMap);
                        });//end group event listener
                    });
                }//endb
            }///end2

            //set up static locations array
            var staticLocations = [];
            adMarkers.map(function (group) {
                if (group.static === true) {//check the static property of the markerGroup
                    console.log('found a static group! ' + group.category);
                    staticLocations.push(group);
                }
            });
            //Start out the map with the static locations displayed
            staticLocations.map(function (staticGroup) {
                showMarkers(staticGroup, myMap);
            });

            //map tab functionality
//            jQuery('#tkab2').click(function (e) {
//                console.log('Tab 3 was clicked!');
//                console.log(adMarkers[1]);
//                showAndExtendMap(adMarkers[1], myMap);
//            });
//            jQuery('#tkab3').click(function (e) {
//                console.log('Tab 3 was clicked!');
//                console.log(adMarkers[2]);
//                showAndExtendMap(adMarkers[2], myMap);
//            });
//            triggerLocations(adMarkers);

        }); //Done Looping through the inital data


        //  We'll only allow
        // one infowindow which will open at the marker that is clicked, and populate based
        // on that markers position.
        /** @function populateInfoWindow
         *   @param {string} marker - A description of the parameter
         *   @param {string} infowindow - A description of the parameter
         *   @param {string} locationInformation - A description of the parameter
         *   @param {object} targetMap - The map object that this infowindow is located on
         *   @returns {undefined} - a description of the value that this function returns
         *   @description This function populates the infowindow when the marker is clicked.
         */
        function populateInfoWindow(marker, infowindow, locationInformation, targetMap) {
            // Check to make sure the infowindow is not already opened on this marker.
            if (infowindow.marker !== marker) {
                infowindow.marker = marker;
                //put this stuff together once you've figured out what data format the acf select field returns
                var adInfoTitle = '<h3 class="mapBoxTitle">' + locationInformation.name + '</h3>';
                var adInfoTitleAndLink = '<a class="mapBoxTitleLink" href="' + locationInformation.googlemapslink + '" target="_blank">' + adInfoTitle + '</a>';
                var adInfoAddressTextOnly = locationInformation.address;
                var adInfoAddressAndLink = '<hr><p class="mapAddress">';
                var adInfoCustomText = '';
                var adInfoContents = adInfoTitle + adInfoAddressAndLink;
                var adInfoWrapper = '<div class="mapBox ">';
                //use this for now
                var contentString = '\
    <div class="mapBox ">\n\
    <div class="map-marker-left">\n\
</div>\n\
<div class="map-marker-right">\n\
<a class="mapBoxTitle" href="' + locationInformation.googlemapslink + '" target="_blank">\n\
<h3 class="mapBoxTitle">' + locationInformation.name + '</h3>\n\
</a>\n\
\n\
<a href="' + locationInformation.googlemapslink + '" target="_blank">' + locationInformation.address + ' &#x2750;</a></p>\n\
</div></div> ';
                infowindow.setContent(contentString);
                infowindow.open(targetMap, marker);
                // Make sure the marker property is cleared if the infowindow is closed.
                infowindow.addListener('closeclick', function () {
                    infowindow.marker = null;
                });
            }
        }

        function showMarkers(markerGroup, theMap) {
            //this is a list of only google maps marker objects
            var markerList = markerGroup.markers;
            //grab the animationt type from the associated markerGroup property
            var currentAnimationType = markerGroup.animation;
            // Extend the boundaries of the map for each marker and display the marker
            for (var i = 0; i < markerList.length; i++) {
                //console.log("Setting map for: " + myMarkers[i].title);
                markerList[i].setMap(theMap);
                //console.log('Init animation Type: ' + currentAnimationType);
                if (currentAnimationType === 'BOUNCE') {
                    currentAnimationType = google.maps.Animation.BOUNCE;
                }
                if (currentAnimationType === 'DROP') {
                    currentAnimationType = google.maps.Animation.DROP;
                }
                if (currentAnimationType === 'NONE') {
                    currentAnimationType = google.maps.Animation.null;
                }
                markerList[i].setAnimation(currentAnimationType);
                //console.log('Changed animation Type: ' + currentAnimationType);
                //if there is no animation associated with the marker, add a drop
                if (markerList[i].getAnimation() === null) {
                    markerList[i].setAnimation(currentAnimationType);
                }
            }
        }
        // This function will loop through the markers array and display them all.
        /** @function showAndExtendMap
         *   @param {object} theMap - A google map object
         *   @param {string} markerGroup - A custom AD object that contains markers and relevant information about the location group
         *   @returns {undefined} - a description of the value that this function returns
         *   @description This function will display the given marker group as well as extend the bounds of the map to fit the newly displayed locations
         */
        function showAndExtendMap(markerGroup, theMap) {
            var bounds = new google.maps.LatLngBounds(theMap.center);
            //this is a list of only google maps marker objects
            var markerList = markerGroup.markers;
            //grab the animationt ype from the associated markerGroup property
            var currentAnimationType = markerGroup.animation;
            // Extend the boundaries of the map for each marker and display the marker
            for (var i = 0; i < markerList.length; i++) {
                //setting the map is what causs the marker to display
                markerList[i].setMap(theMap);
                console.log('set the map for  ' + markerList[i]);
                if (currentAnimationType === 'BOUNCE') {
                    currentAnimationType = google.maps.Animation.BOUNCE;
                }
                if (currentAnimationType === 'DROP') {
                    currentAnimationType = google.maps.Animation.DROP;
                }
                if (currentAnimationType === 'NONE') {
                    currentAnimationType = google.maps.Animation.null;
                }
                markerList[i].setAnimation(currentAnimationType);
                //console.log('Changed animation Type: ' + currentAnimationType);
                //if there is no animation associated with the marker, re-add the animation
                if (markerList[i].getAnimation() === null) {
                    markerList[i].setAnimation(currentAnimationType);
                }
                //extend the bounds of the map to include the location
                bounds.extend(markerList[i].position);
                //var currentZoom = theMap.getZoom();
                //console.log(currentZoom);
            }
            //reset the bounds of the map to match the new bounds created by the new map markers
            theMap.fitBounds(bounds);
        }
        // This function will loop through the listings and hide them all.
        function hideListings(markerGroup) {
            //this is a list of only google maps marker objects
            var markerList = markerGroup.markers;
            for (var i = 0; i < markerList.length; i++) {
                //console.log("Removing map for: " + myMarkers[i].title);
                markerList[i].setMap(null);
            }
        }
        //contructior function for groups of googlemarkers
        function ADMarkerGroup() {
            this.markers = [];
            this.category = '';
            this.mapName = '';
            this.animation = 'DROP';
            this.static = false;
            this.icon = '';
            this.trigger = '';
        }

        //TODO: Do something with geocoding
        //This function doesn't work because it requires too many calls to google's servers in a short period of time. It might be useful as a search feature on the front end?
        /** @function googleLocationFromAddress
         *   @param {string} addressString - an address
         *   @returns {Object} - a google maps lat/long object
         *   @description A function that turns an address into a google maps latlong. this function triggers a query limit error when used because there are too many requests going to googles servers too quickly
         */
        function googleLocationFromAddress(addressString) {
            setTimeout(function () {//set timeout function so you dont go over the google maps query limit
                var geocoder = new google.maps.Geocoder();
                //var mapBounds = mapBase.getBounds();
                var geocodeLatLong; //will be filled with a google location object if the request is ok
                geocoder.geocode({
                    'address': addressString,
                    componentRestrictions: {
                        country: 'US'
                    }

                }, function (results, status) {
                    if (status === 'OK') {
                        geocodeLatLong = results[0].geometry.location;
                        console.log('Found a location from the address!' + geocodeLatLong);
                        var coordinates = [];
                        coordinates[0] = geocodeLatLong.lat();
                        coordinates[1] = geocodeLatLong.lng();
                        return geocodeLatLong;
                    }
                    if (status === 'ZERO_RESULTS') {
                        //console.warn('ZERO_RESULTS was returned for ' + addressString);
                    }
                    if (status === 'OVER_QUERY_LIMIT') {
                        //return 'OVER_QUERY_LIMIT';
                        //console.warn('OVER_QUERY_LIMIT was returned for ' + addressString);
                    }
                    if (status === 'REQUEST_DENIED') {
                        //console.warn('REQUEST_DENIED was returned for ' + addressString);
                    }

                    if (status === 'INVALID_REQUEST') {
                        //console.warn('INVALID_REQUEST was returned for ' + addressString);
                    }
                    if (status === 'UNKNOWN_ERROR') {
                        //console.warn('UNKNOWN_ERROR was returned for ' + addressString);
                    } else {
                        console.warn('AD-1: There was an error: ' + status + ' finding the location at -> ' + addressString);
                        console.log('End timeout' + new Date());
                        return 'ad-error';
                    }

                }, 2000);
            }); //end geocode call
        }//end markerFromAddress

        /** @function googleLocationFromGeometry 
         *   @param {string} geometryString - a string that contains lat/long coordinates separated by a comma.
         *   @returns {Object} - a google lat/long object
         *   @description Returns a google location object when given a valid lat/long string.
         */
        function googleLocationFromGeometry(geometryString) {
            var googleLatLong;
            var coordinates = geometryString.split(',');
            if ((coordinates.length !== 2)) { //very basic error checking
                console.warn('AD-2: There was an error finding these coordinates -> ' + geometryString);
                return 'ad-error';
            } else {
                googleLatLong = new google.maps.LatLng(coordinates[0], coordinates[1]);
            }
            return googleLatLong;
        }

        /** @function createGoogleMap
         *   @param {object} mapInformation - An object parsed from JSON that should have all of the requisite information for seting up a google map
         *   @returns {Object} - a google map
         *   @description A function that creates a new google map based on the information given to to it.
         */
        function createGoogleMap(mapInformation) {
            //create the object that will eventually be returned
            var myGoogleMap;
            //set map zoom levels and make sure it is a integer
            var adMapZoom = parseInt(mapInformation.map_zoom);
            var adMapMinZoom = parseInt(mapInformation.map_min_zoom);
            var adMapMaxZoom = parseInt(mapInformation.map_max_zoom);
            //set map styles and check to make sure it is formatted correctly
            var adMapStyle = mapInformation.map_styles;
            adMapStyle = JSON.parse(adMapStyle);
            //set mapcenter
            var adMapCenter = mapInformation.map_center;
            adMapCenter = googleLocationFromGeometry(mapInformation.map_center);
            if (adMapCenter === 'ad-error') {
                console.error("AD-6: There was an error setting up the map center at -> " + mapInformation.map_center);
            }

            //capture the values for the map settings from the json object
            var adMapType = mapInformation.map_type.toLowerCase();
            var adMapUI = mapInformation.map_ui;
            var adMapBackgroundColor = mapInformation.map_background_color;
            var adMapScroll = mapInformation.map_scrollwheel;
            var adMapDrag = mapInformation.map_draggable;
            //somewhat buggy validation
            if (!adValidation(adMapZoom, [undefined])) {
                console.warn('AD-3: There was an error with the center of your map.');
                return 'ad-error';
            }
            //Put the map settings together
            var mapSettings = {
                zoom: adMapZoom,
                center: adMapCenter,
                styles: adMapStyle,
                mapTypeId: adMapType,
                disableDefaultUI: !adMapUI,
                minZoom: adMapMinZoom,
                maxZoom: adMapMaxZoom,
                scrollwheel: adMapScroll,
                draggable: adMapDrag,
                backgroundColor: adMapBackgroundColor
            };
            //grab the html element that will be the container for the map
            var mapDiv = document.getElementById(mapInformation.map_id);
            //summon the map
            var myGoogleMap = new google.maps.Map(mapDiv, mapSettings);
            return myGoogleMap;
        }//end createGoogleMapFunction

        /** @function createLocationGroup
         *   @param {Object} groupInfo - all of the information needed to instantiate an ADMarkerGroup
         *   @returns {Object} - a custom object : ADMarkerGroup
         *   @description takes unformatted location group data and formats it into information that can be used with other functions
         */
        function createLocationGroup(groupInfo) {
            var theGroup = new ADMarkerGroup();
            var theGroupName = groupInfo.group_name;
            var groupIcon = groupInfo.pin_icon;
            var animationType = groupInfo.animation;
            var staticStatus = groupInfo.static;
            var groupTrigger = groupInfo.trigger;
            var isGroupClickable = groupInfo.clickable;
            var infoWindowFields = groupInfo.info_window_fields;
            theGroup.category = theGroupName;
            theGroup.animation = animationType;
            theGroup.static = staticStatus;
            theGroup.icon = groupIcon;
            theGroup.trigger = groupTrigger;
            theGroup.clickable = isGroupClickable;
            return theGroup;
        }

        /** @function createGoogleMarker
         *   @param {Object} locationInformation - information pertaining to the location. Formatted correctly?
         *   @param {Object} locationGroup - information pertaining to the group. Formatted correctly?
         *   @returns {Object} - a new google marker object
         *   @description ACreates a google marker object based on the information supplied 
         */
        function createGoogleMarker(locationInformation, locationGroup) {

            var myMarker;
            //do some checks to make sure there is a valid location supplied

            //create a googlemaps latlong object
            var mylatLong = locationInformation.latlong;
            mylatLong = googleLocationFromGeometry(locationInformation.latlong);
            //if the lat long is still returning an error then just return an error instead of a real marker
            if (mylatLong === 'ad-error') {
                console.error('AD-4: A location could not be created from this lat/lng: ' + locationInformation.latlong);
                myMarker = 'ad-error';
                return myMarker;
            }

            //if the location object is created, add some more information to the marker
            var myName = locationInformation.name;
            var groupIcon = locationGroup.icon;
            var animationType = locationGroup.animation;
            var groupClickability = locationGroup.clickable;
            //summon a new google maps marker object 
            var myMarker = new google.maps.Marker({
                position: mylatLong,
                title: myName,
                icon: groupIcon,
                clickable: groupClickability,
                animation: animationType

            });
            var optimizedBoolean = true;
            //if the icon is a gif, change the optimized attribute to false so that it can animate on the screen
            if (groupIcon.indexOf('.gif') !== -1) {
                optimizedBoolean = false;
                myMarker = new google.maps.Marker({
                    position: mylatLong,
                    title: myName,
                    icon: groupIcon,
                    clickable: groupClickability,
                    animation: animationType,
                    optimized: optimizedBoolean
                });
            }
            return myMarker;
        }

        /** @function adValidation
         *   @param {Object | Number | String | Bool | Array} theInput - Any value?
         *   @param {Array} theChecks - An array of values to check against
         *   @returns {Bool} - whehter or not the input is valid
         *   @description Returns true or false on data depending on whther or not it matches the criteria
         */
        function adValidation(theInput, theChecks) {
            var theOutput = true;
            if (!Array.isArray(theChecks)) {
                console.warn('AD-0: There was an error when validating your data against the provided array of values.');
                return;
            }

            if ((theChecks === undefined)) {
                var defaultChecks = [null, undefined, '', 0];
                defaultChecks.map(function (x) {
                    if (theInput === x) {
                        theOutput = false;
                        return theOutput;
                    }
                });
            } else {
                theChecks.map(function (x) {
                    if (theInput === x) {
                        theOutput = false;
                        return theOutput;
                    }
                });
            }
            return theOutput;
        }//end crappy validation function

        function triggerLocations(locationGroups) {
            //set up static locations array
            var staticLocations = [];
            locationGroups.map(function (group) {
                if (group.static === true) {//check the static property of the markerGroup

                    staticLocations.push(group);
                }
            });
            //TODO: figure out what i was doing with this...
            //Start out the map with the static locations displayed
            function showStaticLocations() {
                staticLocations.map(function (staticGroup) {
                    console.log('Displaying a static group! ' + staticGroup.category);
                    showAndExtendMap(staticGroup, myMap);
                });
                staticLocations.map(function (staticGroup) {
                    console.log('Displaying a static group! ' + staticGroup.category);
                    showAndExtendMap(staticGroup, myMap);
                });
                //map tab functionality
                for (var counter = 0; counter < locationGroups.length; counter++) {
                    return function () {
                        jQuery(locationGroups[counter].trigger).click(function (e) {
                            console.log(locationGroups[counter].trigger + 'was clicked');
                            var selectedMapGroup = locationGroups[counter].category;
                            console.log('You selected: ' + selectedMapGroup);
                            for (var i = 0; i < locationGroups.length; i++) {
                                var theName = locationGroups[i].category;
                                //show the first section on every click

                                //hide the map groups that have not been selected and are not static
                                if ((theName.toLowerCase() !== selectedMapGroup) && (locationGroups[i].static === false)) {
                                    //hide other markers
                                    hideListings(locationGroups[i]);
                                }
                                //display all markers on the map
                                showStaticLocations();
                                showAndExtendMap(locationGroups[counter], myMap);
                            }

                        });
                    }(); //end returned function
                }
                locationGroups.map(function (theGroup) {
                    return function () {
                        jQuery(theGroup.trigger).click(function (e) {
                            console.log(theGroup.trigger + 'was clicked');
                            var selectedMapGroup = theGroup.category;
                            console.log('You selected: ' + selectedMapGroup);
                            for (var i = 0; i < locationGroups.length; i++) {
                                var theName = locationGroups[i].category;
                                //show the first section on every click

                                //hide the map groups that have not been selected and are not static
                                if ((theName.toLowerCase() !== selectedMapGroup) && (locationGroups[i].static === false)) {
                                    //hide other markers
                                    hideListings(adMarkers[i]);
                                }
                                //display all markers on the map
                                showAndExtendMap(theGroup, myMap);
                            }
                        });
                    }(); //end returned function
                });
            }//end showStatic locations
        }//end trigger locations function


        //|||||||||||||||||||||||||||||||||||||||
        //||||||| Questionable Functions? ||||||
        //|||||||||||||||||||||||||||||||||||||

        //json validator- not sure if it works all the time....
        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
        //make a lat/long from a string?
        function makeLatLong(locationStringParam) {
            var splitByComma = locationStringParam.split(',');
            if (splitByComma.length > 2) { //very basic error checking
                console.warn('AD: There was an error turning this into a lat/long:  ' + locationStringParam);
            }
            var mapLatLong = new google.maps.LatLng(splitByComma[0], splitByComma[1]);
            return mapLatLong;
        }

        //||||||||||||||||||||||||||||||||||
        //|||||||||||||||||||||||||||||||||||
        //||||||||||||||||||||||||||||||||||||
        //|||||||||||||||||||||||||||||||||||||

    }//end of adMapsinit

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>&callback=adMapsInit"
async defer></script>
<style>
    /*    Some Default styles for the info windows
        TODO: Make some of these colors/options dynamic */
    .mapBoxTitle h3{
        color:black;
    }
</style>
<?php 
