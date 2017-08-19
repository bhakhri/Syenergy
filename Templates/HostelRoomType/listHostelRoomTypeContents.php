<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
            <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php
                                      $specialSearchCondition="getHostelRoomTypeData();";
                                      require_once(TEMPLATES_PATH . "/searchForm.php"); 
                                    ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('HostelRoomTypeDiv',340,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="HostelRoomTypeResultDiv"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('HostelRoomTypeDiv',''); ?>
<form name="HostelRoomDetail" action="" method="post">  
<input type="hidden" name="hostelRoomTypeId" id="hostelRoomTypeId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Hostel Room Type </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="roomType" name="roomType" style="width:170px" class="inputbox" maxlength="100" />
     </td>
	</tr>
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Abbr.</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="roomAbbr" name="roomAbbr" style="width:170px" class="inputbox" maxlength="20" />
     </td>
	</tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('HostelRoomTypeDiv');if(flag==true){getHostelRoomTypeData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<?php
// $History: listHostelRoomTypeContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/09    Time: 12:15p
//Updated in $/LeapCC/Templates/HostelRoomType
//Gurkeerat: resolved issue 1341
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Templates/HostelRoomType
//give print & export to excel facility
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/HostelRoomType
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Templates/HostelRoomType
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/07/09    Time: 5:12p
//Updated in $/LeapCC/Templates/HostelRoomType
//bug fixed build no. BuildCC#cc 0001.doc 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:49a
//Created in $/LeapCC/Templates/HostelRoomType
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/02/09    Time: 4:28p
//Created in $/SnS/Templates/Document
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/27/09    Time: 4:06p
//Created in $/SnS/Templates/TestType
//new template for test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:33a
//Created in $/Leap/Source/Templates/TestType
//new template file for test type contents
//
 
?>