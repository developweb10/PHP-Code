<div id="register_order_successModal" class="modal fade" role="dialog" >

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title text-center">Thank you for your order!</h4>

            </div>

            <div class="modal-body">

                <div class='text-center'>

                    <img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" >

                </div>                        

                <Br clear="all" />

                <div class="alert alert-success">
                	
                    @if(isset($user->role_id) && $user->role_id)
                    
	                    A login password has been sent to client's email address. Please ask him to check email and follow the instructions to access account.
                    
                    @else

	                    A login password has been sent to your email address. Please check your email and follow the instructions to access your account.
                        
                    @endif

                    <div class="clearfix"></div>

                </div>

            </div>

        </div>

    </div>

</div>
<script type="application/javascript">

    document.addEventListener( "DOMContentLoaded", function(){

        $("#register_order_successModal").modal("show");

    }, false );

</script>