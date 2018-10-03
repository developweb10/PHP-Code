/*http://api.page2images.com/html2image   

Key: 7353149544040468



http://www.page2images.com/my_account/apikey



*/


var chartInitialized = typeof(chartInitialized !== 'undefined')?chartInitialized:false;

var meter_price = 0;

//var total_price = 0;

var current_date = typeof(server_date) !== 'undefined' ? (server_date.split(" "))[0] : '';

var current_time = typeof(server_date) !== 'undefined' ? (server_date.split(" "))[1] + " " + (server_date.split(" "))[2] : '';

var meter_per_hour_price = typeof(meter_per_hour_price) !== 'undefined' ? meter_per_hour_price : 0;



var submit_user_pay_form = true;





var selected_state 		= "";

var selected_city 		= "";

var selected_town 		= "";

var	selected_Currency 	= "";

var selected_bank		= "";

var selected_mode 		= "";





function initDatepicker(){

		

	$('.datepicker').datepicker({

		format: "yyyy-mm-dd",

		setDate: current_date,

		autoclose: true,

		startDate: "today"



	}).on('changeDate', function(e){

		$(this).datepicker('hide');

	});

	//alert($('.datepicker').val());

}



function initFullDatepicker(){

	$('.datepicker1').datepicker({

		format: "yyyy-mm-dd",

		setDate: current_date,

		autoclose: true

	}).on('changeDate', function(e){

		$(this).datepicker('hide');

	});



}



function initTimepicker(){

	$('.timepicker1').timepicker({

		 defaultTime: current_time,

		minuteStep: 30,

		disableFocus: true,

		template: 'dropdown'						 

	});

}



function calculate_price(){



	console.log("calculate price");



	th = $("#customer_pay_form select[name='expiry_time']");

	hours = th.val();



	console.log(hours);

	

	if( $("#customer_pay_form input[name='meter_id']").val() == '' ){

		meter_per_hour_price = 0;

	}

	

	console.log("meter price"+meter_per_hour_price);

	

	if( meter_per_hour_price > 0 && th.val() > 0 ){

		$(".meter_price_message").html("<div class='alert alert-success text-center'>Total Amount: $"+ (hours*meter_per_hour_price).toFixed(2) + '</div>' ).show();	

		$(th).removeClass("red-border");

		console.log("calculated");

		return 1;

	}

	else{

		$(".meter_price_message").html("").hide();	

		console.log("not calculated");

		return 0;

	}

}



function initDatatable(control){

	if( typeof(control) == 'undefined' ) control = "#datatable";

	

	datatable_searching = typeof(datatable_searching) !== 'undefined' ? datatable_searching : false;

	if( $(control).length )  $(control).DataTable({

		//paging: false,

		searching: datatable_searching,

		bLengthChange: false,

		pageLength: 20

	});

}

	

function initDatatable2(){

	$("#datatable_alone").DataTable({

		paging: false,

		searching: false,

		bLengthChange: false

	});	

}





function open_select(elem) {

	if (document.createEvent) {

		var e = document.createEvent("MouseEvents");

		e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);

		elem[0].dispatchEvent(e);

	} else if (element.fireEvent) {

		elem[0].fireEvent("onmousedown");

	}

}



