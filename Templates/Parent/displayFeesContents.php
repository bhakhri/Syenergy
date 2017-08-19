<?php 
//This file creates Html Form output in "displayFees in parent Module" 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
              <!--  <td valign="top">Reports &nbsp;&raquo;&nbsp; Display Fee Details </td> -->
                 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Fee Details  :</td>
                        <td width="67%" align='right' style="padding-right:5px;">
                            <?php
                                $studentName = $studentDataArr[0]['firstName']." ".$studentDataArr[0]['lastName'];
                            ?>
                            <input type="hidden" name="tStudentId"   id="tStudentId"   value="<?php echo $studentDataArr[0]['studentId']?>"> 
                            <input type="hidden" name="tStudentName" id="tStudentName" value="<?php echo $studentName?>"> 
                            <span class="content_title">Study Period:</span>
                            <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshFeesResultData('<?php echo $studentDataArr[0]['studentId']?>',this.value);"> 
                            <option value="0">All</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStudyPeriodName($studentDataArr[0]['studentId'],$studentDataArr[0]['classId']);
                              ?>
                              </select>&nbsp;&nbsp;  
                          </td>
                    </tr>
                  </table>
                </td>
             </tr>
             <tr>
               <td class="contenttab_row" valign="top">
                  <div id="results"></div>           
               </td>
          </tr>
          <tr>
              <td class="content_title" title="Print" align="right"  valign="top">
                <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp; 
                <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >&nbsp;  
              </td>  
          </tr>   
          </table>
        </td>
    </tr>
  </table>
<?php
//$History: displayFeesContents.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/03/09    Time: 4:42p
//Created in $/LeapCC/Templates/Parent
//initial checkin
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/14/09    Time: 6:40p
//Updated in $/Leap/Source/Templates/Parent
//issue fix 1070, 1003, 346, 344, 1076, 1075, 1073,
//1072, 1071, 1069, 1068, 1067, 1064, 
//1063, 1061, 1060, 438 1001, 1004 
//alignment & formating, validation updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 8/14/09    Time: 12:55p
//Updated in $/Leap/Source/Templates/Parent
//search condition, report formating updated
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/25/08    Time: 12:52p
//Updated in $/Leap/Source/Templates/Parent
//Replaced Srno with # in display
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/23/08    Time: 4:50p
//Updated in $/Leap/Source/Templates/Parent
//modify
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/18/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Parent
//added buttons at the bottom of the display
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/14/08    Time: 2:32p
//Updated in $/Leap/Source/Templates/Parent
//modified the display
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/09/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/Parent
//modifed the display
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/05/08    Time: 7:29p
//Updated in $/Leap/Source/Templates/Parent
//modified breadcrum
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 1:35p
//Updated in $/Leap/Source/Templates/Parent
//added fee listing

?>
