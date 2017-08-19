<?php 

//
//This file creates Html Form output in "Grade" Module 
//--------------------------------------------------------
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
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Consolidated Result Detail : </td>
                        <td class="content_title" title="Add"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td colspan="2">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td class="contenttab_row" valign="top" width="15%" ><nobr>
                           <a href="" onClick="toggleDataFormat('getBatchwiseList',3); return false;">Batch Wise Report</a><br>
                           <a href="" onClick="toggleDataFormat('getInstitutewiseList',3); return false;">Institute Wise Report</a><br>
                           <a href="" onClick="toggleDataFormat('getDepartmentwiseList',3); return false;">Department Wise Report</a><br>
                           <a href="" onClick="toggleDataFormat('getSubjectwiseList',3); return false;">Subject Wise Report</a><br>
                           <a href="" onClick="toggleDataFormat('getClassGroupwiseList',1); return false;">Class Group Wise Report</a><br>   
                           <a href="" onClick="toggleDataFormat('getRoomwiseList',1); return false;">Room Wise Report</a><br>   
                           <a href="" onClick="toggleDataFormat('getBlockswiseList',2); return false;">Block Wise Report</a><br>   
                           <a href="" onClick="toggleDataFormat('getStopPointwiseList',1); return false;">Stop Point Wise Report</a><br>   
                           <a href="" onClick="toggleDataFormat('getDegreewiseList',3); return false;">Degree & Branch Wise Report</a><br>   
                           <a href="" onClick="toggleDataFormat('getDuplicateAttendance',2); return false;">Duplicate Attendance Report</a><br>  
                           </nobr>
                        </td>
                        <td class="contenttab_row" valign="top" width="85%" >
                           <div id="scroll2" style="overflow:auto; height:510px; width:850px; vertical-align:top;">
                              <div id="results" style="height:510px; vertical-align:top;"></div>
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
    <!--Start Add Div-->
<?php
// $History: listConsolidatedDataContents.php $  
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Consolidated
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/04/09   Time: 3:11p
//Updated in $/LeapCC/Templates/Consolidated
//getDuplicateAttendance link added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/26/09   Time: 3:29p
//Updated in $/LeapCC/Templates/Consolidated
//link added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/15/09   Time: 2:18p
//Created in $/LeapCC/Templates/Consolidated
//initial checkin

?>