

<?php
// Purpose: to Show Contents Of List Fee Collection Report Contents
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<script type="text/javascript" language="javascript">
    var dtArray=new Array();  
    var dtInstitute='';

    function getSearchValue(str) {
        
          dtArray.splice(0,dtArray.length); //empty the array  
          
          if(dtInstitute=='') { 
            document.allDetailsForm.instituteId.length = null; 
            addOption(document.getElementById('instituteId'), '', 'All');
          }
          
          ttStr = str;  
          if(ttStr=='' || ttStr =='Degree') {
            document.allDetailsForm.degreeId.length = null; 
            addOption(document.getElementById('degreeId'), '', 'All');
            ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Branch') {
             document.allDetailsForm.branchId.length = null;  
             addOption(document.getElementById('branchId'), '', 'All');
             ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Batch') {
            document.allDetailsForm.batchId.length = null;  
            addOption(document.getElementById('batchId'), '', 'All');
            ttStr='';         
          }
          
          if(ttStr=='' || ttStr =='Class') {
            document.allDetailsForm.classId.length = null; 
            addOption(document.getElementById('classId'), '', 'All');
            ttStr='';         
          }
               
          var len= document.getElementById('hiddenClassId').options.length;
          var t=document.getElementById('hiddenClassId');
          if(len>0) {
              for(k=1;k<len;k++) { 
                 if(t.options[k].value != '') { 
                     retId = (t.options[k].value).split('!!~!!~!!');
                     retName = (t.options[k].text).split('!!~!!~!!');
                     // instituteCode, className, degreeCode, batchName, branchCode, isActive
                     ttStr = str;    
                     
                     // Fetch the Institute
                     if(dtInstitute=='') { 
                        if(checkDuplicate(retName[0]+"institute")!=0) {
                          addOption(document.getElementById('instituteId'), retId[0],  retName[0]);
                        }
                     }
                     
                     // Fetch the degree
                     if(ttStr=='' || ttStr =='Degree') {
                         temp1='';
                         temp2='';
                         if(document.getElementById('instituteId').value!='') {
                           temp1 = document.getElementById('instituteId').value+"~";
                           temp2 = retId[0]+"~";  
                         }
                         
                         ttName = retName[2]+' ('+retName[0]+')';  
                         ttId = retId[2]+'~'+retId[0]+'degree';   
                         if(temp1=='') {
                           if(checkDuplicate(ttId)!=0) {
                             addOption(document.getElementById('degreeId'), retId[2],  ttName);
                           }
                         }
                         else {
                           if(temp1==temp2) {
                             if(checkDuplicate(ttId)!=0) { 
                               addOption(document.getElementById('degreeId'), retId[2],  ttName);
                             }  
                           }
                         }
                         ttStr = '';
                     }       
                     
                     // Fetch the Branch
                     if(ttStr=='' || ttStr =='Branch') {
                         temp1='';
                         temp2='';
                         if(document.getElementById('instituteId').value!='') {
                           temp1 += document.getElementById('instituteId').value+"~";
                           temp2 += retId[0]+"~";  
                         }
                         
                         if(document.getElementById('degreeId').value!='') {
                           temp1 += document.getElementById('degreeId').value+"~";
                           temp2 += retId[2]+"~";  
                         }
                         
                         ttName = retName[4]+' ('+retName[0]+')';  
                         ttId = retId[4]+'~'+retId[0]+'branch';   
                         if(temp1=='') {
                           if(checkDuplicate(ttId)!=0) {
                             addOption(document.getElementById('branchId'), retId[4],  ttName);
                           }
                         }
                         else {
                           if(temp1==temp2) {
                             if(checkDuplicate(ttId)!=0) { 
                                addOption(document.getElementById('branchId'), retId[4],  ttName);
                             }  
                           }
                         }
                         ttStr = '';
                     }
                     
                      // Fetch the Batch
                     if(ttStr=='' || ttStr =='Batch') {
                        temp1='';
                        temp2='';

                        if(document.getElementById('instituteId').value!='') {
                          temp1 += document.getElementById('instituteId').value+"~";
                          temp2 += retId[0]+"~";  
                        }
                        
                        if(document.getElementById('degreeId').value!='') {
                          temp1 += document.getElementById('degreeId').value+"~";
                          temp2 += retId[2]+"~";  
                        }
                        if(document.getElementById('branchId').value!='') {
                          temp1 += document.getElementById('branchId').value+"~";
                          temp2 += retId[4]+"~";
                        }

                        ttName = retName[3]+' ('+retName[0]+')';  
                        ttId = retId[3]+'~'+retId[0]+'batch';   
                        if(temp1=='') {
                           if(checkDuplicate(ttId)!=0) { 
                             addOption(document.getElementById('batchId'), retId[3],  ttName);
                           }
                        }
                        else {
                           if(temp1==temp2) {
                              if(checkDuplicate(ttId)!=0) {   
                                addOption(document.getElementById('batchId'), retId[3],  ttName);
                              }
                           }  
                        }
                        ttStr = '';
                     }
                     
                     // Fetch the Class
                     if(ttStr=='' || ttStr =='Class') {
                          temp1='';
                          temp2='';
                          if(document.getElementById('instituteId').value!='') {
                            temp1 += document.getElementById('instituteId').value+"~";
                            temp2 += retId[0]+"~";  
                          }

                          if(document.getElementById('degreeId').value!='') {
                            temp1 += document.getElementById('degreeId').value+"~";
                            temp2 += retId[2]+"~";  
                          }
                          if(document.getElementById('branchId').value!='') {
                            temp1 += document.getElementById('branchId').value+"~";
                            temp2 += retId[4]+"~";
                          }
                          if(document.getElementById('batchId').value!='') {
                             temp1 += document.getElementById('batchId').value+"~";
                             temp2 += retId[3]+"~";
                          }
                          
                          ttName = retName[1]+' ('+retName[0]+') '+retName[5];  
                          ttId = retId[1]+'~'+retId[0]+'class';   
                          if(temp1=='') {
                             if(checkDuplicate(ttId)!=0) { 
                               addOption(document.getElementById('classId'), retId[1],  ttName);
                             }
                          }
                          else {
                             if(temp1==temp2) {
                               if(checkDuplicate(ttId)!=0) {  
                                 addOption(document.getElementById('classId'), retId[1],  ttName);
                               }
                             }  
                          }  
                          ttStr = '';
                     }
                  } // end If......
               } // end for loop
           } // end if condition
          
           dtInstitute='1';
    }

    function checkDuplicate(value) {
        
        var ii= dtArray.length;
        var fl=1;
        for(var kk=0;kk<ii;kk++){
          if(dtArray[kk]==value){
            fl=0;
            break;
          }  
        }
        if(fl==1){
          dtArray.push(value);
        } 
        
        
         return fl;
    }
    
    function getDateCheck() {
      document.getElementById("fromDate").value="";    
      document.getElementById("toDate").value="";
    }
