<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>

	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
			
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
            
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Conducting Authority</b></nobr></td>
                        <td class="padding" width="1%">:</td>
                        <td width="20%" class="contenttab_internal_rows" align="left"><nobr>
                        <select size="1" style="width:190px;" class="selectfield" name="condunctingAuthority" id="condunctingAuthority" onchange="populateTestTypeCategory(this.value);" >
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTestConductingAuthorityData();
                        ?>
                      </select></nobr></td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Test Type Category</b></nobr></td>
                        <td class="padding" width="1%">:</td>
                        <td width="20%" class="padding" align="left"><nobr>
                        <select size="1" class="selectfield" name="testTypeCategory" id="testTypeCategory" onchange="populateSubject(document.searchForm.condunctingAuthority.value,this.value);" >
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding"><nobr>:
                        <select size="1" class="selectfield" name="subjectId" id="subjectId" onchange="populateTest(document.searchForm.condunctingAuthority.value,document.searchForm.testTypeCategory.value,this.value);" >
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                    </tr>
                    <tr><td colspan="7" headers="5px"></td></tr>
                    <tr>    
                        <td width="7%" class="contenttab_internal_rows" valign="top" rowspan="1" style="padding-top:5px;"><nobr><b>Test</b></nobr></td>
                        <td class="padding" valign="top" rowspan="1">:</td>
                        <td width="20%" class="contenttab_internal_rows" rowspan="1" align="left" valign="top" style="padding-left:3px;padding-top:5px;"><nobr>
                        <div id="containerDiv1" style="display:inline">
                        <select class="selectfield" name="testId" id="testId" size="6" multiple="multiple" onchange="cleanUpData();" >
                        </select>
                        </div>
                        <?php
                        $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
                        if($isIE6==1){
                        ?>    
                         <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("testId","All","searchForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("testId","None","searchForm");'>None</a>
                         <?php    
                         }
                         ?>
                         <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d1"></div>
                         <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                               <tr>
                                  <td id="d3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                                  <td width="5%">
                                  <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('testId','d1','containerDiv1','d3');" />
                                  </td>
                                </tr>
                             </table>
                          </div>
                         </nobr>
                      </td>                                                                                                  
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px;padding-top:0px" valign="top"><nobr><b>Roll Nos.</b><br/>(x,y,z...)</nobr></td>
                        <td class="padding" width="1%" valign="top">:</td>
                        <td class="contenttab_internal_rows"  align="left" colspan="3" valign="top"><nobr>
                          &nbsp;<input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:415px" onchange="cleanUpData();">
                         <input type="image" name="imageField" style="margin-bottom:-6px;" onClick="getGraphData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /><br/>
                         &nbsp;&nbsp;<span class="contenttab_internal_rows">(Best viewable for 5 students)</span>
                        </nobr> 
                     </td>
                    </tr>  
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="text-align:center;" >&nbsp;
                 <div id="resultsDiv1">
                </div>
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr>
           <td align="center" id="saveDiv" style="display:none">
            <input type="image" name="saveGraphSubmit" value="saveGraphSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_graph.gif" onClick="exportImage();return false;" />&nbsp;
            <input type="image" name="imageField" onClick="printReport();return false" src="<?php echo IMG_HTTP_PATH;?>/print.gif" />&nbsp;
            <input type="image" name="imageField" onClick="printCSV();return false" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
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
// $History: listSubjectPerformanceComparisonContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 30/12/09   Time: 15:45
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI Changes in "Test Wise Performance Comparison Report"
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/12/09   Time: 14:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified graph type in "Test wise performance comparison" report in
//teacher end
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/09   Time: 10:26
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002244,0002245,0002246,0002248,0002249
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/10/09   Time: 13:44
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Test wise performance comparison" report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/10/09   Time: 16:50
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected javascript code
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/07/08    Time: 4:52p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>