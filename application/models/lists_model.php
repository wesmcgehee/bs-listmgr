<?php
  class Lists_model extends CI_Model {
  /* Todo:
   * 1) Make all tbl_lsttype, tbl_lstgrp, tbl_lstitem userid-specific for more generic use
   * 2) Check out the static qulifier as well as the deeper oop stuff for php
  */
  public function __construct()
  {
   $this->load->database();
   $this->load->helper('date');
   $this->load->library('siteprocs'); // for common string functions
  }

  public function get_lsttypes($userid = 0, $typeid = GROCERY_TYPE) {
     $query = array();
     $this->db->select('tbl_lsttype.userid as userid, tbl_lsttype.typeid as typeid,tbl_lsttype.descr as tdescr');
     if($userid > 0)
        $query = $this->db->where('userid',$userid);
     if($typeid > 0)
        $query = $this->db->where('typeid',$typeid);
     else
        $query = $this->db->where('typeid >',0);
     $this->db->from('tbl_lsttype');
     $this->db->order_by('tdescr'); 
     $query = $this->db->get();
     return $query->result();
  } //end get_lsttypes

  public function get_groups($userid = 0, $typeid = GROCERY_TYPE) {
     $query = array();
     $this->db->select('tbl_lstgrp.grpid as grpid,tbl_lstgrp.descr as type');
     $this->db->from('tbl_lstgrp');
     if($userid > 0)
        $query = $this->db->where('userid',$userid);
     if($typeid > 0)
        $query = $this->db->where('typeid',$typeid);
     else
        $query = $this->db->where('typeid >',0);
     $this->db->order_by('type'); 
     $query = $this->db->get();
     return $query->result();
  } //end get_groups

  public function get_grpitems($userid = 0, $grpid = 0) {
    $data = array();
    $this->db->select('tbl_lstgrp.grpid as grpid,tbl_lstitem.itemid as itemid,tbl_lstitem.descr as item');
    $this->db->from('tbl_lstitem');
    if($grpid > 0) 
        $this->db->where('tbl_lstitem.grpid', $grpid);
    if($userid > 0) 
        $this->db->where('tbl_lstitem.userid', $userid);
    $this->db->join('tbl_lstgrp', 'tbl_lstgrp.grpid = tbl_lstitem.grpid');
    $this->db->order_by('item'); 
    $query = $this->db->get();
    return $query->result();
  } // end getgrplistitems

  public function get_shopgrps($userid = TEST_USERID) {
    $result = array();
    $this->db->select('tbl_shopgrps.grpid as grpid,tbl_shopgrps.descr descr');
    $this->db->from('tbl_shopgrps');
    $this->db->where('userid', $userid);
    $this->db->order_by('descr'); 
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $rec) {
        $descr = $rec->descr;
        $pos = stripos($descr,'-');
        if($pos > 0) {
            $descr = substr($descr,$pos+1);
        }
        if(strlen($descr) > 0) {
          if(substr($descr,0,1) != '.') 
              $descr = '.'.$descr;
          if(substr($descr,strlen($descr),1) != '.') 
              $descr = $descr.'.';
          $result[$rec->grpid] = $descr;
         //$row_array = array($rec->grpid => $descr);
         //array_push($result,$row_array);
        }
      }
    }
    return $result;
  } // end get_shopgrps

  public function get_usergrps($idlist='') {
    $result = array();
    $idarr = preg_split('/\./',$idlist,-1,PREG_SPLIT_NO_EMPTY);
    if(isset($idarr) && is_array($idarr))
    {
      $this->db->select('itemid, descr');
      $this->db->from('tbl_lstitem');
      $this->db->where_in('itemid', $idarr );
      $this->db->order_by('descr'); 
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $rec) {
          $result[$rec->itemid] = $rec->descr;
          //print_r($result[$rec->itemid]);
        }
      } else {
        //die("nothing found for : ".$idlist);
      }
    } else {
      die("nothing found for : ".$idlist);
    }
    return $result;
  } // end get_usergrps
  public function get_shoplist($userid = TEST_USERID) {
    $result = array();
    $this->db->select('itemid, descr');
    $this->db->from('tbl_shoplist');
    $this->db->where('userid', $userid);
    $this->db->order_by('descr'); 
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $rec) {
        $result[$rec->itemid] = $rec->descr;
        //print_r($result[$rec->itemid]);
      }
    } else {
       // die("nothing found for userid : ".$userid);
    }
    return $result;
  } // end get_shoplist

  public function update_shoppick($userid = TEST_USERID, $recs)
  {
      $rtn = false;
      $rtn = $this->clear_groups();
      foreach($recs as $k => $v){
        $audit = date('Y-m-d H:i:s');
        $data = array('grpid' => $k, 'descr' => $v, 'audit' => $audit);
        $this->db->where('userid', $userid);
        $this->db->where('grpid', $k);
        $query = $this->db->get('tbl_shopgrps');
        if ($query->num_rows() > 0) { // Update
           $data = array('descr' => $v, 'audit' => $audit);
           $this->db->where('userid', $userid);
           $this->db->where('grpid', $k);
           $rtn = $this->db->update('tbl_shopgrps', $data);
        } else {  // Insert
           $data = array('userid' => $userid, 'grpid' => $k, 'descr' => $v, 'audit' => $audit);
           $rtn = $this->db->insert('tbl_shopgrps', $data);
        }
        $query->free_result(); // Release memory
      }
      return $rtn;
  }
  
  public function update_shoplist($userid = TEST_USERID, $recs)
  {
      $rtn = false;
      
      foreach($recs as $k => $v){
        $audit = date('Y-m-d H:i:s');
        $data = array('userid' => $userid, 'itemid' => $k, 'descr' => $v, 'audit' => $audit);
        $this->db->where('userid', $userid);
        $this->db->where('itemid', $k);
        $query = $this->db->get('tbl_shoplist');
        if ($query->num_rows() > 0) { // Update
           $data = array('descr' => $v, 'audit' => $audit);
           $this->db->where('userid', $userid);
           $this->db->where('itemid', $k);
           $rtn = $this->db->update('tbl_shoplist', $data);
        } else {  // Insert
           $rtn = $this->db->insert('tbl_shoplist', $data);
        }
        $query->free_result(); // Release memory
      }
      return $rtn;
  }
  /* Function to update a pair of structured objects rec[$id] = $str
   * Default is 2 recs with 1 being a group record and 2 being an item
   * Grpid is assigned on cnt=0 for Item record Grpid cnt=1
   */
  public function update_lists($recs, $typeid = GROCERY_TYPE)
  {
      $rtn = false;
      $cnt = 0;
      $grpid = 0;
      $itemid = 0;
      foreach($recs as $k => $v){
        $descr = $v;
        if($cnt == 0) // tbl_lstgrp
        {
            $grpid = $k;
            $data = array('descr' => $descr, 'typeid' => $typeid);
            $this->db->where('grpid', $grpid);
            $query = $this->db->get('tbl_lstgrp');
            if ($grpid > 0 && strlen($descr) > 0 && $query->num_rows() > 0) { // Update
               $this->db->where('grpid', $grpid);
               $rtn = $this->db->update('tbl_lstgrp', $data);
            } else {
               $this->db->where('descr', $descr);
               $query = $this->db->get('tbl_lstgrp');
               if ($query->num_rows() > 0) { // Update
                  $rtn = 'group item with this description already exists';
               } else  {
                  $rtn = $this->db->insert('tbl_lstgrp', $data);
                  if($rtn){
                    $grpid = $this->db->insert_id();    // return last grpid inserted
                  }
               }
            }
        } else if ($cnt == 1){ // tbl_lstitem
            $itemid = $k;
            $data = array('descr' => $descr, 'grpid' => $grpid);
            $this->db->where('itemid', $itemid);
            $query = $this->db->get('tbl_lstitem');
            if ($itemid > 0 && strlen($descr) > 0 && $query->num_rows() > 0) { // Update
               $this->db->where('itemid', $itemid);
               $rtn = $this->db->update('tbl_lstitem', $data);
            } else {
               $rtn = $this->db->insert('tbl_lstitem', $data);
            }
        }
        $query->free_result(); // Release memory
        $cnt++;
      }
      return $rtn;
  }
  /* Function to update an item record with grpid, descr based on mode and itemid
   */
  public function update_item($mode, $grpid, $itemid, $descr)
  {
    $rtn = false;
    if(isset($descr)){
      $data = array('descr' => $descr, 'grpid' => $grpid);
      $this->db->where('itemid', $itemid);
      $query = $this->db->get('tbl_lstitem');
      if ($itemid > 0 && strlen($descr) > 0 && $query->num_rows() > 0) { // Update
         $this->db->where('itemid', $itemid);
         if($mode == UPDATE_REC){
            $rtn = $this->db->update('tbl_lstitem', $data); 
         } else if($mode == DELETE_REC) {
            $tables = array('tbl_lstitem', 'tbl_shoplist');  // eliminate shoplist with this item
            $this->db->where('itemid', $itemid);
            $rtn = $this->db->delete($tables);
            if ($this->db->_error_message())
               print_r($this->db->_error_message());
            //$rtn = $this->db->delete('tbl_lstitem', array('itemid' => $itemid));
            // may need to remove itemid in tbl_shopgrp delimited descr field 
         }
      } else {
         $rtn = $this->db->insert('tbl_lstitem', $data);
      }
      $query->free_result(); // Release memory
      if (!$rtn){
        trigger_error('update_item - tbl_lstitem error during '.$mode,E_USER_WARNING); 
      }
    }  
    return $rtn;
  }
  /* Function to update a group record with grpid, descr based on mode and grpid
   */
  public function update_group($mode, $grpid, $descr, $typeid = GROCERY_TYPE)
  {
    $rtn = false;
    if(isset($descr)){
      $data = array('typeid' => $typeid, 'descr' => $descr, 'grpid' => $grpid);
      $this->db->where('grpid', $grpid);
      $query = $this->db->get('tbl_lstgrp');
      if ($grpid > 0 && strlen($descr) > 0 && $query->num_rows() > 0) { // Update
         $this->db->where('grpid', $grpid);
         if($mode == UPDATE_REC){
            $rtn = $this->db->update('tbl_lstgrp', $data); 
         } else if($mode == DELETE_REC) {
            $tables = array('tbl_lstgrp', 'tbl_lstitem', 'tbl_shopgrps');  // eliminate shopgrps with reference to this group
            $this->db->where('grpid', $grpid);
            $rtn = $this->db->delete($tables);
         }
      } else {
         $rtn = $this->db->insert('tbl_lstgrp', $data);
         if($rtn){
           $grpid = $this->db->insert_id();    // return last grpid inserted

           if(isset($grpid) && $grpid > 0) {
              //$mode, $grpid, $itemid, $desc
              $rtn = $this->update_item(INSERT_REC, $grpid, 0, '<first item for group '.$descr.'>');
           }
         }

      }
      $query->free_result(); // Release memory
      if (!$rtn){
        trigger_error('update_group - tbl_lstgroup error during '.$mode,E_USER_WARNING); 
      }
    }  
    return $rtn;
  }
  /* Function bool update_group_rec($mode, $userid = 0, $grpid, $descr, $typeid = GROCERY_TYPE)
   * Handle CRUD for tbl_grps and all children in case of DELETE
   * Default is 2 recs with 1 being a group record and 2 being an item
   * return: grpid (new if an insert)
  */
  public function update_group_rec($mode, $userid = 0, $grpid, $descr, $typeid = GROCERY_TYPE)
  {
    $rtn = $grpid;
    if(isset($descr)){
      $data = array('typeid' => $typeid, 'descr' => $descr, 'grpid' => $grpid);
      $this->db->where('grpid', $grpid);
      if($userid > 0)
        $query = $this->db->where('userid',$userid);
      $query = $this->db->get('tbl_lstgrp');
      if ($grpid > 0 && strlen($descr) > 0 && $query->num_rows() > 0) { // Update
         $this->db->where('grpid', $grpid);
         if($mode == UPDATE_REC){
            if($this->db->update('tbl_lstgrp', $data))
               $rtn = $grpid;
         } else if($mode == DELETE_REC) {
            $tables = array('tbl_lstgrp', 'tbl_lstitem'); // do not delete tbl_shopgrps yet...need for delimited list of tbl_shoplist
            $this->db->where('grpid', $grpid);
            if($this->db->delete($tables))
            {
              $rtn = $grpid;
            }
            if(!$this->_delete_group_children($grpid, $userid)) // clean up children in tbl_shoplist -- no grpid link -- indirect link
            {
              $this->_logerror('error','_delete_group_children returned false');
            }
         }
         if ($this->db->affected_rows() > 0) {
           $rtn = $grpid;
         }         
      } else {
         if($this->db->insert('tbl_lstgrp', $data))
           $rtn = $this->db->insert_id();  // return last grpid inserted
      }
      $query->free_result(); // Release memory
    }
    $this->_logerror('error','update_group_rec returning to controller (grpid) val='.$rtn);
    return $rtn;
  }
  public function get_allitems($limit, $start) {
        $this->db->limit($limit, $start);
        $query = array();
        $this->db->select('tbl_lstgrp.descr as type,tbl_lstgrp.grpid as gid, tbl_lstitem.itemid,tbl_lstitem.descr as item, tbl_lstitem.itemid as iid');
        $this->db->from('tbl_lstgrp');
        $this->db->join('tbl_lstitem', 'tbl_lstgrp.grpid = tbl_lstitem.grpid');
        $this->db->order_by('type', 'asc'); 
        $this->db->order_by('item', 'asc'); 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
  }
  public function find_like($pstr)
  {
      $sqlstr = "select tbl_lsttype.typeid as tid,
               tbl_lsttype.descr as tdescr, 
               tbl_lstgrp.grpid as gid,
               tbl_lstgrp.descr gdescr, 
               tbl_lstitem.itemid as iid, 
               tbl_lstitem.descr as idescr from `tbl_lstitem` 
               inner join (`tbl_lstgrp` 
               inner join `tbl_lsttype`  
               on tbl_lsttype.typeid = tbl_lstgrp.typeid) 
               on tbl_lstitem.grpid = tbl_lstgrp.grpid
               where tbl_lstitem.descr like '%".$this->db->escape_like_str($pstr)."%'
               order by tbl_lstgrp.descr, tbl_lstitem.descr;";
               
       $query = $this->db->query($sqlstr);
       if ($query->num_rows() > 0) {
          foreach ($query->result() as $row) {
              $data[] = $row;
          }
          return $data;
        }
        return false;
  }
  public function clear_groups($userid = TEST_USERID) {
     $query = array();
     $audit = date('Y-m-d H:i:s');
     $data = array('userid' => $userid, 'descr' => '' , 'audit' => $audit);
     $this->db->where('userid', $userid);
     $query = $this->db->get('tbl_shopgrps');
     if ($query->num_rows() > 0) { // Update
        $this->db->where('userid', $userid);
        $rtn = $this->db->update('tbl_shopgrps', $data);
     }
     return true;
  } //end clear_groups
  /* function bool _delete_group_children($grpid, $userid = 0)
   * Delete children of group that has been deleted
   * When deleting tbl_shopgrps,
   * 1) store tbl_shopgrps delimted list to string var descr
   * 2) convert string list into integers
   * 3) delete tbl_shoplist by itemid, userid
   * 4) delete record from #1 (tbl_shopgrps.recid)
   */
  function _delete_group_children($grpid, $userid = 0)
  {
    $rtn = false;
    $recid = 0;
    $this->db->select('tbl_shopgrps.recid, tbl_shopgrps.grpid as grpid,tbl_shopgrps.descr as descr');
    $this->db->from('tbl_shopgrps');
    if($userid > 0)
       $this->db->where('userid', $userid);
    $this->db->where('grpid', $grpid);
    $query = $this->db->get();
    $numrow = $query->num_rows();
    //$this->_logerror('error','userid='.$userid.' grpid='.$grpid.' numrow('.$numrow.') pre-get in _delete_group_children');
    if ($numrow > 0)
    {
      $descr = '';
      foreach ($query->result() as $row)
      {
          echo 'result row='.$row->descr;
          $descr = $row->descr;              // should be just one!
          $recid = $row->recid;
      }    
      if(strlen($descr) > 0)
      {
        if(strpos($descr,'.') === false && is_numeric($descr))
        { // if single itemid append delimiter for array_map
          $descr .= '.';
        }
        if(strpos($descr, '.') !== false)
        {
          $int_array = array_map("intval", explode('.', $descr));
          //print_r($int_array);
          if(is_array($int_array))
          {
            for($i = 0; $i < count($int_array); $i++)
            {
               $itemid = $int_array[$i];
               $this->db->where('userid', $userid);
               $this->db->where('itemid', $itemid);
               $query = $this->db->get('tbl_shoplist');
               if ($query->num_rows() > 0) { // Update
                  $this->db->where('userid', $userid);
                  $this->db->where('itemid', $itemid);
                  $rtn = $this->db->delete('tbl_shoplist');
                  if ($this->db->_error_message())
                     $this->_logerror('error','Deletion error from tbl_shoplist for itemid='.$itemid);
                  else
                     $rtn = true;
                  //$etype = $rtn ? 'error' : 'error';
                  //$this->_logerror($etype,'WM-MSG: from tbl_shoplist for itemid='.$itemid);
               }
            }
          }
        }
      }
      if($recid > 0)
         $this->db->where('recid', $recid);
         $rtn = $this->db->delete('tbl_shopgrps');
         if($rtn)
            $this->_logerror('error','WM-END: from tbl_shopgrps for grpid='.$grpid.' recid='.$recid);
    }
    return $rtn;
  } // end get_userhist
  /*
     * logerror function
     * @param $which - message level must be 'error', 'debug' or 'error' per CI (see link below)
     * @param $errmsg - error to log
     * ref: http://ellislab.com/codeigniter/user-guide/general/errors.html 
  */
  public function _logerror($which,$errmsg)
  {
        $thisclass = basename(__FILE__, '.php');
        $logmsg = $thisclass.'-'.$errmsg;
        log_message($which,$logmsg);
  } // end _logerror
  function get_record($record_id)
  {
     $this->db->where('itemid',$record_id);   //we want the row whose ID matches the value were passing in
     $query = $this->db->get('tbl_lstitem');  //get the table and put it into an object named $query
     if ($query->num_rows() > 0) {
        $row = $query->row();   //gets the first row of the resulting dataset.  In this case, only 1 row will ever be returned
        return $row;
     }
     return false;   //send the record back to the controller
  }
  public function item_count() 
  {
    return $this->db->get('tbl_lstitem')->num_rows();
  }

} //end class