</script>
<select name="hiddenClassId" id="hiddenClassId" style='display:none' >
    <option value="">All</option>
     <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getLoginInstitute();
     ?>
</select>
<?php
  $mystring="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $findme ="listPendingFeeReport";
  $chkPos = strpos($mystring, $findme);
  $showPendingReceipt = "";
  if($chkPos >0) {
    $showPendingReceipt = "style='display:none'";
  }
  
  
?>
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
            </td>
        </tr>
        <tr>
           <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                  <td valign="top" class="content" align="center">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                            <td class="contenttab_border2" align="center">
                              <div style="height:15px"></div>  
                             <table border="0" cellspacing="0" cellpadding="0px" width="10%" align="center">
                             	<tr><td colspan="10" ><table><tr>
									<td class="contenttab_internal_rows" nowrap width="2%" style="font-size: 12px"><nobr><B><font color='red'>
                                    	Report For</font></B></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" nowrap width="2%"><B>&nbsp;:&nbsp;</B></td>
                                    <td class="contenttab_internal_rows" style="font-size: 12px"><nobr>
								     <input type="radio" name="paidReport" id="paid1" value="0" checked onchange="getPaidAtDetails();">Paid Fee&nbsp;&nbsp;
								      <input type="radio" name="paidReport" id="paid2" value="1" onchange="getPaidAtDetails();">Unpaid Fee&nbsp;&nbsp;
								     <!-- <input type="radio" name="paidReport" value="2" id="paid3" onchange="getPaidAtDetails();">Pending Fee&nbsp;&nbsp;</B>
							      --> </nobr>
							    </td>
							    <td class="contenttab_internal_rows" nowrap width="2%" style="font-size: 12px"><nobr><B><font color='red'>
                                    	&nbsp;Fee Of</font></B></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows" nowrap width="2%"><B>&nbsp;:&nbsp;</B></td>
                                      <td class="contenttab_internal_rows" style="font-size: 12px"><nobr>
								     <input type="radio" name="feeReport" id="fee1" value="0" checked onchange="getFeeDetails();">All&nbsp;&nbsp;
								      <input type="radio" name="feeReport" id="fee2" value="1" onchange="getFeeDetails();">Academic Fee&nbsp;&nbsp;
								      <input type="radio" name="feeReport" value="2" id="fee3" onchange="getFeeDetails();">Transport Fee&nbsp;&nbsp;
								      <input type="radio" name="feeReport" value="3" id="fee4" onchange="getFeeDetails();">Hostel Fee&nbsp;&nbsp;</B>
							       </nobr>
							    </td>
                                     
							</td></tr></table></td></tr>
							<tr><td height="10px"></td></tr>
	<tr>
    <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>Institute</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%">  
        <select name="instituteId" id="instituteId" style="width:170px" class="selectfield" onchange="getSearchValue('Degree'); return false;" >
            <option value="">All</option>
        </select>
    </td>
    <td  class="contenttab_internal_rows" nowrap  width="2%" style="padding-left:10px;"><nobr><b>Degree</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%">  
        <select name="degreeId" id="degreeId" style="width:170px" class="selectfield" onchange="getSearchValue('Branch'); return false;" >
            <option value="">All</option>
        </select>
    </td>
    <td  class="contenttab_internal_rows" nowrap width="2%" style="padding-left:10px;"><nobr><b>Branch</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%">  
        <select name="branchId" id="branchId"  class="selectfield"  style="width:170px"  onchange="getSearchValue('Batch'); return false;">
            <option value="">All</option>
           
        </select>
    </td>
    <td  class="contenttab_internal_rows" nowrap width="2%" style="padding-left:10px;"><nobr><b>Batch</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td  class="contenttab_internal_rows" nowrap width="2%">  
        <select name="batchId" id="batchId"  class="selectfield" style="width:170px" onchange="getSearchValue('Class'); return false;">
            <option value="">All</option>
        </select>
    </td>
