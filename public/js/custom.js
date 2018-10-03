/* Change Shipping Status */
function updatepayoutstatus(payouts_EMT_id){
	
	var payout_status = jQuery("#status_"+payouts_EMT_id).val();
	
	jQuery("#payout_"+payouts_EMT_id).fadeIn();
	jQuery.ajax({
		url:'updatepayoutstatus',
		type: 'POST',
		data:{
			payout_id:payouts_EMT_id , payout_status:payout_status
		},
		success:function(data){
			alert(data);
			jQuery("#payout_"+payouts_EMT_id).fadeOut();
			jQuery("#payout_"+payouts_EMT_id).parents('tr').remove();
				
			//alert(data);
		}
	});
}

/* Fetch payout history */
function get_payouts(user_id){
	
	$("#payout_history .modal-body .payout_table").html('');
	$("#payout_history").modal("show");
	$("#payout_history .modal-body .load_img").removeClass('hide');
	
	$.ajax({
		type: 'POST',
		url: 'payouthistory',
		data: { user_id : user_id },
		success: function(result){
			$("#payout_history .modal-body .load_img").addClass('hide');
			$("#payout_history .modal-body .payout_table").html(result);
			$("#payout_history").modal("show");
		} 
	});
}

/* Initialize start time and end time for variable rates */
function date_time_picker_init(){
}

