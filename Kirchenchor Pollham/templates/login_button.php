<!--  -------------------------------------------------------------------------------------------- -->
  <!-- Trigger the modal with a button -->
  <button type="button" id="login_button" style="float: right; margin-right: 20px" class="btn btn-info btn-lg" data-toggle="modal" data-target="#LoginModal">Login</button>
  <button type="button" id="logout_button" style="float: right; margin-right: 20px" class="btn btn-info btn-lg">Logout</button>

  <!-- Modal -->
  <div class="modal fade" id="LoginModal" role="dialog">
  
	<div class="modal-dialog">
	
	  <!-- Modal content-->
	  <form action="Mitglieder.php" method="post" class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Anmeldung</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<label for="usr">Name:</label>
			<input type="text" name="email" class="form-control" id="usr">
		  </div>
		  <div class="form-group">
			<label for="pwd">Password:</label>
			<input type="password" name="password" class="form-control" id="pwd">
		  </div>
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-default">Anmelden</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">Beenden</button>
		</div>
	  </form>
	  
	</div>
<!-- ---------------------------------------------------------------------------------------------- -->