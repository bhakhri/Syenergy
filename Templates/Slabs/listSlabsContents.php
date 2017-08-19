<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DEGREE LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Exam Masters&nbsp;&raquo;&nbsp;Slabs Master</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
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
                        <td class="content_title">Slabs Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddSlabs',350,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
                    <td width="5%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="28%" class="searchhead_text"><b>From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="28%" class="searchhead_text"><b>To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=deliveredTo')" /></td>
                    <td width="28%" class="searchhead_text"><b>Marks</b></td>
                    <td width="10%" class="searchhead_text" align="right"><b>Action</b></td>
                 </tr>
                <?php
                $recordCount = count($slabsRecordArray);
                if($recordCount >0 && is_array($slabsRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($slabsRecordArray[$i]['deliveredFrom']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($slabsRecordArray[$i]['deliveredTo']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($slabsRecordArray[$i]['marks']).'</td>
                        <td width="100" class="padding_top" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$slabsRecordArray[$i]['slabId'].',\'EditSlabs\',350,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteSlabs('.$slabsRecordArray[$i]['slabId'].');"/></td>
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
          <tr><td height="10px"></td></tr>
          <tr>
           <td align="right">
             <a href="javascript:void(0);" onClick="printReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a>
          </td></tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>    

<!--Start Add Div-->
<?php floatingDiv_Start('AddSlabs','Add Slabs'); ?>
<form name="AddSlabs" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td width="31%" class="contenttab_internal_rows"><nobr><strong>Attendance From:</strong></nobr></td>
     <td width="69%" class="padding">
      <input type="text" id="deliveredFrom" name="deliveredFrom"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
      <td width="31%" class="contenttab_internal_rows"><nobr><strong>Attendance To:</strong></nobr></td>
      <td width="69%" class="padding">
      <input type="text" id="deliveredTo" name="deliveredTo"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Marks Alotted:</strong></td>
    <td width="69%" class="padding" colspan="3" align="left">
     <input type="text" id="marks" name="marks" style="width:50px" class="inputbox" maxlength="5"/>
    </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddSlabs');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditSlabs','Edit Slabs '); ?>
<form name="EditSlabs" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="slabId" id="slabId" value="" />  
    <tr>
     <td width="31%" class="contenttab_internal_rows"><nobr><strong>Attendance From:</strong></nobr></td>
     <td width="69%" class="padding">
      <input type="text" id="deliveredFrom" name="deliveredFrom"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
      <td width="31%" class="contenttab_internal_rows"><nobr><strong>Attendance To:</strong></nobr></td>
      <td width="69%" class="padding">
      <input type="text" id="deliveredTo" name="deliveredTo"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Marks Alotted:</strong></td>
    <td width="69%" class="padding" colspan="3" align="left">
     <input type="text" id="marks" name="marks" style="width:50px" class="inputbox" maxlength="5"/>
    </td>
   </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditSlabs');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>   
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listSlabsContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Slabs
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/24/08   Time: 12:15p
//Updated in $/Leap/Source/Templates/Slabs
//Added functionality for slabs report print
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Slabs
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Slabs
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/Slabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Slabs
//corrected breadcrumb and reset button height and width
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:45a
//Created in $/Leap/Source/Templates/Slabs
?>