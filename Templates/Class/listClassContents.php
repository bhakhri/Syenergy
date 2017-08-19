<?php
//-------------------------------------------------------
// Purpose: to design the layout for class.
//
// Author : Rajeev Aggarwal
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Institute Setup&nbsp;&raquo;&nbsp;Class Master</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');blankValues();return false;"/>&nbsp;
                  </form>
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
                        <td class="content_title">Class Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" align="right" onClick="displayFloatingDiv('AddClass','',500,250,300,100);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
                    <td width="3%"  class="unsortable">&nbsp;&nbsp;<b>#</b></td>
                    <td class="searchhead_text" width="55%"><b>Class Name </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="8%" class="searchhead_text"><b>Duration</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=degreeDuration')" /></td>
                     
					<td width="7%" class="searchhead_text"><b>Status</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=isActive')" /></td>
                    <td width="5%" class="unsortable" align="right"><b>Action</b></td>
                 </tr>
                <?php
                $recordCount = count($classRecordArray);
                if($recordCount >0 && is_array($classRecordArray) ) { 
                   for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
						if($classRecordArray[$i]['isActive']==1)
							$statusName = "Active";
						if($classRecordArray[$i]['isActive']==2)
							$statusName = "Future";
						if($classRecordArray[$i]['isActive']==3)
							$statusName = "Past";
						if($classRecordArray[$i]['isActive']==4)
							$statusName = "Unused";
						
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($classRecordArray[$i]['className']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($classRecordArray[$i]['degreeDuration']).'</td>
						 
                        <td class="padding_top" valign="top">'.$statusName.'</td>
                        <td width="100" class="searchhead_text1" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$classRecordArray[$i]['classId'].',\'EditClass\',315,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteClass('.$classRecordArray[$i]['classId'].');"/></td>
                        </tr>';
                    }
                    if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="5" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                }
                ?>                 
                 </table></div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddClass','Add Class'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <form name="addClass" action="" method="post">
 
<tr>
	<td class="contenttab_internal_rows"><strong>Batch:</strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="batch" id="batch" onchange="checkValues()">
		<option value="">Select Batch</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchName']==''? $classRecordArray[0]['batchId'] : $REQUEST_DATA['batchName'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td width="21%" class="contenttab_internal_rows"><strong><nobr>University :</nobr></strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="university" id="university" onchange="checkValues()">
		<option value="">Select University</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getUniversityAbbr($REQUEST_DATA['universityAbbr']==''? $classRecordArray[0]['universityId'] : $REQUEST_DATA['universityAbbr'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Degree : </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="degree" id="degree" onchange="checkValues()">
		<option value="">Select Degree</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getDegreeAbbr($REQUEST_DATA['degreeAbbr']==''? $classRecordArray[0]['degreeId'] : $REQUEST_DATA['degreeAbbr'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Branch : </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="branch" id="branch" onchange="checkValues()">
		<option value="">Select Branch</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branchCode']==''? $classRecordArray[0]['branchId'] : $REQUEST_DATA['branchCode'] );
		  ?>
		</select>
	</td>
</tr>

<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Study Period: </nobr></strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="studyperiod" id="studyperiod" onchange="checkValues()">
		<option value="">Select Study Period</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['periodName']==''? $classRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['periodName'] );
		  ?>
		</select>
	</td>
</tr>
 
<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Degree duration: </nobr></strong></td>
	<td width="79%" class="padding">
		<input type="text" class="inputbox" name="degreeDuration"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Class Name: </nobr></strong></td>
	<td width="79%" class="padding">
		<input type="text" id="className" name="className" style="width:375px" class="inputbox" 
		value="<?php echo CLASS_SEPRATOR ?>"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Description: </strong></td>
	<td width="79%" class="padding">
		<textarea cols="45" rows="5" name="classDescription" class="inputbox1"></textarea>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Status: </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="radioactive" id="radioactive">
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getClassStatus();
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td height="5px"></td>
</tr>
<tr>
	<td align="center" style="padding-right:10px" colspan="2">
	<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
	<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" width="90" height="28" onclick="javascript:hiddenFloatingDiv('AddClass');if(flag==true){location.reload();flag=false;}return false;" />
	</td>
</tr>
<tr>
<td height="5px"></td></tr>
<tr>
 
</form>
</table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditClass','Edit Class '); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<form name="editClass" action="" method="post">
<input type="hidden" name="classId" id="classId" value="" />
<tr>
	<td class="contenttab_internal_rows"><strong>Batch:</strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="batch" id="batch" onchange="checkValuesEdit()">
		<option value="">Select Batch</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchName']==''? $classRecordArray[0]['batchId'] : $REQUEST_DATA['batchName'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td width="21%" class="contenttab_internal_rows"><strong><nobr>University :</nobr></strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="university" id="university" onchange="checkValuesEdit()">
		<option value="">Select University</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getUniversityAbbr($REQUEST_DATA['universityAbbr']==''? $classRecordArray[0]['universityId'] : $REQUEST_DATA['universityAbbr'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Degree : </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="degree" id="degree" onchange="checkValuesEdit()">
		<option value="">Select Degree</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getDegreeAbbr($REQUEST_DATA['degreeAbbr']==''? $classRecordArray[0]['degreeId'] : $REQUEST_DATA['degreeAbbr'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Branch : </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="branch" id="branch" onchange="checkValuesEdit()">
		<option value="">Select Branch</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branchCode']==''? $classRecordArray[0]['branchId'] : $REQUEST_DATA['branchCode'] );
		  ?>
		</select>
	</td>
</tr>

<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Study Period: </nobr></strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="studyperiod" id="studyperiod" onchange="checkValuesEdit()">
		<option value="">Select Study Period</option>
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['studyperiod']==''? $classRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyperiod'] );
		  ?>
		</select>
	</td>
</tr>
 
<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Degree duration: </nobr></strong></td>
	<td width="79%" class="padding">
		<input type="text" class="inputbox" name="degreeDuration" id="degreeDuration"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong><nobr>Class Name: </nobr></strong></td>
	<td width="79%" class="padding">
		<input type="text" id="className" name="className" style="width:375px" class="inputbox" />
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Description: </strong></td>
	<td width="79%" class="padding">
		<textarea cols="45" rows="5" name="classDescription" class="inputbox1"></textarea>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><strong>Status: </strong></td>
	<td width="79%" class="padding">
		<select size="1" class="selectfield" name="radioactive" id="radioactive">
		 
		  <?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getClassStatus($REQUEST_DATA['classStatus']==''? $classRecordArray[0]['isActive'] : $REQUEST_DATA['classStatus'] );
		  ?>
		</select>
	</td>
</tr>
<tr>
	<td height="5px"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" width="90" height="28" 
                    onclick="javascript:hiddenFloatingDiv('EditClass');" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</form>
</table>
<?php 
floatingDiv_End(); 

// $History: listClassContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Class
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/29/08    Time: 10:18a
//Updated in $/Leap/Source/Templates/Class
//updated the textarea cols
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/16/08    Time: 5:54p
//Updated in $/Leap/Source/Templates/Class
//updated the bug no 0000074,0000073,0000072
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/12/08    Time: 1:08p
//Updated in $/Leap/Source/Templates/Class
//added "Class seprator" constant
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 12:17p
//Updated in $/Leap/Source/Templates/Class
//updated class status to "active","future","Past","unused"
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 5:16p
//Updated in $/Leap/Source/Templates/Class
//file updated with dependency constraint and edit module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 11:00a
//Created in $/Leap/Source/Templates/Class
//intial checkin
?>
<!--End: Div To Edit The Table-->
    


