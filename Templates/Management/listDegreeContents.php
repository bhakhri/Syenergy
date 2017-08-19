<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR DEGREE LISTING 
//
// Author :Rajeev Aggarwal 
// Created on : (12.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    <td align="center" style="padding-right:10px" colspan="2"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" /><input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditDegree');return false;" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
<?php floatingDiv_End(); 

// $History: listDegreeContents.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>