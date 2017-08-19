<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Grade Form
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ConsolidatedReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Consolidated Query Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Consolidated/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGrade';   
editFormName   = 'EditGrade';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGrade';
divResultName  = 'results';
page=1; //default page
sortField = 'gradeLabel';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

function toggleDataFormat(str,viewType) {   
  
    url = '<?php echo HTTP_LIB_PATH;?>/Consolidated/ajaxInitList.php';
     new Ajax.Request(url,
       {
             method:'post',
             parameters: {str: str, viewType: viewType},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
        //if(trim(transport.responseText)==false) {
        //   messageBox("<?php echo INCORRECT_FORMAT?>");  
        //}
        //else {
            //document.getElementById("nameRow").style.display='';
            //document.getElementById("nameRow2").style.display='';
            //document.getElementById("resultRow").style.display='';
            document.getElementById('results').innerHTML=trim(transport.responseText); 
       // }
      },
      onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

</script>
</head>
<body>
  <?php 
        require_once(TEMPLATES_PATH . "/header.php");
	    require_once(TEMPLATES_PATH . "/Consolidated/listConsolidatedDataContents.php");
        require_once(TEMPLATES_PATH . "/footer.php");
 ?>
<!--   
    <script language="javascript">
       sendReq(listURL,divResultName,'','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    </script>
--> 
</body>
</html>
