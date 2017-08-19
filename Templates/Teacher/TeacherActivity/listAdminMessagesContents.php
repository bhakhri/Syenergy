<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (22.07.2008)
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
							<!--<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td> -->
 
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
                        <td class="content_title">Messages : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr> 
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>   
                    <td width="100" class="searchhead_text"><b>Sender </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="200" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=subject')" /></td>
                    <td width="400" class="searchhead_text"><b>Synopsis</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=message')" /></td>
                    <td width="100" class="searchhead_text"><b>Dated</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=dated')" /></td>
                    <td width="100" class="searchhead_text" align="right"><b>Details</b></td>
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($msgRecordArray);
                if($recordCount >0 && is_array($msgRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($msgRecordArray[$i]['userName']).'</td>
                        <td class="padding_top" valign="top">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['subject'])),200,1).'</td>
                        <td class="padding_top" valign="top">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['message'])),700,1).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($msgRecordArray[$i]['dated'])).'</td>
                        <td width="100" class="padding_top" align="right" valign="top"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showMessageDetails('.$msgRecordArray[$i]['messageId'].');"/></td>
                        </tr>';
                    }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="6" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="6" align="center">No record found</td></tr>';
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

<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
  <tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="subject" style="overflow:auto; width:630px; height:20px"></div>
</td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Message: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="message" style="overflow:auto; width:630px; height:100px" ></div></td>
</tr> 
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Dated: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" height='20'><span id="dated" style="height:20px"></span></span></td>
</tr> 
<tr><td height="5px"></td></tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="downloadDiv" style="overflow:auto; width:630px;"></div>
</td>
</tr>
   <!--
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding">
         <textarea id="subject" name="subject" cols="20" rows="1" readonly="true"></textarea>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Message : </b></nobr></td>
        <td width="79%" class="padding">
         <div id="message" name="message" style="border:1px solid black;height:100px;width:200px;overflow:auto"></div>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Dated : </b></nobr></td>
        <td width="79%" class="padding">
         <input type="text" id="dated" name="dated" class="inputbox" style="border:0px" readonly="true" />
        </td>
    </tr>
 <tr><td height="5px"></td></tr>
 -->
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php
// $History: listAdminMessagesContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/30/08    Time: 12:26p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/29/08    Time: 6:22p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:55p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>