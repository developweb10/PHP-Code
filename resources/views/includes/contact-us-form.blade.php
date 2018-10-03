<script src='https://www.google.com/recaptcha/api.js'></script>



<div >

	

	<div class="panel panel-default">

		<div class="panel-heading"><i class="fa fa-envelope-o"></i> Contact Us</div>

		<div class="panel-body">

			<div class="col-md-10 col-md-offset-1"> 

				@if ( count($errors) > 0 and !isset($hide_error))

					<div class="alert alert-danger">

						<strong>Whoops!</strong> There were some problems with your input.<br><br>

						<ul>

							@foreach ($errors->all() as $error)

								<li>{{ $error }}</li>

							@endforeach

						</ul>

					</div>

				@endif

				

				@if( Session::has('success') and ( !isset($hide_error) or isset($show_success) ) )

					<div class="alert alert-success">

						<strong>Success!</strong> {{ Session::get('success') }}

					</div>

				@endif

			

				<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/contact-us') }}" id="contact_us_form">

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					

	                <div class="form-group">

						<label class="control-label">Name</label>

						<div class="">

							<input type="text" required name="contact_name" class="form-control" value="" placeholder="Name"  >

						</div>

					</div>

				

					<div class="form-group">

						<label class="control-label">Email</label>

						<div class="">

							<input type="email" required name="contact_email" class="form-control" placeholder="Email"  >

						</div>

					</div>

					

					<div class="form-group">

						<label class="control-label">Subject</label>

						<div class="">

							<input type="text" required name="contact_subject" class="form-control" placeholder="Subject"  >

						</div>

					</div>

				

					<div class="form-group">

						<label class="control-label">Message</label>

						<div class="">

							<textarea name="contact_message" required class="form-control" placeholder="Type your message here..."  ></textarea>

						</div>

					</div>

					<br>

	                <div class="clearfix"></div>

	                <div class="form-group">

						<label class="control-label"></label>

						<div class="">

							<div class="g-recaptcha" data-sitekey="6LfVeCATAAAAACWcfceG7YkyiLDjxSXIQ7aHI7Yq"></div>

						</div>

					</div>

					

					<div class="form-group text-right button-row">

						<div class="">

							<button type="submit" name="contact_us" class="btn btn-success">Submit</button>

						</div>

					</div>

					

					

				</form>

			</div>

					

		</div>

	</div>

</div>

