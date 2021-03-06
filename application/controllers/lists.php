<?php
  define("GROCERY_TYPE",1);
  
class Lists extends CI_Controller {
    //static $userid;
    public function __construct() 
    {
       parent::__construct();
       $this->load->model( 'lists_model' );
       $this->load->helper(array('url','html'));
       $userid = $this->_verify_userid();
       //$this->output->enable_profiler(TRUE); 
    }
   
    public function index()
    {
	$data['title'] = 'Edit Items';
        $userid = $this->_verify_userid();	
        $query = $this->lists_model->get_groups($userid, GROCERY_TYPE);
        foreach($query as $grp){
          $grps[$grp->grpid] = $grp->type;
        }
        $data['groups'] = $grps;
        $query = $this->lists_model->get_grpitems($userid);
        $data['items'] = $query;
        $query = $this->lists_model->get_shopgrps($userid);
        $data['picks'] = $query;
        $this->load->view('templates/header', $data);
        $this->load->view('lists/index', $data);
    	$this->load->view('templates/footer');
    }
 
    function gettypes()  
    {
       $this->load->helper('form');
       $typid = $this->input->post('typeid');
       $descr = $this->input->post('descr');
       if ($typid) 
       {
           $userid = $this->_verify_userid();	
           $typs = array(0 => ADD_NEW_REC);
           $typitems = $this->lists_model->get_lsttypes($userid, GROCERY_TYPE);
           foreach($typitems as $typ){
             $typs[$typ->typeid] = $typ->tdescr;
           }
           $js = 'id="frm-dropdown" class="dropdown"';
           echo form_label('List Types','frm-dropdown');
           echo '<br />';
           echo form_dropdown('frm-dropdown',$typs,'',$js);
           echo '<br />';
           echo '<br />';
           echo form_label('List Description','frm-descr');
           echo '<input type="text" id="frm-descr" value="'.$descr.'"/>';
           echo '<input type="hidden" id="typidnbr" value="'.$typid.'"/>';
           echo '<br />';
           $btnattr = array( 'name' => 'delete',
                             'id' => 'frmdel',
                             'class' => 'frmdel',
                             'content' => 'Delete');
           echo form_button($btnattr);        
           $btnattr = array( 'name' => 'update',
                             'id' => 'frmupd',
                             'class' => 'frmupd',
                             'content' => 'Update');
           echo form_button($btnattr);        
           $btnattr = array( 'name' => 'frmout',
                             'id' => 'frmout',
                             'class' => 'frmout',
                             'content' => 'Cancel');
           echo form_button($btnattr);        
           echo form_fieldset_close();
           $formattr = "</div></div>";
           echo $formattr;
          
       } else { 
          echo '<option> value="-1">Nothing here...</option>';
       }
       exit;
    } 
    function getitems()  
    {
      $grpid = $this->input->post('grpid');
      $userid = $this->_verify_userid();	
      if ($grpid && $userid) 
      {
        $itms = array(0 => ADD_NEW_REC);
        $grpitems = $this->lists_model->get_grpitems($userid, $grpid);
        foreach($grpitems as $itm){
          $itms[$itm->itemid] = $itm->item;
        }
		//prepend array with blank key/value of nothing
		$itms = array("-1" => "")+$itms;
		//echo '<div class="form-group">';
        $js = 'id="itm-dropdown" class="form-control" onChange="showItmDescr();"';
        echo form_label('Available Items','itm-dropdown');
        echo form_dropdown('itm-dropdown',$itms,'',$js);
	  } else { 
	    echo '<option> value="-1">Nothing here...</option>';
      }
      exit;
    } 
    function getgroups()  
    {
        $gid = $this->input->post('grpid');  //set default in dropdown
        $grps = array();
        $userid = $this->_verify_userid();	
        $query = $this->lists_model->get_groups($userid, GROCERY_TYPE);	
    	foreach($query as $grp){
    	  $grps[$grp->grpid] = $grp->type;
    	}
        print_r($gid.' grps='.$grps);
    	$js = 'id="mov-dropdown" class="dropdown"';
    	echo form_label('Assigned Group','mov-dropdown');
    	echo '<br />';
    	echo form_dropdown('mov-dropdown',$grps,$gid,$js);
    	echo '<br />';
        exit;
    } 
    public function savenote()  
    {
        $rtn = '';
        $qtyarray = $this->input->post('qtyarray');
	$showlist = $this->input->post('showlist');
        if(isset($qtyarray) && is_array($qtyarray)) {
          $rec = array();
          // build key = itemid and period delimited string of selected qty details for item
          foreach($qtyarray as $itm){
            if(array_key_exists($itm['id'],$rec)){
               $rec[$itm['id']] = $itm['str'];
               //print_r($rec[$itm['id']].' '.$rec[$itm['str']]);
            } else {
               $rec[$itm['id']] = $itm['str'];
            }  
          }
          //print_r($rec);
          $userid = $this->_verify_userid();
          if(!$this->lists_model->update_shoplist($userid, $rec)){
            $rtn = 'Error updating user selected list';
          }
	  if(isset($showlist) && $showlist)
             $this->showpicks();
        }
        echo $rtn;
    } 
    public function updpicks()  
    {
        $rtn = '';
        $input = $this->input->post('piks');
        if(isset($input) && is_array($input)) {
          $rec = array();
          // build key = grpid and period delimited string of selected itemids
          foreach($input as $itm){
            if(array_key_exists($itm['gd'],$rec)){
               $rec[$itm['gd']] = $rec[$itm['gd']].'.'.$itm['id'];
            } else {
               $rec[$itm['gd']] = $itm['id'];
            }  
          }
          $userid = $this->_verify_userid();
          if(!$this->lists_model->update_shoppick($userid, $rec)){
            $rtn = 'Error updating user selected list';
          }
          $this->showpicks();
        }
        echo $rtn;
    }
    public function savedits()  
    {
        $rtn = '';
	$updid = 0;
        $mode = $this->input->post('mode');
        //echo 'rtn['.$rtn.'] mode['.$mode.']';
        if(isset($mode))
	{
	  $userid = $this->_verify_userid();
          $grpid = $this->input->post('grpid');
          $itemid = $this->input->post('itmid');
	  $gdesc = $this->input->post('gdesc');
          $idesc = $this->input->post('idesc');
	  $gxval = $this->input->post('gxval');
	  $ixval = $this->input->post('ixval');
	  $gchg = ($gxval != $gdesc) && (strlen(trim($gdesc)) > 0) && (stripos($gdesc,ADD_NEW_REC) === false);
	  $ichg = ($ixval != $idesc) && (strlen(trim($idesc)) > 0) && (stripos($idesc,ADD_NEW_REC) === false);
          $idel = $mode === DELETE_REC && !$ichg && $itemid > 0;  // one child in the group
          $gdel = $mode === DELETE_REC && !$ichg && $itemid == 0; // delete group and all children
	  //$rtn .= 'mode['.$mode.'] gdel='. $gdel ? 'yes ' : 'no ';
	  //$this->lists_model->_logerror('ERROR','lists-controller-['.$rtn.']');
	  if($gchg || $gdel)
	  {
             //$rtn .= 'Updated Group Id('.$grpid.')=['.$gdesc.'] DELGRP='.($gdel) ? 'True ' : 'False ';
             $updid = $this->lists_model->update_group_rec($mode,$userid,$grpid,$gdesc);
	  } else {
	     $updid = $grpid;
	  }
	  if(($updid > 0 && $ichg) || $idel)
	  {
	    //$tmp = $ichg ? 'true' : 'false';
	    //$rtn .= '[(ichg='.$tmp.' and updid('.$updid.')!=0 OR idel=true] update item for group';
            if(!$this->lists_model->update_item($mode,$updid,$itemid,$idesc)){
                $rtn = 'Error updating item record';
            } else {
		$rtn = ' Updated Item Id('.$itemid.')=['.$idesc.']';
            }
          //} else {
          //  $rtn .= 'ichg==false && updid==0 no update to items for groupid=='.$grpid;
	  }
 	}
        //$this->lists_model->_logerror('ERROR','lists-controller-['.$rtn.']');
        echo $rtn;
    } 
    public function upditem()  
    {
        $rtn = 'Success updating record';
        $mode = $this->input->post('mode');
        //echo 'rtn['.$rtn.'] mode['.$mode.']';
        if(isset($mode)) {
          $grpid = $this->input->post('grpid');
          $itemid = $this->input->post('itmid');
          $descr = $this->input->post('descr');
          $rtn = 'mode '+$mode+' grpid '+$grpid+' itemid '+$itemid+' descr'+$descr;
          if(!$this->lists_model->update_item($mode,$grpid,$itemid,$descr)){
            $rtn = 'Error updating user selected list';
          }
        } else {
	  $rtn = 'Error mode not set';
	}
        echo $rtn;
    }
    public function updgroup()  
    {
      $rtn = 'pre-update';
      $mode = $this->input->post('mode');
        //echo 'rtn['.$rtn.'] mode['.$mode.']';
      if(isset($mode))
	  {
          $grpid = $this->input->post('grpid');
          $descr = $this->input->post('descr');
          $typid = GROCERY_TYPE;
          $rtn = 'mode '.$mode.' grpid '.$grpid.' descr '.$descr.' type='.$typid;
          if(!$this->lists_model->update_group($mode,$grpid,$descr,$typid)){
            $rtn = 'Error updating group table';
          }
      }
      echo $rtn;
    }
    public function findform()
    {
      $this->load->helper('form');
      echo '<script src="'.base_url().'assets/js/itemfind.js" type="text/javascript"></script>';
      echo '<div class="gridcolumn">';
      echo '<div id="fndform" class="ui-widget-content">';
      $frmtitle = 'Search for Item LIKE';
      //echo form_fieldset('<b><style="text-align:center;">'.$frmtitle.'</style></b>');
	      echo form_fieldset('<b>');
	      echo "<label for='searchstr'>".$frmtitle."</label>";
      echo "<input type='text' name='searchstr' id='searchstr' value='' maxlength='80' size='80' style='width:80%;'/>";
      echo '<br/>';
	      
      $btnattr = array( 'name' => 'findbtn',
			'id' => 'findbtn',
			'class' => 'findbtn',
			'content' => 'Search');
      echo form_button($btnattr);        
      
      $btnattr = array( 'name' => 'nonebtn',
			'id' => 'nonebtn',
			'class' => 'nonebtn',
			'content' => 'Cancel');
      echo form_button($btnattr);        
	      echo form_fieldset_close();
	      
      $formattr = "</div></div>";
      echo $formattr;
	return;
    }
    public function getform()
    {
        $this->load->helper('form');
                
        $which = GROUP_STR;
        $descr = '';
        $grpid = 0;
        $itmid = 0;
        
        $which = $this->input->post('which');
        $grpid = $this->input->post('grpid');
    	$itmid = $this->input->post('itmid');
        $descr = $this->input->post('descr');
        
        $incitem = (isset($which) &&  strtolower($which) == ITEM_STR) ? true : false; // include item or group only update
        
        if($incitem) {
           $descr = (strlen($descr) > 1 || $itmid > 0) ? $descr : ADD_NEW_REC;
           $grps = $this->_get_allgrps(false);
        } else {
           $grps = $this->_get_allgrps();
        }
        echo '<script src="'.base_url().'assets/js/itemgrd2.js" type="text/javascript"></script>';
        echo '<div class="gridcolumn">';
        echo '<div id="itmform" class="ui-widget-content">';
        
        $frmtitle = $incitem ? 'Item Information' : 'Group Information';        
        echo form_fieldset('<b><style="text-align:center;">'.$frmtitle.'</style></b>');
        
        $js = 'id="frm-dropdown" class="dropdown"';
        
        $ddltitle = $incitem ? 'Assigned Group' : 'Selections';        
        echo form_label($ddltitle,'frm-dropdown');
        echo form_dropdown('frm-dropdown',$grps,$grpid,$js);
        
        $ddltitle = $incitem ? 'Item Description' : 'Group Description';        
        echo form_label($ddltitle,'frm-descr');
                
        echo '<input type="text" id="frm-descr" value="'.$descr.'"/>';
        
        echo '<input type="hidden" id="itmidnbr" value="'.$itmid.'"/>';
        echo '<input type="hidden" id="grpidnbr" value="'.$grpid.'"/>';
        echo '<input type="hidden" id="editwhat" value="'.$which.'"/>';
        echo '<br />';

        $btnattr = array( 'name' => 'delete',
                          'id' => 'frmdel',
                          'class' => 'frmdel',
                          'content' => 'Delete');
        echo form_button($btnattr);        
        
        $btnattr = array( 'name' => 'update',
                          'id' => 'frmupd',
                          'class' => 'frmupd',
                          'content' => 'Update');
        echo form_button($btnattr);        
        
        $btnattr = array( 'name' => 'frmout',
                          'id' => 'frmout',
                          'class' => 'frmout',
                          'content' => 'Cancel');
        echo form_button($btnattr);        
        echo form_fieldset_close();
        $formattr = "</div></div>";
        echo $formattr;
        //echo form_close($formattr);
      return;
    } 
    public function itemgrid()
    {
        $this->load->library('pagination');
        $this->load->library('table');
        $data['listtype'] = 'Grocery List';
        $ajaxcall = false;
        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
           $ajaxcall = true;
        }
        $config['base_url'] = base_url().'index.php?lists/itemgrid';
        $config['total_rows'] =  $this->lists_model->item_count();
        $config['per_page'] = 18;
    	$config['num_links'] = 10; // number of numeric pages shown 
        $config['uri_segment'] = 3;
    	$config['full_tag_open'] = '<div class="pagination-digg">';
    	$config['full_tag_close'] = '</div>';
    	$this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	    // generate table data
        $query = $this->lists_model->get_allitems($config["per_page"], $page);
    	$tmplate = array ('table_open'  => '<table id="gridtable" border="1" cellpadding="1" cellspacing="1" class="ui-widget-content">' );
        $this->table->set_template($tmplate);
        $this->table->set_empty('&nbsp;');
    	$tbl_heading = array(
            '0' => array('data' => 'Group',           'style' => 'width: 20%;'),
            '1' => array('data' => 'Item Description', 'style' => 'width: 75%;'),
            '2' => array('data' => 'Edit', 'style' => 'text-align: center; width: 5%;'));
        $this->table->set_heading($tbl_heading);
        
