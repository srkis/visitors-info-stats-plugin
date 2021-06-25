<?php

//Klasa koja prikazuje tabelu na admin page-u.
include_once( WP_PLUGIN_DIR . '/visitors_info/classes/Visitor_info_Data.php' );
Class Visitor_info_render {

  public $url, $info_data;
  function __construct($url)
  {
    $this->url = $url;
    $this->info_data = new Visitor_info_Data();

    $this->render_visitor_info_page();
  }


  public function render_visitor_info_page()
  {


$visitor_infor_main_page = '<!DOCTYPE html>
  <html lang="en">
  <head>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

  <script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



  <script src="https://maps.googleapis.com/maps/api/js?key='.$this->info_data->apikey.'"></script>

  </head>

  <body>

<div class="container">
  <h2>Visitors info statistics</h2>
  <p>Here you can see your website visitors info</p>

  <table id="myTable" class="table row-border hover stripe order-column nowrap">
          <thead>
            <tr>
              <th>User ID</th>
              <th>Country Code</th>
              <th>Country</th>
              <th>City</th>
              <th>Place</th>
              <th>DateTime</th>
              <th>IP Address</th>
              <th>Location</th>
              <th>Referer Page</th>
              <th>Show Details</th>
            </tr>
          </thead>
        </table>
</div>


  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div id="visitor_address" class="col-md-12 modal_body_content">

            </div>
          </div>
          <div class="row">
            <div class="col-md-12 modal_body_map">
              <div class="location-map" id="location-map">
                <div style="width: 350px; height: 350px;" id="map_canvas"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 modal_body_end">
              <p style="color:red;margin-top:10px;"><strong>srdjan.icodes.rocks</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>';

    return $visitor_infor_main_page;

  }


public function renderChartsPage()
{
  $visitor_info_chart_page = '

  <!DOCTYPE HTML>
    <html>
    <head>
    <style type="text/css">


</style>

    </head>
    <body>

    <div class="container">
      <h1>Visitors info statistics</h1>
      <small>Here you can see visitorts statistics in charts</small>

      <div class="row">
        <div class="col-lg-6" >
        <p></p>
          <h3>Visitors info statistics by country</h3>
        This chart show statistics by countries. You can see from which country visitors are came to your website.
        </div>

      </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-sm-6" style="width:700px">
             <canvas id="graphCanvas"></canvas>
            </div>
        </div>
    </div>

    <hr style="font-weight:400" size="30">

    <div class="container">
      <h3>Visitors info statistics by city</h3>
      <small>Here you can see visitorts statistics in charts</small>

      <div class="row">
        <div class="col-lg-6" >
        <p></p>
        This chart show statistics by cities. You can see from which city visitors are came to your website.
        </div>

      </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="col-lg-6-offset-4" style="width:700px">
              <canvas id="citiesChart"></canvas>
            </div>
        </div>
    </div>

    <hr style="font-weight:400" size="30">
  <p></p>

        <div class="container">
          <h3>Visitors info statistics by Devices</h3>
            <div class="row">

                <div class="col-lg-6-offset-4" style="width:350px">
                <p></p>
                This chart show statistics by devices. You can see from which device visitors are came to your website.
                  <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>








    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js"></script>
    </body>
    </html>
  ';

    return $visitor_info_chart_page;

}


































}

 ?>
