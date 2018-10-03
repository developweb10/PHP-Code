<?php /*?><table width="600">
	<tr>
		<td style='width:45%;font-family: arial;' align="left" valign="top">
			<div style='font-size:40px; color:#8FD324; font-weight:bolder; text-align:center; line-height:50px;letter-spacing:-2px;'>
				YOUR MOBILE <br />PARKING METER
			</div>
			<br />
			<div style='text-align:center;'><img src="{{ asset('/images/metercar.jpg') }}" height="80" ></div>
			<br />
			<div style='text-align:center; font-size:45px; color:#A8A8A8;font-family:arial;'>MY-METER.com</div>
		</td>
		<td style='width:55%;' align="left" valign="top">
			<img src="{{ asset('/images/computer.png') }}" style="width:350px;">
		</td>
	</tr>
	<tr>
		<td colspan="2" style=" color:#4A4A4A;    font-size: 16px;    line-height: 32px; word-wrap: break-word; ">	
			<div style="width:700px; text-align:justify">
			With more than 100,000 parking meters worldwide, My-Meter.com has become the leading platform for
			online metering. We have streamlined our services allowing anyone to rent out their parking space,
			whether you're a homeowner, small business, or operations manager. Drivers simply navigate to
			my-meter.com, enter their meter number and desired time, and proceed to checkout. When the
			transaction is complete they can opt-in to receive a text message 5 minutes before the meter expires.
			Your meter number is custom to you and tracks all of the transactions that occur. Just set the hourly rate
			and we’ll send you monthly payments via e-transfer. It's that simple
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<div style="position:absolute;font-size:32px; color:#8FD324; font-weight:bolder; letter-spacing:-2px;left:480px;text-align:center;">
				PROMO CODE:
				<br /><span style="color:gray;letter-spacing:3px;">-{{ $promo_code }}-</span>
			</div>
			<img src="{{ asset('/images/lastimg1.jpg') }}" style="width:700px;" >
		</td>
	</tr>
</table><?php */?>
<html style="padding:0px; margin:0px;">
	<head>
    </head>
    <body style="width:100%; height: 100%; padding:0px; margin:0px; position:relative; font-family:Arial, Helvetica, sans-serif;">
    	<img src="{{ $brochure_image }}" style="width:100%; "  />
    	<div style="position: absolute;    right: 95px;    top: 560px;    font-size: 40px;    color: gray; font-weight:bolder">{{ $promo_code }}</div>
    </body>
</html>