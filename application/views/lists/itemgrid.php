<script src="<?php echo base_url();?>assets/js/itemgrid.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/itemgrd1.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/itemfind.js" type="text/javascript"></script>
   <div id="leftbtncol" class="column">
     <div class="innertube">
        <!-- Split button -->
        <div class="btn-group">
        <button id="frmgupd" class="btn btn-primary" dropdown-toggle" data-toggle="dropdown">
         <span class="glyphicon glyphicon-cog"></span> Edit
             <span class="caret"></span>
         </button>
           <ul class="dropdown-menu" role="menu">
             <li><a href="#">Group Names</a></li>
             <li><a href="#">Group Items</a></li>
             <li class="divider"></li>
             <li><a href="#">Separated link</a></li>
           </ul>
        </div>
        <button id="frmgupd" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Group</button>
        <button id="frmifnd" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Search</button>
        <button id="refresh" type="button"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Refresh</button>
     </div>
     <div class="innertube">
        <div id="gridview">
           <?php include('itempage.php'); ?>
        </div>
     </div>
   </div>      
   <div id="rightgrdcol" class="column">
     <div class="innertube">
        <div id="formarea">
        </div>
     </div>
   </div>
</div>
