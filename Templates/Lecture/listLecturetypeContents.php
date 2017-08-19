<?php
//-------------------------------------------------------
// Purpose: to design the layout for lecture type.
//
// Author : Rajeev Aggarwal
// Created on : (09.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
			 <form action="" method="" name="searchForm">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Academic Masters&nbsp;&raquo;&nbsp;Lecture Type Master</td>
                <td valign="top" align="right">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                <img src="<?php echo IMG_HTTP_PATH; ?>/search.gif" value="Search" name="submit" style="margin-bottom: -5px;"    onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" >
                  &nbsp;
                  
                  </td>
            </tr>
            </table>
			</form>
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
                        <td class="content_title">Lecture Type Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" align="right" onClick="displayWindow('AddLectureType',315,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="70%" class="searchhead_text"><b>Lecture Type</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
					<td width="10%" class="searchhead_text" align="right"><b>Action</b></td>
                 </tr>
                <?php
                $recordCount = count($lecturetypeRecordArray);
                if($recordCount >0 && is_array($lecturetypeRecordArray) ) { 
                   for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($lecturetypeRecordArray[$i]['lectureName']).'</td>
						<td width="100" class="padding_top" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$lecturetypeRecordArray[$i]['lecturetypeId'].',\'EditLectureType\',315,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteLectureType('.$lecturetypeRecordArray[$i]['lecturetypeId'].');"/></td>
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

<?php floatingDiv_Start('AddLectureType','Add Lecture Type'); ?>
 <form name="AddLectureType" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Name <?php echo REQUIRED_FIELD ?>: </b></nobr></td>
    <td width="79%" class="padding"><input type="text" id="lectureType" name="lectureType" class="inputbox" maxlength="20" /></td>
</tr>
 
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddLectureType');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditLectureType','Edit Lecture Type '); ?>
<form name="EditLectureType" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<input type="hidden" name="lectureTypeId" id="lectureTypeId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD ?> : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="lectureType" name="lectureType" class="inputbox" maxlength="20"/></td>
    </tr>
     
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('EditLectureType');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>
<?php 
floatingDiv_End(); 
// $History: listLecturetypeContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/27/09    Time: 7:01p
//Updated in $/LeapCC/Templates/Lecture
//Gurkeerat: resolved issue 1321,1320
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/Lecture
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Lecture
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/02/08    Time: 7:26p
//Updated in $/Leap/Source/Templates/Lecture
//updated with html validator
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Templates/Lecture
//updated formatting
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/25/08    Time: 11:48a
//Updated in $/Leap/Source/Templates/Lecture
//changed search  button
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Templates/Lecture
//updated issue no 0000062,0000061,0000070
?>