        foreach($query as $row) {
           $txt = $row->type.'</td><td>'.$row->item.'</td>';
           $act = '<td><a class="editthis" href="#" onclick="javascript:displayItem('.$row->gid.','.$row->iid.',\''.$row->item.'\')">        <img src="'.base_url().'images/edit.gif" width="12" height="12"/>         </a>';
           $this->table->add_row($txt.$act);
        }
        if($ajaxcall){
           $this->load->view('lists/itempage',$data);
        } else {
           $this->load->view('templates/header', $data);
           $this->load->view('lists/itemgrid',$data);
           $this->load->view('templates/footer');
        }
    }
    public function itemfind()
    {
        $reply = '';
        $this->load->library('table');
        $strtofind = $this->input->post('descr');
        $ajaxcall = false;
        $data['title'] = 'Search with Wildcard';
        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
           $ajaxcall = true;
        }
        if($ajaxcall){
           if(isset($strtofind) && strlen($strtofind) > 1) {
              $query = $this->lists_model->find_like($strtofind);
              if($query) {
                 echo '<script src="'.base_url().'assets/js/itemgrd1.js" type="text/javascript"></script>';
                 $tmplate = array ('table_open'  => '<table id="gridtable" border="1" cellpadding="1" cellspacing="1" class="ui-widget ui-widget-content">' );
                 $this->table->set_template($tmplate);
                 $this->table->set_empty('&nbsp;');
				 if(TEST_MODE) {
                    $tbl_heading = array(
                         '0' => array('data' => 'TId', 'style' => 'text-align: center','style' => 'width: 10'),
                         '1' => array('data' => 'GId', 'style' => 'text-align: center', 'style' => 'width: 10'),
                         '2' => array('data' => 'GDescrip', 'style' => 'width: 100px', 'style' => 'white-space: nowrap;'),
                         '3' => array('data' => 'Id', 'style' => 'text-align: center', 'style' => 'width: 10'),
                         '4' => array('data' => 'Description', 'style' => 'width: 200px', 'style' => 'white-space: nowrap;'),
                         '5' => array('data' => 'Edit', 'style' => 'text-align: center'));
                    $this->table->set_heading($tbl_heading);
                    foreach($query as $row) {
					  $txt = $row->tid.'</td>';
					  $txt .= '<td>'.$row->gid.'</td><td>'.$row->gdescr.'</td>';
					  $txt .= '<td>'.$row->iid.'</td><td>'.$row->idescr.'</td>';
					  $act = '<td><a class="editthis" href="#" onclick="javascript:displayItem('.$row->gid.','.$row->iid.',\''.$row->idescr.'\')">        <img src="'.base_url().'images/edit.gif" width="12" height="12"/>         </a>';
					  $this->table->add_row($txt.$act);
					}
                 } else {
                    $tbl_heading = array(
					  '0' => array('data' => 'Group',           'style' => 'width: 20%;'),
					  '1' => array('data' => 'Item Description', 'style' => 'width: 75%;'),
					  '2' => array('data' => 'Edit', 'style' => 'text-align: center; width: 5%;'));
				    $this->table->set_heading($tbl_heading);
                    foreach($query as $row) {
					  $txt = $row->gdescr.'</td>';
					  $txt .= '<td>'.$row->idescr.'</td>';
					  $act = '<td><a class="editthis" href="#" onclick="javascript:displayItem('.$row->gid.','.$row->iid.',\''.$row->idescr.'\')">        <img src="'.base_url().'images/edit.gif" width="12" height="12"/></a>';
					  $this->table->add_row($txt.$act);
					}
				 }
                 echo $this->table->generate();
              }
              else {
                $reply = 'nothing found';
              }
           } else {
              $reply = 'nothing sent to find';
           }
           if(strlen($reply) > 0)
              echo $reply;
        } else {
           $this->load->view('templates/header', $data);
           $this->load->view('lists/itemfind',$data);
           $this->load->view('templates/footer');
        }
    }
    // Get all stored selected items and linked desc (quantities, etc)
    public function showpicks()
    {
        $this->load->library('table');
        $tmplate = array ( 'table_open'  => '<table id="showpicks" cols="2" border="0" cellpadding="2" cellspacing="1">' );
        $this->table->set_template($tmplate);
        $userid = $this->_verify_userid();	
        $query = $this->lists_model->get_groups($userid, GROCERY_TYPE);	
        foreach($query as $grp){
          $grps[$grp->grpid] = $grp->type;
        }
        $userid = $this->_verify_userid();
        $dtail = $this->lists_model->get_shoplist($userid);
        $query = $this->lists_model->get_shopgrps($userid);
        foreach($query as $k => $v){
            $iarr = $this->lists_model->get_usergrps($v);
            $this->table->add_row('<b>'.$grps[$k].'</b>');
            foreach($iarr as $s => $t){
              $desc = '';
              if(count($dtail) >= 0) {
                foreach($dtail as $i => $j){
                    if($s == $i){
                       $desc = $j;
                       break;
                    }
                }
               }
               $k++;
               $txt = '<input type="text" style="margin:0; padding:0;" size="10" maxlength="20" id="txt.'.$s.'" name="txt.'.$s.'" value="'.$desc.'" />&nbsp;&nbsp;</td><td>';
               $this->table->add_row($txt.$t);
            }
        }
	    echo $this->table->generate();
    }
   
    function _get_allgrps($addnew=true)
    {
        if($addnew)
    	   $grps = array(0 => ADD_NEW_REC);
        else
    	   $grps = array(0 => '--Select--'); // force onchange event
           
        $userid = $this->_verify_userid();	
        $query = $this->lists_model->get_groups($userid, GROCERY_TYPE);
    	foreach($query as $grp){
    	  $grps[$grp->grpid] = $grp->type;
    	}
    	return $grps;
    }
    function _verify_userid()
    {
       $sproc = new siteprocs();
       $uid = $sproc->getLoginId();      
       if($uid <= 0)
         $this->lists_model->_logerror('ERROR','User id is not set in _verify_userid()');
      return $uid;	 
  }
}
