@extends('app')



@section('content')

<div class="container-fluid">

	<div class="panel panel-default">

		<div class="panel-heading"></div> <!-- Manage Content -->

		<div class="panel-body">

			@if ( count($errors) > 0)

				<div class="alert alert-danger">

					<strong>Whoops!</strong> There were some problems with your input.<br><br>

					<ul>

						@foreach ($errors->all() as $error)

							<li>{{ $error }}</li>

						@endforeach

					</ul>

				</div>

			@endif

			

			@if( Session::has('success'))

				<div class="alert alert-success">

					<strong>Success!</strong> {{ Session::get('success') }}

				</div>

			@endif
			<ul id="admin_navigation" class="nav nav-tabs" role="tablist">

				<li class="dropdown @if ($vars['tab'] === '' || $vars['tab'] === 'home') active @endif" role="presentation">
                	<a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="">Home</span><b class="caret"></b></a> <!-- aria-controls="home" role="tab"  -->
                    <ul class="dropdown-menu" role="menu">
                      <li class="@if ($vars['tab'] === '' || $vars['tab'] === 'home') active @endif"><a href="#home" role="tab" data-toggle="tab">Home</a></li>
                      <li><a href="#testimonials" role="tab" data-toggle="tab">Testimonials</a></li>
                      <li><a href="#footer" role="tab" data-toggle="tab">Footer</a></li>
                      <li><a href="#social_sharing" role="tab" data-toggle="tab">Social Sharing</a></li> 
                    </ul>
                </li>
				
				<li role="presentation" class="dropdown @if ($vars['tab'] === 'faq' ) active @endif" >
	                <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="">Legal</span><b class="caret"></b></a> <!-- aria-controls="home" role="tab"  -->
                    <ul class="dropdown-menu" role="menu">
	                    <li><a href="#terms" role="tab" data-toggle="tab" aria-controls="terms">Terms & Conditions</a></li>
	                	<li><a href="#privacy" role="tab" data-toggle="tab" aria-controls="privacy">Privacy Policy</a></li>
    	                <li><a href="#owner_agreement" role="tab" data-toggle="tab" aria-controls="owner_agreement">Owner Agreement</a></li>
                        <li><a href="#sa_agreement" role="tab" data-toggle="tab" aria-controls="sa_agreement">Associate Agreement</a></li>
                        <li><a href="#sm_agreement" role="tab" data-toggle="tab" aria-controls="sm_agreement">Manager Agreement</a></li>
                    </ul>
                </li>
                
                
				<li role="presentation" class="dropdown @if ($vars['tab'] === 'sa_dashboard' ) active @endif" >
	                <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="">Associate</span><b class="caret"></b></a> <!-- aria-controls="home" role="tab"  -->
                    <ul class="dropdown-menu" role="menu">
	                    <li><a href="#sa_dashboard" role="tab" data-toggle="tab" aria-controls="sa_dashboard">SA Dashboard</a></li>
	                	<li><a href="#sa_marketing" role="tab" data-toggle="tab" aria-controls="sa_marketing">SA Marketing</a></li>
                    </ul>
                </li>
                
               <li role="presentation" @if ($vars['tab'] === 'owner_content') class="active" @endif ><a href="#owner_content" aria-controls="owner_content" role="tab" data-toggle="tab">Landowner</a></li> <!-- Owner Content -->

				<li role="presentation" class="dropdown @if ($vars['tab'] === 'automated_emails') active @endif" >
                	<a class="dropdown-toggle" href="#" role="tab" data-toggle="dropdown"><span class="">Automated Emails</span><b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
	                    <li><a href="#automated_emails" role="tab" data-toggle="tab" aria-controls="automated_emails" data-attr="landowners_emails">Landowners</a></li>
	                	<li><a href="#automated_emails" role="tab" data-toggle="tab" aria-controls="automated_emails" data-attr="sa_emails">Sales Associate</a></li>
    	                <li><a href="#automated_emails" role="tab" data-toggle="tab" aria-controls="automated_emails" data-attr="sm_emails">Sales Manager</a></li>
                    </ul>
                </li>
                
                <li role="presentation" class="dropdown @if ($vars['tab'] === 'faq' ) active @endif" >
	                <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="">FAQ</span><b class="caret"></b></a> <!-- aria-controls="home" role="tab"  -->
                    <ul class="dropdown-menu" role="menu">
	                    <li><a href="#faq" role="tab" data-toggle="tab" aria-controls="faq">FAQ</a></li>
	                	<li><a href="#faq_landowner" role="tab" data-toggle="tab" aria-controls="faq_landowner">FAQ Landowner</a></li>
    	                <li><a href="#faq_sa" role="tab" data-toggle="tab" aria-controls="faq_sa">FAQ SA</a></li>
                    </ul>
                </li>
                
                <li role="presentation" @if ($vars['tab'] === 'companies') class="active" @endif ><a href="#companies" aria-controls="companies" role="tab" data-toggle="tab">Towing</a></li>
                
				<!--<li role="presentation" @if ($vars['tab'] === 'testimonials') class="active" @endif ><a href="#testimonials" aria-controls="testimonials" role="tab" data-toggle="tab">Testimonials</a></li>-->
                
				<!--<li role="presentation" @if ($vars['tab'] === 'terms') class="active" @endif ><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">Terms & Conditions</a></li>

				<li role="presentation" @if ($vars['tab'] === 'privacy') class="active" @endif ><a href="#privacy" aria-controls="privacy" role="tab" data-toggle="tab">Privacy Policy</a></li>

				<li role="presentation" @if ($vars['tab'] === 'owner_agreement') class="active" @endif ><a href="#owner_agreement" aria-controls="owner_agreement" role="tab" data-toggle="tab">Owner Agreement</a></li>-->


				<!--<li role="presentation" @if ($vars['tab'] === 'sa_agreement') class="active" @endif ><a href="#sa_agreement" aria-controls="sa_agreement" role="tab" data-toggle="tab">Associate Agreement</a></li>

				<li role="presentation" @if ($vars['tab'] === 'sm_agreement') class="active" @endif ><a href="#sm_agreement" aria-controls="sm_agreement" role="tab" data-toggle="tab">Manager Agreement</a></li>-->



				

				<!--<li role="presentation" @if ($vars['tab'] === 'footer') class="active" @endif ><a href="#footer" aria-controls="footer" role="tab" data-toggle="tab">Footer</a></li>-->

				<!--<li role="presentation" @if ($vars['tab'] === 'faq_landowner' ) class="active" @endif ><a href="#faq_landowner" aria-controls="faq_landowner" role="tab" data-toggle="tab">FAQ Landowner</a></li>

				<li role="presentation" @if ($vars['tab'] === 'faq_sa' ) class="active" @endif ><a href="#faq_sa" aria-controls="faq_sa" role="tab" data-toggle="tab">FAQ SA</a></li>
 
				<li role="presentation" @if ($vars['tab'] === 'sa_dashboard' ) class="active" @endif ><a href="#" aria-controls="sa_dashboard" role="tab" data-toggle="tab">Associate</a></li>-->

				<!--<li role="presentation" @if ($vars['tab'] === 'sa_marketing' ) class="active" @endif ><a href="#sa_marketing" aria-controls="sa_marketing" role="tab" data-toggle="tab">SA Marketing</a></li>

				

				<li role="presentation" @if ($vars['tab'] === 'social_sharing' ) class="active" @endif ><a href="#social_sharing" aria-controls="social_sharing" role="tab" data-toggle="tab">Social Sharing</a></li>-->

				

			</ul>

			<div class="tab-content">

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === '' || $vars['tab'] === 'home') active @endif "id="home">

					<br />

					@include('admin.manage-home')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'testimonials') active @endif " id="testimonials">

					<br />

					@include('admin.manage-testimonials')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'faq' ) active @endif" id="faq">

					<br />

					@include('admin.manage-faq')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'terms') active @endif" id="terms">

					<br />

					@include('admin.manage-terms')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'privacy') active @endif " id="privacy">

					<br />

					@include('admin.manage-privacy')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'owner_agreement') active @endif " id="owner_agreement">

					<br />

					@include('admin.manage-owner_agreement')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'owner_content') active @endif " id="owner_content">

					<br />

					@include('admin.manage-owner_content')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'sa_agreement') active @endif " id="sa_agreement">

					<br />

					@include('admin.manage-sa_agreement')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'sm_agreement') active @endif " id="sm_agreement">

					<br />

					@include('admin.manage-sm_agreement')

				</div>


				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'automated_emails') active @endif " id="automated_emails">

					<br />

					@include('admin.manage-automated_emails')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'footer') active @endif " id="footer">

					<br />

					@include('admin.manage-footer')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'faq_landowner' ) active @endif" id="faq_landowner">

					<br />

					@include('admin.manage-faq_landowner')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'faq_sa' ) active @endif" id="faq_sa">

					<br />

					@include('admin.manage-faq_sa')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'sa_dashboard' ) active @endif" id="sa_dashboard">

					<br />

					@include('admin.manage-sa_dashboard')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'sa_marketing' ) active @endif" id="sa_marketing">

					<br />

					@include('admin.manage-sa_marketing')

				</div>

				<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'social_sharing' ) active @endif" id="social_sharing">

					<br />

					@include('admin.manage-social_sharing')

				</div>
                
                <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'companies' ) active @endif" id="companies">

					<br />

					@include('admin.companies')

				</div>

			</div>

		</div>   

	</div>

</div>



@endsection





@section('additionalJS')
    
	<script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>

	<script>

		function inti_tinymce(){

			tinymce.init({ 

				selector: ".text-editor" ,

				theme: "modern",

				plugins: [

					 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",

					 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

					 "save table contextmenu directionality emoticons template paste textcolor"

			   ],

			   toolbar: "insertfile undo redo | styleselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

			   content_css: [

				'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',

				'//www.tinymce.com/css/codepen.min.css'

			  ],

			  fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt 72pt"

			});

		}

		inti_tinymce();

	</script>

@endsection