$(document).ready(function () {

	//setTimeout(function(){$('#country_list').trigger('change');},300);
	
	//center select box text start

	jQuery('.sleectText').click(function(e) {

		open_select($(this).parent('div').find('.select_center_text'));

	});

	jQuery('select.select_center_text').change(function(e) {

		var selectval = $(this).find("option:selected").text();

		target = jQuery(this).parent('div').find('.sleectText');

		if( target.is("button") ){

			target.html(selectval+' <i class="fa fa-tachometer" aria-hidden="true" style="font-size: 22px;padding-left: 10px;"></i>');

		}else{

			target.val(selectval);

		}



	});



	//center select box text ends





	initDatepicker();

	initTimepicker();

	initFullDatepicker();

	initDatatable();

	initDatatable2();

	

	$('.datetimepicker').datetimepicker({

		format: 'YYYY-MM-DD HH:mm:ss'

	});

	

	

	var min_height = $(window).height() - ($("body > .banner-img").height() + $("body > footer").height()) + 3;

	$("body > .content-container").css("min-height", min_height + "px");

	

	$(".nav.nav-tabs li").click(function(){

		setTimeout(function(){

			var min_height = $(window).height() - ($("body > .navbar").height() + $("body > footer").height()) + 3;

			$("body > .content-container").css("min-height",min_height + "px");

		}, 500);

	});

	

	

	$('input[type="reset"]').click(function(){

		form = $(this).parents("form");

		form.find("input[type='text'],select").val('');

		form.submit();

	});

	

	

	/*$(document).delegate("#meter_search",'keyup',function(){

		var val = $(this).val();

		var visible = 0;

		parent_meters = $(this).parents("#lot_meters");

		parent_meters.find(".no-records-row").hide();

		

		parent_meters.find(".meter_id").each(function(){

			this_meterid = $.trim($(this).html());

			if( this_meterid.indexOf(val) == 0 ){

				$(this).parent(".data-row").show();	

				visible = visible+1;

			}

			else{

				$(this).parent(".data-row").hide();	

			}

		});

		if( !visible ){

			parent_meters.find(".no-records-row").show();

		}

		

	});*/

	$(document).delegate("#meter_search",'keyup',function(){

		var val = $(this).val();

		var visible = 0;

		parent_meters = $(this).parents(".lot_meters").find("tbody");

		

		parent_meters.find("tr").each(function(){

			this_meterid = $.trim($(this).find(".meter_id").html());

			if( this_meterid.indexOf(val) == 0 ){

				$(this).show();	

				visible = visible+1;

			}

			else{

				$(this).hide();	

			}

		});

		if( !visible ){

			//parent_meters.find(".no-records-row").show();

		}

		

	});

	

	$(document).delegate("#meter_search_inspections",'click',function(e){

		return false;

	});

	

	$(document).delegate("#meter_search_inspections",'keyup',function(e){



		var val = $(this).val();

		var visible = 0;

		meters = $(this).parents("#datatable_alone").find("tbody");

		footer = $(this).parents("#datatable_alone").find("tfoot");

		

		footer.hide();

		

		meters.find("tr").each(function(){

			this_meterid = $.trim($(this).find("td:first").html());

			if( this_meterid.indexOf(val) == 0 ){

				$(this).show();	

				visible = visible+1;

			}

			else{

				$(this).hide();	

			}

		});

		if( !visible ){

			footer.show();

		}

		e.stopPropagation();

																	  

	});

	

	if( chartInitialized ) loadReport();

	$(document).delegate("a.load-report","click",function(){

		if( !chartInitialized ){ loadReport(); }											  

	});

	$("#submit_report").click(function(){

		loadReport();					   

	});

	

	if( typeof(initializeAffChart) !== 'undefined' && initializeAffChart ) loadAffChart(chart_data);



	$(document).delegate("a[href='#overview']","click",function(){

		if( initializeAffChart ){ loadAffChart(chart_data); }											  

	});

	

	$(document).delegate("#meter_count", "blur change", function () {

		var thVal = parseInt($(this).val());

		if( isNaN(thVal) || thVal <= 0 ) thVal = 1;

		if( thVal.toString().length > 3 ) thVal = thVal.toString().slice(0,3); 

		$(this).val(thVal);

		meter_price = (thVal*meter_base_price).toFixed(2);

		$(".newMeter-form #total_amount_paid .price").html("$" + meter_price);

		//date = $("#meter_1 input[name='expiry_date[]']").val();

		//add meter expiries

		/*$("#meter_1").siblings().remove();

		for( i=2; i<=thVal; i++ ){

			this_html = '<div id="meter_'+i+'"><label class="col-md-3 row control-label">Expiry Meter '+i+':</label><div class="col-md-10 row"><div class="col-md-5"><div class="input-group"><input type="text" name="expiry_date[]" class="form-control input-small datepicker" placeholder="Expiry Date" value="'+date+'"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div></div><div class="col-md-5"><div class="input-group"><input type="text" name="expiry_time[]" class="form-control input-small timepicker1" placeholder="Expiry Time"><span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span></div></div><div class="col-md-2 hours_count to-text">O Hrs</div></div></div>';

			$("#newMeterModal #meters_expiries").append(this_html);	

			initDatepicker();

			initTimepicker();

		}*/

		//calculate price

		//calculatePrice();

		

	});

	

	$(document).delegate("input[name='expiry_date[]'], input[name='expiry_time[]']", "change", function () {

		calculatePrice();   

	});

	

	$('#editLotModal').on('shown.bs.modal', function (e) {

		showLoader();

		var lotID = current_lot_id; //$("#current_lot_id").val();

		$.post(home_url+"/editGroupHTML",{lotID: lotID},function(data){

			$('#editLotModal .modal-body').html(data);

			$('.timepicker1').timepicker();

			hideLoader();

		});

	});

	

	

	$("#delteGroup").click(function(){

		$.post(home_url+"/deleteGroup",{lotID: current_lot_id},function(data){

			window.location.reload();

		});

	});

	

	$("#deleteMeter").click(function(){

		var meter_data = checkedMeters();

		if( meter_data[0] > 0 ){

			$.post(home_url+"/deleteMeter",{ meter_id: meter_data[1] },function(data){

				window.location.reload();

			});

		}else{

			alert("No meters selected.");	

		}

	});

	

	$(document).delegate("#proceed_to_checkout","click",function(){



		pp_form = $(".newMeter-form");

		

		if( pp_form.find("select[name='lot_id']").val() == '' ){

			pp_form.find("select[name='lot_id']").addClass("red-border");

			return false;

		}

		else{

			pp_form.find("select[name='lot_id']").removeClass("red-border");

		}



		if( pp_form.find("input[name='meter_count']").val() == '' ){

			pp_form.find("input[name='meter_count']").addClass("red-border");

			return false;

		}

		else{

			pp_form.find("input[name='meter_count']").removeClass("red-border");

		}

		

		if( $(".newMeter-form #paypal_form").is(":visible") ){



			if( pp_form.find("input[name='cc_number']").val() == '' ){

				pp_form.find("input[name='cc_number']").addClass("red-border");

				return false;

			}

			else{

				pp_form.find("input[name='cc_number']").removeClass("red-border");

			}

			

			if( pp_form.find("select[name='expiry_month']").val() == '' ){

				pp_form.find("select[name='expiry_month']").addClass("red-border");

				return false;

			}

			else{

				pp_form.find("select[name='expiry_month']").removeClass("red-border");

			}

			

			if( pp_form.find("select[name='expiry_year']").val() == '' ){

				pp_form.find("select[name='expiry_year']").addClass("red-border");

				return false;

			}

			else{

				pp_form.find("select[name='expiry_year']").removeClass("red-border");

			}

			

			$(".newMeter-form").submit();

		}else{

			 $(".newMeter-form #paypal_form").slideDown();

		}

	});

	

	$("#customer_pay_form").delegate("#pay_button","click",function(){

		//$("#customer_pay_form input[name='meter_id']").keyup();

		
		
		var validate_return = validate_meter();

		

		console.log("validate_return : "+validate_return);

		if( validate_return ){

			var price_return = calculate_price();

			

			console.log("price_return : "+price_return);

			

			if( validate_return + price_return == 0 ){

				$('html,body').animate( { scrollTop: $('input[name="meter_id"]').offset().top }, 'slow' );

				return false;	

			}

			

			

			pp_form = $("#customer_pay_form #paypal_form");

		

			if( pp_form.is(":visible") ){

				

				pp_form.find('input[required="required"],select[required="required"],.sleectText').removeClass("red-border");

				pp_form.find('input[required="required"],select[required="required"]').each(function(){

					if( $(this).val() == '' ){

						$(this).addClass("red-border");

						if( $(this).parent('div').find('.sleectText').length > 0 ){

							$(this).parent('div').find('.sleectText').addClass("red-border");

						}

						submit_user_pay_form = false;

					}

				});

				if( submit_user_pay_form ) {

					//$('input[name="cardType"]').removeAttr('disabled');

					$("#customer_pay_form").submit();

				}

				else{

					if( $('.red-border').length )

						$('html,body').animate( { scrollTop: $('.red-border:first-of-type').offset().top }, 'slow' );

				}

			}else{

				pp_form.show('slow');

				$('html,body').animate( { scrollTop: pp_form.offset().top }, 'slow' );

				

			}

		}

	});

	

	$("#customer_pay_form").delegate('input[name="meter_id"]',"blur",function(){



		validate_meter();

		/*var this_form = $("#customer_pay_form");

		var err_c = this_form.find(".meter_message");

		var err_span = this_form.find(".meter_message").find("span");

		meter_per_hour_price = 0;

		err_span.html("Stall # should be a 6-digit number.");

		thVal = $(this).val();



		if( isNaN(thVal) ){

			err_c.removeClass("alert-success").addClass("alert-danger").show();

			err_span.html("Stall # should be a 6-digit number.");

			$(this).addClass("red-border");

			console.log("added");



			submit_user_pay_form = false;

		}else{

			if( thVal.toString().length > 6 ) {

				thVal = thVal.toString().slice(0,6); 	

				$(this).val(thVal);

				submit_user_pay_form = true;

			}

			else if( thVal.toString().length < 6 ) {

				err_c.removeClass("alert-success").addClass("alert-danger").show();

				err_span.html("Stall # should be a 6-digit number.");

				submit_user_pay_form = false;



				$(this).addClass("red-border");

				console.log("added");

			}

			else{

				submit_user_pay_form = true;	

			}

		}

		var th = $(this);

		if (submit_user_pay_form){

			$.post(home_url+"/getCustMeters",{meter_id: $(this).val()},function(data){

				if(data['error'] != ""){

					err_c.removeClass("alert-success").addClass("alert-danger").show();

					err_span.html(data['error']);

					th.addClass("red-border");

					console.log("added");

				}else{

					meter_per_hour_price = data["price"];

					err_c.removeClass("alert-danger").addClass("alert-success").show();

					err_span.html(data['data']);	



					th.removeClass("red-border");

					console.log("removed");

				} 

				calculate_price($("#customer_pay_form select[name='expiry_time']"));

			});

		}

		else{

			calculate_price($("#customer_pay_form select[name='expiry_time']"));	

		}*/

	});

	

	$("input.aff-url").focus(function () { 

		$(this).select(); 

	}).mouseup(function (e) {e.preventDefault(); });

	

	$("#generate_signage_html").on("change",function(){

		var f = $("#signage_form");										

		lot_id = f.find('select[name="lot_id"]').val();

		lot_price = f.find('select[name="lot_id"] option:selected').data("price");

		meter_id = f.find('select[name="meter_id"]').val();

		$(".lot_print_price").html(lot_price);

		$(".meter_print_id").html(meter_id);

	});

	

	$(document).delegate("#check_meter_button","click",function(){

		var meter_id = $("#check_meter_id").val();		

		var html_c = $("#check-meter-result");

		var check = true;

		if( isNaN(meter_id) ){

			html_c.html("<div class='alert alert-danger'>Stall # should be a 6-digit number.</div>");

			check = false;

		}else{

			if( meter_id.toString().length > 6 ) {

				html_c.html("<div class='alert alert-danger'>Stall # should be a 6-digit number.</div>");

				check = false;

			}

			else if( meter_id.toString().length < 6 ) {

				html_c.html("<div class='alert alert-danger'>Stall # should be a 6-digit number.</div>");

				check = false;

			}

		}

		if(check){

			$.post(home_url+"/getMeterDetails",{meter_id: meter_id},function(data){

				html_c.html(data);

			});

		}

	});

	/*$('.groupbutton').click(function(event){

	   if($(this).parent().hasClass('selected'))

	   {

			event.stopPropagation();

			$(this).find(".dropdown-menu").toggle();

	   }

	 });

	

	 $(document).click(function(e) {

		if (!$(e.target).is('.groupbutton, .groupbutton *')) {

			$(".groupbutton .dropdown-menu").hide();

		}

	});*/



	$("#dropdown-button-container button, #dropdown-button-container .dropdown-menu > li").click(function(e){

		$("#dropdown-button-container .dropdown-menu").toggle();

		e.stopPropagation();

		e.preventDefault();

	});



	$(document).delegate("#country_list", "change", function(){

		var country_id = $(this).val();
		var country_iso = $(this).find('option:selected').attr('data-iso');

		$("#city_list").html('<option value="">Select City</option>');

		if( country_id > 0 ){

			$.post(home_url+"/get_states_by_country", { country_id:country_id }, function(response){

				$("#state_list").html(response);

			});
			$.post(home_url+"/get_payment_banks", { country_iso:country_iso }, function(response){
				$("#bank_list").html(response);
				hideLoader();

			});


		}else{

			$("#state_list").html('<option value="">Select State/Province</option>');

		}

	});

		



	$(document).delegate("#state_list", "change", function(){

		var country_id = $("#country_list").val();

		var state_id = $(this).val();

		if( state_id != '' ){

			$.post(home_url+"/get_cities_by_state", { country_id:country_id, state_id:state_id }, function(response){

				$("#city_list").html(response);

			});

		}else{

			$("#city_list").html('<option value="">Select City</option>');

		}

	});
	
	$(document).delegate("#country_list_sm", "change", function(){

		var country_id = $(this).val();

		$("#city_list_sm").html('<option value="">Select City</option>');

		if( country_id > 0 ){

			$.post(home_url+"/get_states_by_country", { country_id:country_id }, function(response){

				$("#state_list_sm").html(response);

			})

		}else{

			$("#state_list_sm").html('<option value="">Select State/Province</option>');

		}

	});

		



	$(document).delegate("#state_list_sm", "change", function(){

		var country_id = $("#country_list_sm").val();

		var state_id = $(this).val();

		if( state_id != '' ){

			$.post(home_url+"/get_cities_by_state", { country_id:country_id, state_id:state_id }, function(response){

				$("#city_list_sm").html(response);

			})

		}else{

			$("#city_list_sm").html('<option value="">Select City</option>');

		}

	});









	//payment details functions

	$("#add_payment_details").click(function(){

		showLoader();

		$("#payment_details_dialog").modal();

		$.post(home_url+"/add_payment_details", function(response){

			$("#payment_details_dialog .modal-body").html(response);

			initFullDatepicker();

			hideLoader();

		})

	});



	$(document).delegate("#CountryIsoCode","change",function(){

		var country = $(this).val();

		showLoader();

		$.post(home_url+"/get_payment_states", { country:country, selected_state:selected_state }, function(response){

			$("#StateId").html(response);

			$("#StateId").change();

		});



		$.post(home_url+"/get_payment_banks", { country:country, selected_bank:selected_bank }, function(response){

			$("#BankId").html(response);

			hideLoader();

		});



	});



	$(document).delegate("#StateId","change",function(){

		var country = $("#CountryIsoCode").val();

		var state = $(this).val();

		showLoader();

		$.post(home_url+"/get_payment_cities", { country:country, state:state, selected_city:selected_city }, function(response){

			$("#CityId").html(response);

			$("#CityId").change();

		})

	});



	$(document).delegate("#CityId","change",function(){

		var country = $("#CountryIsoCode").val();

		var state = $("#StateId").val();

		var city = $(this).val();

		showLoader();

		$.post(home_url+"/get_payment_towns", { country:country, state:state, city:city, selected_town:selected_town  }, function(response){

			if( $.trim(response) != '' ){

				$(".TownId_div").show();

				$("#TownId").html(response);

			}else{

				$(".TownId_div").hide();

			}

			hideLoader();

		});



		$.post(home_url+"/get_payment_modes", { country:country, city:city, selected_mode:selected_mode }, function(response){

			$("#PaymentModeId").html(response);

			$("#PaymentModeId").change();

			hideLoader();

		});



	});



	$(document).delegate("#PaymentModeId","change",function(){

		var country = $("#CountryIsoCode").val();

		var state = $("#StateId").val();

		var city = $("#CityId").val();

		var payment_mode = $(this).val();

		showLoader();

		$.post(home_url+"/get_payment_currency", { country:country, state:state, city:city, payment_mode:payment_mode, selected_Currency:selected_Currency }, function(response){

			$("#ReceiveCurrencyIsoCode").html(response);

			hideLoader();

		});

	});



	$(document).delegate("#enter_payment_details","click",function(){

		form = $("#payment_details_form");

		req_fields = form.find(".required");

		$error = 0;

		req_fields.each(function(){

			$(this).removeClass("red-border");

			if( $(this).val() == "" ){

				$(this).addClass("red-border");

				//$error++;

			}

		});

		if( !$error ){

			showLoader();

			$.post(home_url+"/save_payment_details", form.serialize(), function(response){

				$("#message_div_html").html(response);

				$('#message_div_html').focus();

				hideLoader();

			});

		}

	});





});



