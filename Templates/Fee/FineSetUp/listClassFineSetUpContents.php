<?php
//-------------------------------------------------------
// Purpose: to design the layout for Fine Modue.
// Author : Harpreet Kaur
//--------------------------------------------------------
?>
<select name="hiddenClassId" id="hiddenClassId" style='display:none' >
    <option value="">All</option>
     <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getLoginInstitute();
     ?>
</select>

<?php
  $mystring="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $findme ="fineStudentReport.php";
  $chkPos = strpos($mystring, $findme);
  $showPendingReceipt = "style='display:none'";   
  if($chkPos >0) {
    $showPendingReceipt = "";
  }
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		  <tr><td height="15px"></td></tr>
              		  <tr> 
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
			
		        <tr>
			        <td valign="top"  nowrap="nowrap" class="contenttab_border2" >   
                       <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                         <tr>
                           <td class="contenttab_internal_rows" style="text-align:right">
		                       <nobr>
		                         <b><a href="javascript:void(0);" style="cursor:pointer;" onclick="getShowDetail();" class="link">
		                              <label id="lblMsg" style="color:red;font-size:14px;">Please Click to Add Fine</label>
		                            </a>
		                         </b>
		                       </nobr>
                            </td>
                          </tr>
                        </table>     
		             </td>
                </tr>
                <tr><td style="height:10px"></td></tr>
                <tr>                                                     
                                <td valign="top" class="contenttab_row1">
                                    <form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;"> 
                                       <table width="98%"  border="0" cellspacing="0" cellpadding="0px" align="center">
                                          <tr>
                                            <td class="contenttab_internal_rows" nowrap width="2%"><nobr><b>Include Classes</b></nobr></td>
                                            <td class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" nowrap width="2%"> 
                                                <input type='radio' name='activeRadio' id='classActive' value='1' onclick="getSearchValue('');" checked='true'>Active &nbsp;&nbsp; 
                                                <input type='radio' name='activeRadio' id='classFuture' value='2' onclick="getSearchValue('');"  > &nbsp; &nbsp;Fututre&nbsp;&nbsp;
                                                <input type='radio' name='activeRadio' id='classPast'   value='3' onclick="getSearchValue('');" > &nbsp; &nbsp;Past&nbsp; 
                                            </td> 
                                            <td class="contenttab_internal_rows" nowrap width="2%" style="padding-left:10px;"><nobr><b>Institute</b></nobr></td>
                                            <td class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" nowrap width="2%">  
                                                <select name="instituteId" id="instituteId" style="width:230px" class="selectfield" onchange="getSearchValue('Degree'); return false;" >
                                                  <option value="">All</option>
                                                </select>
                                            </td>
                                            <td  class="contenttab_internal_rows" nowrap  width="2%"><nobr><b>Degree</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%">  
                                                <select name="degreeId" id="degreeId" style="width:210px" class="selectfield" onchange="getSearchValue('Branch'); return false;" >
                                                    <option value="">All</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td height='5px'></td></tr>
                                        <tr>
                                            <td  class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>Branch</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%">  
                                                <select name="branchId" id="branchId"  class="selectfield"  style="width:230px"  onchange="getSearchValue('Batch'); return false;">
                                                    <option value="">All</option>
                                                   
                                                </select>
                                            </td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%" style="padding-left:10px;"><nobr><b>Batch</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%">  
                                                <select name="batchId" id="batchId"  class="selectfield" style="width:230px" onchange="getSearchValue('Class'); return false;" >
                                                    <option value="">All</option>
                                                </select>
                                            </td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>Fine Type<?php echo REQUIRED_FIELD;?></b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td  class="contenttab_internal_rows" nowrap width="2%">  
                                              <select name="fineTypeId" id="fineTypeId" style="width:210px" class="selectfield" >
                                             <option value="">Select</option>
                                             <option value="1">Academic</option>
                                             <option value="2">Transport</option>
                                             <option value="3">Hostel</option>

                                             </select>
                                            </td>
                                        </tr>
                                        <tr><td height='6px'></td></tr>
                                        <tr>
                                            <td class="contenttab_internal_rows" nowrap width="2%" colspan="15">
                                               <table width="100%" border="0px" cellspacing="0px" cellpadding="0px" class="contenttab_border2">
                                                    <tr>
                                                      <td class="contenttab_internal_rows" nowrap width="2%"  valign="top">
                                                        <nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr>
                                                      </td>
                                                      <td valign="top"class="contenttab_internal_rows" rowspan="2" nowrap style="padding-left:10px;width:2%;">&nbsp;</td>
                                   
                                                      <td class="contenttab_internal_rows" rowspan="2" nowrap width="96%" id="addFine" style="overflow:auto;display:none;vertical-align:top;" >
                                                             <div id="scroll2" style="overflow:auto; height:120px;vertical-align:top;width:100%">
                                                                 <div id='anyidT'>
                                                                     <table width="100%" border="0" cellspacing="1" cellpadding="0" id="anyid">
                                                                        <tbody id="anyidBody">
                                                                               <tr class="rowheading">
                                                                                <td width="3%" class="searchhead_text" style="padding: 0px 0px 0px 0px;"><b>Sr.</b></td>                     
                                                                                <td class="searchhead_text" align="center" width='15%'><b>From Date</b></td>
                                                                                <td class="searchhead_text"  align="center" width='15%'><b>To Date</b></td>    
                                                                                <td class="searchhead_text" width='15%' style="padding: 0px 0px 0px 10px;" align='left'><b>Charges Format</b></td>
                                                                                <td class="searchhead_text" width='20%' align='left' style="padding: 0px 0px 0px 10px;"><b>Amount</b></td>
                                                                                <td class="searchhead_text" width='8%' align='center'><b>Delete</b></td>
                                                                            </tr>
                                                                        </tbody>
                                                                     </table>
                                                                  </div>
                                                              </div>   
                                                              <span id='addMore'>
                                                                 <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
                                                                <div id='addRowDiv'></div>
                                                                 <h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
                                                               </span> 
						                                 </td>
                                                         <td id="showFine" style="text-align:left;" rowspan="2" valign="bottom">
		                                                   <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onclick="return showPreviousFineDetail();return false;" />
					                                     </td> 
                                                    </tr>    
                                                    <tr>
                                                      <td  valign="top" class="contenttab_internal_rows" nowrap width="2%">
                                                        <select name="fineClassId[]" id="fineClassId" multiple size="6" style="width:330px;" class="selectfield" >
                                                           <option value="">Select</option>
                                                        </select>
                                                        <br>
                                                        <div align="left">
                                                        Select &nbsp;
                                                        <a class="allReportLink" href="javascript:makeSelection('fineClassId[]','All','allDetailsForm');">All</a> / 
                                                        <a class="allReportLink" href="javascript:makeSelection('fineClassId[]','None','allDetailsForm');">None</a>
                                                        </div></nobr>
                                                      </td>
                                                    </tr> 
                                              </table>
                                            </td>
                                        </tr>
                                        <tr>
				
                                           <td style="text-align: right;padding-left:20px;" valign="top" class="contenttab_internal_rows" nowrap  colspan="20"> <div id="saveFineDetail" style="display:none;">
                                             <input type="image" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return addForm(this.form);return false;" /></div>
                                           </td>
                                        </tr>
                                        </table>
                                         
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style1='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Class Fine Setup Detail :</td>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style1='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                   <div style="overflow:auto; height:320px; vertical-align:top;width:1000px"> 
                                      <div id = 'results'></div>
                                   </div>   
                                </td>
                            </tr>
                            <tr id='nameRow2' style1='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
