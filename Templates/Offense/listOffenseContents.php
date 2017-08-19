<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Jaineesh
// Created on : (22.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                                      $specialSearchCondition="getOffenseData()";
                                      require_once(TEMPLATES_PATH . "/searchForm.php"); 
                                    ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('OffenseActionDiv',330,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="OffenseResultDiv"></div></td>
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
<?php floatingDiv_Start('OffenseActionDiv',''); ?>
<form name="OffenseDetail" action="" method="post">  
<input type="hidden" name="offenseId" id="offenseId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr> 
      <td width="9%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Offense Name<?php echo REQUIRED_FIELD; ?> :</strong></nobr></td>
      <td width="91%" class="padding">
      <input type="text" id="offenseName" name="offenseName"  style="width:170px" class="inputbox" maxlength="64"/>
     </td>
   </tr>
    <tr> 
      <td width="9%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Offense Abbr.<?php echo REQUIRED_FIELD; ?> :</strong></nobr></td>
      <td width="91%" class="padding">
      <input type="text" id="offenseAbbr" name="offenseAbbr"  style="width:170px" class="inputbox" maxlength="15"/>
     </td>
   </tr>
   
   <tr> 
      <td width="9%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Offense Desc :</strong></nobr></td>
      <td width="91%" class="padding">
      <input type="text" id="offenseDesc" name="offenseDesc"  style="width:170px" class="inputbox" maxlength="50"/>
     </td>
   </tr>
   
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('OffenseActionDiv');if(flag==true){getOffenseData();flag=false;}return false;" />
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
// $History: listOffenseContents.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/23/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Offense
//fixed bug nos. 0002338, 0002341, 0002336, 0002337
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Templates/Offense
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Templates/Offense
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/02/09    Time: 3:23p
//Updated in $/LeapCC/Templates/Offense
//put required field
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/27/09    Time: 3:04p
//Updated in $/LeapCC/Templates/Offense
//put offense/achv tab in student info
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:43p
//Created in $/LeapCC/Templates/Offense
//get the template of offense
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:42p
//Created in $/LeapCC/Templates/Block
//get the template of offense
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:17p
//Updated in $/Leap/Source/Templates/Offense
//modified for hidden 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:15p
//Created in $/Leap/Source/Templates/Offense
//get the template of offense
//

?>