/*function calculatePrice(){

	all_meters = $("#meters_expiries > div");

	total_hours = 0;

	all_meters.each(function(){

		thisD = $(this).find('input[name="expiry_date[]"]').val();

		thisT = $(this).find('input[name="expiry_time[]"]').val();



		diff = new Date(thisD + " " +thisT) - new Date(server_date);

		

		var diffSeconds = diff/1000;

		var hours = Math.floor(diffSeconds/3600);

		var minutes = Math.floor(diffSeconds%3600)/60;

		

		if( minutes > 0 ) hours++;

		$(this).find(".hours_count").html(hours + " Hrs");

		

		total_hours = total_hours+hours;

	});

	price_per_hour = $(".lots-list .lot_"+current_lot_id).data("price");

	console.log( total_hours + "*" + price_per_hour + "+" + meter_price );

	total_price = (total_hours * price_per_hour)*1 + meter_price*1;

	$("#total_amount_paid .price").html("$"+ (total_price*1).toFixed(2));

}*/



function selectGroup(group_id){

	current_lot_id = group_id;

}



function validate_meter(){

	console.log("validate meter");

	var th = $("#customer_pay_form input[name='meter_id']");

	var this_form = $("#customer_pay_form");

	var err_c = this_form.find(".meter_message");

	var err_span = this_form.find(".meter_message").find("span");

	meter_per_hour_price = 0;

	err_span.html("Stall # should be a 6-digit number.");

	thVal = th.val();



	if( isNaN(thVal) ){

		err_c.removeClass("alert-success").addClass("alert-danger").show();

		err_span.html("Stall # should be a 6-digit number.");

		$(this).addClass("red-border");

		submit_user_pay_form = false;

	}else{

		if( thVal.toString().length > 6 ) {

			thVal = thVal.toString().slice(0,6); 	

			th.val(thVal);

			submit_user_pay_form = true;

		}

		else if( thVal.toString().length < 6 ) {

			err_c.removeClass("alert-success").addClass("alert-danger").show();

			err_span.html("Stall # should be a 6-digit number.");

			submit_user_pay_form = false;



			th.addClass("red-border");

		}

		else{

			submit_user_pay_form = true;	

		}

	}

	

	if (submit_user_pay_form){

		console.log("get meter details");

		$.post(home_url+"/getCustMeters",{meter_id: th.val()},function(data){

			console.log(data);

			if(data['error'] != ""){

				err_c.removeClass("alert-success").addClass("alert-danger").show();

				err_span.html(data['error']);

				th.addClass("red-border");

			}else{

				meter_per_hour_price = data["price"];

				err_c.removeClass("alert-danger").hide();

				//err_span.html(data['data']);	

				err_span.html('');



				th.removeClass("red-border");

			} 

			//calculate_price($("#customer_pay_form select[name='expiry_time']"));

			calculate_price();

		});

	}

	

	//check time selected

	if( $("#customer_pay_form select[name='expiry_time']").val() == '' ){

		$("#customer_pay_form select[name='expiry_time']").addClass("red-border")	

		submit_user_pay_form = false;

	}

	else{

		$("#customer_pay_form select[name='expiry_time']").removeClass("red-border")	

	}

	

	if (submit_user_pay_form){ return 1; } else{ return 0; }



}



