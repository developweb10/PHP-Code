@extends('welcome-app')

@section('content')
<script>

  var previous = 0;
  /******************** information wiondow hover open ********************/
    function icon(obj, map) {
		index = obj.getTitle();
		$('.gmnoprint').removeAttr('title');
		infowindows[index].open(map, obj);
	}
	
  /******************** information wiondow hover out ********************/
  	function icon_out(obj, map) {
		for (var i = 0; i < infowindows.length; i++) {
			infowindows[i].close();
		}
	}
	
function styleMap(map)
{

	var styles = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":20},{"color":"#000000"},{"lightness":20}]},
				  /*{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#cccccc"},{"lightness":16}]},*/
				  {"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
				  {"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#C9C9C9"},{"lightness":0}]},
				  {"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#C9C9C9"},{"lightness":0}]},
				  {"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#F5F5F5"},{"lightness":0}]},
				  {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#F5F5F5"},{"lightness":0}]},
				 /*  {"featureType": "road","elementType": "geometry","stylers": [{"color":"#ff0000"},{ "visibility": "simplified" }]},*/
				  {"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#C9C9C9"},{"lightness":00}]},
				  {"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#C9C9C9"}]},
				  {"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#C9C9C9"}]},
				  {"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#FFFFFF"}]},
				/* {"featureType":"transit","elementType":"geometry","stylers":[{"color":"#C9C9C9"}]},*/
				  {"featureType":"water","elementType":"geometry","stylers":[{"color":"#C9C9C9"}]}];
	
	
	/* var options = {
        zoom: 15,
        center:  new google.maps.LatLng(49.895136,-97.1383744),
        //mapTypeId: google.maps.MapTypeId.ROADMAP,
        
    };

    map = new google.maps.Map($('#map')[0], options);
    map.setOptions({
        styles: styles,
		
    });*/
	
	
	
	map.setOptions({disableDefaultUI: true,styles: styles});
	//map.setOptions({streetViewControl: false});
	//map.setOptions({mapTypeId: "ROADMAP" });
}

</script>
<style>
	.mapper_map {
		width:100% !important;
		height:575px !important;
	}
	.gm-style-mtc{
		display:none;
	}
	#slideshow img {
		display: none !important;
	}
	body.welcome .content-container {
    	min-height: auto !important;
	}
	@media only screen and (max-width: 690px) {
		.navbar-header{
			background-color: rgba(135, 210, 54, 0) !important;
		}
		.panel, body.welcome .banner-img {
    		background-color: rgba(255, 255, 255, 0) !important;
		}
	.fa-bars:before {
		background-image: url(/dev/public/images/menu-button-green.png);
	}
	}
	@media only screen and (max-width: 769px) and (min-width: 690px) {
		.mapper_map {
			width:100% !important;
			height:900px !important;
		}
	}
</style>
	<div class="content-container"> 
        <div class="container-fluid" style="margin-top:0px; margin-bottom:0px">
            <div class="row">
            	<div class="" style="background-color:#fff;">
                    <div id="map" class="mapper_map">
                        <?php // echo "<pre>"; print_r($vars);  ?>
                        {!! Mapper::render() !!}
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection