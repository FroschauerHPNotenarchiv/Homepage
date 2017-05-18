<?php

	require_once "admin_user_administration_func.php";
	require_once "admin_constants.php";

?>

<!-- Modal -->
	<div class="modal fade" id="Activities_Modal" role="dialog" data-backdrop="static" data-keyboard="false">

	<div class="modal-dialog" >

	  <!-- Modal content-->
	  <form action="" method="post" class="modal-content" id="form_modal" >
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title" id="tag01">Konfiguration</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<p id="modal_msg"></p>
			</div>
		  <div class="form-group">
			<label for="usr" style="color: black" id="tag02">Titel: 
			<input type="text" name="email" class="form-control" id="usr"></label>
		  </div>
		  <div class="form-group">
			<label for="pwd" style="color: black" id="tag03">Beschreibung: 
			<input type="password" name="password" class="form-control" id="pwd"></label>
		  </div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" id="activityConfigurationConfirmation">Bestätigen</button>
		  <button type="button" class="btn btn-default" id="activityConfigurationDismiss" data-dismiss="modal">Abbrechen</button>
		  <br />
		  <p id="modal_message"></p>
		</div>
	  </form>
	  
	</div>