function getsignagemeters(th){

	this_lot = $(th).val();

	this_form = $("#signage_form");

	$.post(home_url+"/getMeterOptons",{lotID: this_lot},function(data){

		this_form.find('.checkboxes-section').html(data);

		$("#generate_signage_html").trigger("change");

	});

}



function selectLot(th){

	lotID = $(th).val();

				

	var htm_control = $("#meters_html_section");

	

	var lot_name = $(th).find("option[value='"+lotID+"']").text(); /*$(th).find(".lot_name-html").html();*/

	var lot_price = $(th).find("option[value='"+lotID+"']").data("price"); /*$(th).data("price");*/

	

	$(".newMeter-form input[name='lot_id']").val(lotID);

	//$(".newMeter-form .lot_price_html").html("$ "+lot_price+" / hr");

	$(".newMeter-form .lot_name_html").html(lot_name);

	

	$("#editLotModal .modal-title").html(lot_name);

	current_lot_id = lotID;/*$("#current_lot_id").val(lotID);*/

	

	$("#deleteLotModal .modal-title .name").html(lot_name);

	

	showLoader();

	$.post(home_url+"/getMeters",{lotID: lotID},function(data){

		htm_control.html(data);

		$("#paypal_form").hide();

		hideLoader();

	});



}



function loadReport(){

	htm_control = $(".report_statistics")

	

	showLoader();

	$.post(home_url+"/report",$("#report_filter_form").serialize(),function(data){

		htm_control.html(data.contents);

		loadChart(data.chart_data);

		hideLoader();

	});

}