</tr>
<tr><td height='5px'></td></tr>
<tr>
    <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>Class</b></nobr></td>
    <td class="contenttab_internal_rows" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td class="contenttab_internal_rows" nowrap width="2%" colspan='12'>  
      <table border="0" cellspacing="0" cellpadding="0px" width="10%" align="left"> 
         <tr>
            <td class="contenttab_internal_rows" nowrap width="2%">  
                <select name="classId" id="classId" style="width:340px;" class="selectfield" >
                  <option value="">All</option>
                </select>
            </td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap ><nobr><b>&nbsp;&nbsp;Receipt No.</b></nobr></td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows"  nowrap ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap > 
              <input name="receiptNo" id="receiptNo" class="inputbox" style="width:110px" type="text">  
            </td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap style="padding-left:10px" align="left">
                <a href="" title="Click to Clear Date Check" alt="Click to Clear Date Check" onClick="getDateCheck(); return false;">
                <nobr><b><u>Receipt Date&nbsp;</u>:</b></nobr>
                </a>
            </td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap> 
                <nobr>&nbsp;&nbsp;From&nbsp;&nbsp;</nobr>
            </td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap><nobr> 
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('fromDate','');
                ?>
            </td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap><nobr>&nbsp;&nbsp;To&nbsp;&nbsp;</nobr></td>
            <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" nowrap><nobr> 
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('toDate','');
                ?>
            </td>
         </tr>
      </table>
   </td>
