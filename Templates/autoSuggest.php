<script type='text/javascript'>
function buildEmployeeList()
{
var suggestList="<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    
    $results=HtmlFunctions::getInstance()->getAllEmployeeData('','and instituteId='.$sessionHandler->getSessionVariable("InstituteId"));
    if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
            echo $results[$i]['employeeName']."(".$results[$i]['employeeCode']."), "; 
            }
    }
?>";
return suggestList;
}
</script>