function showLoader(){

	$('.loaderimg').addClass('loadershow');

}

function hideLoader(){

	$('.loaderimg').removeClass('loadershow');

}





function changeGroup(th,id){

	$("#changeMeterGroup").modal("show");

	var control = $("#changeMeterGroup");

	var meter_data = checkedMeters();

	control.find('select[name="lot_id"]').val(current_lot_id);

	if( meter_data[0] == 0 || !meter_data[0] ){

		control.find('.modal-footer #changeGroupButton').hide();

		control.find('.modal-body').hide();

		control.find('.modal-error-body').show().html('Please select meter(s) to continue.');

	}

	else{

		control.find('.modal-body .meter-name').html(meter_data[2]);

		control.find('.modal-footer #changeGroupButton').show();

		control.find('.modal-body').show();

		control.find('.modal-error-body').hide();

	}

	control.find('input[name="meter_id"]').val(meter_data[1]);

}



function deleteMeter(th,id){

	$("#deleteMeterGroup").modal("show");

	var control = $("#deleteMeterGroup");

	var meter_data = checkedMeters();

	if( meter_data[0] == 0 || !meter_data[0] ){

		control.find("#deleteMeter").hide();

		control.find('.modal-body').hide();

		control.find('.modal-error-body').show().html('Please select meter(s) to continue.');

	}

	else{

		control.find("#deleteMeter").show();

		control.find('.modal-body').show();

		control.find('.modal-body .meter-name').html(meter_data[2]);

		//control.find(".modal-body").html("Are you sure you want to delete "+ ((meter_data[0]>1)? "these meters" : "this meter") + "?");

		//control.find(".modal-title").html("Delete Meter"+ ((meter_data[0]>1)? "s" : "") +":" + meter_data[2]);

		control.find('.modal-error-body').hide();

	}

}



