<?php 
//it contain the template of time table 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
            
           <tr>
        <td valign="top">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
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
                        <td class="content_title">Time Table :</td>
                         <td align='right'>
                            <span class="content_title">Study Period:</span>
                           <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $studentDataArr[0]['studentId']?>',this.value);"> 
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
                    <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                        <div id="timeTableResultDiv" style="width:98%; vertical-align:top;"></div>
                    </div>
                 </td>
              </tr>
              <tr>
                  <td colspan='1' align='right'><div id = 'saveDiv3'></div></td>
               </tr> 
             </td>
              </tr>
           <tr>
           <td class="content_title" align="right" style="padding-right:10px">
           <!--   <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/print.gif"  onClick="printReport(); return false;" >&nbsp;
                  <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif"  onClick="printCSV(); return false;" >&nbsp;
           -->       
           </td>
           </tr>
          </table>
        </td>
    </tr>
    
    </table>

    
<?php
//$History: timeTableContents.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/21/10    Time: 5:39p
//Updated in $/LeapCC/Templates/Parent
//format udpated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/04/09    Time: 5:37p
//Updated in $/LeapCC/Templates/Parent
//showTimeTablePeriodsColumnsCSV, showTimeTablePeriodsRowsCSV function
//base code update
//

?>
