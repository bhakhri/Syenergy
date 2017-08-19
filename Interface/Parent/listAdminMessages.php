<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentAdminMessages');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(); 
//UtilityManager::ifStudentNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Admin Messages </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','align="left"',false), 
                               new Array('message',' Admin Messages','width="55%"','',true),
                               new Array('visibleFromDate','Visible From','width="12%"','align="center"',true),
                               new Array('visibleToDate','Visible To','width="12%"','align="center"',true),
                               new Array('attachment','Attachment','width="12%"','align="center"',false),
                               new Array('Action','Action','width="10%"','align="center"',false));


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxDisplayAdminMessages.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'visibleFromDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

//This function Displays Div Window

    function editWindow(id,dv,w,h) {
        //displayWindow(dv,w,h);
        height=screen.height/5;
        width=screen.width/4.5;
        displayFloatingDiv(dv,'', w, h, width,height);
        populateValues(id);   
    }


//This function populates values in View Deatil form through ajax 
    function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxAdminGetValues.php';
         new Ajax.Request(url,
           {      
             method:'post',
             parameters: {messageId: id},
                 
              onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                  hideWaitDialog();
                  j= trim(transport.responseText).evalJSON();
                  document.getElementById("innerAdmin").innerHTML = j.message;
                  document.getElementById("innerSubject").innerHTML = j.subject;
                  document.getElementById("visibleMessageFromDate").innerHTML = customParseDate(j.visibleFromDate,"-");
                  document.getElementById("visibleMessageToDate").innerHTML = customParseDate(j.visibleToDate,"-");
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

<?php 
    function trim_output($str,$maxlength,$mode=1,$rep='...'){
        $ret=($mode==2?chunk_split($str,30):$str);
        if(strlen($ret) > $maxlength){
            $ret=substr($ret,0,$maxlength).$rep;
        }
        return $ret;
    }
?>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/adminMessagesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>        
</body>
</html>
<?php 
//History: $


?>