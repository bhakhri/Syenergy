<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
// Author :NISHU BINDAL
// Created on : (20.2.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Bus Fee Master: </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" style="padding:5px 5px 0px 5px">
             <form action="" method=""  name="searchForm" id="searchForm" onsubmit="return false;">
             <fieldset>
    			<legend class="contenttab_internal_rows"><strong>Search Filters&nbsp;:</strong></legend>
                 <table width="10%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td class="contenttab_internal_rows" align="left" style="padding-left:5px" valign='top'>
                            <nobr><b>Route Name</b><?php echo REQUIRED_FIELD;?></nobr>
                        </td>
                        <td valign='top' class="contenttab_internal_rows"><b>:</b></td>
			            <td class="padding" align="left" valign='top' class="contenttab_internal_rows">
                        <select  name="routeId[]" id="routeId" onchange="resetForm('restAll');getBusStopCity();" style="width:320px" multiple size=4 class="selectfield" >
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->fetchBusRoutes();
                         ?>
                        </select>
                        <br/><span class="contenttab_internal_rows">Select &nbsp;<a class='allReportLink'  href='javascript:void(0)' onclick="makeSelection('routeId[]','All','searchForm');resetForm('restAll');getBusStopCity();">All</a> / <a class='allReportLink' href='javascript:void(0)'; onclick="makeSelection('routeId[]','None','searchForm');resetForm('restAll');" >None</a></span></td>
                        <td class="contenttab_internal_rows" align="left" style="padding-left:5px" valign='top'>
                            <nobr><b>Bus Stop City<?php echo REQUIRED_FIELD;?></b></nobr>
                        </td>
                       <td valign='top' class="contenttab_internal_rows"><b>: </b></td>
			<td class="padding" valign='top'>
                        <select size="1"  name="busStopCity"  style="width:320px" id="busStopHeadId" class="selectfield" onchange="resetForm('');">      
                         <option value="">Select</option>
                        </select></td>
                        <tr><td height="10px"></td></tr>
                        <tr>
                          <td class="contenttab_internal_rows" nowrap>  
                            <b>Include Classes</b> 
                          </td>                        
                          <td  class="contenttab_internal_rows" nowrap><b>:</b></td>        
                          <td class="contenttab_internal_rows" valign='top' colspan="10">
                            <table width="10%" border="0" cellspacing="0" cellpadding="0" > 
                              <tr>
                                  <td class="contenttab_internal_rows" nowrap> 
                                    <input name="classStatus" id="classStatus1" value="1" onClick="resetForm('restAll');getAllDegree('all');" checked="checked" type="radio">Active&nbsp;
                                    <input name="classStatus" id="classStatus2" value="2" onClick="resetForm('restAll');getAllDegree('all');" type="radio">Futrue
                                  </td>
                                  <td  class="contenttab_internal_rows" width="2%" style="padding-left:15px;"  nowrap>
                                        <nobr><b>Degree</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" width="2%" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="2%" nowrap colspan="10">  
                                    <select name="degreeId" id="degreeId" onChange="getAllDegree('Degree'); return false;" style="width:330px" class="selectfield" onchange="getBranches()" >
                                      <option value="">Select</option>
                                      
                                     </select>
                                   </td>
                                   <td class="contenttab_internal_rows" nowrap style="padding-left:10px;">  
                                      <b>Branch</b> 
                                  </td>     
                                  <td  class="contenttab_internal_rows" width="2%" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="2%" >  
                                    <select name="branchId" id="branchId"  class="selectfield"  style="width:230px" onChange="getAllDegree('Branch'); return false;">
                                       <option value="">Select</option>
                                     </select>
                                   </td>
                                 </tr>
                              </table>
                            </td>
                          </tr> 
                          <tr><td height="10px"></td></tr>
                          <tr>
                              <td class="contenttab_internal_rows" nowrap style="padding-left:5px;">  
                                  <b>Batch</b> 
                              </td>     
                              <td  class="contenttab_internal_rows" width="2%" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
			                  <td class="contenttab_internal_rows" colspan="10">
                                <table width="10%" border="0" cellspacing="0" cellpadding="0" > 
                                  <tr>
                                    <td class="contenttab_internal_rows" nowrap align="left">
                                       <select size="1"  name="batchId" id="batchId" style="width:260px" class="selectfield" onChange="getAllDegree('Batch'); return false;">
                                          <option value="">Select</option>
                                       </select>
                                    </td>
                   	                <td class="contenttab_internal_rows" align="left"  style="padding-left:5px;">
                                        <nobr><b>Class</b></nobr>
                                    </td>
                                    <td  class="contenttab_internal_rows" width="2%" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
			                        <td class="contenttab_internal_rows" valign='top' colspan="14" >
                                       <table width="10%" border="0" cellspacing="0" cellpadding="0" > 
                                         <tr>
                                           <td class="contenttab_internal_rows" align="left" nowrap> 
                                              <select  name="classId" id="classId"  class="selectfield" onchange="resetForm('');" style="width:370px">
                                                <option value="">Select</option>
                                              </select>
                                           </td>
                   	                       <td class="contenttab_internal_rows" align="left" nowrap style="padding-left:25px;">  
                                             <input type="image" id="imageField1" name="imageField1" onClick="validateData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                                           </td>
                                         </tr>
                                       </table>
  					</td>       
                                </tr>
                             </table>
                     </td>
                   </tr>
                    </table>
                    </fieldset>
                    <div  id='feeTextBoxDiv' style='display:none'>
                          <br/>
                     <fieldset>
    			<legend class="contenttab_internal_rows"><strong>Bus Fee&nbsp;:</strong></legend>
                    <table cellspacing=0 cellpadding=0 border=0 width='100%'>
                    	<tr > 	
                    	<td class="contenttab_internal_rows" align="left" style="padding-left:5px;" width="10%"><nobr><b>Bus Fee</b></nobr></td>
			<td  width=1% align='center'><b>: </b></td>
			<td class="padding" align="left" width="23%">
			<input type='text' name='fillBusFee' onchange="fillTextBox(this.value)" style="width:180px;text-align:right;" id='fillBusFee'>
			</td>
			<td  class="contenttab_internal_rows" style="padding-left:10px;" width="10%">
			<nobr><b>Over Write Values</b></td>
			<td width='1%' align='center'><b>:</b></td>
			<td  class="contenttab_internal_rows" ><input type='radio' name='opertionMode' id='overideValues'>&nbsp;<b>Yes</b>&nbsp;&nbsp;&nbsp;<input type='radio' name='opertionMode' id='notOverideValues' checked='true'>&nbsp;<b>No</b></nobr>	
			</td>
                    
    			</tr>
    			</table>
    			</filedset>
    			</div>
                    </form>
                </td>
             </tr>
              <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do Not Delete-->
                 <input type="hidden" name="mem">
                 <input type="hidden" name="mem">
                <!--Do Not Delete-->
                 <div id="results">
                </div>
                </form>
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr>
                <td align="right">
                  <input type="image" id="imageField2"  name="imageField2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
                  <input  type="image" id="imageField3"  name="imageField3" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="resetForm('all'); return false;" />
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table>
              </div>
             </td>
          </tr>

          </table>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    </table>

