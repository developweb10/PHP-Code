<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width; initial-scale=1.0;"><title>Untitled Document</title>
<script src="https://my-meter.com/dev/public/js/jquery.min.js"></script>

	<script src="https://my-meter.com/dev/public/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
<script src="https://my-meter.com/dev/public/js/bootstrap-slider.js"></script>
<link href="https://my-meter.com/dev/public/css/bootstrap-slider.css" rel='stylesheet' type='text/css' />

</head>

<body>
	<input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="14"/>
    
    <script>
    var slider = new Slider('#ex1', {
        formatter: function(value) {
            return 'Current value: ' + value;
        }
    });
	</script>
</body>
</html>