$(document).ready(function(){
	
	
	/*$(function () {
            $('.datetimepicker6').datetimepicker();
            $('.datetimepicker7').datetimepicker({
                useCurrent: false //Important! See issue #1075
            });
            $(".datetimepicker6").on("dp.change", function (e) {
				alert(e.date);
				$(this).parents('.test_test').find('.datetimepicker7').css("border","2px solid red");
				$(this).parents('.test_test').find('.datetimepicker7').data("DateTimePicker").minDate("Min date : "+e.date);
                //$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            });
            $(".datetimepicker7").on("dp.change", function (e) {
                //$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
				alert("Max date : "+e.date);
				$(this).parents('.test_test').find('.datetimepicker6').css("border","2px solid green");
				$(this).parents('.test_test').find('.datetimepicker6').data("DateTimePicker").maxDate(e.date);
            });
        });*/
/*	$('.start_time').datetimepicker({
    minDate: new Date(),
    format: "YYYY-MM-DD",
    pickTime: false
});
$('.end_time').datetimepicker({
    minDate: new Date(),
    format: "YYYY-MM-DD",
    pickTime: false
});

$(".start_time").on("dp.change", function (e) {
    $('.end_time').data("DateTimePicker").setMinDate(e.date);
});
$(".end_time").on("dp.change", function (e) {
    $('.start_time').data("DateTimePicker").setMaxDate(e.date);
});*/
   
		/*$('.start_time').on('dp.change', function (selected) {
			alert("start Changed 1");
			var minDate = new Date(selected.date.valueOf());
			$(selected.currentTarget).parents('.variable_rate_panel').find('.end_time').css("border","2px solid red");
			 $('.end_time').data("DateTimePicker").minDate('2017/16/11');
//			$(selected.currentTarget).parents('.variable_rate_panel').find('.end_time').datetimepicker({minDate: minDate}); 
		});
		
		$('.end_time').on('dp.change', function (selected) {
			alert("End changed 2");
			var maxDate = new Date(selected.date.valueOf());
			$(selected.currentTarget).parents('.variable_rate_panel').find('.start_time').css("border","2px solid green");
			$(selected.currentTarget).parents('.variable_rate_panel').find('.start_time').datetimepicker({maxDate: maxDate});
			
		});*/
		

		/*$('.start_time').each(function(index, element) {
           $(this).datetimepicker().on('dp.change', function (selected) {
		
				var minDate = new Date(selected.date.valueOf());	alert(minDate);
				$(selected.currentTarget).parents('.variable_rate_panel').find('.end_time').datetimepicker('minDate', minDate);
			});
	
        });*/

	 
	$('.defaultstart_time').timepicker({defaultTime: '06:00 PM'});
	$('.defaultend_time').timepicker({defaultTime: '06:00 AM'});
	//$('#offer_start_at').datetimepicker();
	//$('#offer_end_at').datetimepicker();
	//$('.date_time_picker').datetimepicker();
	
	 $(document).on("click", "a.add_variable_rate", function() {
		 
		var count = $("#Rates_section .variable_rate_panel").length;
		var no_of_panels = count+1;
		$(".clon .variable_rate_panel").clone().appendTo(".variable_rates .variable_rates_groups");
		$(".variable_rate_panel:last-child").css('margin-top','10px');
		$(".variable_rate_panel:last-child").attr('id','variable_rate_'+no_of_panels);
		
		$("#variable_rate_"+no_of_panels).find('.collapse').attr('id','collapse'+no_of_panels);
		$("#variable_rate_"+no_of_panels).find('.panel-heading a').attr('href','#collapse'+no_of_panels);
		$("#variable_rate_"+no_of_panels).find('.panel-title a').html('<i class="fa fa-caret-right"></i> Variable Rate '+no_of_panels+' :');
		//$(".variable_rate_panel:last-child .lbl").html("Variable Rate "+no_of_panels+":");
		
		$('.variable_rate_panel:last-child .defaultstart_time').timepicker();
		$('.variable_rate_panel:last-child .defaultend_time').timepicker();
		//$('.variable_rate_panel:last-child .date_time_picker').datetimepicker();
		$('.variable_rate_panel:last-child .date_time_picker').datetimepicker({format: "mm/dd/yyyy HH:ii P",
showMeridian: true,autoclose: true,todayBtn: true,minuteStep: 10,});
		$('.variable_rate_panel:last-child .start_time').datetimepicker().on('changeDate', function(ev){
			
	 
		   $(this).parents('.variable_rate_panel').find('.end_time').datetimepicker('setStartDate', ev.date);
  
		});
		$('.variable_rate_panel:last-child .end_time').datetimepicker().on('changeDate', function(ev){
		 
			$(this).parents('.variable_rate_panel').find('.start_time').datetimepicker('setEndDate', ev.date);
		  //alert('test2');
		});
		
		
	});
	
	function reset_modal(){
		
		if($('input[name=form_action]').val() == '0'){
			$('input[name=form_action]').val('1');
		}
		//$(".modal-title").text("Add Offer");
		
		$('#offer_name').val('');
		$('.meter_ids').prop("checked",false);
		$('#offer_start_at').val('');
		$('#offer_end_at').val('');
		$('#price').val('');	
	}
	
	/*$('.add_variable_rate').click(function(){
		
		/* Reset the Offer Modal Window */
		//reset_modal();
	//});*/
	
  /*$('#users_checkboxes').multiselect({
		includeSelectAllOption: true  
  });*/
  
  /* put the title of sub-tabs in parent tab if selected from content manager */
  $("a:not(.dropdown-toggle)").click(function(){ //.dropdown-menu li 
	  	/* Reset the all dropdowns if clicked */
		if($('li.dropdown').find('a.dropdown-toggle span.parent').length > 0){
			$('li.dropdown').find('a.dropdown-toggle span.parent').removeClass("hide parent");
			$('li.dropdown').find('a.dropdown-toggle span.child').remove(); 
		}
		$(this).parents('li').find('a.dropdown-toggle span:not(.child)').addClass("hide parent");
		if($(this).parents('li').find('a.dropdown-toggle span.child').length > 0){
			$(this).parents('li').find('a.dropdown-toggle span.child').html($(this).text());		
		}else{
			var child_menu = "<span class=' child '>"+$(this).text()+"</span>";
			$(this).parents('li').find('a.dropdown-toggle').prepend(child_menu);
		}
	});
  
	/* Toggle Automated Emails dropdown menus */
	$("#admin_navigation .dropdown-menu a").on('click', function(event) {
		var target = $(this).attr('data-attr');
		$("#automated_emails").find(".tab-pane").addClass('hide');
		if (target !== "") {
			if($("#"+target).length > 0){
				$("#"+target).removeClass("hide");
			}
		} // End if
	});
	
	/* Check select group method either existing (if 0) or new (if 1) */
	$('input[name=grouping_method]').change(function(e) {
		var group_method = $(this).val();
		//$("#grouping").fadeIn('slow');
		if(group_method == 0){ 
			// Existing Group
			$("#new_group").fadeOut('slow');
			$("#existing_group").fadeIn('slow');
		}else{ 
			// New Group
			$("#existing_group").fadeOut('slow');
			$("#new_group").fadeIn('slow');
		}
    });

	/*admin navigation on mobile*/
	var windowSize = $(window).width();
	if(windowSize <= 767)
	jQuery( window ).scroll(function() {
		jQuery( "#bs-example-navbar-collapse-1" ).css( "width", "0" )
	});
	
	/* Use promo code internally to give discount to landowner */
	
	$("#create_new_client").click(function(e) {
        $.post(home_url+"/update_order", { changed_by:'promo_code' , meter_count:1 }, function(response){
			$('#loading_img_total').hide();
			/*$('.sub_count').text("$"+response['sub_total']);*/
			$('.payable_amount').text("$"+response['amount']); 
			$('.sub_count').next("span.bold_text").text(" (-"+promo_code_discount+"%)");
			$('input[name=payable_amount]').val(response['amount']);
			
	   })
	   
		//jQuery('input[name=refered_by]').val(data_array.referred_by);
		//jQuery('input[name=commission]').val(data_array.commission);
    });

	 /**************************************** Manage date and time according to the slected Offer Type **************************************************/
	 
	 $(document).delegate("#Rates_section select.offer_type","change",function(e) {
		//var offer_type = $(this+"option:selected").attr('data-type');
		var offer_type = $("option:selected", this).attr("data-type");
		
		var target = $(this).parents('.variable_rate_panel').attr('id');
		
		// Reset unrequired fields
			
		$("#Rates_section #"+target+" .date_time_picker").val('');
		$("#Rates_section #"+target+" .timepicker").val('');
		
		if(offer_type != ''){
			
			if( offer_type == "timepicker" ){
				
				$("#Rates_section #"+target+" .date_time_picker").parents('.form-group.date_fields').hide();
				$("#Rates_section #"+target+" .timepicker").parents('.form-group.time_fields').show();
				
			
			}else{
				$("#Rates_section #"+target+" .timepicker").parents('.form-group.time_fields').hide();
				$("#Rates_section #"+target+" .date_time_picker").parents('.form-group.date_fields').show();

			}	
			
		}else{
			
			
			/****************** Weekends ********************/
			
			// Hide unrequired fields
			$("#Rates_section #"+target+" .time_fields").hide();
			$("#Rates_section #"+target+" .date_fields").hide();
			
			
			
			//alert( $("#Rates_section #"+target+" .date_time_picker").val() );
			//alert( $("#Rates_section #"+target+" .timepicker").val() );
			
			
		}
		
		
    });
	
	
	/****************************************************** Delete Variable Rate *************************************************************************/
	
    $(document).on('click','.variable_rate_close', function(e) {
		var rate_id = $(this).attr("data-attr");
		var target = $(this);
		if (confirm("Are you sure?")) {
			
			if(rate_id == "" || rate_id == undefined){
				$(this).parents('.variable_rate_panel').remove();
			}else{
				$.ajax({
					url:'home/delete_varaiable_rate',
					type:'POST',
					data:{ rate_id : rate_id },
					success:function(data){
						target.parents('.variable_rate_panel').remove(); 
					}
				});	
			}
			
		}
    });


	$('select[name="shipping_city"]').change(function(){
			var payment_id = $(this).val();
			$.ajax({
				type: 'POST',
				url: 'home/location_meter_id',
				data: {payment_id: payment_id},
				success: function (data) { 
					//console.log(data);
					//alert(data.length);
					$html = "<table>";
					for(var i =0;i<data.length;i++){
						console.log(data[i]);
						$html += "<tr><td>"+data[i]+"</td></tr>";
					}
					$html += "</table>";
					$('.meters_id').html($html);
					
				}
				
			});
			
	});
					
					
		
	
});

