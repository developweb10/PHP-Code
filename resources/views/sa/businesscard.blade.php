<html style="padding:0px; margin:0px;">
	<head>
		<style type="text/css">
            @font-face {
              font-family: 'GardensC';
              src: url({{ asset("/fonts/GardensC.otf") }});
            }
        </style>
    </head>
    <body style="width:100%; height: 100%; padding:0px; margin:0px; position:relative; font-family:'GardensC', Arial, Helvetica, sans-serif; color:gray;">
    	<img src="{{ $business_card_image }}" style="width:100%;"  />
        <div style="position:absolute; top: 16px; right:20px; font-size:16px; width:140px; text-align:right;">{{ $sa->name }}</div>
        <div style="position:absolute; top: 56px; right:20px; font-size:14px; width:140px; text-align:right;">{{ $sa->email }}</div>
        <div style="position:absolute; top: 90px; right:20px; font-size:16px; width:60px; text-align:right; ">{{ $sa->promo_code }}</div>
    </body>
</html>

