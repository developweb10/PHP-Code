/*http://api.page2images.com/html2image   
 
Key: 7353149544040468



http://www.page2images.com/my_account/apikey



*/

jQuery(function ($) {
   /* $("#tabs").tabs({
        active: 0,
        disabled: [1, 2,3] 
    });*/
});
		 
	
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

		autoclose: true



	}).on('changeDate', function(e){

		$(this).datepicker('hide');

	});

	//alert($('.datepicker').val());

}



function initFullDatepicker(){

	$('.datepicker1').datepicker({

		format: "yyyy-mm-dd",

		setDate: current_date,

		autoclose: true,

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

	console.log("ex1 : "+$("#ex1").val());

	console.log("calculate price");

	console.log($('select[name=expiry_time]').length);
	if($('select[name=expiry_time]').length > 0){
		th = $("#customer_pay_form select[name='expiry_time']");

		hours = th.val();
	}else{
		var time_array = ["0",".5","1","1.5","2","2.5","3","4","5","6","7","8","9","10"];
		
		th = $("#ex1");
		time_array_index = th.val();
		hours = time_array[time_array_index];
		$('input[name=expiry_time]').val(hours);
	}



	console.log(hours);

	console.log("meter price before"+meter_per_hour_price);

	if( $("#customer_pay_form input[name='meter_id']").val() == '' ){

		meter_per_hour_price = 0;

	}

	

	console.log("meter price"+meter_per_hour_price);

	

	if( meter_per_hour_price > 0 && hours > 0 ){
		//alert($('select[name=expiry_time]').length);
		if($('select[name=expiry_time]').length > 0){
			$(".meter_price_message").html("<div class='alert alert-success text-center'>Total Amount: $"+ (hours*meter_per_hour_price).toFixed(2) + '</div>' ).show();	
		}else{
			if(hours == .5 || hours == 0.5){
				var booked_hr = "30 min";
			}else if(hours == 1){
				var booked_hr = "1 Hr.";
			}else{
				var booked_hr = hours+" Hrs.";
			}
			
			$(".meter_price_message").html("<div class='slide_label'>Time:"+' '+booked_hr+' '+"<br>$"+''+(hours*meter_per_hour_price).toFixed(2) + '</div>' ).show();
			//$(".meter_price_messages").html("<div style='font-size: 22px;color: #fff;font-weight: 500;text-align: center;margin-bottom:20px;'>$"+'&nbsp;'+(hours*meter_per_hour_price).toFixed(2) + '</div>' ).show();	
		}

		$(th).removeClass("red-border");

		console.log("calculated");

		return 1;

	}

	else{

		//$(".meter_price_message").html("").hide();	
		$('.meter_price_message').html("<div class='slide_label'> 	Time:  <br />      Slide the dial below        </div>");
		//alert($("#ex1").val());
		if($("#ex1").val() == 0){
			
		}
		
		console.log("not calculated");

		return 0;

	}

}


  

function initDatatable(control){
	if( typeof(control) == 'undefined' ) control = "#datatable";

	

	datatable_searching = typeof(datatable_searching) !== 'undefined' ? datatable_searching : false;
	
	if(control == "#order_datatable"){
		if( $(control).length )  $(control).DataTable({	
			searching: datatable_searching,	
			bLengthChange: false,	
			pageLength: 20,
			order : [[0,"desc"]]
	
		});
	}else{
		if( $(control).length )  $(control).DataTable({	 
			searching: datatable_searching,	
			bLengthChange: false,	
			pageLength: 20	,
			"fnDrawCallback": function(oSettings) {
				
				if (this.fnSettings().fnRecordsDisplay() < 21) {
					$('.dataTables_paginate').css('visibility','hidden');
				}
			}

			/*"fnDrawCallback":function(){
				alert( $('#datatable_paginate span span.paginate_button').size());
				alert(this.fnSettings().fnRecordsDisplay());
				if ( $('#datatable_paginate span span.paginate_button').size()) {
					$('#datatable_paginate')[0].style.display = "block";
				} else {
					$('#datatable_paginate')[0].style.display = "none";
				}
			}*/
			
		});
	}
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
var previous_city = "";
$( function() {
		
		if(($( "#cities" ).val() != "")){
			previous_city = $( "#cities" ).val();
		}
		var state_code = '';
		if($('#state_list').val() != ''){
			state_code = $('#state_list').val();
		}
		if($( "#cities" ).length > 0){
			$.post(home_url+"/auto_suggest_cities", { country_id:2 , state_code:state_code  }, function(response){
				//$("#activation_steps #state_list").html(response);
				$( "#cities" ).autocomplete({
				  source: response
				});
			})	
		}
		//alert("TEST -- "+response);
		/*var availableTags = [
		  "Perl",
		  "PHP",
		  "Python",
		  "Ruby",
		  "Scala",
		  "Scheme"
		];
		$( "#tags" ).autocomplete({
		  source: availableTags
		});*/
  } );
  

$(document).ready(function () {
	 $('.dropdown-menu li a').click(function(){
		 var tab_name = $(this).text();
		 $('.selected_detail').text(tab_name);
	 });
 
			
			
	//var min_height = $(window).height() - ($("body > .banner-img").height() + $("body > footer").height());
	//alert($(window).outerHeight()+" - "+$("body > footer").outerHeight()+20);
	var min_height = $(window).outerHeight() - ( $("body > footer").outerHeight()); // +20
	//alert($(window).outerHeight() + " - ("+$("body > footer").outerHeight()+") = "+min_height);
	$("body > .content-container").css("min-height", min_height + "px");

	

	$(".nav.nav-tabs li").click(function(){

		setTimeout(function(){

			var min_height = $(window).height() - ($("body > .navbar").height() + $("body > footer").height()) + 3;

			$("body > .content-container").css("min-height",min_height + "px");

		}, 500);

	});
	
	if($("#towing_datatable_filter").length > 0){
		$("#towing_datatable_filter input").addClass("form-control");
		$("#towing_datatable_filter").css("position","absolute");
		if($("#towing_datatable").parent('#towing_datatable_wrapper').prev('.alert').length > 0){
			$("#towing_datatable_filter").css("top","-127px");
		}else{
			$("#towing_datatable_filter").css("top","-57px");
		}
	
	}
	
	if(document.getElementsByTagName) {

		var inputElements = document.getElementsByTagName('input');
		
		for (i=0; inputElements[i]; i++) {
			console.log(inputElements[i].className.indexOf('disableAutoComplete'));
			if (inputElements[i].className && (inputElements[i].className.indexOf('disableAutoComplete') != -1)) {
		
				inputElements[i].setAttribute('autocomplete','off');
			
			}
			
		}
	
	}

	/* Get Payouts 
	$(document).on('click','button[name=export_payments]',function(e){
		//alert("TEST");
		jQuery.ajax({
			url:'payoutsemt',
			type: 'POST',
			success:function(data){
				alert(data);
			}
		});
	});
	*/
	
	/* get single record */
	
	$(document).on('click', '#getOrder', function(e){
		
		e.preventDefault();
		
		var order_id = $(this).attr('data-id');
		
		$('#dynamic-content').hide();
		$('#modal-loader').show();
		
		jQuery.ajax({
			url:'singleorder',
			type: 'POST',
			data:{
				order_id:order_id
			},
			success:function(data){
				$('#dynamic-content').hide();	
				$('#dynamic-content').show();
				$('#shipping_address').html(data[0].shipping_address);
				$('#shipping_meters').html(data[0].shipped_meters);
				if(typeof(data[0].referred_by) !== "undefined" && data[0].referred_by != 0){
					$('#shipping_reference').html(data[0].referred_by);
				}else{
					$('#shipping_reference').html("");
				}
				$('#shipping_city').html(data[0].shipping_city);
				$('#shipping_province').html(data[0].shipping_province);
				$('#shipping_zip').html(data[0].shipping_postal);
				/*$('#txt_position').html(data.position);
				$('#txt_office').html(data.office);*/
				$('#modal-loader').hide();
			}
		});
		
		/*$.ajax({
			url: 'singleOrder',
			type: 'POST',
			data: 'id='+uid,
			dataType: 'json'
		})
		.done(function(data){
			console.log(data);
			
			
			
		})
		.fail(function(){
			alert('Ajax Request Failed...');
		});*/
			
	});
	/* get single record */	
	
	/*$('#cities').keyup(function(e) {
		var state_code = '';
		if($('#state_list').val() != ''){
			state_code = $('#state_list').val();
		}
		alert(state_code);
		$.post(home_url+"/auto_suggest_cities", { country_id:2 , state_code:state_code  }, function(response){
			alert(response);
			//$("#activation_steps #state_list").html(response);
			$( "#cities" ).autocomplete({
			  source: response
			});
		})
    });*/
	$.post(home_url+"/get_states_by_country", { country_id:2  }, function(response){
		$("#activation_steps #state_list").html(response);
	})
	
	//jQuery('li.step2 , li.step3 , li.step4').addClass("disabled");
	
	jQuery('input[name=towing_contact]').blur(function(e) {
		format_contact(jQuery(this).val());
    });
	/*jQuery('input[name=accept_is_agreed]').on('click',function(){
		
		jQuery('.agree').val(1);
		
		jQuery('#termsDialog').modal( "toggle" );
	});*/
	jQuery('.owner_agreement').click(function(e) {
        //if(jQuery('input[name=is_agreed]:checked').prop('checked') ){
			jQuery.ajax({
				url:'home/agree_to_terms',
				data:{role:3},
				type: 'POST',
				success:function(data){
					//alert(data);
					var agree_terms_data = jQuery.parseJSON(data);
					var data_ = jQuery.parseJSON(agree_terms_data[0]['page_content']);
					$("#termsDialog_UI .modal-title").html(data_['page_title']);
					$("#termsDialog_UI .modal-body").html(data_['page_content']);
					$("#termsDialog_UI").modal("show");
				}
			});
		//}
    });
	
	function validate_steps(step_no){
		
		jQuery('#activation_steps #step'+step_no).find('input[type=text],input[type=checkbox],input[type=number],input[type=email],select').each(function(index, element) {
		   
		   if(jQuery('#activation_steps #step'+step_no).find('input[name=is_agreed]').length > 0 ){
				if(jQuery('input[name=is_agreed]:checked').prop('checked') ){
					
				}else{
					jQuery('input[name=is_agreed]').parent('div').addClass("red-border");
				}
			}
			
			if((jQuery('#activation_steps #step'+step_no).find('input[name="meter_id[]"]').length > 0) && (jQuery('#activation_steps #step'+step_no).find('input[name="all_meters"]').length > 0) && (jQuery('.replace_parking_sign').css("display") != "none" )) {
				
				if(jQuery('input[name="meter_id[]"]:checked').prop('checked') ){
					jQuery('.meter_error').text("");
					jQuery('input[name="meter_id[]"]').removeClass("red-border");
				}else{
					jQuery('.meter_error').text("(Please select atleast one option)");
					jQuery('input[name="meter_id[]"]').addClass("red-border");
				}
			}
		   var field_value = jQuery(this).val();
		   
           if(jQuery(this).attr("name") == "towing_companies"){
				//jQuery('input[name=towing_contact]').val(field_value);
		   }
		   if(jQuery(this).attr("name") != "promo_code" && jQuery(this).attr("name") != "meter_id[]" && jQuery(this).attr("name") != "all_meters"){
				//console.log("value is -- "+field_value);
				if(jQuery('.new_parking_sign').css("display") != "none" )
				{
					
					if(field_value == '' || field_value == '0'){	
						console.log("REd border added");
						jQuery(this).addClass("red-border");
					}else{
						jQuery(this).removeClass("red-border");
					}
					//console.log(jQuery(this).html());
					if(jQuery(this).attr("name") == "confirm_email" || jQuery(this).attr("name") == "email" ){
						//console.log(jQuery(this).attr("name"));
						//console.log("c_email "+jQuery('input[name=confirm_email]').val());
						//console.log("email "+jQuery('#activation_steps input[name=email]').val());
						var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
						
						if(jQuery('input[name=confirm_email]').val() != '' && jQuery('#activation_steps input[name=email]').val() != ''){
							if((jQuery('input[name=confirm_email]').val() != jQuery('#activation_steps input[name=email]').val())  || (!filter.test(jQuery('input[name=confirm_email]').val()) && !filter.test(jQuery('input[name=email]').val()))){
								jQuery('input[name=confirm_email]').addClass("red-border");
								jQuery('#activation_steps input[name=email]').addClass("red-border");
							}
						
						}
					}
				}
		   }else{}
        });
		//alert(jQuery('#step'+step_no).find(".red-border").length);
		if(jQuery('#activation_steps #step'+step_no).find(".red-border").length == 0){
			
			var next_step = parseInt(step_no)+1;
			
			if(next_step != 4){
				jQuery('.tabbale .step'+next_step).removeClass("ui-state-disabled");
				
				jQuery('.tabbale .step'+next_step).find('a').attr("data-toggle","tab");

				jQuery('.tabbale .step'+next_step).find('a').trigger('click');
			}else{
				
			}
		}
		
	}
	jQuery("input[name=promo_code]").keyup(function(e) {
       
		var promo_code = jQuery.trim(jQuery(this).val());
		
		console.log("promo_code : "+promo_code+" LEngth : "+promo_code.length);
		if((promo_code != "") && (promo_code.length == 4)){
		   jQuery.ajax({
				url:'home/validate_promo_code_landowner_UI',
				data:{promo_code:promo_code,section:'landowner_UI'},
				type: 'POST',
				success:function(data){
					
					var data_array = jQuery.parseJSON(data);
					var meter_count = jQuery('#parking_meter_count').val();
					
					if(data_array.error == null){
					   $('.payable_amount').text("");
					   $('#loading_img_total').show();
					   if(meter_count == 1){
						   $('.meter_count').text(meter_count+" Parking Sign");
					   }else{
					   	   $('.meter_count').text(meter_count+" Parking Signs");
					   }
					   $('.meter_count').next("span.bold_text").text(" (x $"+config_meter_base_price+")");
					   $.post(home_url+"/update_order", { changed_by:'promo_code' , meter_count:meter_count }, function(response){
							$('#loading_img_total').hide();
							/*$('.sub_count').text("$"+response['sub_total']);*/
							$('.payable_amount').text("$"+response['amount']); 
							$('.sub_count').next("span.bold_text").text(" (-"+promo_code_discount+"%)");
							$('input[name=payable_amount]').val(response['amount']);
							
					   })
					   
						jQuery('input[name=refered_by]').val(data_array.referred_by);
						jQuery('input[name=commission]').val(data_array.commission);
						jQuery('input[name=promo_code]').removeClass('red-border');
					}else{
					   $('.payable_amount').text("");
					   $('#loading_img_total').show();
					   if(meter_count == 1){
						   $('.meter_count').text(meter_count+" Parking Sign");
					   }else{
					   	   $('.meter_count').text(meter_count+" Parking Signs");
					   }
					   
					   $('.sub_count').next("span.bold_text").text("");
					   
					   $.post(home_url+"/update_order", { changed_by:'meter_count' , meter_count:meter_count }, function(response){
							$('#loading_img_total').hide();
							/*$('.sub_count').text("$"+response['sub_total']);*/
							$('.payable_amount').text("$"+response['amount']); 
							$('input[name=payable_amount]').val(response['amount']);
							
					   })
						jQuery('input[name=promo_code]').addClass('red-border');
						
					}
				}
			});
			
		}
		else if(promo_code == "" || promo_code.length < 4 || promo_code.length > 4){
				jQuery('input[name=promo_code]').addClass('red-border');
				var meter_count = jQuery('#parking_meter_count').val();
			   $('.payable_amount').text("");
			   $('#loading_img_total').show();
			   if(meter_count == 1){
				   $('.meter_count').text(meter_count+" Parking Sign");
			   }else{
				   $('.meter_count').text(meter_count+" Parking Signs");
			   }
			   $('.sub_count').next("span.bold_text").text("");
			   
			   $.post(home_url+"/update_order", { changed_by:'meter_count' , meter_count:meter_count }, function(response){
					$('#loading_img_total').hide();
					$('.sub_count').text("$"+response['sub_total']);
					$('.payable_amount').text("$"+response['amount']); 
					$('input[name=payable_amount]').val(response['amount']);
					
			   })
			   
		}
		});
	jQuery(document).delegate('#activation_steps input ,#activation_steps  select','change blur', function () { 
		
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if(jQuery(this).attr("name") == "confirm_email" || jQuery(this).attr("name") == "email" ){
			if(jQuery('input[name=confirm_email]').val() != '' && jQuery('#activation_steps input[name=email]').val() != ''){
				if((jQuery('input[name=confirm_email]').val() != jQuery('#activation_steps input[name=email]').val())  || (!filter.test(jQuery('input[name=confirm_email]').val()) && !filter.test(jQuery('input[name=email]').val()))){
					jQuery('input[name=confirm_email]').addClass("red-border");
					jQuery('#activation_steps input[name=email]').addClass("red-border");
					var current_step = jQuery(this).parents('.tab-pane').attr("id");
					var current_step_no = current_step.replace("step","");
					var next_step = parseInt(current_step_no)+1;
					for(var i = next_step; i<=4;i++){
						jQuery('li.step'+i).addClass("ui-state-disabled");
						jQuery('li.step'+i).find('a').removeAttr("data-toggle");
					}
				}else{
					jQuery('input[name=confirm_email]').removeClass("red-border");
					jQuery('#activation_steps input[name=email]').removeClass("red-border");
				}
			
			}
		}
		else if(jQuery(this).attr("name") == "promo_code"){}

		else if(jQuery(this).attr("name") == "is_agreed" || jQuery(this).attr("name") == "meter_id[]"){
			if(jQuery(this).attr("name") == "meter_id[]"){
				if(jQuery('input[name="meter_id[]"]:checked').prop('checked') ){
					jQuery('.meter_error').text("");
					jQuery('input[name="meter_id[]"]').removeClass("red-border");
				}else{
					jQuery('.meter_error').text("(Please select atleast one option)");
					jQuery('input[name="meter_id[]"]').addClass("red-border");
				}
			}else{

				if(jQuery('input[name=is_agreed]:checked').prop('checked') ){
					jQuery('input[name=is_agreed]').parent('div').removeClass("red-border");
				}else{
					jQuery('input[name=is_agreed]').parent('div').addClass("red-border");
				}
			}
			
			
		}
		else {
			if(jQuery(this).attr("name") != "all_meters"){
				if(jQuery(this).val() == ""){
					
					jQuery(this).addClass("red-border");
					var current_step = jQuery(this).parents('.tab-pane').attr("id");
					var current_step_no = current_step.replace("step","");
					var next_step = parseInt(current_step_no)+1;
					for(var i = next_step; i<=4;i++){
						jQuery('li.step'+i).addClass("ui-state-disabled");
						jQuery('li.step'+i).find('a').removeAttr("data-toggle");
					}
				}else{
					//console.log("Remove boredr");
					jQuery(this).removeClass("red-border");
				}
			}
		}
	});
	/*Switch the tabs of modal Window shown when Landowner firstly access the account*/
	var $go_next=1;
	jQuery('.btnNext , input[name=proceed_checkout]').click(function(){ 
	//alert("btn clk");
		var step_no = jQuery(this).attr("data-id");
		
		validate_steps(step_no);
		$go_next=1;
		
	});
	jQuery('.btnBack').click(function(){ 
		jQuery('.steps_list.nav-tabs > .active').prev('li').find('a').trigger('click');
	});

	/* Print Hour Rate on sign graphics
	function print_hours(Hourly_rate){
		if(Hourly_rate != '' && Hourly_rate != '0.00') 
		{
			Hourly_rate = Hourly_rate.split('.');
			jQuery('.lg_text').text(Hourly_rate[0]);
			jQuery('.md_text').text(Hourly_rate[1]); 
			jQuery('.dot').text('.');
		}
		else
		{
			jQuery('.lg_text').text('');
			jQuery('.md_text').text(''); 
			jQuery('.dot').text('');
		}
	}*/
	jQuery('.step2,.step3,.step4').click(function(e) {
		if($("#order_type").val() != 0){ 
			var Hourly_rate = jQuery('select[name=price]').val();
			//print_hours(Hourly_rate);
		}
		
    });
	
	/* Format Towing Contact Number */
	function format_contact(contact_no){
		contact_no=contact_no.replace(/\(|\)|-| |#|_/g,'');  
		var contact_len = contact_no.length;
		contact_no = contact_no.substring(0, 3) + "-"  +contact_no.substring(3, 6) + "-" + contact_no.substring(6, contact_len);
		
		//alert("length : "+jQuery('.contact_no').length+" Contact No "+contact_no);
		if(jQuery('.contact_no').length > 0)
		{
			jQuery('.contact_no').text(contact_no);
		}  
		if(jQuery('input[name=towing_contact]').length > 0){
			jQuery('input[name=towing_contact]').val(contact_no);
			//alert(jQuery('input[name=towing_contact]').val());
		}
	}
	function addCommas(x) {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	}
	
	function update_amount(meter_count){
		
		if(meter_count == 1){
			$('.meter_count').text(meter_count+" Parking Sign");
		}else{
	    	$('.meter_count').text(meter_count+" Parking Signs");
		}
	    if(jQuery('input[name=promo_code]').val().trim() != ""){
		   var changed_by = "promo_code";
	    }else{
		   var changed_by = "meter_count";
	    }
	    $.post(home_url+"/update_order", { changed_by:changed_by , meter_count:meter_count }, function(response){
		  
			$('.sub_count').text("$"+response['sub_total']);
			$('.payable_amount').text("$"+response['amount']); 
			$('.meter_count').next("span.bold_text").text(" (x $"+config_meter_base_price+")");
			$('input[name=payable_amount]').val(response['amount']);
			
	    })
		   
	    
		   
	}
	function monthly_earning(meter_count){
		
		var hourly_rate = jQuery('select[name=price]').val();
	   
	    if(meter_count != '' && hourly_rate != '')
	    {
		   var expected_monthly_amount = ((meter_count * hourly_rate) - (30 * meter_count * hourly_rate)/100) * 8 *22;
		 
		   var monthly_amount = addCommas(expected_monthly_amount.toFixed(2));
		  
			if(meter_count == 1){
				var text = " meter ";
			}else{
				var text = " meters ";
			}
		   var calculation_desc = meter_count+text+'x $'+hourly_rate+'/hr x 8 hrs/day x 22 days/month (-30% Service Fee)';
		   jQuery('.calculation_desc').html(calculation_desc);
		   //jQuery('.calculation_desc').parent('div').css("min-height","186px");
		   /*jQuery('.calculation_desc').css("margin-bottom","70px");
		   jQuery('.calculation_desc').css("float","left");*/
		   if(jQuery('.expected_amount').length > 0)
		   {
			   jQuery('.expected_amount').text(monthly_amount);
		   }
	    }
	    if(hourly_rate != '')
	    {
	    	//print_hours(hourly_rate);
			
	    }
	}
	jQuery('#parking_meter_count ').change(function(e) { 
		
		var parking_count = $(this).val();
		
		/* Update the calculations */
		update_amount(parking_count);
		
		/* Update Estimated monthly Earnings */
		monthly_earning(parking_count);
        
    });
	jQuery('select[name=price] ').change(function(e) { 
		
		var parking_count = $('#parking_meter_count ').val();
		/* Update Estimated monthly Earnings */
		monthly_earning(parking_count);
        
    });
	jQuery('input[name="meter_id[]"]').change(function(e) { 
		/* print the hour rate of first selected meter on sign graphics */
		$("#landowner_meter_ids tr").each(function(index, element) {
			if($(this).find('input[type=checkbox]').prop('checked')){
				var hour_rate = $(this).find('input[type=checkbox]').attr("data-price");
				var meter_id = $(this).find('input[type=checkbox]').val();
				//print_hours(hour_rate);
				console.log(meter_id);
				$.post(home_url+"/order_towing_details", { meter_id:meter_id }, function(response){
					if(response == ""){
					}else{
						console.log(response[0]["towing_company"]);
						$("#cities").val(response[0]["shipping_city"]);
						$("#cities").blur();
						/* IF company name does not exists in order details that means there is no company located in shipped city so user manually entered contact number*/
						$('input[name=towing_contact]').val(response[0]["towing_contact_no"]);
						$('input[name=address]').val(response[0]["shipping_address"]);
						$('#user_states').val(response[0]["shipping_province"]);
						$('input[name=postal_code]').val(response[0]["shipping_postal"]);
						jQuery('.contact_no').text(response[0]["towing_contact_no"]);
						setTimeout(function(){
							jQuery('select[name=towing_companies]').val(response[0]["towing_company"]);
						
						},700);
						
						$("#cities").val(response[0]["shipping_city"]);
					}
	
				});
				return false; 
			}else{
				//print_hours('2.00');
			}
		});

		


		/* If all meters are selected then make the "All" option to be selected and vice  versa */
		var uncheck_all = 0;
		if($(this).prop('checked')){
			$("#landowner_meter_ids tr").each(function(index, element) {
				if($(this).find('input[type=checkbox]').prop('checked')){
					


				}else{
					uncheck_all= uncheck_all+1;
				}
			});
			if(uncheck_all > 0){
				$('input#all_meters').prop('checked',false);
			}else{
				$('input#all_meters').prop('checked',true);
			}
		}else{
			$('input#all_meters').prop('checked',false);
		}
		
		/* Update the calculations */
		var parking_count = $("#landowner_meter_ids tr td").find('input[type=checkbox]:checked').length;
		update_amount(parking_count);
		
	});
	$('input#all_meters').click(function() {
		$("#landowner_meter_ids tr").each(function(index, element) {
			if($('input#all_meters').prop('checked')){
				$("#landowner_meter_ids tr td").find('input[type=checkbox]').prop('checked',true);
				
				/*If All meters are selected then pick the price of first checkbox */
				var hour_rate = $("#landowner_meter_ids tr:first td").find('input[type=checkbox]').attr("data-price");
				//print_hours(hour_rate);
			
			}else{
				$("#landowner_meter_ids tr td").find('input[type=checkbox]').prop('checked',false);
				/*If No meter is selected then put default price */
				var hour_rate = $("#landowner_meter_ids tr:first td").find('input[type=checkbox]').attr("data-price");
				//print_hours("2.00");
			}
		});
		
		/* Update the calculations */
		var parking_count = $("#landowner_meter_ids tr td").find('input[type=checkbox]:checked').length;
		update_amount(parking_count);
		
	});
	/*$("#landowner_meter_ids tr td input[type=checkbox]").click(function() {
		
	});*/
	
	/*Whether the landowner wants to buy new parking signs or replace the existing*/
	$('.select_parking').click( function(){
		
		var parking_order = $(this).attr('data-type'); //alert(parking_order);
		if(parking_order =='replace_parking'){
			/* 0 Represents Replacement */
			$("#order_type").val(0); 
			$('.new_parking_sign').find('input , select').each(function(index, element) {
	            $(this).removeClass("red-border");
	        });
			$('.replace_parking_sign').show();
			$('.new_parking_sign').hide();
		}
		
		if(parking_order =='new_parking'){
			/* 1 Represents Purchasing */
			$("#order_type").val(1); 
			$('.replace_parking_sign').find('input , select').each(function(index, element) {
				
				
	            $(this).removeClass("red-border");
				
	        });
			$('.replace_parking_sign').hide();
			$('.new_parking_sign').show();
			//$('.landowner_new_parking .tab-pane.exist_parking').hide();
		}
		
		$('.landowner_new_parking').show();
		$('.landowner_park_view').hide();
		//$('.landowner_new_parking input#'+parking_order).val('1');
		$('.landowner_new_parking .nav-tabs li.new_parking').addClass('active');
		$('.landowner_new_parking .tab-pane.new_parking').addClass('active');

		/* Reset the selected meters if user switches the case */
		$("#parking_meter_count").val(1);
		$("select[name=price]").val("2.00");
		$("#all_meters").prop('checked',false);
		$("#landowner_meter_ids tr").each(function(index, element) {
			if($("#landowner_meter_ids tr td").find('input[type=checkbox]').prop('checked')){
				$("#landowner_meter_ids tr td").find('input[type=checkbox]').prop('checked',false);
			}
		});
		/* Reset the estimated monthly calculations */
		monthly_earning(1);

		/* Reset the account details if user switches the case */
		return update_amount(1);

	}); 

	function TowingCompanies(city){
		
		/*jQuery( ".towing_companies" ).fadeOut( 1000, function() {
	   		jQuery("#loading_img_landownwer").fadeIn();
	  	});
//		*/
		jQuery(".towing_companies").fadeOut();
		/*jQuery("#loading_img_landownwer").show();*/
		jQuery(".company_contact_number").fadeOut();
		jQuery("input[name=towing_contact]").val('');
		jQuery('.contact_no green_text').text('');
		
		jQuery.ajax({
			url:'home/TowingCompany',
			data:{city:city},
			type: 'POST',
			success:function(data){ 
				//jQuery("#loading_img_landownwer").css('border','1px solid red');
				if(data == ""){
					var towing_html = '';
					jQuery('.company_contact_number').show();
					jQuery("input[name=towing_contact]").val('');
					jQuery('.contact_no green_text').text('');
				}else{
					var towing_html = "<select name='towing_companies' class='form-control' required> <option value=''> Select Towing Service </option> "; 
					jQuery.each(data, function(index, value) {
						
						towing_html = towing_html + "<option value="+value["id"]+">"+value["company"]+"</option>";
					});
					towing_html = towing_html + "</select>";
				}

				//var towing_html = "<label class='control-label'>Towing Company</label><select name='towing_companies' class='form-control' required> "; 
				/*console.log("test"+data);
				var count_companies = data.length;
				if(count_companies > 0){
					jQuery('.company_contact_number').hide();
					jQuery("input[name=towing_contact]").val('');
					jQuery('.contact_no green_text').text('');
					var towing_html = "<select name='towing_companies' class='form-control' required> <option value=''> Select Towing Service </option> "; 
				
					jQuery.each(data, function(index, value) {
						var contactNO = value['contact'];
						contactNO = contactNO.replace('(', "");
						contactNO = contactNO.replace(')', "");
						contactNO = contactNO.replace(' ', "");
						//alert(contactNO);
						towing_html = towing_html + "<option value="+contactNO+">"+value["company"]+"</option>";
						//alert(index + ': '+value["company"] );
						//alert(index + ': '+value["contact"] );
					});
					towing_html = towing_html + "</select>";
				}
				else
				{
					var towing_html = '';
					jQuery('.company_contact_number').show();
					jQuery("input[name=towing_contact]").val('');
					jQuery('.contact_no green_text').text('');
				}*/
				
				/*jQuery( ".towing_companies" ).fadeOut( 1000, function() {
			   		jQuery("#loading_img_landownwer").fadeIn();
			  	});*/
				
				jQuery('.towing_companies').html(towing_html); 
				jQuery('.towing_companies').fadeIn();
			}
			
		  });
    	 
	}
	
	jQuery('input[name=city]').on( "autocompleteselect", function( event, ui ) {
		jQuery('input[name=city]').val(ui.item.value);
		
		jQuery('input[name=city]').blur();
		event.preventDefault();
	} );
	
	jQuery('input[name=city]').blur(function(e) {
		//alert("city blured");
		var city = jQuery('input[name=city]').val(); 
		//alert("city : "+city+" previous_city "+previous_city); 
		if(previous_city != city){
			TowingCompanies(city);
			previous_city = city; 
		}
		
		e.preventDefault();
	});
	
	
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
	if(jQuery("#order_datatable").length > 0){
		initDatatable("#order_datatable");	
	}
	if(jQuery("#payout_history_datatable").length > 0){
		initDatatable("#payout_history_datatable");	
	}
	
	
	initDatatable2();

	

	$('.datetimepicker').datetimepicker({

		format: 'YYYY-MM-DD HH:mm:ss'

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
		var client_id = "";
		
		if($("#deleteMeterGroup").find("input[name=client_id]").length){
			var client_id = $("#deleteMeterGroup").find("input[name=client_id]").val();
			
		}

		if( meter_data[0] > 0 ){

			$.post(home_url+"/deleteMeter",{ meter_id: meter_data[1] , client_id : client_id },function(data){

				window.location.reload();

			});

		}else{

			alert("No meters selected.");	

		}

	});

	

	$(document).delegate("#proceed_to_checkout","click",function(){
		
		var error = 0;
		if($(".newMeter-form").length > 0){
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
			
		}else if($("#activation_steps").length > 0){/*
			pp_form = $("#activation_steps");
			if( pp_form.find("input[name='parking_meter_count']").val() == '' ){

				pp_form.find("input[name='parking_meter_count']").addClass("red-border");
				
                $("li.step4").removeClass("active"); //Remove any "active" class	
				$("div#step4").removeClass("active");
				
                //$("li.step4 a").attr("aria-expanded",false);	
        		$("li.step1").addClass("active"); //Add "active" class to selected tab
				$("div#step1").addClass("active");
				
				//$("li.step1 a").attr("aria-expanded",true);
				return false;
				error = 1;
	
			}
	
			else{
	
				pp_form.find("input[name='parking_meter_count']").removeClass("red-border");
				error = 0;
	
			}
			
			if( pp_form.find("select[name='price']").val() == '' ){ 
				
				pp_form.find("select[name='price']").addClass("red-border");
				
                $("li.step4").removeClass("active"); //Remove any "active" class	
				$("div#step4").removeClass("active");
				
                //$("li.step4 a").attr("aria-expanded",false);	
        		$("li.step1").addClass("active"); //Add "active" class to selected tab
				$("div#step1").addClass("active");
				
				//$("li.step1 a").attr("aria-expanded",true);
				return false;
				error = 1;
			}
			else{
	
				pp_form.find("select[name='price']").removeClass("red-border");
				error = 0;
	
			}
		*/}
		else{
		}
		
		if( $(".newMeter-form #paypal_form").is(":visible")  )//||  $("#activation_steps #paypal_form").is(":visible") 
		{

			//alert("YS");

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
			if($("#activation_steps").length > 0){
				$("#activation_steps").submit();
			}

		}else{

			 $(".newMeter-form #paypal_form").slideDown();

		}

	});

	$("#sample_pay_button").click(function(e) {
		
		var th = $("#sample_customer_pay_form input[name='meter_id']");

		var this_form = $("#sample_customer_pay_form");
	
		var err_c = this_form.find(".sample_meter_message");
	
		var err_span = this_form.find(".sample_meter_message").find("span");
	
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
		if( $("#sample_customer_pay_form select[name='expiry_time']").val() == '' ){

			$("#sample_customer_pay_form select[name='expiry_time']").addClass("red-border")	

				submit_user_pay_form = false;

			}

		else{

			$("#sample_customer_pay_form select[name='expiry_time']").removeClass("red-border")	

		}


		if(submit_user_pay_form == true)
		{
			$("#sample_customer_pay_form").submit();	
		}
	
	});
	
	
	$("#activation_steps").delegate("#ld_pay_button","click",function(){
		/*validate 4 step activation*/
	});
	
	$("#customer_pay_form").delegate("#pay_button","click",function(){
		//alert("TESt");
		//$("#customer_pay_form input[name='meter_id']").keyup();

		var validate_return = 1;

		console.log("validate_return : "+validate_return);

		if( validate_return ){

			var price_return = calculate_price();
			//alert(price_return);
			if(price_return == 0 && $('#ex1').length>0){
				$('html,body').animate( { scrollTop: $('#ex1').offset().top }, 'slow' );
				return false;
			}/*else if(price_return == 0 && $('select[name=expiry_time]').length>0){
				return false;
			}*/
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
				//alert("submit_user_pay_form : "+submit_user_pay_form);
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
	$("#customer_pay_form").delegate('input[name="meter_id"]',"keyup",function(){
		var th = $("#customer_pay_form input[name='meter_id']");

		var this_form = $("#customer_pay_form");

		var err_c = this_form.find(".meter_message");

		var err_span = this_form.find(".meter_message").find("span");

		meter_per_hour_price = 0;

		err_span.html("Stall # should be a 6-digit number.");

		thVal = th.val();
		
		if(thVal.toString().length > 6){
			
			if($('.customer_pay_form_mobile_view').length > 0){
				$('.slide_pay_form').fadeOut();
				$('.info_text').fadeIn();
			}
		

			err_c.removeClass("alert-success").addClass("alert-danger").show();

			err_span.html("Stall # should be a 6-digit number.");

			th.addClass("red-border");

			
		}else if(thVal.toString().length < 6){
			if($('.customer_pay_form_mobile_view').length > 0){
				$('.slide_pay_form').fadeOut();
				$('.info_text').fadeIn();
			}
		}
		
		if($('.customer_pay_form_mobile_view').length > 0 && thVal.toString().length == 6){
			validate_meter();
			
		}
	});
	
	/*$("#customer_pay_form").delegate('input[name="meter_id"]',"keyup",function(){
		
		
		if($('#ex1').val() != 0){
			$('#ex1').val(0);
			
			//calculate_price();
		}
	});*/
	/*
	
	$("#customer_pay_form").delegate('input[name="meter_id"]',"blur",function(){

		validate_meter();

		var this_form = $("#customer_pay_form");

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

		}

	});
*/
	

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

	
	



	$(document).delegate("#state_list , #user_states", "change", function(){ 
		/* On change of state reset the previous city */
		previous_city = "";
		//alert($(this).parents('form').find('#cities.ui-autocomplete-input').length);
		if($(this).parents('form').find('#cities.ui-autocomplete-input').length > 0){

			$( "#cities" ).val('');
			var state_code = '';
			if($("#user_states").length > 0){
				if($('#user_states').val() != ''){
					state_code = $('#user_states').val();
				}
			}else{
				if($(this).parents('form').find('#state_list').val() != ''){
					state_code = $(this).parents('form').find('#state_list').val();
				}
			}
			//alert($("#user_states").length+" -- "+state_code);
			$.post(home_url+"/auto_suggest_cities", { country_id:2 , state_code:state_code  }, function(response){
				//alert(response);
				//$("#activation_steps #state_list").html(response);
				$( "#cities" ).autocomplete({
				  source: response
				});
			})
			
		}else{
			var country_id = $("#country_list").val();
				//alert(country_id);
		
			var state_id = $(this).val();
			//alert(state_id);
			if( state_id != '' ){
	
				$.post(home_url+"/get_cities_by_state", { country_id:country_id, state_id:state_id }, function(response){
	
					$("#city_list").html(response);
	
				});
	
			}else{
	
				$("#city_list").html('<option value="">Select City</option>');
	
			}
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

		
	$(document).delegate("#city_list", "change", function(){
		var city_id = $(this).val();
		//alert(city_id);
		jQuery.ajax({
			url:'../home/TowingCompany',
			data:{city_id:city_id},
			type: 'POST',
			success:function(data){ 
				//alert(data);
				if(data == ""){
					var towing_html = '';
					$('select[name=towing_companies]').addClass('hide_it');
					jQuery('.company_contact_number').removeClass("hide_it");
					jQuery("input[name=towing_contact]").val('');
					//jQuery('.contact_no green_text').text('');
				}else{
					var towing_html = ""; 
					jQuery.each(data, function(index, value) {
						
						towing_html = towing_html + "<option value="+value["id"]+">"+value["company"]+"</option>";
					});
					$('select[name=towing_companies]').html(towing_html);
				}
			}
		});
		
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
	
	/* To be removed for now */
	/*$("#add_payment_details").click(function(){

		showLoader();

		$("#payment_details_dialog").modal();

		$.post(home_url+"/add_payment_details", function(response){

			$("#payment_details_dialog .modal-body").html(response);

			initFullDatepicker();

			hideLoader();

		})

	});*/



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

	/* To be removed for now */
	/*
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

	*/
	jQuery('.towing_companies').on( "change", "select[name=towing_companies]", function() {
		var id = jQuery(this).val();
		//alert("you cahnaged towing company ");
		if($("#admin_meter_section").length > 0 ){
			var url = '../home/TowingCompany';
		}else{
			var url = 'home/TowingCompany';
		}
		jQuery.ajax({
			url:url,
			data:{id:id},
			type: 'POST',
			success:function(data){ 
				var contact_no = data[0]["contact_no"];
				format_contact(contact_no);
			}
		});
		
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

	function test1(){
	alert("M test1 function");
} 

function validate_meter(){
	
	//alert("validate meter");

	var th = $("#customer_pay_form input[name='meter_id']");  

	var this_form = $("#customer_pay_form");

	var err_c = this_form.find(".meter_message");

	var err_span = this_form.find(".meter_message").find("span");

	meter_per_hour_price = 0;

	err_span.html("Stall # should be a 6-digit number.");

	thVal = th.val();

	//alert(thVal);

	if( isNaN(thVal) ){

		err_c.removeClass("alert-success").addClass("alert-danger").show();

		err_span.html("Stall # should be a 6-digit number.");

		$(this).addClass("red-border");

		submit_user_pay_form = false;

	}else{

		if( thVal.toString().length > 6 ) {

			thVal = thVal.toString().slice(0,6); 	

			th.val(thVal);
			
			$('.slide_pay_form').fadeOut();			
			$('.info_text').fadeIn();
			
			submit_user_pay_form = true;

		}

		else if( thVal.toString().length < 6 ) {
			
			$('.slide_pay_form').fadeOut();
			$('.info_text').fadeIn();
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

		//console.log("get meter details"); alert(home_url+th.val());
		
		$.post(home_url+"/getCustMeters",{meter_id: th.val()},function(data){
			
			console.log(data);

			if(data['error'] != ""){
				submit_user_pay_form = false;
				//alert("Invalid Meter");
				err_c.removeClass("alert-success").addClass("alert-danger").show();

				err_span.html(data['error']);

				th.addClass("red-border"); 
				

			}else{
				submit_user_pay_form = true;
				//alert("valid meter");
				console.log('Price : '+data["price"]);
				meter_per_hour_price = data["price"];
				err_c.removeClass("alert-danger").hide();
				//err_span.html(data['data']);	
				err_span.html('');
				th.removeClass("red-border");
			} 
			//alert("TSt");
			//calculate_price($("#customer_pay_form select[name='expiry_time']"));
			//Calculate price only when user slides the time slider and click the pay button
			/* calculate_price(); */
			
			//alert("Expiry time : "+$("#customer_pay_form select[name='expiry_time']").length);

			if (submit_user_pay_form){ 
				//check time selected
				if($("#customer_pay_form select[name='expiry_time']").length > 0 ){
					if( $("#customer_pay_form select[name='expiry_time']").val() == '' ){
						$("#customer_pay_form select[name='expiry_time']").addClass("red-border")	
						submit_user_pay_form = false;
					}
					else{
						$("#customer_pay_form select[name='expiry_time']").removeClass("red-border")	
					}
				}else if($("#customer_pay_form #ex1").length > 0 && $("#customer_pay_form #ex1").is(':visible')){
					//alert("Range : "+$("#customer_pay_form #ex1").val());
					if( $("#customer_pay_form #ex1").val() == '' || $("#customer_pay_form #ex1").val() == 0 ){
			
						$("#customer_pay_form #ex1").addClass("red-border")	;
						//alert("time empty");
						submit_user_pay_form = false;
				
					}
					else{
				
						$("#customer_pay_form #ex1").removeClass("red-border")	
				
					}
				}
				//alert("Valid "+valid);
				if($('#ex1').val() != 0){
					$('#ex1').val(0);
					$('.meter_price_message.price-msg.to-text').html("<div class='slide_label'> 	Time:  <br />      Slide the dial below        </div>");
					
				}
				
				$('.info_text').fadeOut();
				$('.slide_pay_form').fadeIn();
			} 
			

		});

	}

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
	
	/*control.find('select[name="lot_id"]').val(current_lot_id);*/
	
	if( meter_data[0] == 1 ){
		control.find('select[name="group_id"]').val(meter_data[3]);
	}else{
		control.find('select[name="group_id"]').val(control.find("select[name='group_id'] option:first").val());
	}
	
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
	
	var Meter_groupid = "";

	$(".lot_meters input[name='selectedMeters']").each(function(){

		if( $(this).is(":checked") ){

			checkedMeterIds = (( checkedMeterIds == '' ) ? ($(this).val()) : (checkedMeterIds + "," + $(this).val())) ;	

			meter_id = $(this).parents("tr").find(".meter_id a").html();

			checkedMeters = (( checkedMeters == '' ) ? meter_id : (checkedMeters + "," + meter_id)) ;	
			
			if(meter_count == 0){
				
				Meter_groupid = $(this).attr("group_id");
			
			}

			meter_count++;

		}											

	});

	return [meter_count,checkedMeterIds,checkedMeters,Meter_groupid];

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

$(document).delegate("#createNewUserModal select[name=role_id]", "change", function(){
	var role_id = $(this).val();
	if(role_id == 3) // Landowner
	{
		$("#createNewUserModal .commission_section").hide();
		$("#createNewUserModal .commission_section .sa_commission").attr('name','');
		$("#createNewUserModal .commission_section .sm_commission").attr('name','');
		$("#createNewUserModal .commission_section .sa_commission").css('display','none');
		$("#createNewUserModal .commission_section .sm_commission").css('display','none');
	}
	else if(role_id == 5) // sm
	{
		$("#createNewUserModal .commission_section").show();
		$("#createNewUserModal .commission_section .sa_commission").attr('name','');
		$("#createNewUserModal .commission_section .sa_commission").css('display','none');
		$("#createNewUserModal .commission_section .sm_commission").attr('name','commission');
		$("#createNewUserModal .commission_section .sm_commission").css('display','block');
	}	
	else  // sa
	{
		$("#createNewUserModal .commission_section").show();
		$("#createNewUserModal .commission_section .sm_commission").attr('name','');
		$("#createNewUserModal .commission_section .sm_commission").css('display','none');
		$("#createNewUserModal .commission_section .sa_commission").attr('name','commission');
		$("#createNewUserModal .commission_section .sa_commission").css('display','block');
	}
});

	//	data:{order_id:order_id},
/* Change Shipping Status */
function update_status(order_id){
	
	var shipping_status = jQuery("#status_of_"+order_id).val();
	
	jQuery("#order_"+order_id).fadeIn();
	jQuery.ajax({
		url:'updatestatus',
		type: 'POST',
		data:{
			order:order_id , shipping_status:shipping_status},
			success:function(data){
			jQuery("#order_"+order_id).fadeOut();
			//alert(data);
		}
	});
}

/*


	$(document).ready(function(e){
		alert("TEST");
	});
		$('input[name="meter_id"]').keyup(function(e){
			
			if($(this).val().length > 6 )
			{
				alert("length should not excceed 6");
				var err_span = $("#customer_pay_form").find(".meter_message").find("span");

				err_span.html("Stall # should be a 6-digit number.");
				$("#customer_pay_form").find(".meter_message").show();
			}

		});
	*/
$(window).load(function(e) {
    //alert("loaded");
	$('#overlay').hide();
	jQuery("#slideshow").find('img').show();
});
var table = $('#towing_datatable').DataTable({		
	bLengthChange: false,	
	pageLength: 20,
	language: {
		search: "_INPUT_",
		searchPlaceholder: "Search by City"
	},
	"columnDefs": [
		{ "targets": [1,2,3], "searchable": false }
	]

});
$('input[name=search_by_city]').on( 'keyup', function () { 
	
	table.column(0).search( this.value ).draw(); 
} );

$('.new_order').click( function(){
	
	$('.landowner_park_view').hide();
	$('.landowner_new_parking').show();
});
/*$('.exist_order').click( function(){
	
	$('.landowner_park_view').hide();
	$('.landowner_exist_parking').show();
});*/
$('.landowner_new_parking .close').click( function(){
	
	$('.landowner_park_view').show();
	$('.landowner_new_parking').hide();
});

$('.showpark_view').click( function(){
	
	$('.landowner_park_view').show();
	$('.landowner_new_parking').hide();
	$('.landowner_new_parking .nav-tabs li').removeClass('active');
	$('.landowner_new_parking .tab-pane').removeClass('active');
});


$('.main-tabs li a').click(function(){
	$('.cust_top').click();
});


$('#add_meter').click(function(e) {
    //alert("you clicked");
	$('#newMeterModal').modal('hide');
	setTimeout(function() {
		$('#meter_Added').modal({backdrop: 'static', keyboard: false})
	}, 9000);
});
$("#meter_Added_close").click(function(e) {
    location.reload();
});