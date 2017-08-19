<?php 
//it contain the template of time table 
//
// Author :Rajeev Aggarwal
// Created on : 05-09-2008
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
                <td valign="top">Scheduling &nbsp;&raquo;&nbsp; Display Master Time Table </td>
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
                <td class="contenttab_border2" height="20">

                   <form action="" method="POST" name="timeTableForm" id="timeTableForm">
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td height="5"></td>
					</tr>
					<tr>	
						<td class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
						<td class="padding"><select size="1" name="studentClass" id="studentClass" onChange="autoPopulate(this.value,'subject','Add');" class="inputbox1">
						<option value="">Select</option>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getClassData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
						?>
						</select></td>
						<td class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
						<td class="padding"><select size="1" class="selectfield" name="studentGroup" id="studentGroup"class="inputbox1" onChange="clearText()">
						<option value="">Select</option>
						</select></td>
						 <td align="left"  valign="middle">
                         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                        </td>
						<td class="padding"  style="display:none"><select size="1" class="selectfield" name="subject" id="subject" class="inputbox1">
						<option value="">Select</option>
						</select></td>
						
					</tr>
					<tr><td colspan="4" height="5px"></td></tr>

					</table>
					 </form>
                </td>
             </tr>
             <tr>
             <td valign="top" >
             <!--Time Table Data Template-->
              <div id="results">
              
              </div>
            <!--Time Table Data Template Ends-->           
             </td>
          </tr>
         
          </table>
		 
        </td>
    </tr>
    </table>
<?php
//$History: masterTimeTableContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/04/08    Time: 5:56p
//Updated in $/Leap/Source/Templates/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/04/08    Time: 5:55p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin
 
?>
