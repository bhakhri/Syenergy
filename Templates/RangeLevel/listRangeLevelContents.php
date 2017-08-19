<?php 

//
//This file creates Html Form output in "Range Level" Module 
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Exam Masters&nbsp;&raquo;&nbsp;Range Level Master</td>
                <td valign="top" align="right">
               <form action="" method="" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');return false;">

                    <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />

                    <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;

                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');

                    return false;"/>

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
                        <td class="content_title">Range Level Detail : </td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddRange',315,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
	 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                 <tr class="rowheading">
                    <td width="5%" class="unsortable"><b>#</b></td>
                    <td width="30%" height="20"  class="searchhead_text"><strong>From Range</strong>&nbsp;<img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField=rangeFrom')" /></td>
                     <td width="30%" height="20"  class="searchhead_text"><strong>To Range</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField='+sortField)" /></td>
                     <td width="30%" height="20"  class="searchhead_text"><strong>Range Label</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField='+sortField)" /></td>
                    <td width="5%" class="unsortable" align="right"><b>Action</b></td>
                 </tr>
                <?php    
                $recordCount = count($rangeLevelRecordArray);
                if($recordCount >0 && is_array($rangeLevelRecordArray) ) { 
                   for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($rangeLevelRecordArray[$i]['rangeFrom']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($rangeLevelRecordArray[$i]['rangeTo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($rangeLevelRecordArray[$i]['rangeLabel']).'</td>
						<td width="100" class="searchhead_text1" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$rangeLevelRecordArray[$i]['rangeId'].',\'EditRange\',300,100); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteRange('.$rangeLevelRecordArray[$i]['rangeId'].');"/></td>
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
    <!--Start Add Div-->

<?php floatingDiv_Start('AddRange','Add Range'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="addRange" action="" method="post">
	<tr>
		<td class="contenttab_internal_rows" ><strong>Range From<?php echo REQUIRED_FIELD;?></strong></td>
		<td class="padding">:</td>
		<td class="padding"><input type="text" maxlength="100" id="rangeFrom" name="rangeFrom" style="width:142px" />
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Range To<?php echo REQUIRED_FIELD;?></strong></td>
		<td class="padding">:</td>
        <td class="padding">
		<input type="text" maxlength="10" id="rangeTo" name="rangeTo" style="width:142px" />
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><strong>Range Label<?php echo REQUIRED_FIELD;?></strong></td>
		<td class="padding">:</td>
        <td class="padding">
		<input type="text" maxlength="10" id="rangeLabel" name="rangeLabel" style="width:142px" />
		</td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
		<tr>
		<td align="center" style="padding-right:10px" colspan="3">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
		<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddRange');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	</form>
</table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditRange','Edit Range'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="editRange" action="" method="post">
		<input type="hidden" name="rangeId" id="rangeId" value="" /> 
		<tr>
			<td class="contenttab_internal_rows" ><strong>Range From <?php echo REQUIRED_FIELD;?></strong></td>
			<td class="padding">:</td>
        <td class="padding">
			<input type="text" maxlength="100" id="rangeFrom" name="rangeFrom" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Range To <?php echo REQUIRED_FIELD;?> </strong></td>
			<td class="padding">:</td>
        <td class="padding">
			<input type="text" maxlength="10" id="rangeTo" name="rangeTo" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Range Label <?php echo REQUIRED_FIELD;?> </strong></td>
			<td class="padding">:</td>
        <td class="padding">
			<input type="text" maxlength="10" id="rangeLabel" name="rangeLabel" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
			onclick="javascript:hiddenFloatingDiv('EditRange');return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
	</form>
</table>
    <?php floatingDiv_End();
// $History: listRangeLevelContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:04p
//Updated in $/LeapCC/Templates/RangeLevel
//Gurkeerat: resolved issue 1276
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/27/09    Time: 4:25p
//Updated in $/LeapCC/Templates/RangeLevel
//Gurkeerat: resolved issue 1279,1277,1280,1278
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/RangeLevel
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/08    Time: 3:05p
//Updated in $/Leap/Source/Templates/RangeLevel
//changed search button
//
	?>
    <!--End: Div To Edit The Table-->