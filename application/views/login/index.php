<?php   
   if(isset($_GET['logout']) && $_GET['logout'] == 1) {  
       setcookie('authorized', 0, time()-3600);  
   }   
?>  
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>prototype bs-listmgr</title>
<?php include_once('application/views/templates/jsandcss.php'); ?>
<script src="<?php echo base_url();?>assets/js/useredit.js" type="text/javascript"></script>
<body>
 <!-- Modal -->
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="form-signin-heading">Please sign in</h3>
            <form class="form-signin">
                <input type="text" name="uname" id="uname" class="form-control" placeholder="Username" autofocus>
                <br />
                <input type="text" name="email" id="email" class="form-control" placeholder="Email address">
                <div class="checkbox pull-left">
                  <label> <input type="checkbox" value=""> Remember me </label>
                </div>
            </form>
          </div>
          <div class="modal-footer">
             <button class="btn btn-lg btn-primary" onclick="javascript: doLogin();">Sign in</button>            
          </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
<!--  <div class="contentwrapper"> -->
<!--
    <div id="user-area"></div>
    <h2 class="form-signin-heading">Please sign in</h2>
      <form class="form-signin">
        <fieldset>
          <p class="validateTips">Please login.</p>
          <input type="text" name="uname" id="uname" class="form-control" placeholder="Username" autofocus>
          <input type="text" name="email" id="email" class="form-control" placeholder="Email address">
          <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
           </label>
           <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>            
        </fieldset>
      </form>
    </div>  
    <div id="dlg-forgot" title="Reset Password">
      <form>
        <fieldset>
            <label for="uname3">Username</label>
            <input type="text" name="uname3" id="uname3" class="text ui-widget-content ui-corner-all" />
            <br />
            <label for="email3">Email</label>
            <input type="text" name="email3" id="email3" value="" class="text ui-widget-content ui-corner-all" />
        </fieldset>
      </form>
    </div>'
-->    
<!-- contentwrapper -->
</body>
</html>
