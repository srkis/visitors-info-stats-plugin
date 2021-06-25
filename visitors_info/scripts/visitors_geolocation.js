
jQuery(document).ready( function () {

let NO_STORAGE = false;

let currentTimeStamp = new Date().getTime();
let timestampPlusSevenDate = new Date(currentTimeStamp);
    timestampPlusSevenDate.setDate(timestampPlusSevenDate.getDate()+ 7);

  let timestampPlusSeven = timestampPlusSevenDate.getTime();  //


   let getStorage = window.localStorage.getItem('visitorInfo');

  if(getStorage == null) {

    NO_STORAGE = true;
  }

if(getStorage !== null ){
  let storage =  JSON.parse(window.localStorage.getItem('visitorInfo'));


  let fromLocalStorage = new Date(storage.created);
  fromLocalStorage.setDate(fromLocalStorage.getDate()+ 7);

  let fromLocalStoragePlusSevenDays = fromLocalStorage.getTime();

  if(fromLocalStoragePlusSevenDays <= currentTimeStamp) {
          NO_STORAGE = true;
  }


}


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
            //console.log('reverse geocode:', data.results[0].address_components);

                let currentTimeStamp = new Date().getTime();
                const visitor = {
                created: currentTimeStamp,
              }

         window.localStorage.setItem('visitorInfo', JSON.stringify(visitor));

  		        //console.log("DATA:",data.results);
              addVisitors(data.results);

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


    if(NO_STORAGE == true){

        findLatLonFromIP().then((latlng) => {
          return getCountryCodeFromLatLng(latlng.lat, latlng.lng);
        }).then((countryCode) => {
        });

    }else{

     // console.log("Returned because localStorage is set");
    }




function addVisitors(visitorData)
{

     let params = {};
  params.action = 'add_visitors';

  jQuery.ajax({
    url: getBaseURL()+"/wp-admin/admin-ajax.php",
    type: 'POST',
    dataType: 'json',

    data:{
      action: params.action,
      results: visitorData,
      referrer: document.referrer,

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




function getDateTime() {
        var now     = new Date();
        var year    = now.getFullYear();
        var month   = now.getMonth()+1;
        var day     = now.getDate();
        var hour    = now.getHours();
        var minute  = now.getMinutes();
        var second  = now.getSeconds();
        if(month.toString().length == 1) {
             month = '0'+month;
        }
        if(day.toString().length == 1) {
             day = '0'+day;
        }
        if(hour.toString().length == 1) {
             hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
             minute = '0'+minute;
        }
        if(second.toString().length == 1) {
             second = '0'+second;
        }
        var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
         return dateTime;
    }




});
