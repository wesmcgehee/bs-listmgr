<script src="<?php echo base_url();?>assets/js/useredit.js" type="text/javascript"></script>
<div id="user-area"></div>
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
       <h3 class="form-signin-heading"><?php echo $title;?></h3>
    </div>
    <form id="frmformat" class="input-medium userform">
          <!-- <div id="dlg-edituser"> -->
          <p class="validateTips">All form fields are required.</p>
          <label for="fname">First Name</label>
          <input type="text" name="fname" id="fname" class="form-control" value="<?php echo $users['fname'];?>" />
          <label for="lname">Last Name</label>
          <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $users['lname'];?>" />
          <label for="uname">Username</label>
          <input type="text" name="uname" id="uname" class="form-control" value="<?php echo $users['uname'];?>"/>
          <label for="email">Email</label>
          <input type="text" name="email" id="email" class="form-control" value="<?php echo $users['email'];?>"/>
          <!-- <label for="pword">Password</label>
               <input type="password" name="pword" id="pword" class="text ui-widget-content ui-corner-all" value="<?php echo $users['pword'];?>"/> -->
          <input type="hidden" name="userid" value="<?php echo $users['userid'];?>"/>
          <div class="modal-footer">
             <button type="button" onclick="javascript: doGoHome(); return false;" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Cancel</button>            
             <button type="button" onclick="javascript: doUpdate(); return false;" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save</button>            
          </div>
    </form>
    <!-- </div> modal-header -->
  </div> <!-- modal-content -->
</div> <!-- modal-dialog -->  
<div id="err-box" class="alert alert-dismissable">
    
</div>