function checkedMeters(){

	var checkedMeterIds = "";

	var checkedMeters = "";

	var meter_count = 0;

	$(".lot_meters input[name='selectedMeters']").each(function(){

		if( $(this).is(":checked") ){

			checkedMeterIds = (( checkedMeterIds == '' ) ? ($(this).val()) : (checkedMeterIds + "," + $(this).val())) ;	

			meter_id = $(this).parents("tr").find(".meter_id a").html();

			checkedMeters = (( checkedMeters == '' ) ? meter_id : (checkedMeters + "," + meter_id)) ;	

			meter_count++;

		}											

	});

	return [meter_count,checkedMeterIds,checkedMeters];

}



function loadChart(chart_data){

	ls = chart_data.label;

	dataset = chart_data.dataset ;



	if( ls.length <= 0 ){

		$("#canvas_container").html('<div class="alert alert-danger">There were no transactions for the selected period.</div>');

		return false;

	}





	$("#canvas_container").html('<canvas id="canvas" height="300" width="600"></canvas>');

	

	if(!$("#canvas").is(":visible")){ chartInitialized = false; }else{ chartInitialized = true; }

	

	var canvas = document.getElementById("canvas");

	var barChartData = {

		labels : ls,

		datasets : [

			{

				fillColor : "#95C55E",

				strokeColor : "rgba(220,220,220,0.8)",

				highlightFill: "rgba(220,220,220,0.75)",

				highlightStroke: "rgba(220,220,220,1)",

				data : dataset

			}

		]

	

	}

	var ctx = canvas.getContext("2d");

	window.myBar = new Chart(ctx).Bar(barChartData, {

		responsive : true,

		scaleGridLineColor : "#95C55E",

		barStrokeWidth : 1,

		scaleLabel : "<%= '$' + Number(value) %>",

		/*legendTemplate: "<ul class=\"<%%=name.toLowerCase()%>-legend\"><%% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%%=datasets[i].strokeColor%>\"></span><%%if(datasets[i].label){%><%%=datasets[i].label%> <strong><%%=datasets[i].value%></strong><%%}%></li><%%}%></ul>",*/

		tooltipTemplate: "<%= label %>: $ <%= Number(value) %>"

	});

}



