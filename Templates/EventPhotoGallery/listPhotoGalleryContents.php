<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<form name="listForm" id="listForm" method="post" onSubmit="return false;" >
<?php
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<tr>
    <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="30">
                        <td class="contenttab_border" height="20" style="border-right:0px;"></td>
                        <td width="24%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title = "Add Item">
                            
                            <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="blankValues(); displayWindow('AddPhoto',450,250); return false;" />&nbsp;

                        </td>
                           
                    </tr>
                    <tr>
                         <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                    </tr>
                    <tr>
                         <td align="right" colspan="2">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                            <tr>
                                <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image" name="downloadImg" src="<?php echo IMG_HTTP_PATH;?>/download2.gif" onClick="printReport()" /       >&nbsp;&nbsp;&nbsp;&nbsp;
                               <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title="Print">&nbsp;&nbsp;&nbsp;
                               <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" title="Export To Excel"> 
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
</form>
<!--Add Photos-->
<?php  floatingDiv_Start('AddPhoto','Add Event Photographs'); ?>
<form name="addPhoto" id="addPhoto" method="post" target="uploadTargetAdd" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
   <input type='hidden'  name='mainPhotoGalleryId' id='mainPhotoGalleryId' value=''>
     <table width="10%" border="0" cellspacing="0" cellpadding="0">
	 <tr>
		<td class="contenttab_internal_rows" nowrap width="2%"><nobr><b>Event Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>:</b></nobr></td>
		<td class="padding" width="2%" nowrap colspan='10' >
		    <input type="text" style="width:400px" name="eventName" id="eventName" class="textarea" value="" />
	    </td>
	 </tr>
     <tr>
        <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>Event Description</b></nobr></td>
        <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>:</b></nobr></td>
        <td class="padding" width="2%" nowrap colspan='10' >
       <input type="text" style="width:400px" name="eventDescription" id="eventDescription" class="textarea" value=""/>
       </td>
     </tr>  
     <tr>
       <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>Visible From</b></nobr></td>
        <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>:</b></nobr></td>
        <td class="padding" nowrap width="2%" ><nobr>
          <table width="10%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="contenttab_internal_rows" nowrap width="2%"><nobr>
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('visibleFrom',date('Y')."-".date('m')."-".date('d'));
                    ?></nobr>
                </td>
                <td class="contenttab_internal_rows" nowrap width="2%" style="padding-left:15px;"><nobr><b>Visible To</b></nobr></td>
                <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>:</b></nobr></td>
                <td colspan="1" class="padding" nowrap width="2%" ><nobr>
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('visibleTo',date('Y')."-".date('m')."-".date('d'));
                    ?></nobr>
                </td>
              </tr>
          </table>
         </td>       
      </tr> 
      <tr>
              <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><strong>Role Visible To<?php echo REQUIRED_FIELD ?></strong></nobr></td>
              <td class="contenttab_internal_rows" nowrap width="2%" ><b>:</b></td>
              <td class="padding" valign="top" colspan="10">
                <select size="4" multiple="multiple"  name="roleId[]" id="roleId" style="width:225px">
                 <?php
							  require_once(BL_PATH.'/HtmlFunctions.inc.php');
							  echo HtmlFunctions::getInstance()->getRoleData();
							?>             
                </select>
               <div>
               Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("roleId","All","addPhoto");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("roleId","None","addPhoto");'>None</a></div></nobr>
             </td>
          </tr>
          <tr>
              <td class="contenttab_internal_rows" nowrap width="2%" ><nobr>
              <td colspan="12" class="padding" nowrap width="2%" ><nobr>
              <div id="note"></div></td></td>
           </tr>         
        </tr>     
    </table>	

   <table width="10%" border="0" cellspacing="0" cellpadding="0">
    <tr>
       <td width="100%" style="width:550px;" >
          <div id="tableDiv" style="height:200px;width:700px;overflow:auto;">
	        <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
		      <tbody id="anyidBody">
		           <tr class="rowheading">
		             <td width="5%" class="contenttab_internal_rows"><b>#</b></td>
		             <td width="30%" class="contenttab_internal_rows" align='left'><b>Upload Photos</b>&nbsp;(<?php echo "Max Size ".ceil(MAXIMUM_FILE_SIZE/1024)." kb ";?>)<br><span><span style="font-weight:bold;font-size:10px;color:red;">('gif' , 'jpg' , 'jpeg' , 'png' , 'bmp')</span>
		             <td width="55%" class="contenttab_internal_rows" align='left'><b>Comments</b></td>
		             <td width="10%" class="contenttab_internal_rows" align='center'><b>Delete</b></td>
		           </tr>
		        </tbody>
             </table>
         </div>    
       </td>
    </tr>
   <tr>
       <td colspan="2">
          <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
          <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
        </td>
    </tr> 
    <tr>
      <td height="5px" colspan="2"></td></tr>
    <tr>
       <td align="center" style="padding-left:40px" colspan="4">
    
          <input type="image" name="uploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="uploadImages(); return false;" />&nbsp;
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddPhoto');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
      </td>
   </tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" style="width:0px;height:0px;border:0px solid #fff;"></iframe>   
</form>
<?php floatingDiv_End(); ?>
<!--Help  Details  End -->

<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Photo Gallery','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll12" style="overflow:auto; width:700px; height:500px; vertical-align:top;">
                <div id="photoResultsDiv" style="width:98%; vertical-align:top;"></div>

            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>

