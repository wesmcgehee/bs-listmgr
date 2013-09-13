<?php
     function getUserAgent()
    {
        $ci = get_instance();
        $ci->load->library('user_agent');
        
        if ($ci->agent->is_browser())
        {
            $agent = $ci->agent->browser().' ver '.$ci->agent->version();
        }
        elseif ($ci->agent->is_robot())
        {
            $agent = $ci->agent->robot();
        }
        elseif ($ci->agent->is_mobile())
        {
            $agent = $ci->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }
        
        echo '<h6> agent: '.$agent;
        
        echo '  platform: '.$ci->agent->platform().' </h6>'; // Platform info (Windows, Linux, Mac, etc.)
    }
?>
<div class="jumbotron">
  <div class="container">
    <?php if(TEST_MODE) { ?>
       <hr />
       <h6>This is a "static" HOME page. You may change the content of this page
          by updating the file <tt><?php echo __FILE__; ?></tt>.</h6>
       </p>
    <?php getUserAgent(); } ?>
  </div>  
</div>