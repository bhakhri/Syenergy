<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DEGREE LISTING 
//
//
// Author :Rajeev Aggarwal 
// Created on : (15.10.2008 )
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Degree Details</td>
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
                        <td class="content_title">Degree Detail : </td>
                         
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                 <tr class="rowheading">
                    <td width="3%"  class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="72%" class="searchhead_text"><b>Degree Name </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="15%" class="searchhead_text"><b>Degree Code</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=degreeCode')" /></td>
                    <td width="10%" class="searchhead_text"><b>Abbr</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=degreeAbbr')" /></td>
                    
                 </tr>
                <?php
                $recordCount = count($degreeRecordArray);
                if($recordCount >0 && is_array($degreeRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($degreeRecordArray[$i]['degreeName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($degreeRecordArray[$i]['degreeCode']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($degreeRecordArray[$i]['degreeAbbr']).'</td>
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
    </td>
    </tr>
    </table>    

<!--Start Add Div-->
<?php floatingDiv_Start('AddDegree','Add Degree'); ?>
<form name="AddDegree" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Degree Name:</strong></td>
     <td width="69%" class="padding">
      <input type="text" id="degreeName" name="degreeName"  style="width:150px" class="inputbox" maxlength="30"/>
     </td>
   </tr>
   <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Degree Code:</strong></td>
     <td width="69%" class="padding">
      <input type="text" id="degreeCode" name="degreeCode"  style="width:150px" class="inputbox" maxlength="10"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Degree Abbr :</strong></td>
    <td width="69%" class="padding">
     <input type="text" id="degreeAbbr" name="degreeAbbr" style="width:150px" class="inputbox" maxlength="10"/>
    </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDegree');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditDegree','Edit Degree '); ?>
<form name="EditDegree" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="degreeId" id="degreeId" value="" />  
    <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Degree Name:</strong></td>
     <td width="69%" class="padding">
      <input type="text" id="degreeName" name="degreeName"  style="width:150px" class="inputbox" maxlength="30"/>
     </td>
   </tr>
   <tr>
     <td width="31%" class="contenttab_internal_rows"><strong>Degree Code:</strong></td>
     <td width="69%" class="padding">
      <input type="text" id="degreeCode" name="degreeCode"  style="width:150px" class="inputbox" maxlength="10"/>
     </td>
   </tr>
   <tr>
    <td class="contenttab_internal_rows"><strong>Degree Abbr :</strong></td>
    <td width="69%" class="padding">
     <input type="text" id="degreeAbbr" name="degreeAbbr" style="width:150px" class="inputbox" maxlength="10"/>
    </td>
   </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDegree');return false;" />
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
// $History: scListDegreeContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:28p
//Created in $/Leap/Source/Templates/Management
//intial checkin
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Degree
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Degree
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:47p
//Updated in $/Leap/Source/Templates/Degree
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Degree
//corrected breadcrumb and reset button height and width
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:42p
//Updated in $/Leap/Source/Templates/Degree
//Solved TabOrder Problem
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Templates/Degree
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:59p
//Updated in $/Leap/Source/Templates/Degree
//Added AjaxList Functinality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:23p
//Updated in $/Leap/Source/Templates/Degree
//Adding AjaxEnabled Delete functionality
//
//***********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/18/08    Time: 2:38p
//Updated in $/Leap/Source/Templates/Degree
//Removing errors done
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Templates/Degree
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Degree
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:07a
//Created in $/Leap/Source/Templates/Degree
//Initial Checkin
?>