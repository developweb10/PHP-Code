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
								This Agreement is made by and agreed to between My-Meter (Us, We), and You (as a Sales Associate) and entitles you to financial compensation (payouts) for Transactions (payments) made by drivers using Our Website. It is Your obligation to review and accept or decline this Agreement as it is presented to You. If accepted by You, compliance with this Agreement is the responsibility of both You, and Us.
							</p>
							<p>
								Each party represents and warrants to the other party that the person executing this Agreement is authorized to do so on such party's behalf.
							</p>
							<p>
								My-Meter has elected to enter into this Agreement with you with recognition that My-Meter's success depends on financially sound, responsible, efficient, vigorous and successful independent Sales Associates, whose business conduct is free of false, deceptive or misleading advertising, merchandising, pricing and practices, and with confidence in Sales Associate's integrity and ability, and in the Sales Associate's expressed intention to deal fairly with My-Meter and its customers, and to perform and carry out Sales Associate's duties, obligations and responsibilities as set forth in this Agreement.
							</p>
							<p>
								You understand that You are entering into an Independent Sales Associate agreement, and will be obligated to comply with the terms and conditions of this Agreement and that in return, My-Meter will allow You to be an Independent Sales Associate and Representative and to represent My-Meter of its services, pursuant to the specific terms and conditions of this Agreement, and to earn commissions.
							</p>
	
							<h3>1. Approvals</h3>
							<p>
								When You recruit a new registered property owner (User) to My-Meter.com through the URL (Tracking Code) assigned to You within Your login account, You are entitled to a 5% commission of all payments that that User has earned from Drivers paying for parking. My-Meter shall be obligated to compensate You in accordance with this Agreement.??
							</p>
	
							<h3>2. Payouts</h3>
							<p>
								Subject to other provisions in this Agreement, My-Meter.com shall credit Your Account with a payout for each qualifying Transaction in accordance with the payout rate for the relevant transaction. On the 1st day of each calendar month, My-Meter.com will issue to You any positive balance in Your Account for Transactions reported for the previous month. Payments will be sent to your email address via Paypal. If you do not already have a Paypal account, you will need to create one after receipt of your first payment.
							</p>
							
							<h3>3. Accurate Information</h3>
							<p>
								You agree to provide My-Meter.com with accurate information about You, and to maintain up-to-date "Account" information (such as contact & Payment information, etc.).
							</p>
							
							<h3>4. Marketing policies</h3>
							<p>
								You will at all times promote vigorously and effectively the sale of My-Meter's products, goods and services through all channels of distribution available to You without limitation by marketing area or territory, in conformity with My-Meter's established marketing policies and programs.
							</p>
	
								
							<h3>5. Commitment to Personal Sales</h3>
							<p>
								You understand that My-Meter is an e-commerce Company, which distributes its products/ services on-line, through person-to-person sales, and through seminars and conventions. You agree that as a My-Meter Sales Associate, You will actively develop personal customers, promote and represent My-Meter with Integrity in sales negotiations, whether it is to individuals, companies, or whatever entity, or through networks, events, programs or otherwise,
							</p>
							
							<h3>6. Territory</h3>
							<p>
								You understand and acknowledge that the rights of Independent Sales Associate under this Agreement do not constitute a franchise; that there is no exclusive territory granted, and that each My-Meter Independent Sales Associate has the right to sell My-Meter products, good and services, events and opportunities, in any territory.
							</p>
	
							
							<h3>7. Sales / Representation of competitive Companies and Organizations</h3>
							<p>
								It is specifically understood and agreed between the parties that Independent Sales Associates may not represent other company or entities in the same or similar business as My-Meter, and doing so, without the express written consent of My-Meter shall be cause for termination of this Agreement, with or without damages. Sales Associate will not, under any circumstances, show test data, video comparisons, Audio clips or charts of any kind comparing My-Meter products, goods and services to competing brands, unless My-Meter is shown as equal or superior. If Sales Associate cannot show My-Meter products, goods and services as equal or superior to competing products, My-Meter's name or logos shall not be shown or mentioned in any way.
							</p>
							
							<h3>8. No Challenge to Proprietary Rights</h3>
							<p>
								You acknowledge that You obtain no proprietary rights to My-Meter.com trademarks, service marks, tradenames, URLs, copyrighted material, patents, and patent applications, and agree not to challenge My-Meter.com's proprietary rights.
							</p>
								
							<h3>9. Confidential and Proprietary Information</h3>
							<p>
								You understand that all reports and marketing literature/ material and proprietary data base information, provided to You by My-Meter are confidential, copyrighted and/or constitute proprietary information and business trade secrets belonging exclusively to My-Meter, unless obtained from independent outside sources, or from information in the public domain. You agree not to disclose the confidential and proprietary information of My-Meter, whether directly or indirectly, or whether explicitly or implicitly. You strictly agree not to use or allow others to use, any confidential and proprietary information of My-Meter in any way and for any purpose other than to promote and further my business and association with My-Meter.
							</p>
	
							<h3>10. Data Ownership</h3>
							<p>
								You understand that all personally identifiable information and other data, if any, provided by Visitors or collected through the Tracking Code or in response to an advertisement or request for information and / or any or all reports, results, and/or information created, compiled, analyzed and / or derived is the sole and exclusive property of My-Meter.com
							</p>
	
							<h3>11. Trade Practices</h3>
							<p>
								I also understand and agree that I will be responsible for complying with any and all laws and regulations relating to the operation of my business, and I agree not to engage in any deceptive or unlawful trade practice, as defined by federal state or local law. I further agree not to do or communicate anything, which would damage pH Miracle, unless compelled by law or subpoena.
							</p>
	
	
							<h3>12. Tax Status and Obligations</h3>
							<p>
								My-Meter is not obligated to and shall not provide You with tax and / or legal advice. My-Meter undertakes no duty to investigate or research Your tax status and / or obligations, and such research and investigation is solely Your responsibility. You are obligated to independently assess and comply with all relevant tax and legal requirements, and are responsible for Your own tax collection and reporting obligations arising from commission earned from My-Meter. Accordingly, as an independent sales associate / representative and operator of my own business, I will be responsible for the payments of my own taxes including federal and state income or business taxes, self employment taxes, and any other taxes relating to or arising from operation of my business or from commissions paid on sales pH Miracle products, goods or services.
							</p>
	
							<h3>13. Independent Sales Agent Relationship</h3>
							<p>
								You understand that as Independent Sales Associates to My-Meter that You are not an employee, franchisee, agent, partner or joint venture of, or licensor to My-Meter, even if You provide any services, information or material to My-Meter, which are outside the scope of my status as an Independent Sales Associate. You will not represent that You have any relationship with My-Meter other than that of an Independent Sales Associate. You understand that You are neither authorized to, nor will You for any reason, incur any debt, expense or obligation for or on behalf of My-Meter. You understand You will have discretion in how You promote My-Meter, and structuring events and opportunities for My-Meter, along with the sales of any of Your other business products and other product representations, provided You comply with the Terms and Conditions of this Agreement.
							</p>
							<br />
							<p>
								As an Independent Sales Associate, You understand and acknowledge that You will be responsible for Your own business activities and that You will have absolute control over the operation of Your business. You will be responsible for establishing Your own goals and business methods including purchasing any business materials and tools that You determine are necessary for the operation of Your business. You further affirm that You will positively represent My-Meter, and the goods and services that We market, and provide quality service to potential customers. 
							</p>
	
							<h3>14. Indemnification Obligations</h3>
							<p>
								You shall defend, indemnify and hold My-Meter.com harmless against all claims, suits, demands, damages, liabilities, losses, penalties, interest, settlements and judgments, costs and expenses (including attorneys' fees) incurred, claimed or sustained by third parties, directly or indirectly as a result of (a) Your breach of or non-compliance with this Agreement, (b) Your violation, or alleged violation, of any law, &copy; any content, goods or services offered, sold or otherwise made available by You to any person or entity, (d) Your acts or omissions in using, displaying or distributing any internet links obtained from the My-Meter or elsewhere, including but not limited to Your use of internet links via email distribution, (e) any claim that My-Meter.com is obligated to pay tax obligations in connection with payment made to You pursuant to this Agreement, and (f) any violation or alleged violation by You of any rights of another, including breach of a person's or entity's intellectual property rights (each (a)-(f) individually is referred to hereinafter as a "Claim"). Should any Claim give rise to a duty of indemnification under this Section 14, My-Meter shall promptly notify You, and My-Meter shall be entitled, at its own expense, and upon reasonable notice to You, to participate in the defense of such Claim. Participation in the defense shall not waive or reduce any of Your obligations to indemnify or hold My-Meter harmless.
							</p>
	
	
							<h3>15. Limitation of Liabilities</h3>
							<p>
								Any obligation or liability of My-Meter.com under this agreement shall be limited to the total of Your Payouts Paid to You by My-Meter under this agreement during the year preceding the claim.
							</p>
	
	
							<h3>16. Force Majeure</h3>
							<p>
								Neither party shall be liable by reason of any failure or delay in the performance of its obligations hereunder for any cause beyond the reasonable control of such party, including but not limited to electrical outages, failure of Internet service providers, default due to Internet disruption (including without limitation denial of service attacks), riots, insurrection, acts of terrorism, war (or similar), fires, flood, earthquakes, explosions, and other acts of God.
							</p>
							
							<h3>17. Term</h3>
							<p>
								This Agreement shall commence upon Your indication that You have accepted this Agreement by providing the required information and 'clicking through' the acceptance button at My-Meter.com and shall continue until terminated in accordance with the terms of this Agreement. This Agreement may be terminated by either party upon fifteen (15) business days' written notice. This Agreement may be terminated immediately upon notice of Your breach of this Agreement. Your Account may be deactivated and/or Payouts may be withheld during investigation of breach of this Agreement.
							</p>
	
							<h3>18. Post-termination</h3>
							<p>
								Upon termination of this Agreement, any outstanding payments shall be paid by My-Meter to You within ninety (60) business days of the termination date. Provisions of this Agreement that by their nature and context are intended to survive the termination of this Agreement shall survive the termination of this Agreement to the extent that and as long as is necessary to preserve a party's rights under this Agreement that accrued prior to termination.
							</p>
	
	
							<h3>19. Entire Agreement, Assignment and Amendment</h3>
							<p>
								This Agreement, including the Introduction, contains the entire understanding and agreement of the parties and there have been no promises, representations, agreements, warranties or undertakings by either of the parties, either oral or written, except as stated in this Agreement. This Agreement may only be altered, amended or modified by an instrument that is assented to by each party including written instrument signed by the parties or through a "click through" acknowledgement of assent. No interlineations to this Agreement shall be binding unless initialed by both parties. Notwithstanding the foregoing, My-Meter shall have the right to modify or amend ("Change") this Agreement, in whole or in part, by posting a revised Agreement at least fifteen (15) business days prior to the effective date of such Change. Your continued use of the Partners Program after the effective date of such Change shall be deemed Your acceptance of the revised Agreement.
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