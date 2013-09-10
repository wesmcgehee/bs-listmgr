<script type="text/javascript">
  $(document).ready(function () {
    $('#mybutton')
          .button({
                icons: {
                text: false,
                    primary: 'ui-icon-circle-plus'
                },
             })
         .click(function() {
              $('#getfile').change();
              makeFileList();
                  
        })
    $('#doupload')
            .button({
                icons: {
                text: true,
                    primary: 'ui-icon-gear'
                },
             })
            .click(function() {
                  //makeFileList();
                  
        })
    })
 function makeFileList() {
           var input = document.getElementById("getfile");
           var ul = document.getElementById("fileList");
           while (ul.hasChildNodes()) {
               ul.removeChild(ul.firstChild);
           }
           for (var i = 0; i < input.files.length; i++) {
               var li = document.createElement("li");
               li.innerHTML = input.files[i].name;
               ul.appendChild(li);
           }
           if(!ul.hasChildNodes()) {
               var li = document.createElement("li");
               li.innerHTML = '';
               ul.appendChild(li);
           }
 }
</script>
<style>
label.cabinet
{
    width: 300px;
    height: 100px;
    /* background: url(images/bluebolt.jpg) 0 0 no-repeat; */
    display: block;
    overflow: hidden;
    cursor: pointer;
    border: outset;
}
#getfile {
    position: absolute;
    height: 33px;
    width: 113px;
    left: 153px;
    top: 180px;
    z-index: 1;
    opacity:0.4;
    filter:alpha(opacity=40); /* For IE8 and earlier */    
    -moz-opacity: 0;
    filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
}

</style>
        <!-- IMPORTANT:  FORM's enctype must be "multipart/form-data" -->
        <form method="post" action="index.php?upload/imagefile" enctype="multipart/form-data">
            <label class="cabinet">
                <button id="mybutton" style="z-index: 2;">Browse</button>
                <input name="Filedata[]" id="getfile" type="file" multiple onChange="makeFileList();" />
            </label>
            
            <input type="submit" id="doupload" value="Upload" />
        </form>
     <div id="leftbtncol" class="column">
     <div class="innertube">
       <p>
 	      <strong>Files You Selected:</strong>
	   </p>
	    <div id="fileList">...no files selected for upload</div>
        <div id="gridview">
        </div>
     </div>
   </div>      
   <div id="rightgrdcol" class="column">
     <div class="innertube">
        <div id="formarea">
           <p>form-column</p>
        </div>
     </div>
   </div>
</div>
