<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="POST" name="attendancePercentFrm" id="attendancePercentFrm" onsubmit="return false;">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
       <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Attendance Marks Percent Details:</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
				<table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
				<tr>
				 <td valign="top" class="content" align="center">
				 <table width="100%" border="0" cellspacing="0" cellpadding="5">
				 <tr>
					<td class="contenttab_border2" align="center">
					<table border="0" cellspacing="0" cellpadding="0px" width="20%">
                    <tr>	
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>Attendance Set&nbsp;:</b></nobr></td>
                        <td  class="padding" nowrap>  
                          <select size="1" class="selectfield" name="attendanceSetId" id="attendanceSetId" onChange="hideValue(); return false;" style="width:350px">
                              <option value="" selected="selected">Select</option>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getAttendanceSetData('','WHERE evaluationCriteriaId = 5 ');
                                ?>
                          </select>
                          <select size="1" class="selectfield" name="timeTableLabelId" id="timeTableLabelId" style="display:none;">
                            <option value="" selected="selected">Select</option>
                            <?php
                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                               echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                            ?>
                          </select>
                        </td>
				<!--  
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>Time Table&nbsp;:</b></nobr></td>
                        <td  class="padding" nowrap>  
                          <select size="1" class="selectfield" name="timeTableLabelId" id="timeTableLabelId" onBlur="getClassDegree(); return false;">
                            <option value="" selected="selected">Select</option>
                            <?php
                               //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                               //echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                            ?>
                          </select>
                        </td>
                        <td class="contenttab_internal_rows" nowrap><nobr><b>Degree&nbsp;:</b></nobr></td>
                        <td class="padding" nowrap>  
                          <select size="1" class="selectfield" name="degreeId" id="degreeId" onChange="hideValue();">
                               <option value="" selected="selected">Select</option>
                          </select>
                        </td> 
                        <td class="contenttab_internal_rows" nowrap><nobr><b>Subject Type&nbsp;:</b></nobr></td>
                         <td class="padding" nowrap>  
                         <select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" onChange="hideValue();">
						    <option value="" selected="selected">Select</option>
                            <?php
                              //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              //echo HtmlFunctions::getInstance()->getSubjectTypeData();
                            ?>
						  </select>
                        </td>
                   -->                             
                        <td class="padding" nowrap>  
                        &nbsp;&nbsp;&nbsp;
                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return showAttendancePercent();  return false;" />  
                        <td>
				    </tr>
			</table>
			</td>
			</tr>
             <div id="results" style="display:none">      
                 <tr id="results11" style="display:none">
                    <td class="contenttab_row" valign="top" >   
                       <!-- <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
                            <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                        </div>   -->                           
                             <table class="padding" width="100%" border="0"  id="anyid">
                                <tbody id="anyidBody">
                                  <tr class="rowheading">
                                    <td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
                                    <td class="searchhead_text" width="25%" align="left"><nobr><b>Percent From</b></nobr></td>
					                <td class="searchhead_text" width="25%" align="left"><nobr><b>Percent To</b></nobr></td>
                                    <td class="searchhead_text" width="25%" align="left"><nobr><b>Marks Scored</b></nobr></td>
                                    <td class="searchhead_text" width="17%" align="center"><nobr><b>Action</b></nobr></td>
                                  </tr>
                                </tbody>
                             </table>               
                        <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
                            <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                        </div>   
                     </td>
                   </tr>
  				   <tr>
                        <!-- <td height="5"><input type="hidden" name="tempValue" id="tempValue" /></td> -->
                   </tr>
                   <tr id="trAttendance" style="display:none">
                      <td  align="right" style="padding-right:5px" colspan="9">
                    <!--<input type="hidden" name="listSubject" value="1"> -->
            <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
             &nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;
                    </td>
                 </tr>
             </div>                  
           </table>
         </td>
       </tr>
    </table>
                    
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
</form>             
    <script type="text/javascript">
      periodIds = '<?php echo $periodIdValues; ?>';
    </script>
<?php 
// $History: listAttendanceMarksPercentContents.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 1/28/10    Time: 10:35a
//Updated in $/LeapCC/Templates/AttendancePercent
//size increase format updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 1/21/10    Time: 2:38p
//Updated in $/LeapCC/Templates/AttendancePercent
//format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/21/10    Time: 12:59p
//Updated in $/LeapCC/Templates/AttendancePercent
//look & feel updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/29/09   Time: 4:16p
//Updated in $/LeapCC/Templates/AttendancePercent
//validation format update (remove )
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/29/09   Time: 3:38p
//Updated in $/LeapCC/Templates/AttendancePercent
//onHide function added (attendance set)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/29/09   Time: 2:05p
//Updated in $/LeapCC/Templates/AttendancePercent
//new enhancement attendance Set Id base checks updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AttendancePercent
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/20/09   Time: 3:43p
//Updated in $/LeapCC/Templates/AttendancePercent
//look & feel updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/20/09   Time: 12:26p
//Updated in $/LeapCC/Templates/AttendancePercent
//degreeId, timeTableLabelId added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/09    Time: 12:23p
//Updated in $/LeapCC/Templates/AttendancePercent
//code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 12:29p
//Created in $/LeapCC/Templates/AttendancePercent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 11:26a
//Created in $/Leap/Source/Templates/ScTimeTable
//coursewise time table added
//

?>
 
    


