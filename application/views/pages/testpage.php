<?php
    function getSessionData()
    {
        $ci = get_instance();
        $ci->load->library('session');
        $sarr = $ci->session->all_userdata();
        var_dump($sarr);
        echo '<br />';
        if(isset($sarr['usrid']) && $sarr['usrid'] &&
           isset($sarr['fname'])      && $sarr['fname'])
          print_r('userid: '.$sarr['usrid'].' fname: '.$sarr['fname']);
    	echo 'done...';
    }
    function getCacheInfo()
    {
        $ci = get_instance();
        $ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        var_dump($ci->cache->cache_info());
        $ci->cache->clean();
    }
    function showImageConvertPath()
    {
      //system("/usr/local/bin/convert -version");
      echo "<pre>";
        system("type convert"); 
      echo "</pre>";
    }
    function imageMagikVersion()
    {
        exec("/usr/bin/convert -version",$out,$returnval);
        print_r($out[0]);
    }
    function displayFileExist($file)
    {
        if (file_exists($file)) {
           echo "The file $file exists";
        } else {
           echo "The file $file does not exist";
        }        
    }
?>
<div id="about">
   <h1>Developer Test Page</h1>
   <?php getSessionData(); ?>
   <p />
   <p>

<!-- Single button -->
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Edit <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Group</a></li>
    <li><a href="#">Item</a></li>
    <li><a href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div>
</div>
   </p>
      <?php $path = base_url();
            $file = 'style/jcrop/jquery.Jcrop.css';
         echo displayFileExist($file); ?>


</div>
