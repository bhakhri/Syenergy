<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
               <td valign="top">Time Table&nbsp;&raquo;&nbsp;Manage Time Table</td>
                 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			  
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Timetable Detail:</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><form action="" method="POST" name="timeTableForm" id="timeTableForm">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
				 <td valign="top" class="content">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border1">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td height="5" colspan="6"></td>
					</tr>
					<tr>
                        <td class="contenttab_internal_rows" align="left" ><b>Period Slot: </b></td>
                        <td align="left" class="padding" ><select name="periodSlotId" id="periodSlotId" onChange="this.form.submit()" class="inputbox1" onChange="this.form.submit()" >
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getPeriodSlot($REQUEST_DATA['periodSlotId']);
                        ?>
                        </select> </td>                    	
					    <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table: </b></nobr></td>
                        <td class="padding"><select size="1" name="timeTableLabelId" id="timeTableLabelId" class="inputbox1">
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData($REQUEST_DATA['timeTableLabelId']);
                        ?>
                        </select></td>
                        <td class="contenttab_internal_rows"><nobr><b>Teacher: </b></nobr></td>
                        <td class="padding"><select size="1" class="selectfield" name="teacher" id="teacher" onChange="showTimeTable()" class="inputbox1">
                        <option value="">Select</option>
                          <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTeacher();
                          ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td class="padding"><select size="1" name="studentClass" id="studentClass" onChange="autoPopulate(this.value,'subject','Add');" class="inputbox1">
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getClassData();
                        ?>
                        </select></td>
						<td class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
						<td class="padding"><select size="1" class="selectfield" name="subject" id="subject" onChange="showTimeTable();getGroups()" class="inputbox1">
						<option value="">Select</option>
						</select></td>

						<td class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
						<td class="padding"><select size="1" class="selectfield" name="studentGroup" id="studentGroup" onChange="showTimeTable()" class="inputbox1">
						<option value="">Select</option>
						</select></td>
						<td class="contenttab_internal_rows"><nobr><b></b></nobr></td>
						<td height="10" ></td>
					</tr>
					<tr><td colspan="6" height="5px">&nbsp;</td></tr>
					</table>
				</td>
			</tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
                    
					<td width="10%" class="searchhead_text"><b>Period</b></td>
                    <td width="10%" class="searchhead_text"><b>Monday</b></td>
                    <td width="10%" class="searchhead_text"><b>Tuesday</b></td>
                    <td width="10%" class="searchhead_text"><b>Wednesday</b></td>
                    <td width="10%" class="searchhead_text"><b>Thursday</b></td>
                    <td width="10%" class="searchhead_text"><b>Friday</b></td>
                    <td width="10%" class="searchhead_text"><b>Saturday</b></td>
                    <td width="10%" class="searchhead_text"><b>Sunday</b></td>
                 </tr>
				 <?php
					$recordCount = count($periodRecordArray);
					if($recordCount >0 && is_array($periodRecordArray) ) { 
					   for($i=0; $i<$recordCount; $i++ ) {
							
							$bg = $bg =='row0' ? 'row1' : 'row0';
						    echo '<tr class="'.$bg.'">
								
								<td class="padding_top" valign="top">'.strip_slashes($periodRecordArray[$i]['periodNumber']).'</td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'1" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'1" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'1\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'1" id="timeTableId'.$periodRecordArray[$i]['periodId'].'1" value="" />
                                </td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'2" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'2" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'2\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'2" id="timeTableId'.$periodRecordArray[$i]['periodId'].'2" value="" /></td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'3" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'3" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'3\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'3" id="timeTableId'.$periodRecordArray[$i]['periodId'].'3" value="" /></td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'4" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'4" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'4\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'4" id="timeTableId'.$periodRecordArray[$i]['periodId'].'4" value="" /></td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'5" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'5" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'5\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'5" id="timeTableId'.$periodRecordArray[$i]['periodId'].'5" value="" /></td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'6" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'6" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'6\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'6" id="timeTableId'.$periodRecordArray[$i]['periodId'].'6" value="" /></td>
								<td class="padding_top" valign="top"><select name="roomPeriod'.$periodRecordArray[$i]['periodId'].'7" id="roomPeriod'.$periodRecordArray[$i]['periodId'].'7" onChange="roomSelected(\''.$periodRecordArray[$i]['periodId'].'7\')"><option value="">Room</option>'.$returnValues.'</select>
                                <input type="hidden" name="timeTableId'.$periodRecordArray[$i]['periodId'].'7" id="timeTableId'.$periodRecordArray[$i]['periodId'].'7" value="" /></td>
								</tr>';
							}
                     
					}
					else {
						echo '<tr><td colspan="5" align="center">No record found</td></tr>';
					}
                ?>  
				 <tr>
					<td height="5"><input type="hidden" name="tempValue" id="tempValue" /></td>
				 </tr>
				 <tr>
					<td  align="right" style="padding-right:5px" colspan="9">
					<input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validatetTimetableForm();return false;" />&nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;</td>
				 </tr>
                 </table></div>          
             </td>
          </tr>
          </table>
        </td>
		</tr>
		</table>
		</form>         
	</td>
	</tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: timetableContents.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:30a
//Updated in $/LeapCC/Templates/TimeTable
//Gurkeerat: resolved issue 1413
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:51p
//Updated in $/LeapCC/Templates/TimeTable
//modified in breadcrumb
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:35p
//Updated in $/LeapCC/Templates/TimeTable
//change the breadcrumb
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/07/09    Time: 12:47p
//Updated in $/LeapCC/Templates/TimeTable
//getTimeTableClassGroups function added 
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 12/17/08   Time: 6:59p
//Updated in $/LeapCC/Templates/TimeTable
//removed institutes dropdown
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/16/08   Time: 7:06p
//Updated in $/LeapCC/Templates/TimeTable
//Added HTML to populate Institute and Period Slot dropdown, will be
//working further on this tomorrow
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
//
//*****************  Version 13  *****************
//User: Pushpender   Date: 10/25/08   Time: 6:01p
//Updated in $/Leap/Source/Templates/TimeTable
//associated Time Table labels with Periods
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 10/08/08   Time: 4:09p
//Updated in $/Leap/Source/Templates/TimeTable
//optimized code, removed extra arguments from getTeacher functions and
//getClassData
//
//*****************  Version 11  *****************
//User: Pushpender   Date: 10/07/08   Time: 5:48p
//Updated in $/Leap/Source/Templates/TimeTable
//removed trailing spaces
//
//*****************  Version 10  *****************
//User: Pushpender   Date: 10/07/08   Time: 5:43p
//Updated in $/Leap/Source/Templates/TimeTable
//Added the functionality for Time Table Labels
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 9/24/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/TimeTable
//corrected wednesday spellings
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 9/20/08    Time: 11:42a
//Updated in $/Leap/Source/Templates/TimeTable
//added hidden field tempValue
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 9/19/08    Time: 8:26p
//Updated in $/Leap/Source/Templates/TimeTable
//added hidden fields for existing records in db
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:05p
//Updated in $/Leap/Source/Templates/TimeTable
//updated file with bug fixes
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/26/08    Time: 11:43a
//Updated in $/Leap/Source/Templates/TimeTable
//added css class to select box
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/25/08    Time: 6:29p
//Updated in $/Leap/Source/Templates/TimeTable
//removed print button
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/25/08    Time: 4:07p
//Updated in $/Leap/Source/Templates/TimeTable
//updated refresh screen for room
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/22/08    Time: 12:31p
//Updated in $/Leap/Source/Templates/TimeTable
//added print report 
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:23p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin

?>