<?php
//-------------------------------------------------------
// Purpose: to design the layout for payment status.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
           <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top" width="100%">
			<form action="" method="POST" name="listForm" id="listForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" >
			 <tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="contenttab_border2">
					<tr>	
						<td class="contenttab_internal_rows" width="11%"><nobr><b>Degree</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top" width="30%"><select size="1" class="inputbox1" name="degree" id="degree">
						<option value="">All</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getClassData();
						?>
						</select></td>
						<td class="contenttab_internal_rows" width="11%"><nobr><b>Batch</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top" width="20%"><select size="1" class="inputbox1" name="batch" id="batch">
						<option value="">All</option>
						  <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchName']==''? $REQUEST_DATA['batch'] : $REQUEST_DATA['batchName'] );
						  ?>
						</select>
						</td>
						<td class="contenttab_internal_rows"><nobr><b>Study Period</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"><select size="1" class="inputbox1" name="studyperiod" id="studyperiod">
						<option value="">All</option>
						  <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['periodName']==''? $REQUEST_DATA['studyperiod'] : $REQUEST_DATA['periodName'] );
						  ?>
						</select>
						</td>
					</tr>
					<tr>	
						<td class="contenttab_internal_rows"><nobr><b>Student Name</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"><input type="text" name="studentName" class="inputbox"></td>
						<td class="contenttab_internal_rows"><nobr><b>Roll No</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"><input type="text" name="studentRoll" id="studentRoll" class="inputbox"></td>
						<td class="contenttab_internal_rows"><nobr><b>Fee Cycle</b></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"> <select size="1" name="feeCycle" id="feeCycle"  onChange="populateValues()" class="inputbox1">
						  <option value="">All</option>
						  <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getFeeCycleData();
						  ?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><B>From Date</B> </td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="contenttab_internal_rows" style="text-align:left"><?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('fromDate','');
						?><B>To Date:</B><?php
							   require_once(BL_PATH.'/HtmlFunctions.inc.php');
							   echo HtmlFunctions::getInstance()->datePicker('toDate','');
						?></td>
						<td class="contenttab_internal_rows"><nobr><B>Instrument Status</B></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"><select size="1" name="paymentStatus" id="paymentStatus" class="inputbox1">
						  <option value="">All</option>
						  <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getFeeReceiptPaymentStatus();
						  ?>
						</select>
						</td>
						<td class="contenttab_internal_rows"><nobr><B>Receipt Status</B></nobr></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="padding_top"><select size="1" name="receiptStatus" id="receiptStatus" class="inputbox1">
						  <option value="">All</option>
						  <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getFeeReceiptStatus();
						  ?>
						</select>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><B>Amount Paid From</B></td>
						<td class="contenttab_internal_rows"><B>:</B></td>
						<td class="contenttab_internal_rows1"><input type="text" name="fromAmount" id="fromAmount" class="inputbox1" size="9"><B>&nbsp;To:</B><input type="text" name="toAmount" id="toAmount" class="inputbox1" size="10"></td>
						<td  align="right" style="padding-right:5px" colspan="2"></td>
						<td  align="right" style="padding-right:5px" colspan="4">
						<input type="hidden" name="listStudent" value="1"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateStatus();document.getElementById('saveDiv').style.display='';document.getElementById('showTitle').style.display='';document.getElementById('showData').style.display='';return false;"/>
						
						</td>
					</tr>
					</table>
				</td>
			</tr>
            <tr id="showTitle" style="display:none">
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Fee Receipt Status: </td>
						<td colspan='1' align='right'>
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeStatusCSV();return false;"/></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id="showData" style="display:none">
                <td class="contenttab_row" valign="top" ><div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
					<td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="20%" class="searchhead_text"><b>Name</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="10%" class="searchhead_text"><b>Roll No</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=rollNo')" /></td>
					<td width="15%" class="searchhead_text"><b>Univ.No</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=rollNo')" /></td>
					 <td width="12%" class="searchhead_text"><b>Fee Payable</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=universityRollNo')" /></td>
                    <td width="10%" class="searchhead_text"><b>Fine</b></td>
                    <td width="10%" class="searchhead_text"><b>Concession</b></td>
                    <td width="15%" class="searchhead_text"><b>Amount Payable</b></td>
					<td width="18%" class="searchhead_text" nowrap><b>Amount Paid</b></td>
                 </tr>
                <?php
                $recordCount = count($studentRecordArray);
                if($recordCount >0 && is_array($studentRecordArray) ) { 
                   for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        $rollno = $studentRecordArray[$i]['rollNo'];
						$cls = $studentRecordArray[$i]['className'];
						$batchName = $studentRecordArray[$i]['batchName'];
						
						//$classNameArr = explode(" - ", $cls);
						if($rollno=="")
							$rollno = "--";

						$uniRollno = $studentRecordArray[$i]['universityRollNo'];
						if($uniRollno=="")
							$uniRollno = "--";
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['fullName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($rollno).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($uniRollno).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($cls).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($batchName).'</td>
                        <td width="100" class="padding_top"><a href="studentDetail.php?id='.strip_slashes($studentRecordArray[$i]['studentId']).'&degree='.$REQUEST_DATA['degree'].'&batch='.$REQUEST_DATA['batch'].'&studyperiod='.$REQUEST_DATA['studyperiod'].'&studentName='.$REQUEST_DATA['studentName'].'&studentRoll='.$REQUEST_DATA['studentRoll'].'" title="Edit"><U>Show Detail</U></a></td>
                        </tr>';
                    }
                    if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="8" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';              
					}
                }
                else {
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                }
                ?>   
                 </table></div>          
             </td>
          </tr>
		  <tr>
			<td height="10"></td>
		  </tr>
         <tr><td colspan='1' align='right'><div id = 'saveDiv' style="display:none"><input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validatetStatus();return false;" />&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeStatusCSV();return false;"/></div></td></tr>
          </table>
        </td>
    </tr>
    </table>
	 </form>
    </td>
    </tr>
    </table>
<?php 
// $History: paymentReceiptStatus.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/28/09   Time: 6:45p
//Updated in $/LeapCC/Templates/Student
//added ids for fields
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Student
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/18/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Student
//Updated report formatting so that "outstanding" field stand Out
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Templates/Student
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/09/08    Time: 12:27p
//Updated in $/Leap/Source/Templates/Student
//updated with payment instrument search criteria
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:04p
//Created in $/Leap/Source/Templates/Student
//initial checkin
?>
 
    


