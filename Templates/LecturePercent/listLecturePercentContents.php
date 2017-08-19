<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="POST" name="lecturePercentFrm" id="lecturePercentFrm" onSubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
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
                        <td class="content_title">Lecture Percent Details:</td>
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
                          <select size="1" class="selectfield" name="attendanceSetId" id="attendanceSetId" onChange="hideValue(); return false;" style="width:200px">
                              <option value="" selected="selected">Select</option>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getAttendanceSetData('','WHERE evaluationCriteriaId = 6 ');
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
                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="showLecturePercent();return false;" />  
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
                                    <td  class="searchhead_text" width="5px" align='center'><b>Sr. No.</b></td>
                                    <td  class="searchhead_text" width="25px" align='center'><b>Lecture Delievered</b></td>
                                    <td  class="searchhead_text" width="25px" align='center'><b>Lecture Attended From</b></td>
                                    <td  class="searchhead_text" width="25px" align='center'><b>Lecture Attended To</b></td>
                                    <td  class="searchhead_text" width="25px" align='center'><b>Marks Scored</b></td>
                                    <td  class="searchhead_text" width="20px" align='center'><b>Action</b></td>
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
                   <tr id="trLecture" style="display:none">
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
/*
<form action="" method="POST" name="lecturePercentFrm" id="lecturePercentFrm" onSubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
               <td valign="top">Activities&nbsp;&raquo;&nbsp;Exam Activities&nbsp;&raquo;&nbsp;Attendance Marks Slab</td>
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
                        <td class="content_title">Lecture Percent Details:</td>
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
					<table border="0" cellspacing="0" cellpadding="5" width="30%" >
					<tr>
						<td class="contenttab_internal_rows1"><nobr><b>Select Time Table: </b></nobr></td>
						<td class="padding"><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getClassDegree();">
							<option value="">Select</option>
							<?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
							?>
							</select>
						</td>

						<td class="contenttab_internal_rows1"><nobr><b>Select Degree: </b></nobr></td>
						<td class="padding"><select size="1" class="inputbox1" name="degree" id="degree" onChange="cleanUpTable();">
							<option value="">Select</option>
							<!--
							<?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
							?>-->
							</select>
						</td>

						<td class="contenttab_internal_rows"><nobr><b>Subject Type&nbsp;:</b></nobr></td>
                         <td class="contenttab_internal_rows1">  
                         <select size="1" class="inputbox1" name="subjectTypeId" id="subjectTypeId">
						    <option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getSubjectTypeData();
                            ?>
						  </select>
                        </td>
						<td class="padding" nowrap>  
                        &nbsp;&nbsp;&nbsp;
                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="showLecturePercent();return false;" />  
                        <td>
				    </tr>
			</table>
			</td>
			</tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results" style="display:none">
            <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></div>                          <table class="padding" width="100%" border="0"  id="anyid">
                            <tbody id="anyidBody">
                              <tr class="rowheading">
                                <td  class="searchhead_text" align="center"><b>Sr. No.</b></td>
                                <td  class="searchhead_text" align="left"><b>Lecture Delievered</b></td>
					            <td  class="searchhead_text" align="left"><b>Lecture Attended From</b></td>
								<td  class="searchhead_text" align="left"><b>Lecture Attended To</b></td>
                                <td  class="searchhead_text" align="left"><b>Marks Scored</b></td>
                                <td  class="searchhead_text" align="center"><b>Action</b></td>
                              </tr>
                            </tbody>
                         </table>               
                 </td>
               </tr>
				     <tr>
                        <!-- <td height="5"><input type="hidden" name="tempValue" id="tempValue" /></td> -->
                 </tr>
                 <tr id="trLecture" style="display:none">
                    <td  align="right" style="padding-right:5px" colspan="9">
                    <!--<input type="hidden" name="listSubject" value="1"> -->
    <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
   &nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;
                    </td>
                 </tr>
               </table>
            </div>          
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
-->
   <script type="text/javascript">
      periodIds = '<?php echo $periodIdValues; ?>';
   </script>
*/
?>
<?php 
// $History: listLecturePercentContents.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 12/29/09   Time: 6:52p
//Updated in $/LeapCC/Templates/LecturePercent
//attendance Set Id base code updated
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/LecturePercent
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/20/09   Time: 3:41p
//Updated in $/LeapCC/Templates/LecturePercent
//Fixed error in IE browser, attendance marks slabs
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/20/09   Time: 1:56p
//Updated in $/LeapCC/Templates/LecturePercent
//modification in code to show exact values
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:53a
//Updated in $/LeapCC/Templates/LecturePercent
//modification in code if select different degree to show attendance
//marks slabs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Templates/LecturePercent
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/18/09   Time: 3:33p
//Updated in $/LeapCC/Templates/LecturePercent
//Add Time Table Label dropdown and change in interface of attendance
//marks slabs. Now user can add the marks between the range for Lecture
//attended. 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/22/09    Time: 10:52a
//Updated in $/LeapCC/Templates/LecturePercent
//correct the bread crum
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 10:21a
//Updated in $/LeapCC/Templates/LecturePercent
//modified to check some validations
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/30/09    Time: 3:45p
//Created in $/LeapCC/Templates/LecturePercent
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
 
    


