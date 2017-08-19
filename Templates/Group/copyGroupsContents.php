<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
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
                        <td class="content_title">Copy Groups : </td>
                        <td class="content_title">&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <table border="0" cellpadding="0" cellspacing="0">
                 <tr>
                  <td class="contenttab_internal_rows"><b>Target Class</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="targetClassId" id="targetClassId" class="selectfield" style="width:250px;" size="1" onchange="getPreviousClass(this.value);">
                    <option value="">Select</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getActiveClassesWithNoGroups();
                    ?>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows"><b>Source Class</b></td>
                  <td class="padding">:</td>
                  <td class="padding">
                   <select name="sourceClassId" id="sourceClassId" style="width:250px;" class="selectfield" size="1" >
                    <option value="">Select</option>
                   </select>
                  </td>
                  <td class="padding">
                    <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/copy_groups.gif" title="Copy Groups"   style="margin-bottom: -2px;" onClick="copyGroups();return false;"/>
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
<?php
// $History: copyGroupsContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/09   Time: 14:46
//Updated in $/LeapCC/Templates/Group
//Chenged the button
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:16
//Created in $/LeapCC/Templates/Group
//Done group coping module
?>