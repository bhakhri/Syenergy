<?php
//-------------------------------------------------------
// Purpose: to design the layout for SMS.
//
// Author : Parveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">      

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
             <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                <tr>
                    <td valign="top" class="contenttab_row1">
                            <table width="20%" align="center" border="0" >
                                <tr>
                                    <td style="valign:top" class="contenttab_internal_rows">
                                        <strong><nobr>Time Table Label</nobr></strong>&nbsp;
                                    </td>
                                    <td class="padding">:</td>
                                    <td>
                                        <select id="labelId" name="labelId" class="selectfield" onChange="showResult(); return false;">
                                            <option value="" selected="selected">Select</option>
                                            <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                            ?>
                                        </select>
                                    </td>
                                  </tr>
                                </table>
                        </td>
                     </tr>
                   </table>                  
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Subject Wise Optional Group Detail : </td>
                        <td class="content_title" align="right">
                          <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
                          <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">&nbsp;
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                  <div id="scroll2" style="overflow:auto; width:100%; height:405px; vertical-align:top;">
                    <div id="results" style="width:98%; vertical-align:top;"></div>
                  </div>
                </td>
          </tr>
          <tr><td height="10px"></td></tr>
          <tr>
           <td align="right">
             <input type="image" src="<?php echo IMG_HTTP_PATH ?>/save.gif" border="0" onClick="addOptionalGroups()">     
          </td></tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<!--Start Student List  Div-->
<?php floatingDiv_Start('divMessage','Student Details','',''); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:350px; height:200px; vertical-align:top;">
                <div id="message" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>