<div class="container-fluid">

    <div class="row">

        <div class="">



        	<div class="panel panel-default">

        		<!-- <div class="panel-heading">Marketing</div> -->

        		<div class="panel-body">

						@if ((!config('is_Ipad')) && config('is_mobile'))  
                            <span class="to-text page_title"><b>Marketing</b></span>
                        @endif

        			<div class="row">

        				

                        <?php /*

        				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="min-height:260px; display:table;">

                        	<div style="display:table-cell; vertical-align:middle;">

                                {{ app('App\Http\Controllers\UtilsController')->html_decode($vars["marketing_data"]->marketing_text) }}

                            </div>

        				</div>

                        */ ?>

                        <div class="col-lg-2 col-md-2"></div>

        				

        				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-center ">

                            <h3 class="text-center">Business Card</h3>

                        	<div class="col-lg-12 col-md-12 btn-default" style="min-height:260px;display:table;width:100%" >

                               <!-- <a class="btn btn-default btn-lg" href="{{ $vars["businesscard"] }}" title="Download Business Card" target="_blank" style="position:absolute;     width: 176px; border-radius: 0;" >

                                   <i class="fa fa-download" aria-hidden="true"></i> Download

                                </a>-->

                                <a href="{{ $vars["businesscard"] }}" title="Download Business Card" target="_blank" download style="display:table-cell; vertical-align:middle;" >

                                	<img src="{{ $vars["businesscard_img"] }}" border="0" width="200" />

                                </a>

                                <!--<object data="{{ $vars["businesscard"] }}" type="application/pdf" width="180" height="310">

                                </object>-->

                            </div>

                            <a href="{{ $vars["businesscard"] }}" title="Download Business Card" target="_blank" download >

                                 <button class="btn btn-default btn-lg btn-block">Download <i class="fa fa-download" aria-hidden="true"></i></button>

                            </a>

        				</div>

        				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-center">

                            <h3 class="text-center">Marketing Brochure</h3>

                        	<div class="col-lg-12 col-md-12 btn-default" style="min-height:260px;" >

                                <!--<a class="btn btn-default btn-lg" href="{{ $vars["brochure"] }}" title="Download Brochure" target="_blank" style="position:absolute;     width: 176px; border-radius: 0;" >

                                   <i class="fa fa-download" aria-hidden="true"></i> Download

                                </a>-->	

                                <a href="{{ $vars["brochure"] }}" title="Download Brochure" target="_blank" download >

                                    <img src="{{ $vars["brochure_img"] }}" border="0" width="200" />

                                </a>

                                <!--<object data="{{ $vars["brochure"] }}" type="application/pdf" width="180" height="310">

                                </object>-->

                            </div>

                            <a href="{{ $vars["brochure"] }}" title="Download Brochure" target="_blank" download >

                                <button class="btn btn-default btn-lg btn-block">Download <i class="fa fa-download" aria-hidden="true"></i></button>

                            </a>

        				</div>

        				

        			</div>



        		</div>

        	</div>

        

        </div>

    </div>

</div>



<style type="text/css">a:hover, a:focus{ text-decoration: none;  }</style>