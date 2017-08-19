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
                 if(t.options[k].value != '' ) { 
                     retId = (t.options[k].value).split('!!~!!~!!');
                     retName = (t.options[k].text).split('!!~!!~!!');
                     // instituteCode, className, degreeCode, batchName, branchCode, isActive
                     ttStr = str;    
                     
                     if(retId[5]==1) {
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
                     }
                  } // end If
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
  $findme ="fineStudentReport.php";
  $chkPos = strpos($mystring, $findme);
  $showPendingReceipt = "style='display:none'";   
  if($chkPos >0) {
    $showPendingReceipt = "";
  }
?>

<table border="0" cellspacing="0" cellpadding="0px" width="10%" align="center">
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
            <td class="contenttab_internal_rows" nowrap ><nobr><b>&nbsp;&nbsp;Receipt No.</b></nobr></td>
            <td class="contenttab_internal_rows"  nowrap ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
            <td  class="contenttab_internal_rows" nowrap > 
              <input name="receiptNo" id="receiptNo" class="inputbox" style="width:110px" type="text">  
            </td>
            <td class="contenttab_internal_rows" nowrap style="padding-left:10px" align="left">
                <a href="" title="Click to Clear Date Check" alt="Click to Clear Date Check" onClick="getDateCheck(); return false;">
                <nobr><b><u>Receipt Date&nbsp;</u>:</b></nobr>
                </a>
            </td>
            <td class="contenttab_internal_rows" nowrap> 
                <nobr>&nbsp;&nbsp;From&nbsp;&nbsp;</nobr>
            </td>
            <td class="contenttab_internal_rows" nowrap><nobr> 
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->datePicker('fromDate','');
                ?>
            </td>
            <td class="contenttab_internal_rows" nowrap><nobr>&nbsp;&nbsp;To&nbsp;&nbsp;</nobr></td>
            <td class="contenttab_internal_rows" nowrap><nobr> 
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
     </table>
   </td>          
</tr>
<tr><td height='10px'></td></tr>  
<tr>
    <td class="contenttab_internal_rows" nowrap valgin="top" colspan="10">
      <table border="0" cellspacing="0" cellpadding="0px" width="20%" align="left">       
       <tr>
         <td <?php echo $showPendingReceipt; ?> class="contenttab_internal_rows" align="left">
            <nobr><B>Show Fine Detail&nbsp;:&nbsp;</b></nobr>
         </td> 
         <td <?php echo $showPendingReceipt; ?> >
           <nobr>
              <input id="showPending2" name="showPending" value="1" type="radio">&nbsp;Paid&nbsp;
              <input id="showPending3" name="showPending" value="2" checked="checked" type="radio">&nbsp;Unpaid&nbsp;
              <input id="showPending1" name="showPending" value="3" type="radio">&nbsp;Both&nbsp;
           </nobr>   
           <span style="padding-left:20px"></span>
         </td>    
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
<script type="text/javascript" language="javascript">
    getSearchValue('');
</script>
