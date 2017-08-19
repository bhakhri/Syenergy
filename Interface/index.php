<?php
  global $FE;
  require_once($FE."/Library/common.inc.php");
  require_once(BL_PATH."/UtilityManager.inc.php");
  UtilityManager::ifLoggedIn();
  require_once(BL_PATH . "/Index/init.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo SUBSCRIPTION_STATUS=='PENDING' ? SITE_NAME.' Subscription Pending' : SITE_NAME.": Login"; ?> </title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <?php 
    echo UtilityManager::includeCSS("login.css");
    echo UtilityManager::includeJS("winjs/prototype.js"); 
    echo UtilityManager::includeJS("functions.js");  
  ?>
  <script type="text/javascript">
    function validateLoginForm() {
      var fieldsArray = [
        ["username","Enter username"],
        ["password", "Enter password"],
        ["instituteId", "Select an institute "],
        ["sessionId", "Select a session"]
      ];

      for(var i = 0; i < fieldsArray.length; i++) {
        if(isEmpty(document.getElementById(fieldsArray[i][0]).value)) {
          document.getElementById(fieldsArray[i][0]).focus();
          return false;
        }
      }
    }

    function populateValues() {
      if(trim(document.getElementById('username').value) == '') {
        return false;
      }

      var url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetInstituteSessionValue.php';
      var params = "&username=" + trim(document.getElementById('username').value); 
      
      new Ajax.Request(url, {
        method: 'POST',
        asynchronous: false,
        parameters: params,     
        onSuccess: function(transport) {
          if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
            showWaitDialog(true);
          } else {
            var j = eval('(' + transport.responseText + ')');    
            var len = document.form1.instituteId.length;
            for(var i = 0; i < len; i++){
              if(document.form1.instituteId.options[i].value == j.id) {
                document.form1.instituteId.options[i].selected = true;
                break;
              }
            }
            document.form1.sessionId.value = document.getElementById('sessionIds').value;
          }
        },
        onFailure: function() { messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
      });
    }
  </script>
  <style>

  </style>
  </head>

  <body class="login_body">
    <?php require_once(TEMPLATES_PATH . "/Index/internalIndex.php"); ?>
    <script type="text/javascript">
      try{
        if(document.getElementById) document.getElementById('username').focus();
      } catch(e) {}

      try {
        var insId=document.getElementById('instituteId');
        if(insId.options.length > 1){
          insId.selectedIndex = 1;
        }
      } catch(e) {}
    </script>
  </body>
</html>

