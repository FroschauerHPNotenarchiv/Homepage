<!--  -------------------------------------------------------------------------------------------- -->
	<!-- Trigger the modal with a button -->
	<!-- PasswordAlterationModal -->
	<button type="button" id="login_button" style="width: 100%; background-color: #717070; margin-right: 20px" class="btn btn-info btn-lg" data-toggle="modal" data-target="#LoginModal">Login</button>
	<button type="button" id="logout_button" style="width: 100%; background-color: #717070; margin-right: 20px" class="btn btn-info btn-lg">Logout</button>
	<button type="button" id="request_password_resubmission_button" style="display:none" data-toggle="modal" data-target="#LoginModal">Request Password Resubmission</button>

	<!-- Modal -->
	<div class="modal fade" id="LoginModal" role="dialog" data-backdrop="static" data-keyboard="false">

	<div class="modal-dialog" >

	  <!-- Modal content-->
	  <form action="?checkRequestPassword" method="post" class="modal-content" id="form_modal" >
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title" id="tag01">Anmeldung</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<p id="modal_msg"></p>
			</div>
		  <div class="form-group">
			<label for="usr" style="color: black" id="tag02">Name</label>
			<input type="text" name="email" class="form-control" id="usr">
		  </div>
		  <div class="form-group">
			<label for="pwd" style="color: black" id="tag03">Passwort</label>
			<input type="password" name="password" class="form-control" id="pwd">
		  </div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" id="confirmLogin">Anmelden</button>
		  <button type="button" class="btn btn-default" id="dismissButton" data-dismiss="modal">Beenden</button>
		  <br />
		  <p id="modal_message"></p>
		</div>
	  </form>
	  
	</div>
	
	<!-- ----------------------------------------------------------------------------------------------- -->
	
	<!-- <?php //include_once "dialog_user_password_alteration.php" ?> -->
	
	<!-- ---------------------------------------------------------------------------------------------- -->