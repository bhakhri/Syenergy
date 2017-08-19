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
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
            <tr>
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
                        <td class="content_title">Change Student Branch : </td>
                         
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="studentForm" id="studentForm" method="post" action="" onsubmit="return false;" >
                <table align="left" border="0" width="100%" cellpadding="0" cellspacing="0" >
                    <tr>
                        <!--<td  width="7%" class="contenttab_internal_rows1" align="left"><nobr><b>Time Table</b></nobr></td>
                        <td  width="10%" class="padding" ><nobr><b>:&nbsp;</b>
                        <select size="1" class="inputbox" name="labelId" id="labelId" onchange="getTimeTableClasses();">
                        <option value="" >Select</option>
                        <?php
                          //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          //echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                        ?>
                        </select></nobr>
                        </td>
                       --> 
                        <td class="contenttab_internal_rows1" style="padding-left:10px;" align="left" width="4%">
                            <b>Class</b>
                        </td>
                        <td class="padding" width="15%"><nobr><b>:</b>
                            <select size="1" class="inputbox" name="classId" id="classId" style="width:250px;" onchange="hideResults();">
                                <option value="">Select</option>
                                <?php
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->getSelectedTimeTableClassesForBranchChange();
                                ?> 
                            </select></nobr>
                        </td>
                      <td align="left" style="padding-right:5px;">
                        <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateData(this.form);return false;" />
                     </td>
                    </tr> 
                    <tr>
                     <td colspan="5">
                      <div id="results"></div>
                     </td>
                    </tr>
                    <tr>
                     <td colspan="5" height="5px;">
                     </td>
                    </tr> 
                    <tr id="saveTrId" style="display:none;">
                     <td colspan="5" align="center">
                      <input type="image" name="studentListSubmit1" value="studentListSubmit1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="doBranchChange();return false;" /> 
                     </td>
                    </tr>
                    </table>
                   </form> 
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
// $History: listTeacherAttendanceReportContents.php $
?>