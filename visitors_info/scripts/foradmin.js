

jQuery(document).ready( function () {

       let params = {};
       params.action = 'get_visitors';

 params.action = 'get_visitors';

      jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',

        data:{
          action: params.action, // this is the function in your functions.php that will be triggered

        },

        success: function( data ){

          populateDataTable(data);

        }
      });




    function checkBeforeRequest(params) {
        if ("migrateProducts" in params && "migrateSingle" in params && "prod_id" in params && "limit" in params) {
            return false;
        }
    }

    function ajaxCall(params) {

       
        jQuery.ajax({
          url: ajaxurl,
          type: 'POST',
          dataType: 'json',
          cache: false,
          data:{
            action: params.action, 

          },
          success: function( data ){
      
          }
        });
  }


  function populateDataTable(data) {

      //jQuery("#myTable").DataTable().clear();

          var table = jQuery('#myTable').dataTable({
            "columnDefs": [ {
                     "targets": -1,
                     "data": null,
                     "defaultContent": "<button type='button' class='btn btn-primary btn-sm'>Show Details!</button>"
                 } ]
          });

          var row = 1;
          jQuery.each(data, function (index, value) {
              jQuery('#myTable').dataTable().fnAddData( [
                  value.ID,
                  value.country_code,
                  value.country_name,
                  value.city,
                  value.place,
                  value.dateTime,
                  value.ipaddress,
                  value.location,
                  value.referer_page,
                  value.formatted_address,
                ]);
             row++;
          });

          // Re-init map before show modal
          jQuery('#myTable tbody').on( 'click', 'button', function () {
                  var data = table.api().row(jQuery(this).parents('tr') ).data();
                  latLng = data[7].split(',');


                  jQuery('#visitor_address').html('');
                  initializeGMap( latLng[0], latLng[1], data[9]);
                  jQuery( "#myModal" ).modal('toggle');
              } );
      }



    function getBaseURL() {
    var url = location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));

    if (baseURL.indexOf('http://localhost') != -1) {

        var url = location.href;
        var pathname = location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {

        return baseURL + "/";
      }
    }


  var map = null;
  var myMarker;
  var myLatlng;

  function initializeGMap(lat, lng, address) {

    jQuery('<p><strong>Address:</strong> ' +address+'</p>').appendTo('#visitor_address');

    myLatlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: 14,
      zoomControl: true,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    myMarker = new google.maps.Marker({
      position: myLatlng
    });
    myMarker.setMap(map);
  }


  jQuery('#myModal').on('show.bs.modal', function(event) {
    var button = jQuery(event.relatedTarget);

    ///initializeGMap(button.data('lat'), button.data('lng'));
    jQuery("#location-map").css("width", "100%");
    jQuery("#map_canvas").css("width", "100%");
  });

  // Trigger map resize event after modal shown
  jQuery('#myModal').on('shown.bs.modal', function() {
    google.maps.event.trigger(map, "resize");
    map.setCenter(myLatlng);
  });


  var apiKey = "xxxxxxx";

    function findLatLonFromIP() {
      return new Promise((resolve, reject) => {
      jQuery.ajax({
          url: `https://www.googleapis.com/geolocation/v1/geolocate?key=xxxxxxx`,
          type: 'POST',
          data: JSON.stringify({considerIp: true}),
          contentType: 'application/json; charset=utf-8',
          dataType: 'json',
          success: (data) => {
            if (data && data.location) {
              resolve({lat: data.location.lat, lng: data.location.lng});
            } else {
              reject('No location object in geolocate API response.');
            }
          },
          error: (err) => {
            reject(err);
          },
        });
      });
    }


  function getCountryCodeFromLatLng(lat, lng) {
      return new Promise((resolve, reject) => {
        jQuery.ajax({
          url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=`+lat+`,`+lng+`&key=xxxxxxx`,
          type: 'GET',
          data: JSON.stringify({considerIp: true}),
          dataType: 'json',
          success: (data) => {
        
            data.results.some((address) => {
              address.address_components.some((component) => {
                if (component.types.includes('country')) {
                  return resolve(component.short_name);
                }
              });
            });
            reject('Country not found in location information.');
          },
          error: (err) => {
            reject(err);
          },
        });
      });
    }

  findLatLonFromIP().then((latlng) => {
    return getCountryCodeFromLatLng(latlng.lat, latlng.lng);
  }).then((countryCode) => {
    
  });




  function showGraph(data)
          {
              {

                       var name = [];
                       var marks = [];
                       var countries = [];

                       for (var i in data) {
                           countries.push(data[i].country_name);
                       }


                          for( i in data ) {
                        marks.push(getOccurrence(countries, data[i].country_name));

                          }

                       var uniqueCountryName = countries.filter((v, i, a) => a.indexOf(v) === i);

                      var chartdata = {
                          labels: uniqueCountryName,
                          datasets: [
                              {
                                  label: 'Visits',
                                  backgroundColor: '#49e2ff',
                                  borderColor: '#46d5f1',
                                  hoverBackgroundColor: '#CCCCCC',
                                  hoverBorderColor: '#666666',
                                  data: marks
                              }
                          ]
                      };

                      var graphTarget = jQuery("#graphCanvas");


                      setTimeout(function(){
                        var barGraph = new Chart(graphTarget, {
                            type: 'bar',
                            data: chartdata
                        });
                      }, 500);


              }
          }



          function getOccurrence(array, value) {
              return array.filter((v) => (v === value)).length;
          }



  }); // Document ready