/************ Variable rates panel accordions toggling *********/
(function() {
  
  $("#accordion").on("show.bs.collapse hide.bs.collapse", ".panel", function(e) {
    if (e.type=='show'){
      $(this).addClass('active1');
    }else{
      $(this).removeClass('active1');
    }
  });  

}).call(this);


var repeatingEvents = [{
    "id":"2","title":"$2.50","price":"2.50","backgroundColor":"#96e63f","dow":"[0,6]","ranges":[{"start":"2017-11-01","end":"2017-11-28"}],"start":"10:00","end":"14:00"
}];

//emulate server
var getEvents = function( start, end ){
    return repeatingEvents;
}

$('#calendar1').fullCalendar({
    defaultDate: moment(),
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    defaultView: 'month',
    eventRender: function(event, element, view){
        console.log(event.start.format());
		
        return (event.ranges.filter(function(range){
				console.log("range : "+range.end);
            return (event.start.isBefore(range.end) &&
                    event.end.isAfter(range.start));
        }).length)>0;
    },
    events:[{"id":"1","title":"$4.00","price":"4.00","backgroundColor":"#87d236","ranges":[{"start":"2017-11-22 23:52:37","end":"0000-00-00 00:00:00"}],"start":"2017-11-22 23:52:37","end":"2017-11-30 10:30:00"},{"id":"2","title":"$2.50","price":"2.50","backgroundColor":"#96e63f","dow":"[0,6]","ranges":[{"start":"2017-11-22","end":"2037-11-23"}],"start":"10:00","end":"14:00"},{"id":"23","title":"$2.00","price":"2.00","backgroundColor":"#56e63f","ranges":[{"start":"2017-11-22 23:52:37","end":"0000-00-00 00:00:00"}],"start":"2017-11-22 23:52:37","end":"2017-11-30 10:30:00"},{"id":"24","title":"$2.00","price":"2.00","backgroundColor":"#4ce63f","ranges":[{"start":"2017-11-22 23:52:37","end":"0000-00-00 00:00:00"}],"start":"2017-11-22 23:52:37","end":"2017-11-30 10:30:00"},{"id":"36","title":"$6.00","price":"6.00","backgroundColor":"#92eb32","ranges":[{"start":"2017-12-27 10:37:00","end":"2017-11-28 10:36:00"}],"start":"2017-12-27 10:37:00","end":"2017-11-28 10:36:00"},{"id":"39","title":"$4.00","price":"4.00","backgroundColor":"#5cff4e","ranges":[{"start":"2017-11-22 23:52:37","end":"0000-00-00 00:00:00"}],"start":"2017-11-22 23:52:37","end":"2017-11-30 10:30:00"},{"id":"40","title":"$4.50","price":"4.50","backgroundColor":"#32eb5b","dow":"[0,6]","ranges":[{"start":"2017-11-22","end":"2037-11-23"}],"start":"10:00","end":"14:00"}]
});
/************ landowner email buuton toggle  ************/
if($('#email_feature').prop("checked")){
		
	$("#email_feature_hidden").prop("disabled",true);
}
$("#email_feature").change(function(){
	
	if($('#email_feature').prop("checked")){
		
		$("#email_feature_hidden").prop("disabled",true);
		$("#recipient_email").css("display",'inline');
		$("#recipient_email").prop("disabled",false);
	}
	else{
		
		$("#email_feature_hidden").prop("disabled",false);
		$("#recipient_email").css("display",'none');
		$("#recipient_email").prop("disabled",true);
	}
});

