<?php 
//it contain the template of time table for class
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
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

                    <table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td class="contenttab_internal_rows" ><nobr><b>Time Table</b></nobr></td>
                        <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>    
                        <td class='contenttab_internal_rows' align='left'><nobr>
                            <select size="1" class="inputbox1" name="labelId" id="labelId" class="inputbox1" style="width:150px" onchange="populateRoom();">
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTimeTableLabelDate();
                            ?>
                            </select></nobr>
                        </td>
                        <td class="contenttab_internal_rows" ><nobr><b>&nbsp;Room</b></nobr></td>
                        <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>    
                        <td class='contenttab_internal_rows' align='left'><nobr>
                            <select size="1" class="inputbox1"  style="width:260px" name="roomId" id="roomId">
                            </select></nobr>
                        </td>
                          <td class="contenttab_internal_rows" id="timeTableCheck" ><nobr>
                            <?php 
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                echo HtmlFunctions::getInstance()->makeTimeTableSearch(); 
                            ?>     
                        </nobr></td>
                        <td align="left" valign="middle" style="padding-left:20px">
                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show.gif" onClick="return getTimeTableData();return false;" />
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_row" valign="top" >
             <!--Time Table Data Template-->
                <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                   <div id="results" style="width:98%; vertical-align:top;"></div>
                </div>
            <!--Time Table Data Template Ends-->           
             </td>
          </tr>
         
          </table>
          
        </td>
    </tr>
    </table>
<?php
//$History: listRoomTimeTableContents.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Templates/TimeTable
//validation & condition format updated 
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 4/05/10    Time: 6:38p
//Updated in $/LeapCC/Templates/TimeTable
//bug fixing. FCNS No.1524
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/TimeTable
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/18/09    Time: 10:53a
//Updated in $/LeapCC/Templates/TimeTable
//sorting & validations updated & CSV file created
//
//*****************  Version 4  *****************
//User: Administrator Date: 26/05/09   Time: 11:31
//Updated in $/LeapCC/Templates/TimeTable
//Corrected table width
//
//*****************  Version 3  *****************
//User: Administrator Date: 26/05/09   Time: 11:28
//Updated in $/LeapCC/Templates/TimeTable
//Changed showlist button to show
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/05/09    Time: 11:22a
//Updated in $/LeapCC/Templates/TimeTable
//Changed Time table format so that admin can decide the display of time
//table i.e in periods in rows or periods in column
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/14/09    Time: 6:14p
//Created in $/LeapCC/Templates/TimeTable
//Intial checkin
?>
