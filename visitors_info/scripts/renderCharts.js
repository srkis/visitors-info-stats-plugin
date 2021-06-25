

jQuery(document).ready( function () {

       let params = {};
       params.action = 'get_visitors';

 params.action = 'get_visitors';

      jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',

        data:{
          action: params.action, 

        },

        success: function( data ){

          showGraph(data);
          showCitiesStats(data);
          showDevicesChart(data);
        }
      });


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


  function showGraph(data) {

             var name = [];
             var marks = [];
             var countries = [];

             for (var i in data) {
                 countries.push(data[i].country_name);
             }

              countries.sort();

              var current = null;
              var count = 0;

              for(var i = 0; i < countries.length; i++) {

                  if(countries[i] != current) {

                  if(count > 0) {
                    marks.push(count);
                  }
                  current = countries[i];
                  count = 1;
                }else{
                  count++;
                }
              }

              if(count > 0) {
                    marks.push(count);
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

              var barGraph = new Chart(graphTarget, {
                  type: 'bar',
                  data: chartdata
              });

          }


          //PIE Charts
      function showCitiesStats(data) {

        var cities = [];
        var labels = [];
        var marks = [];

        for (var i in data) {
            cities.push(data[i].city);
        }

          var uniqueCityName = cities.filter((v, i, a) => a.indexOf(v) === i);
          uniqueCityName.sort();

          cities.sort();

          var current = null;
          var count = 0;

          for(var i = 0; i < cities.length; i++) {

              if(cities[i] != current) {

              if(count > 0) {
                marks.push(count);
              }
              current = cities[i];

              count = 1;
            }else{
              
              count++;
            }
          }

          if(count > 0) {
                marks.push(count);
            }

          var ctx = document.getElementById("citiesChart").getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: uniqueCityName,
                datasets: [{
                label: 'Visits',
                backgroundColor: [
                  '#712ecc',
                  "#2ecc71",
                  "#3498db",
                  "#95a5a6",
                  "#9b59b6",
                  "#f1c40f",
                  "#e74c3c",
                  "#34495e",
                  "#cc2e89"
                  ],
                  data: marks
                }]
              }
            });

      }




      //PIE Charts
  function showDevicesChart(data) {

    var devices = [];
    var labels = [];
    var marks = [];

    for (var i in data) {
        devices.push(data[i].device);
    }

      var uniqueDeviceyName = devices.filter((v, i, a) => a.indexOf(v) === i);
      uniqueDeviceyName.sort();

      devices.sort();

      var current = null;
      var count = 0;

      for(var i = 0; i < devices.length; i++) {

          if(devices[i] != current) {

          if(count > 0) {
            marks.push(count);
          }
          current = devices[i];

          count = 1;
        }else{
          count++;
        }
      }

      if(count > 0) {
            marks.push(count);
        }

      var ctx = document.getElementById("devicesChart").getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'pie',
          data: {
            labels: uniqueDeviceyName,
            datasets: [{
              backgroundColor: [
                "#2ecc71",
                "#3498db",
                "#95a5a6",
                "#9b59b6",
                "#f1c40f",
                "#e74c3c",
                "#34495e",
                "#cc2e89"
              ],
              data: marks
            }]
          }
        });

  }


  }); // Document ready
