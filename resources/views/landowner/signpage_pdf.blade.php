<html style="padding:0px; margin:0px; background-color:#70E412; border:0px;">
<head>
	<style>
		@font-face {
		  font-family: 'Impact';
		  font-style: normal;
		  font-weight: 400;
		  src: url({{ asset("/fonts/ufonts.com_impact.ttf") }});
		}
		@font-face {
		  font-family: 'Eurostile';
		  font-style: normal;
		  font-weight: 400;
		  src: url({{ asset("/fonts/films.eurostile.ttf") }});
		}
	</style>
</head>
<body style="padding:0px; margin:0px;border: 0; width:795px; height:100%;text-align:center; background-color:#70E412;">
	@foreach( $vars['meter_ids'] as $key=>$meter_id )
    	<img src="{{ $vars['signage_img'] }}" border="0" style="width:746px; margin-top:0px; margin-left:13px;" >
        <div style="font-family:Impact; position:absolute; top: 70px; left:375px; color: #70E412; font-size: 11em;">{{ $vars['lot_price'] }}</div>
        <div style="font-family: Impact, Haettenschweiler, 'Franklin Gothic Bold', Charcoal, 'Helvetica Inserat', 'Bitstream Vera Sans Bold', 'Arial Black', 'sans serif'; position:absolute; color: #70E412;  top: 272px; left:565px; font-size:5em">/ hr.</div>
    	<div style="position:absolute;top: 480px;left:55px;color: #fff;font-size: 11em; font-family:Eurostile;">{{ substr($meter_id,0,3) }}</div>
    	<div style="position:absolute;top: 480px;left: 400px;color: #fff;font-size: 11em; font-family:Eurostile;">{{ substr($meter_id,3,3) }}</div>
        
        <div style="position:absolute;bottom:50px; left:440px; color: #70E412; font-size: 2em; font-family:Eurostile;" >{{ $vars['towing_company_number'] }}</div>
		<?php /*?><table style="text-align:center; width:100%;">
			<tr style="background-color: #A6E35D;margin:5px;" valign="middle">&nbsp;
				<td style="font-size:10em; margin-top:60px; float:left; width: 35%;" valign="middle" height="300" >
					<img src="{{ asset('/images/iphone.png') }}" border="0" style="width: 200px; vertical-align:top; " />
				</td>
				<td style="font-size:8em; margin-top:60px;float:left; width: 65%;" >$<span class="lot_print_price">{{ $lot_price }}</span> <br />/ hr </td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<br />
					<div style="font-size:7em; margin-top:15px; text-align:center;">my-meter.com</div>
					<br />
					<div style="font-size:10em; margin-top:10px;  text-align:center;"># <span class="meter_print_id">{{ $meter_id }}</span></div>
					<br /><br /><br /><br />
					<div  style="text-align:center;">
						<img src="{{ asset('/images/card_types.png') }}" border="0" style="height: 100px; " />
					</div>
				</td>
			</tr>
		</table><?php */?>
	@endforeach
</body>
</html>
