<!-- To do:
* Verify ins/upd/del
* Verify no blank grp/itm inserts
* Check ClearControls; reset itm-dropdown, etc
* Break code into js file
* Consolidate with common.js
-->
<script type="text/javascript">
$(function() {
    $(':input[type="checkbox"]').wijcheckbox();
    $('#accordion').accordion({
                        animated: 'easeOutBack',
                        active: false,          //close at all panels startup
                        autoHeight: false,
                        navigation: false,
                        collapsible: true,
                        clearStyle : true,
                        icons: { 'header': 'ui-icon-plus', 'activeHeader': 'ui-icon-minus' }
    });
    $('#accordionResizer').resizable({
                           minHeight: 40,
                           resize: function() {
                              $('#accordion').accordion('resize');
                           }
    });
    $('#accordian').hide();

    $('#prntlist').prop('disabled',true);
    //Append a click event listener to button
    $('#showlist').bind('click', function() {
       $('#prntlist').button('enable');
    });
    //destroy modals and reset for re-initialization:
    $("#pop-edit").on('hidden.bs.modal', function () {
       $(this).data('bs.modal', null);
    });
    //clear all controls when modal is shown:
    $('#pop-edit').on('show.bs.modal', function() {
         resetControls();
    })
    //clear all controls when modal is shown:
    $("#mov-edit").on('hidden.bs.modal', function () {
       $(this).data('bs.modal', null);
    });
    /*
    $('#grp-descr').change( function() {
        console.log('grp-descr.change');
    });
    */
    $('#mov-dropdown').change(function() {
        $('#mov-descr').show();
        var selData = rtnSelectedIdStr('mov');
        if(selData['sid'] > 0)
        {
            paramData = { grpid:  selData['sid'] };
        }
        console.log('mov-dropdown-id('+paramData['grpid']+') grpstr('+selData['str']+')');
    });
    $('#grp-dropdown').change(function () {
      var paramData = new Array();
      var selData = { sid: 0,
                      str: ''}
      selData = rtnSelectedIdStr('grp');
      if(selData['sid'] > 0)
      {
          paramData = { grpid:  selData['sid'] };
      }
      console.log('paramData-id('+paramData['grpid']+') grpstr('+selData['str']+')');
      if(paramData['grpid'] != '' && paramData['grpid'] != 'undefined')
      {
          $('#itm-dropdown').empty();
          $('#itm-dropdesc').show();
          clearTextBox('itm-descr');
          $.ajax({
              type: 'POST',
              url: 'index.php?lists/getitems',
              data: paramData,
              cache: false,
              async: false,
              success:
                function(data){
                 $('#itm-droparea').show();
                 $('#itm-droparea').html(data);
             },
             error: function(response) {
                  console.log('Ajax-error: '+response.status + ' ' + response.statusText);
             }              
         });
      } else {
         $('#itm-dropdown').empty();
         $('#itm-droparea').hide();
      }
   });
   $('#printdata').click(function() {
       callPrint('showhere');
   });
   $('#showlist').click(function() {
           var ar = getUserChkdItems();
           if(ar.length >= 0){
                 listArrayToConsole(ar);
           }
           var paramdata = {
                  'piks': ar
           };                     
           $('#showhere').empty();
           $.ajax({
                  type: 'POST',
                   url: 'index.php?lists/updpicks',
                  data: paramdata,
                 cache: false,
                 async: false,
           success: function(data){
                                $('#showhere').html(data); 
                                $('#showhere').show();
                },
          complete: function (xhr, status) {
                                if (status === 'error' || !xhr.responseText) {
                                   showAlert('saveList-Complete-status=error','alert-error');
                                } else {
                                   var data = xhr.responseText;
                                   $('#showhere').html(data).append;
                                }
          },
          error: function(response) {
                                 showAlert('saveList-Ajax-error: '+response.status + ' ' + response.statusText,'alert-error');
                        }
           });                   
                 
        });
   $('#prntlist').click(function() {
        var ar = getUserItemDescr();
        if(ar.length >= 0){
          var paramdata = {
             'qtys': ar
          };                     
          $('#showhere').empty();
          $.ajax({
           type: 'POST',
           url: 'index.php?lists/prntsave',
           data: paramdata,
           cache: false,
           async: false,
          success: function(data){
                 $('#showhere').html(data); 
                 $('#showhere').show();
                 callPrint('showhere');
           },
          complete: function (xhr, status) {
                 if (status === 'error' || !xhr.responseText) {
                    showAlert('prntsave-complete-status=error','alert-error');
                 } else {
                   var data = xhr.responseText;
                   $('#showhere').html(data).append;
                 }
           },
            error: function(response) {
                    showAlert('prntsave-error: '+response.status + ' ' + response.statusText,'alert-error');
           }
         });
       }
   });
   $('#refresh').click(function() {
       window.location.reload(true);            
   });
});
function toggleButtons(which)
{
    var desc = which.value;
    var temp = $('#edt-sav-btn').prop('disabled');
    console.log('disabled: '+temp);
    var mute = (desc.length == 0 || desc.indexOf('--') !== -1);
    $('#edt-sav-btn').prop('disabled',mute);
    
    /*
    console.log('toggleButtons-'+which.name);
    console.log('this.text='+which.text);
    console.log('this.value='+which.value);
    var instr = $('#grp-descr').text();
    console.log(' instr-'+instr);
    */
}
function callPrint(strid) {
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', 'PrintWindow', 'left=210,top=110,width=800,height=900,toolbar=yes,scrollbars=yes,resizable=yes');
        WinPrint.document.write('<html><head><title>The List</title>'+
                        '<link rel="stylesheet" href="<?php echo base_url();?>style/print.css" type="text/css" />'+
                        '</head><body><div="prnt">');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.write('</div><p><button id="prntlist" class="prntlist" onclick="print();">Print</button></body></html>');
        WinPrint.document.close();
        WinPrint.focus();
}
function resetControls()
{
   clearTextBox('grp-descr');
   clearTextBox('itm-descr');
   clearDropDown('grp-dropdown');
   clearDropDown('itm-dropdown');
   $('#itm-descarea').hide();
   $('#edt-sav-btn').prop('disabled',true);
   $('#edt-del-btn').prop('disabled',true);
   $('#edt-mov-btn').prop('disabled',true);
}
function closeAndReset()
{
        $('#mov-edit').modal('hide');
        $('#pop-edit').modal('hide');
        resetControls();
        return false;
}
function updChangedFields()
{
   //does group record need updating:
   var rtn = false;
   var msg = '';
   var param = { mode: 'upd',
                grpid: 0,
		gdesc: '',
		itmid: 0,
		idesc: ''};
   var tmp = rtnTextboxIdStr('grp'); //get grp dropdown id
   if(typeof tmp != 'undefined' && tmp['sid'] >= 0) {
      param['grpid'] = tmp['sid'];
      param['gdesc'] = tmp['str'];
   }
   tmp = rtnTextboxIdStr('itm'); //get itm dropdown id
   if(typeof tmp != 'undefined' && tmp['sid'] >= 0) {
      param['itmid'] = tmp['sid'];
      param['idesc'] = tmp['str'];
   }
   //if no itm chosen but itm-descr is changed
   if(tmp['sid'] <= 0) {
      param['idesc'] = rtnTextboxStr('itm');
   }
   console.log('updChangedFields-grpid('+param.grpid+') gdesc['+param.gdesc+'] itmid('+param.itmid+') idesc['+param.idesc+']');
   if(param.gdesc.length > 0 && param.gdesc.indexOf('--') < 0) {
      $('#showhere').empty();
      $.ajax({
       type: 'POST',
       url: 'index.php?lists/updlist',
       data: param,
       cache: false,
       async: false,
      success: function(data){
         if(data.length > 0 && data.indexOf('Error') === -1) {
            msg = data+': Error not present';
         } else {
            msg = data+': Error!';
         }
         console.log(msg);
         $rtn = true;
       },
      complete: function (xhr, status) {
	if (status === 'error' || !xhr.responseText) {
	       msg = 'updGroupRecord-Complete-status with error';
	}
      },
      error: function(response) {
                 msg = 'updGroupRecord-error: '+response.status + ' ' + response.statusText;
           }                                  
    });
    closeAndReset();
    showAlert(msg,'alert-info');
   }
   return rtn;
}
function updGroupRecord(mode)
{
   var rtn = false;
   var msg = '';
   var param = { mode: 'upd',
                grpid: 0,
		descr: '' };
   if(mode){
     param['mode'] = mode;
   }
   var tmp = rtnTextboxIdStr('grp'); //get grp dropdown id
   if(typeof tmp != 'undefined' && tmp['sid'] >= 0) {
      param['grpid'] = tmp['sid'];
      param['descr'] = tmp['str'];
   }
   console.log(param);
   if(param['mode'].length > 0 && param['descr'].length > 0)
   {
      $('#showhere').empty();
      $.ajax({
       type: 'POST',
       url: 'index.php?lists/updgroup',
       data: param,
       cache: false,
       async: false,
      success: function(data){
         if(data.length > 0 && data.indexOf('Error') === -1) {
            msg = data+': Error not present';
         } else {
            msg = data+': Error!';
         }
         console.log(msg);
         $rtn = true;
       },
      complete: function (xhr, status) {
                 if (status === 'error' || !xhr.responseText) {
                        msg = 'updGroupRecord-Complete-status with error';
                 }
         },
      error: function(response) {
                 msg = 'updGroupRecord-error: '+response.status + ' ' + response.statusText;
           }                                  
    });
    closeAndReset();
    showAlert(msg,'alert-info');
    
  }
  return rtn;
}
function updItemRecord(mode)
{
   var rtn = false;
   var param = { mode: 'upd',
                grpid: 0,
		itmid: 0,
		descr: '' };
   if(mode){
          param['mode'] = mode;
   }
   var tmp = rtnTextboxIdStr('mov'); //get mov dropdown id
   if(typeof tmp != 'undefined' && tmp['sid'] >= 0) {
          param['grpid'] = tmp['sid'];
   }
   tmp = rtnTextboxIdStr('itm');
   if(typeof tmp != 'undefined' && tmp['sid'] >= 0)  {
           param['itmid'] = tmp['sid'];
           param['descr'] = tmp['str'];
   }
   console.log(param);
   if(param['mode'].length > 0 && param['itmid'] > 0 && param['descr'].length > 0)
   {
      $('#showhere').empty();
      $.ajax({
       type: 'POST',
       url: 'index.php?lists/upditem',
       data: param,
       cache: false,
       async: false,
      success: function(data){
         if(data.length > 0 && data.indexOf("Error") !== -1) {
                tmp = 'data: Error not present';
            showAlert(data,"alert-success");
         } else {
                tmp = 'data: Error!';
            showAlert(data,"alert-error");
         }
             console.log(tmp);
             $rtn = true;
       },
      complete: function (xhr, status) {
                 if (status === 'error' || !xhr.responseText) {
                        showAlert('updItemRecord-Complete-status with error','alert-error');
                 }
         },
      error: function(response) {
                 showAlert('updItemRecord-error: '+response.status + ' ' + response.statusText,'alert-error');
           }                                  
        });
        closeAndReset();
  }
  return rtn;
}
function clearTextBox(which)
{
   var str = '';
   $('#' + which).show();
   $('input[type="text"]').each(function(){
           if($(this).attr('name') == which){
          $(this).val(str);
           }
   });
}
function clearDropDown(which)
{
   var str = '';
   $('#' + which).show();
   $('select').each(function(){
           if($(this).attr('name') == which){
          $('select option:selected').removeAttr('selected');
          //$(this).attr('selectedIndex', '-1');
           }
   });
}
function rtnSelectedIdStr(which)
{
        var rtn = { sid: 0,
                                str: ''};
        var id = 0;
        var str = '';
        $('#' + which + '-dropdown option:selected').each(function () {
                 str = $(this).text();
                 id = $(this).val();
         });
        if(str != '')
        {
          $('#' + which + '-descr').show();
          $('input[type="text"]').each(function(){
                  if($(this).attr('name') == which + '-descr'){
                         $(this).val(str);
                  }
           });
   }
   rtn = { sid: id, str: str };
   return rtn;
}
function rtnTextboxStr(which) {
  var rtn = '';
  $('input[type="text"]').each(function(){
     if($(this).attr('name') == which + '-descr'){
       rtn = $(this).val();
     }
  });
  return rtn;
}
function rtnTextboxIdStr(which) {
  var rtn = { sid: 0,
              str: ''};
  var id = 0;
  var tbx1 = '';
  var tbx2 = 'xx';
  $('#' + which + '-dropdown option:selected').each(function () {
	   tbx1 = $(this).text();
	   id = $(this).val();
  });
  if(tbx1 != '')
  {
    $('input[type="text"]').each(function(){
	    if($(this).attr('name') == which + '-descr'){
			   tbx2 = $(this).val();
		    }
     });
  }
  rtn = { sid: id,
          str: tbx2   };
  return rtn;
}
function getUserChkdItems()
{
  var arr = new Array();
  $("input[type='checkbox']:checked").each(
     function() { var itm = { 'gd' : this.id.substr(0,this.id.indexOf('.')),
                              'id' : this.id.substr(this.id.indexOf('.')+1) };
                  arr.push(itm);
  }
 )
 return arr;
}
function listArrayToConsole(arr)
{
   if($.isArray(arr)){
         for(j = 0; j < arr.length; j++){
           var grpid = arr[j].gd + '|' + arr[j].id;
           console.log('['+grpid+']');
         }
   }
}
function showItmDescr()
{
    $('#itm-descarea').toggle();
    $('#itm-descarea').show();
    $('#itm-descr').show();
     var selData = rtnSelectedIdStr('itm');  // populate textbox
}
function getUserItemDescr()
{
  var arr = new Array();
  $("input[type='text']").each( function() {
      var itm = { 'id'  : this.id.substr(this.id.indexOf('.')+1),
                  'str' : this.value};
      if(itm.id.indexOf('-descr') <= 0){
        arr.push(itm);
      } 
 })
 return arr;
}
</script>
   <div id="leftcolumn" class="column">
      <div class="innertube">
        <button id="refresh" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
        <button id="editlist" data-toggle="modal" data-target="#pop-edit" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Edit</button>
        <button id="showlist" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span> List</button>
        <button id="prntlist" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Print</button>
      </div>
      <div class="innercolumn">
            <div id="accordionResizer" style="padding: 1px; height: 90%;" class="ui-widget-content">
          <div id="accordion">
                 <?php foreach($groups as $k => $v): 
                         echo '<h3><a href="#sec-'.$k.'">'.$v.'</a></h3>';
                     $cnt = 0;
                     foreach($items as $row):
                   if($cnt == 0){
                      echo '<div>'; 
                      $cnt++;
                   }
                           if($row->grpid == $k) {
                        $checked = '';
                        foreach($picks as $key => $val){
                          if($key == $k)
                          {
                            if(stripos($val, '.'.$row->itemid.'.') !== false){
                              $checked = 'checked=checked';
                               break;
                             }
                           }
                        }
                            echo '<input id="'.$k.'.'.$row->itemid.'" type="checkbox" '.$checked.' />';
                            echo '<label style="font-weight: normal;" for="'.'g.'.$k.'.c.'.$row->itemid.'">'.$row->item.'</label>';
                         }
                     endforeach; 
                 if($cnt > 0) { 
                       echo '</div>';  //atleast one item for group
                 }
                     $cnt = 0; 
                 endforeach; ?> 
              </div>
            </div>
      </div>
   </div>
   <div id="midcolumn" class="column">
      <div class="innertube">
          <div id="ajaxLoadAni"></div>
          <div id="showhere"></div>
          <div class="modal fade" id="pop-edit" tabindex="-1" role="dialog" aria-labelledby="pop-edit-label" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h3 class="form-heading"><?php echo $title;?></h3>
                  </div>                
                  <form class="input-medium frmformat" role="form">
                     <p class="validateTips">Select what to update</p>
                     <fieldset>
                           <div class="form-group">
                             <label for="grp-dropdown">Available Groups</label>
                             <select name="grp-dropdown" class="form-control" id="grp-dropdown" style="width: 70%; margin: 5px 5px 5px 5px;"> 
                                  <option value="-1"></option>
                                  <option value="0">--Add New--</option>
                                  <?php foreach($groups as $k => $v): ?> 
                                             <?php echo '<option value='.$k.'>'.$v.'</option>'; ?>     
                                  <?php endforeach; ?> 
                              </select>
                           </div>
                           <div class="form-group">
                             <div id='grp-descr'>
                               <label for="grp-descr">Group Description</label>
                                   <input type="text" class="form-control" name="grp-descr" id="grp-descr" onkeyup="javascript: toggleButtons(this);" value=""/>
                             </div>
                           </div>
                           <div class="form-group">
                              <div id='itm-droparea'></div>
                           </div>
                           <div class="form-group" id="itm-descarea">
                               <label for="itm-descr">Item Description</label>
                            <input type="text" class="form-control" name="itm-descr" id="itm-descr" onkeyup="javascript: toggleButtons(this);" value="" />
                           </div>
                          </fieldset>
                          <div class="modal-footer">
                             <button type="button" id="edt-sav-btn" data-dismiss="modal" onclick="javascript: updChangedFields(); return false;" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save</button>            
                             <button type="button" id="edt-del-btn" data-dismiss="modal" onclick="javascript: updItemRecord('del'); return false;" class="btn btn-primary"><span class="glyphicon glyphicon-minus-sign"></span> Delete</button>
                             <button type="button" id="edt-mov-btn" data-toggle="modal" data-target="#mov-edit" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Move</button>
                             <button type="button" id="edt-bye-btn" data-dismiss="modal" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Close</button>
                          </div>
                  </form>
            </div> <!-- modal-content -->
      </div> <!-- modal-dialog -->
            </div> <!-- pop-edit -->
                  <div class="modal fade" id="mov-edit" tabindex="-1" role="dialog" aria-labelledby="mov-edit-label" aria-hidden="true">
                    <div class="modal-dialog">
                         <div class="modal-content">
                                <div class="modal-header">
                                   <h3 class="form-heading">Available Groups</h3>
                                </div>          
                                <form class="input-medium frmformat" role="form">
                                   <p class="validateTips">Select desired group</p>
                                   <fieldset>
                                     <div class="form-group">
                                           <label for="mov-dropdown">Move to Group</label>
                                           <select name="mov-dropdown" class="form-control" id="mov-dropdown">
                                                    <li role="presentation" class="dropdown-header">Select Group</li>
                                                <option value="-1"></option>
                                                <option value="0">--Add New--</option>
                                                <?php foreach($groups as $k => $v): ?> 
                                                           <?php echo '<option value='.$k.'>'.$v.'</option>'; ?>     
                                                <?php endforeach; ?> 
                                            </select>
                                         </div>
                                   </fieldset>
                                   <div class="modal-footer">
                                           <button type="button" onclick="javascript: updItemRecord('upd'); return false;" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Save</button>
                                           <button type="button" data-dismiss="modal" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Close</button>
                                   </div>
                                </form>
                           </div>
                        </div>
                  </div>
      </div> <!-- innertube -->
   </div>      
