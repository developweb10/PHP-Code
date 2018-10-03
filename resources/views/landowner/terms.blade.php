@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		
		<!-- Delete Lot Modal -->
		<div id="termsDialog" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-center">{{ $data->page_title }}</h4>
					</div>
					<div class="modal-body" style="max-height:500px; overflow-y:scroll;">
						@if ( $data->page_content != '' )
							{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}
						@else
							<p>
								These terms and conditions apply to all services ("Services") provided or arranged by My-Meter ("us", "we") to or for You, the owner or operator of one or more parking spaces and recipient of the Services ("you" or the "Parking Space Owner").
							</p>
							<p>
								We may amend these terms from time to time. Any amendments or new terms and conditions will be provided to you. You may terminate this Agreement if you do not wish to be bound by any such amendments but by continuing to use our website or Services you will be deemed to have accepted the new terms.
							</p>
							<h3>The Parking Space</h3>
							<p>
								Before any Booking Period you must ensure that the Parking Space is in a satisfactory condition and is able to meet the requirements of the Driver under the booking.
							</p>
							<p>
								You represent and warrant that you own the Parking Space or that you are authorized to allow third parties to use the Parking Space and, where necessary, you have permission from your landlord, tenant or condominium association (or other persons who control any condominium of which the Parking Space is a part) to do so. If you are in any doubt you should check the terms of your lease (or sublease), freehold title, mortgage, deed of trust, condominium documents or any other documents of record to ensure that you are able to grant a license to use your Parking Space in the manner envisaged by this agreement and/or your agreement with Drivers.
							</p>
							<p>
								We will not be liable to you, the Driver or any other third party (such as a landlord, tenant, condominium association (or any other persons who control any condominium of which the Parking Space is a part) or management company).
							</p>
							<h3>Approvals</h3>
							<p>
								You represent and warrant that you have all necessary regulatory and planning approvals to grant a license to use the Parking Space and that the license to use the Parking Space will comply with all applicable laws, Tax requirements and rules and regulations that may apply to the Parking Space, including but not limited to zoning laws and laws governing rental of or licenses to use residential and other properties.
							</p>
							<p>
								You agree that we shall not be liable to you in any way whatsoever if you suffer any loss as a result of any governmental authority or any other relevant public authority bringing proceedings against you or taking any other action against you as a result of renting out the Parking Space on the Website. 
							</p>
							<h3>Your obligations</h3>
							<p>
								You must:
								<ul>
									<li>honour all bookings with Drivers;</li>
									<li>deal with all Drivers in a professional and courteous manner and in such a way as to not cause any harm or damage to our reputation;</li>
									<li>deal with all queries from Drivers relating to a Parking Space or booking in a prompt and satisfactory manner;</li>
									<li>comply with all applicable laws, Tax requirements and rules and regulations that may apply to the Parking Space, including but not limited to zoning laws and laws governing rental of or licenses to use residential and other properties;</li>
								</ul>
							</p>
							
							<h3>Payment & Fees</h3>
							<p>
								Once we have received the necessary payment from the Driver a booking will be deemed to have been accepted and you will have entered a binding agreement with the Driver to allow the Driver to occupy the Parking Space for the duration of the booking period ("Booking Period"). Our standard policy is to collect the full amount owed by the Driver for the Parking Space at the time they make their booking. The Driver must agree to the terms of the Parking Space Booking Agreement and payment must be received in full before the booking is confirmed. You authorize us to accept and hold such payments on your behalf. 
							</p>
							<p>
								We retain a 20% fee and will forward monthly payouts to you via e-transfer using the email address you provide to us on the Account page of your login dashboard. 
							</p>
							
							<h3>Chargebacks</h3>
							<p>
								From time to time we may collect payment on your behalf from a Driver which we either have to repay to a Driver's credit card provider or which is deducted from a retention we have with our credit card processors (a "Chargeback"). If we are subject to a Chargeback in respect of a booking of your Parking Space you agree that we will not be under any obligation to make payment to you of any amount which is the subject of a Chargeback.
							</p>
							<p>
								In the event of a Chargeback in relation to an amount we have already paid to you we reserve the right to deduct an amount equal to the Chargeback from your next payout.
							</p>
								
							<h3>Complaints and Disputes</h3>
							<p>
								You agree that if you have any dispute with a Driver concerning your Parking Space or any use of the Parking Space you will attempt to resolve it in the first instance by directly communicating with the Driver.
							</p>
							
							<h3>Termination</h3>
							<p>
								Either party may terminate this Agreement at any time but upon termination you agree to honour any outstanding bookings. From the date of termination we will not accept any new payments for the Parking Space.
							</p>
							<p>
								We will be entitled to terminate this Agreement immediately if:
								<ul>
									<li>you are in material breach of any of the terms of this Agreement; or</li>
									<li>you do anything to put our goodwill or reputation at risk; or</li>
									<li>we have any reason to believe that you are not authorized to grant a license to use the Parking Space;</li>
									<li>you refuse to cooperate with us in respect of this Agreement.</li>
								</ul>
							</p>
							
							<h3>Insurance</h3>
							<p>
								You will be entirely responsible for any and all insurance that you may require for the purposes of granting any license to use your Parking Space.
							</p>
							
							<h3>Disclaimers</h3>
							<p>
								My-Meter makes no warranty that the Website or Services will meet your requirements or be available on an uninterrupted, secure, or error-free basis. My-Meter makes no warranty regarding the quality of the Services or the accuracy, timeliness, truthfulness, completeness or reliability of any content obtained through the Website or Services.
							</p>
							
							<h3>Our liability</h3>
							<p>
								You agree that My-Meter is not liable for any loss arising from your dealings with any Driver or arising from the Parking Space and we shall have no liability to you whatsoever for any act or omission of the Driver in connection with the Parking Space or a Booking. 
							</p>
							<p>
								You agree that the exclusion of liability is reasonable in all circumstances, especially in light of the fact that our Services include only the provision of the Website and Services and responsibility for the Parking Space and fulfillment of a Booking lies solely with the Owner and Driver for whom we act only as an agent in a limited capacity.
							</p>
								
							<h3>Confidentiality</h3>
							<p>
								My-Meter agrees not to divulge or allow to be divulged any confidential information relating to Your affairs other than to its employees, associates or contractors (if any) who are subject to appropriate non-disclosure undertakings (if required), or where the other party has consented to such disclosure or where required by law to make such disclosure. Either party may upon termination of this Agreement require by notice in writing to the other party the destruction or return of any confidential material in that party's possession or control. The confidentiality obligation set out here shall expire 3 years after the expiry or termination of the Agreement.
							</p>
						
						@endif

					</div>
					<div class="modal-header text-right">
						<form method="post" action="{{ URL::to('/home/accpetterms') }}" class="form-inline" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="submit" name="is_agreed" value="Accept Terms & Conditions" class="btn btn-success" /> 
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clearfix"></div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function(){
		
		$("#termsDialog").modal({backdrop: 'static', keyboard: false});
		
	}, false);
</script>

@endsection  