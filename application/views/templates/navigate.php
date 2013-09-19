  <!-- Fixed navbar -->
  <div class="navbar navbar-inverse navbar-custom navbar-static-top" role="navigation">
    <div class="container">   
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"  href="<?php echo base_url();?>">Prototype List Manager</a>
      </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">      
      <!-- Begin menu markup -->
      <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo base_url();?>index.php?lists">Groceries</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Images <b class="caret"></b></a>
               <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
                  <li><a href="<?php echo base_url();?>index.php?upload">Upload Files</a></li>
                  <li><a href="<?php echo base_url();?>index.php?gallery">View Gallery</a></li>
                  <li><a href="<?php echo base_url();?>index.php?gallery/images">Edit Images</a></li>
                  <li><a href="<?php echo base_url();?>index.php?gallery/cleanup">Cleanup User Files</a></li>
                 </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Test Stuff <b class="caret"></b></a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu0">
                 <li><a href="<?php echo base_url();?>index.php?news">News Feed</a></li>
                 <li><a href="<?php echo base_url();?>index.php?smileys">Smileys</a></li>
                 <li><a href="<?php echo base_url();?>index.php?lists/itemgrid">Edit Groups/Items</a>
                 <li><a href="<?php echo base_url();?>index.php?users">User Grid</a></li>
              </ul>
            </li>
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Help<b class="caret"></b></a>
              <ul class="dropdown-menu">
                 <li><a href="<?php echo base_url();?>index.php?session">Dev-Session</a></li>
                 <li><a href="<?php echo base_url();?>index.php?login">Login Form</a></li>
                 <li><a href="<?php echo base_url();?>index.php?login/userform">User Settings</a></li>
                 <li><a href="<?php echo base_url();?>index.php?login/signout">Logout</a></li>
                 <li><a href="<?php echo base_url();?>index.php?about">About</a></li>
               </ul>
          </li>
        </ul> <!--flyoutmenu-->
        <?php $uname = getLoginName();
           if($uname != '') { ?>
                <form class="navbar-form navbar-right">
                   <div class="form-group">
                      <div class="navbar-text pull-right"><h5 class="text-cyan">Logged in as <?php echo $uname; ?>&nbsp;&nbsp;&nbsp;&nbsp;</h5></div>
                   </div>
                    <button type="submit" class="btn btn-success" onclick="javascript: doLogout();">Sign out</button>
                </form>
        <?php } else {
           header("Location: index.php?login");
         } ?>
      </div><!--/.navbar-collapse -->
    </div> <!-- container -->
   </div> <!-- navbar navbar-inverse navbar-fixed-top -->
</div><!-- navbar-top -->   
 <div class="container"> <!-- top content div closed in footer -->
<script type="text/javascript">
  function doLogout()
  {
      $.ajax({
       type: 'POST',
		url: 'index.php?login/signout',
		cache: false,
		async: false,
        dataType: 'json',
		success:
		  function(data){
            console.log('signout-success-signed out...');
		},
		beforeSend: function(){
		},
		complete: function (xhr, status) {
		    if (status === 'error' || !xhr.responseText) {
		       console.log('signout-complete-status=error');
		    }
        },
		error: function(response) {
		    console.log('signout-ajax-error: '+response.status + ' statusText: ' + response.statusText);
		}
       });
  }
</script>
<?php
   /**
    * getLoginName
    * @param   none
    * @return  logged in name
   */
    function getLoginName() {
 	    $rtn = '';
        $ci = get_instance();
	    $ci->load->library('session');
	    $sarr = $ci->session->all_userdata();
        if (isset($sarr['fname']) && $sarr['fname'] != "") {
          $rtn = $sarr['fname'];
	    }
	    return $rtn;
    }
?>