</tr>
<tr><td height="8"></td></tr>  
<tr>
   <td  class="contenttab_internal_rows" width="2%"  nowrap valign="middle" colspan='15'>  
     <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="left"> 
       <tr>
           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>Roll No./Reg No.</b></nobr></td>
           <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
           <td  class="contenttab_internal_rows" nowrap width="2%">  
             <input name="rollNo" id="rollNo" class="inputbox" style="width:220px" type="text">  
           </td>
           <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Student Name</b></nobr></td>
           <td class="contenttab_internal_rows" nowrap valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
           <td  class="contenttab_internal_rows" nowrap valgin="top"  width="2%"> 
             <input name="studentName" id="studentName" class="inputbox" style="width:210px" type="text">  
           </td>
           <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;"  width="2%" ><nobr><b>Father's Name</b></nobr></td>
           <td class="contenttab_internal_rows" nowrap valgin="top" width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
           <td  class="contenttab_internal_rows"  nowrap valgin="top" width="2%"> 
             <input name="fatherName" id="fatherName" class="inputbox" style="width:210px" type="text">  
           </td>
       </tr>
        <?php
          $mystring="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
          $findme ="listConsolidatedFeeDetailsReport.php";
          $chkPos = strpos($mystring, $findme);
          $showPendingReceipt = "style='display:none'";   
          if($chkPos >0) {
            $showPendingReceipt = "";
          }
        ?>
        <tr <?php echo $showPendingReceipt; ?> ><td height="5px"></td></tr>
        <tr <?php echo $showPendingReceipt; ?> >
          <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>Fee Of<?php echo REQUIRED_FIELD; ?></b></nobr></td>
          <td class="contenttab_internal_rows" valgin="top" nowrap width="2%" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td> 
          <td  class="contenttab_internal_rows" nowrap width="2%">  
             <select name="feeOf" id="feeOf" style="width:225px" class="selectfield">
                <option value="">Select</option>
                <option value="1">Academic Fee</option>
                <option value="2">Hostel Fee</option>
                <option value="3">Transport Fee</option>
            </select>
          </td>
        </tr>
     </table>
   </td>          
</tr>
<tr>
   <td  class="contenttab_internal_rows" width="2%"  nowrap valign="middle" colspan='15'>  
     <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="left"> 
      
      </table>    
</tr>
<tr><td height='10px'></td></tr>  
<tr>
    <td class="contenttab_internal_rows" nowrap valgin="top" colspan="10">
      <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="left">       
       <tr>
         <td class="contenttab_internal_rows" nowrap valgin="top">
            <b>Starting Record No.:</b>&nbsp;
         </td>
         <td class="contenttab_internal_rows" nowrap valgin="top"> 
           <input id="startingRecord" name="startingRecord" class="inputbox1" maxlength="5" value="1" style="width:50px" type="text">
         </td>
         <td class="contenttab_internal_rows" nowrap valgin="top" style="padding-left:10px;">
            <b>Show No. of Records in Print & CSV Report:</b>&nbsp;
         </td>
         <td class="contenttab_internal_rows" nowrap valgin="top"> 
           <input id="totalRecords" name="totalRecords" class="inputbox1" maxlength="5" value="500" style="width:50px" type="text">
         </td>
       </tr>
      </table>
     </td>    
</tr>
<tr><td height='10px'></td></tr>  
<tr>
   <td class="contenttab_internal_rows" nowrap colspan="15" >  
     <center>
       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm('allDetailsForm');  return false;" />  
     </center> 
   <td>
</tr>
<tr><td height='10px'></td></tr>
</table>  
                           </td>
                         </tr> 
                         <tr>
                            <td colspan='1' class='contenttab_row' id='nameRow' style='display:none;'>
                                   <div >
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" class="contenttab_border" >
                                      <tr>
                                        <td class="content_title" align="left">Pending Fee Report :</td>
                                      </tr>
                                    </table>
                                </div>                  
                                <div id="resultsDiv"></div>
                             </td>
                          </tr>
                          <tr id='cancelDiv1' style='display:none;'>
                            <td height='15px' align='right' style='padding-top:15px;'>
                                <!--<input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  onclick="javascript:resetResult('all');return false;" />-->
                                <input name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onclick="printReport();return false;" type="image">&nbsp;
                                <input name="imageField" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onclick="printReportCSV();return false;" type="image">
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
</td>
</tr>
</table>    



<script type="text/javascript" language="javascript">
    getSearchValue('');
</script>