function loadAffChart(chart_data){

	

	showLoader();

	$("#canvas_container").html('<canvas id="canvas" height="300" width="600"></canvas>');

	

	ls = chart_data.label;

	dataset = chart_data.dataset ;

	

	if( ls.length <= 0 ){

		$("#canvas_container").hide();

	}

	else{

		$("#canvas_container").show();

	}

	

	var canvas = document.getElementById("canvas");

	var barChartData = {

		labels : ls,

		datasets : [

			{

				fillColor : "#95C55E",

				strokeColor : "rgba(220,220,220,0.8)",

				highlightFill: "rgba(220,220,220,0.75)",

				highlightStroke: "rgba(220,220,220,1)",

				data : dataset

			}

		]

	

	}

	

	var ctx = canvas.getContext("2d");

	window.myBar = new Chart(ctx).Bar(barChartData, {

		responsive : true,

		scaleGridLineColor : "#95C55E",

		barStrokeWidth : 1,

		scaleLabel : "<%= '$' + Number(value) %>",

		tooltipTemplate: "<%= label %>: $ <%= Number(value) %>"

	});

	hideLoader();

}





function openModalLoader(){

	if( $("#createNewUserModal .modal-content").html() == '' )

		$("#createNewUserModal .modal-content").html( '<div class="text-center"><img src="../images/loading.gif" border=0></div>' );

}