/************ landowner sms buuton toggle  ************/
if($('#sms_feature').prop("checked")){
		
	$("#sms_feature_hidden").prop("disabled",true);
	//$("#recipient_mobile").prop("disabled",false);
}
$("#sms_feature").change(function(){
	
	if($('#sms_feature').prop("checked")){
		
		$("#sms_feature_hidden").prop("disabled",true);
		$("#recipient_mobile").prop("disabled",false);
		$("#recipient_mobile").css("display",'inline');
	}
	else{
		
		$("#sms_feature_hidden").prop("disabled",false);
		$("#recipient_mobile").prop("disabled",true);
		$("#recipient_mobile").css("display",'none');
	}
});
/************ landowner variables buuton toggle  ************/
if($('#variable_rates').prop("checked")){
		
	$("#variable_rates_hidden").prop("disabled",true);
}
$("#variable_rates").change(function(){
	
	if($('#variable_rates').prop("checked")){
		
		$("#variable_rates_hidden").prop("disabled",true);
	}
	else{
		
		$("#variable_rates_hidden").prop("disabled",false);
	}
});

$('.timepicker').timepicker().on('changeTime.timepicker', function (e) {
	
    var hour = e.time.hours; console.log("hour : "+hour);
    if (e.time.meridian === "PM" && hour !== 12) {
        hour += 12;
    }
    hour += (e.time.minutes / 100);
    if (hour < 10) {
        $('#timepicker').timepicker('setTime', '10:' + e.time.minutes + ' AM');
    }
    else if (hour > 22) {
        $('#timepicker').timepicker('setTime', '10:' + e.time.minutes + ' AM');
    }
});




