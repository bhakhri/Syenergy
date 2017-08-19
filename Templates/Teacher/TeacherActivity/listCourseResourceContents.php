<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Cource Resource 
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
							
							
                      
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
		 <?php
                          $permissionsArray=$sessionHandler->getSessionVariable('CourseResourceMaster');
                          $addPermission=0;
                          if($permissionsArray['add']==1){
                              $addPermission=1;
                          }
                        if($addPermission==1){  
                        ?>

          <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><a style="cursor:pointer" onClick="displayWindow('AddResourceDiv','',300,250);blankValues();return false;">
                      <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                         onClick="displayWindow('AddResourceDiv','',300,250);blankValues();return false;" />&nbsp;</td>
		 <?php
                        }  
                       ?>  
    </tr>
   
             <tr>
                <td class="contenttab_row" valign="top" colspan=2>
                <div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="2%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="10%" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="15%" class="searchhead_text"><b>Description</b></td>
                    <td width="10%" class="searchhead_text"><b>Type</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=resourceName')" /></td>
                    <td width="8%" class="searchhead_text"><b>Date</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=postedDate')" /></td>
                    <td width="8%" class="searchhead_text"><b>Link</b></td>
                    <td width="5%" class="searchhead_text"><b>Attachment</b></td>
                    <td width="10%" class="searchhead_text" align="right"><b>Action</b></td>
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($resourceRecordArray);
                if($recordCount >0 && is_array($resourceRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                    
                    //for file downloading
                    $fileStr='<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />';    
                    //for url clicking
                    $urlStr='<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>';
                    
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['subject']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output($resourceRecordArray[$i]['description'],100)).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['resourceName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['postedDate']).'</td>
                        <td class="padding_top" valign="top">'.(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : $urlStr).'</td>
                        <td class="padding_top" valign="top" align="center">'.(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :$fileStr).'</td>
                        <td width="10%" class="padding_top" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$resourceRecordArray[$i]['courseResourceId'].',\'EditResourceDiv\',315,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteResource('.$resourceRecordArray[$i]['courseResourceId'].');"/></td>
                        </tr>';
                    }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="8" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
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
    
<?php
    $str=''; 
    for($i=0;$i<count($allowedExtensionsArray);$i++){
        if($str!=''){
            $str .=',';
        }
        if($i%8==0){
            $str .='<br/>';
        }
        $str .=$allowedExtensionsArray[$i];
    }
?>    
    
<!--Start Add Div-->     
<?php floatingDiv_Start('AddResourceDiv','Add Resource'); ?>
    <form name="AddResource" id="AddResource" action="<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/resourceFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="subject" id="subject" tabindex="1" onChange ="getGroups('AddResource');">
         <option value="">Select Course</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
          ?>
        </select>
        </td>
    </tr>
	 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Group <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select multiple name='group[]' id='group' size='5' class='htmlElement2' style='width:185px'>
          <?php
          // require_once(BL_PATH.'/HtmlFunctions.inc.php');
          // echo HtmlFunctions::getInstance()->getTeacherSubjectData();
          ?>
        </select>
		<div align="left">
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('group[]','All','AddResource');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('group[]','None','AddResource');">None</a>
          </div></nobr>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="category" id="category" tabindex="2">
         <option value="">Select Category</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getResourceCategoryData();
          ?>
        </select>
        </td>
    </tr>
	
    <tr>    
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Description <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding">
         <textarea id="description" name="description" rows="5" cols="20" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="3">
         </textarea>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>URL</b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <input type="text" id="resourceUrl" name="resourceUrl" class="inputbox" maxlength="100" tabindex="4"/>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Upload File</b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
          <input type="file" id="resourceFile" name="resourceFile" class="inputbox" tabindex="5">
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td class="padding">&nbsp;</td>
        <td width="79%" class="padding">
          Allowed File Types  : <?php echo $str; ?>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td class="padding">&nbsp;</td>
        <td width="79%" class="padding">
          Maximum File Size : <?php echo round(MAXIMUM_FILE_SIZE/(1024*1024),2); ?> MB
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" tabindex="6" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddResourceDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="7"/>
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<!--Start Edit Div-->     
<?php floatingDiv_Start('EditResourceDiv','Edit Resource'); ?>
   <form name="EditResource" id="EditResource" action="<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/resourceFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="courseResourceId" id="courseResourceId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="subject" id="subject" tabindex="1" onChange="getGroups('EditResource');">
         <option value="">Select Course</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
          ?>

        </select>
        </td>
    </tr>
	 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Group <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <select multiple name='group[]' id='group' size='5' class='htmlElement2' style='width:190px'>
        
        </select>
		<div align="left">
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('group[]','All','EditResource');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('group[]','None','EditResource');">None</a>
          </div></nobr>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="category" id="category" tabindex="2">
         <option value="">Select Category</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getResourceCategoryData();
          ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Description <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <textarea id="description" name="description" rows="5" cols="20" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="3">
         </textarea>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>URL</b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <input type="text" id="resourceUrl" name="resourceUrl" class="inputbox" maxlength="100" tabindex="4" />
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Upload File</b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
          <input type="file" id="resourceFile" name="resourceFile" class="inputbox" tabindex="5"><label id="uploadIconLabel" style="padding-left:20px;"></label>
          <label id="uploadIconLabel2" style="padding-left:5px;"></label>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td class="padding">&nbsp;</td>
        <td width="79%" class="padding">
          Allowed File Types  : <?php echo $str; ?>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td class="padding">&nbsp;</td>
        <td width="79%" class="padding">
          Maximum File Size : <?php echo round(MAXIMUM_FILE_SIZE/(1024*1024),2); ?> MB
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');"  tabindex="6" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditResourceDiv');return false;" tabindex="7" />
   </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<?php
// $History: listCourseResourceContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/06/09   Time: 14:18
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modifed look and feel as mailed by kabir sir.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:51
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Fixes Bugs
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:21a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Upload Resource" Module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 4:20p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected "Course" dropdown
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 3:06p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected BreadCrumb String
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:48p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created CourseResource Module
?>