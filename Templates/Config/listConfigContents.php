<?php 
//
//This file creates Html Form output in "Config" Module 
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Local Masters&nbsp;&raquo;&nbsp;Config Masters</td>
                <td valign="top" align="right">
               <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
				  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>
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
                        <td class="content_title">Config Detail : </td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddConfig',315,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
	 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                 <tr class="rowheading">
                    <td width="10%" class="unsortable"><b>#</b></td>
                    <td width="27%" height="20"  class="searchhead_text"><strong>Parameter</strong>&nbsp;<img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                      <td width="27%" height="20"  class="searchhead_text"><strong>Label</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=labelName')" /></td>
                      <td width="27%" height="20"  class="searchhead_text"><strong>Value</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=value')" /></td>
                    <td width="10%" class="unsortable" align="right"><b>Action</b></td>
                 </tr>
                <?php    
                $recordCount = count($configRecordArray);
                if($recordCount >0 && is_array($configRecordArray) ) { 
                   for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($configRecordArray[$i]['param']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($configRecordArray[$i]['labelName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($configRecordArray[$i]['value']).'</td>
						<td width="100" class="searchhead_text1" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$configRecordArray[$i]['configId'].',\'EditConfig\',300,100); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteConfig('.$configRecordArray[$i]['configId'].');"/></td>
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

<?php floatingDiv_Start('AddConfig','Add Config'); ?>
<form name="addConfig" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="contenttab_internal_rows" width="30%"><strong>Parameter :</strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="20" id="param" name="param" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Label : </strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="64" id="label" name="label" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Value : </strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="30" id="val" name="val" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddConfig');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditConfig','Edit Config'); ?>
<form name="editConfig" action="" method="post">
	<input type="hidden" name="configId" id="configId" value="" /> 
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="contenttab_internal_rows" width="30%"><strong>Parameter :</strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="20" id="param" name="param" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Label : </strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="64" id="label" name="label" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Value : </strong></td>
			<td width="79%" class="padding">
			<input type="text" maxlength="30" id="val" name="val" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditConfig');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End();
// $History: listConfigContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Config
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/05/08    Time: 5:42p
//Created in $/Leap/Source/Templates/Config
//file added for config masters
//

?>
    <!--End: Div To Edit The Table-->