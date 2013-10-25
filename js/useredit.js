//<script type="text/javascript">
$(function() {
    $(":input[type='checkbox']").wijcheckbox();
    $(":input[type='radio']").wijradio();
    $(":input[type='text']").wijtextbox();
 });
var fname = $( "#fname" ),
    lname = $( "#lname" ),
    uname = $( "#uname" ),
    email = $( "#email" ),
    userid = $( "#userid" )
    allFlds = $( [] ).add( fname ).add( lname ).add( uname ).add( email ),
    tips = $( ".validateTips" );
    function doLogin()
    {
        if(loginUser()){
                //alert('window.location call');
                window.location = 'http://mangumreunion.com/listmgr/cibs/';
        } else {
           updateTips('Invalid login');
           clearTextBox('uname');
           clearTextBox('email');
        }
    }
    function doUpdate()
    {
       var bValid = validateUserData();               
       if ( bValid ) {
          updUserData('upd');
          window.location = 'http://mangumreunion.com/listmgr/cibs/';
       }
    }
    function doGoHome()
    {
       window.open('index.php','_self');        
    }
    function updUserData(mode)
    {
        if(typeof mode == 'undefined' || mode === ''){
           mode = 'upd';
        }
        var params = {
           emode: mode,
           fname: $('#fname').val(),
           lname: $('#lname').val(),
           uname: $('#uname').val(),
           email: $('#email').val(),
           //pword: $('#pword').val()
        };
        $.ajax({
        type: 'POST',
        url: 'index.php?login/upduser',
        dataType: 'json',
        data: params,
        cache:false,
        async: false,
        success:
          function(data){
            //console.log( 'Ajax-no errors' );
            //console.log('data.message='+data);
            alert(data);
            $('#user-area').append(data); 
        },
        beforeSend: function(){
            //console.log( 'Ajax-beforeSend' );
        },
        complete: function (xhr, status) {
            if (status === 'error' || !xhr.responseText) {
               //console.log('Complete-status=error');
            } else {
              var data = xhr.responseText;
              $('#user-area').html(data).append;
              //console.log( 'Ajax-complete-xhr.resonseText='.data);
            }
                $( "#dlg-edituser" ).wijdialog( "close" );              
            },
        error: function(response) {
            //console.log('Ajax-error: '+response.status + ' ' + response.statusText);
        }
        });
    }
        function loginUser()
        {
      var rtn = false;
          var params = {
          uname: $('#uname').val(),
          email: $('#email').val()
      };
      $.ajax({
                type: 'POST',
                url: 'index.php?login/signon',
                data: params,
                cache: false,
                async: false,
        dataType: 'json',
                success:
                  function(data){
                    //console.log( 'Ajax-no errors' );
            var pname = data.uname;
            if (typeof pname !== 'undefined') {
                if(pname.toLowerCase().indexOf(params['uname'].toLowerCase()) != -1){
                   rtn = true;
                }
            }
                },
                beforeSend: function(){
                    //console.log( 'Ajax-beforeSend' );
                },
                complete: function (xhr, status) {
                    if (status === 'error' || !xhr.responseText) {
                       //console.log('Complete-status=error');
                    }
        },
                error: function(response) {
                    //console.log('Ajax-error: '+response.status + ' statusText: ' + response.statusText);
                }
      });
      return rtn;
        }

    function validateUserData() {
        var bValid = false;
        var fname = $('#fname'),
            lname = $('#lname'),
            uname = $('#uname'),
            email = $('#email')
        bValid = checkLength( fname, "fname", 3, 25 );
        bValid = bValid && checkLength( lname, "lname", 3, 25 );
        bValid = bValid && checkLength( uname, "uname", 3, 16 );
        bValid = bValid && checkLength( email, "email", 6, 80 );
        //bValid = bValid && checkLength( pword, "pword", 5, 16 );
        bValid = bValid && checkRegexp( fname, /^[a-z]([a-z_])+$/i, "First name may only be letters." );
        bValid = bValid && checkRegexp( lname, /^[a-z]([a-z_])+$/i, "Last name may only be letters." );
        bValid = bValid && checkRegexp( uname, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
        // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
        bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "Invalid email (eg. ui@jquery.com)" );
        //bValid = bValid && checkRegexp( pword, /^([0-9a-zA-Z])+$/, "Password field only allows : a-z 0-9" );
        if(bValid){
           return true;    
        }
        return false;
    }
    function showUserDialog(){
        $( "#dlg-edituser" ).wijdialog( "open" );
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
    function setTextBoxVal(which, txt)
    {
       var str = txt.val();
       $('#' + which).show();
       $('input[type="text"]').each(function(){
           if($(this).attr('name') == which){
              $(this).val(str);
           }
       });
    }
