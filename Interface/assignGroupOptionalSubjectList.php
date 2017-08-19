<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Group Assignment
// Author :Kavish Manjkhola
// Created on : 07-Feb-2011
// Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjectsList');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Optional Subjects to Students(Advanced) </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

 //This function Validates Form
var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), 
                               new Array('select','<input type="checkbox" name="checkAll" id="checkAll" onClick="selAll();" />Select','width="10%"','',false), 
                               new Array('rollNo','Roll No','width="20%"','',true), 
                               new Array('firstName','First Name','width="25%"','',true), 
                               new Array('lastName','Last Name','width="25%"','',true), 
                               new Array('regNo','Reg. No','width="20%"','',true));

//validate form
//check if all students have been assigned group, then prompt, if admin says yes, remove the previous assignment, then show the list

noStudentAdmitted = false;
rollNoNotAssigned = false;
attendanceAlreadyTaken = false;
testsAlreadyTaken = false;
shiftPressed = false;

function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("sortBy","<?php echo SELECT_SORTING;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	showStudentGroups();
}
function printReport() {
	form = document.assignGroup;
	degree = form.degree.value;
	sortBy = form.sortBy.value;
	var path='<?php echo UI_HTTP_PATH;?>/assignGroupOptionalSubjectListReport.php?degree='+degree+'&sortBy='+sortBy;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"AssignGroupReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){

    }
}
function printCSV() {
	form = document.assignGroup;
	degree = form.degree.value;
	sortBy = form.sortBy.value;
    path='<?php echo UI_HTTP_PATH;?>/displayAssignGroupOptionalSubjectListCSV.php?degree='+degree+'&sortBy='+sortBy;
	window.location = path;
}
function showStudentGroups() {

	    form = document.assignGroup;
	    var degree = form.degree.value;
	    var sortBy = form.sortBy.value;
	    var url = '<?php echo HTTP_LIB_PATH;?>/Student/initShowStudentOptionalGroups.php';

	    var pars = 'degree='+degree+'&sortBy='+sortBy;
	     if (degree == '' || sortBy == '') {
		     return false;
	     }
	     new Ajax.Request(url,
		    {
		     method:'post',
		     parameters: pars,
		     asynchronous:false,
		     onCreate: function(){
			     showWaitDialog(true);
		     },
		     onSuccess: function(transport){
				    hideWaitDialog(true);
				    var j = eval('(' + transport.responseText + ')');
				    totalGroups = j['totalGroups'];
				    groupRowData = '';
				    var tableData ='';
				    if (typeof totalGroups === "undefined") {
					    messageBox("No Groups Found");
					    return false;
				    }
				    <?php
				    ///////////////////////////////////////////////////////////
				    //Purpose: To Check if there is any optional subject or not
				    //Author: Kavish Manjkhola
				    //Created on : 07-Feb-2011
				    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
				    //---------------------------------------------------------
				    ?>
				    if (totalGroups == 0) {
					    var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					    var tableData = globalTB;
					    tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text reportBorder">#</td><td width="8%" class="searchhead_text reportBorder">Roll No.</td><td width="8%" class="searchhead_text reportBorder">U.Roll No.</td><td width="15%" class="searchhead_text reportBorder">Student Name</td>';
					    tableData += '<td width=78% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
					    tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
				    }
				    else {
					    var talign="align='center'";
					    var spacePerGroup = parseInt(75 / totalGroups);
					    var tableData = globalTB;
						// alert(globalTB);die;
					    <?php
					    /////////////////////////////////////////////////////////////////
					    //Purpose: To Populate Optional Subjects with Child Subjects
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //----------------------------------------------------------------
					    ?>

					    tableData = tableData +'<tr class="rowheading"><td rowspan="3" width="2%" class="searchhead_text">#</td><td rowspan="3" width="8%" class="searchhead_text">Roll No.</td><td width="8%" rowspan="3" class="searchhead_text">U.Roll No.</td><td width="15%" rowspan="3" class="searchhead_text">Student Name</td>';
					    parentSubjectCtr = 0;
					    totalParentSubjects = j['hasParentSubjects'].length;
					    while (parentSubjectCtr < totalParentSubjects) {
						    parentSubjectCode = j['hasParentSubjects'][parentSubjectCtr]['subjectCode'];
						    parentSubjectId = j['hasParentSubjects'][parentSubjectCtr]['subjectId'];
						    totalChildSubjects = j['getChildSubjects'][parentSubjectId].length;
                            if(totalChildSubjects>0) {
						        childSubjectCtr = 0;
						        totalChildGroups = 0;
                                while(childSubjectCtr < totalChildSubjects) {
							        childSubjectId = j['getChildSubjects'][parentSubjectId][childSubjectCtr]['childSubjectId'];
							        totalChildGroups += j['groupDetails'][childSubjectId].length;
							        childSubjectCtr++;
						        }
						        var colspan ='';
						        if(totalChildGroups!='' && totalChildGroups >=2) {
						          colspan = " colspan='"+totalChildGroups+"'";
						        }
						        if(totalChildGroups == 0) {
							        colspan = " colspan='"+totalChildSubjects+"'";
						        }
								// alert(totalChildGroups);die;
tableData +='<td width="'+spacePerGroup+'" '+colspan+' '+talign+' class="searchhead_text">'+parentSubjectCode+'</td>';
                            }
						    parentSubjectCtr++;
					    }
                      
                        
					    <?php
					    /////////////////////////////////////////////////////////////////
					    //Purpose: To Populate Optional Subjects without Child Subjects
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //----------------------------------------------------------------
					    ?>
                        parentSubjectCtr = 0;
					    totalChildGroups = 0;
					    totalParentSubjects = j['noParentSubjects'].length;
					    while (parentSubjectCtr < totalParentSubjects) {
						    parentSubjectCode = j['noParentSubjects'][parentSubjectCtr]['subjectCode'];
						    childSubjectId = j['noParentSubjects'][parentSubjectCtr]['subjectId'];
                            totalChildGroups=0; 
                            if (typeof j['groupDetails'][childSubjectId] === "undefined") {
                              totalChildGroups=0;  
                            }
                            else {
                              totalChildGroups = j['groupDetails'][childSubjectId].length;  
                            }                            
                            var colspan ='';
						    if(totalChildGroups!='' && totalChildGroups >=2) {
							    colspan = " colspan='"+totalChildGroups+"'";
						    }
						    tableData += '<td width="'+spacePerGroup+'" '+colspan+' '+talign+' rowspan ="2" class="searchhead_text">'+parentSubjectCode+'</td>';
						    parentSubjectCtr++
					    }
					    tableData += '</tr>';
                      
                        <?php
					    /////////////////////////////////////////////////////////////////
					    //Purpose: To Populate Child Subjects of Major/Minor Subjects
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //----------------------------------------------------------------
					    ?>
                        tableData += '<tr class="rowheading">';
					    parentSubjectCtr = 0;
					    totalParentSubjects = j['hasParentSubjects'].length;
					    while (parentSubjectCtr < totalParentSubjects) {
						    parentSubjectId = j['hasParentSubjects'][parentSubjectCtr]['subjectId'];
                            totalChildSubjects = j['getChildSubjects'][parentSubjectId].length;
						    childSubjectCtr = 0;
                            while(childSubjectCtr < totalChildSubjects) {
							    childSubjectCode = j['getChildSubjects'][parentSubjectId][childSubjectCtr]['childSubjectCode'];
							    childSubjectId = j['getChildSubjects'][parentSubjectId][childSubjectCtr]['childSubjectId'];
							    //totalAllottedGroups = j['groupDetails'][childSubjectId].length;
                                totalAllottedGroups=0;
                                if (typeof j['groupDetails'][childSubjectId] === "undefined") {
                                  totalAllottedGroups=0;  
                                  tableData = tableData +'<td width="'+spacePerGroup+'" '+colspan+' '+talign+' class="searchhead_text">&nbsp;</td>';
                                }
                                else {
                                  totalAllottedGroups = j['groupDetails'][childSubjectId].length;
                                }                            
                            
							    var colspan ='';
							    if(totalAllottedGroups!='' && totalAllottedGroups >=2) {
								    colspan = " colspan='"+totalAllottedGroups+"'";
							    }
                                tableData = tableData +'<td width="'+spacePerGroup+'" '+colspan+' '+talign+' class="searchhead_text">'+childSubjectCode+'</td>';
							    childSubjectCtr++;
						    }
                            
						    parentSubjectCtr++;
					    }
					    tableData = tableData + '</tr>';
                        
                    
                       
					    <?php
					    ////////////////////////////////////////////////////////////////////////
					    //Purpose: To Populate GROUPS of Child Subjects of Major/Minor Subjects
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //----------------------------------------------------------------------
					    ?>
                        tableData = tableData + '<tr class="rowheading">';
					    parentSubjectCtr = 0;
					    totalParentSubjects = j['hasParentSubjects'].length;
					    while (parentSubjectCtr < totalParentSubjects) {
						    parentSubjectId = j['hasParentSubjects'][parentSubjectCtr]['subjectId'];
						    totalChildSubjects = j['getChildSubjects'][parentSubjectId].length;
						    childSubjectCtr = 0;
						    while(childSubjectCtr < totalChildSubjects) {
							    childSubjectId = j['getChildSubjects'][parentSubjectId][childSubjectCtr]['childSubjectId'];
							    totalGroups = j['groupDetails'][childSubjectId].length;
							    groupCtr = 0;
							    while(groupCtr < totalGroups) {
								    groupName = j['groupDetails'][childSubjectId][groupCtr]['groupName'];
								    groupId =  j['groupDetails'][childSubjectId][groupCtr]['groupId'];
								    tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">'+groupName+'</td>';
								    groupCtr++;
							    }
							    childSubjectCtr++;
						    }
						    parentSubjectCtr++;
					    }
                        <?php
					    ////////////////////////////////////////////////////////////////
					    //Purpose: To Populate GROUPS of subjects without child subjects
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //--------------------------------------------------------------
					    ?>
                        parentSubjectCtr = 0;
					    totalChildGroups = 0;
					    totalParentSubjects = j['noParentSubjects'].length;
					    while (parentSubjectCtr < totalParentSubjects) {
						    parentSubjectCode = j['noParentSubjects'][parentSubjectCtr]['subjectCode'];
						    childSubjectId = j['noParentSubjects'][parentSubjectCtr]['subjectId'];
                            totalChildGroups=0;   
						    if (typeof j['groupDetails'][childSubjectId] === "undefined") {
                              totalChildGroups=0;  
                              tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">&nbsp;</td>';   
                            }
                            else {
                              totalChildGroups = j['groupDetails'][childSubjectId].length;
                            } 
                            groupCtr = 0;
						    while(groupCtr < totalChildGroups) {
							    groupName = j['groupDetails'][childSubjectId][groupCtr]['groupName'];
							    groupId =  j['groupDetails'][childSubjectId][groupCtr]['groupId'];
							    tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">'+groupName+'</td>';
							    groupCtr++;
						    }
						    parentSubjectCtr++;
					    }
					    tableData += '</tr>';
						tableHeading=tableData;
					    <?php
					    ///////////////////////////////////////////////////////////////////////////////////////////////
					    //Purpose: To Populate Students of a particular class and check for the groups allotted to them
					    //Author: Kavish Manjkhola
					    //Created on : 07-Feb-2011
					    //Copyright 2008-2011: syenergy Technologies Pvt. Ltd.
					    //---------------------------------------------------------------------------------------------
					    ?>
					    totalStudentCount = j['studentDetails'].length;
					    studentCtr = 0;
					    while(studentCtr < totalStudentCount) {
						    recordCounter = studentCtr+1;
							if(recordCounter%11==0) {
								tableData +=tableHeading;
							}
							
                            studentId = j['studentDetails'][studentCtr]['studentId'];
                            var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						    tableData += '<tr '+bg+'>';
						    tableData += '<td>'+recordCounter+'</td>';
						    tableData += '<td>'+j['studentDetails'][studentCtr]['rollNo']+'</td>';
						    tableData += '<td>'+j['studentDetails'][studentCtr]['universityRollNo']+'</td>';
						    tableData += '<td>'+j['studentDetails'][studentCtr]['studentName']+'</td>';
						    parentSubjectCtr = 0;
						    totalParentSubjects = j['hasParentSubjects'].length;
                            while (parentSubjectCtr < totalParentSubjects) {
							    parentSubjectId = j['hasParentSubjects'][parentSubjectCtr]['subjectId'];
                                totalChildSubjects = j['getChildSubjects'][parentSubjectId].length;
							    childSubjectCtr = 0;
                                if(totalChildSubjects==0) {
                                  //tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">&nbsp;</td>';  
                                }
                                else {
							        while(childSubjectCtr < totalChildSubjects) {
								        childSubjectId = j['getChildSubjects'][parentSubjectId][childSubjectCtr]['childSubjectId'];
								        //totalGroups = j['groupDetails'][childSubjectId].length;
                                        totalGroups=0;       
                                        if (typeof j['groupDetails'][childSubjectId] === "undefined") {
                                          totalGroups=0;  
                                          //tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">22&nbsp;</td>';  
                                        }
                                        else {
                                          totalGroups = j['groupDetails'][childSubjectId].length;
                                        } 
								        groupCtr = 0;
								        while(groupCtr < totalGroups) {
									        groupId = j['groupDetails'][childSubjectId][groupCtr]['groupId'];
									        groupName = j['groupDetails'][childSubjectId][groupCtr]['groupName'];
									        studentId = j['studentDetails'][studentCtr]['studentId'];
									        checkBoxName = "chk["+parentSubjectId+"]["+childSubjectId+"]["+groupId+"]["+studentId+"]";
									        checkBoxId = "chk_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+recordCounter;
									        tdId = "td_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+recordCounter;
									        checked = '';
									        tdClass = 'class="" ';
									        if (j['studentGroupDetails'][studentId]) {
										        perStudentGroups = j['studentGroupDetails'][studentId].length;
										        perGroupCtr = 0;
										        while(perGroupCtr < perStudentGroups) {
											        studentGroup = j['studentGroupDetails'][studentId][perGroupCtr]['groupId'];
											        groupParentSubject = j['studentGroupDetails'][studentId][perGroupCtr]['parentOfSubjectId'];
                                                    
											        if(groupId == studentGroup && groupParentSubject == parentSubjectId) {
                                                        
												        checked = ' checked';
												        tdClass = ' class = "highlightPermission" ';
											        }
											        perGroupCtr++;
										        }
									        }
									        tableData += '<td '+tdClass+' width="'+spacePerGroup+'" id="'+tdId+'"><input type="checkbox" '+checked+' id="'+checkBoxId+'" name="'+checkBoxName+'" value="'+studentId+'" onclick=\"return makeSelection(this.id);\" ></td>';
									        groupCtr++;
								        }
								        childSubjectCtr++;
							        }  
                                }
							    parentSubjectCtr++;
						    }
						    parentSubjectCtr = 0;
						    totalChildGroups = 0;
						    totalParentSubjects = j['noParentSubjects'].length;

						    while (parentSubjectCtr < totalParentSubjects) {
							    parentSubjectCode = j['noParentSubjects'][parentSubjectCtr]['subjectCode'];
							    childSubjectId = j['noParentSubjects'][parentSubjectCtr]['subjectId'];
							    //totalChildGroups = j['groupDetails'][childSubjectId].length;
                                
                                totalChildGroups=0;
                                if (typeof j['groupDetails'][childSubjectId] === "undefined") {
                                  totalChildGroups=0;  
                                  tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">&nbsp;</td>';   
                                }
                                else {
                                  totalChildGroups = j['groupDetails'][childSubjectId].length;
                                } 
                                
                          
                                groupCtr = 0;
							    while(groupCtr < totalChildGroups) {
								    groupId =  j['groupDetails'][childSubjectId][groupCtr]['groupId'];
								    studentId = j['studentDetails'][studentCtr]['studentId'];
								    checkBoxName = "chk["+childSubjectId+"]["+groupId+"]["+studentId+"]";
								    checkBoxId = "chk_"+childSubjectId+"_"+groupId+"_"+recordCounter;
								    tdId = "td_"+childSubjectId+"_"+groupId+"_"+recordCounter;
								    checked = '';
								    tdClass = 'class="" ';
                                    if (j['studentGroupDetails'][studentId]) {
									    perStudentGroups = j['studentGroupDetails'][studentId].length;
									    perGroupCtr = 0;
									    while(perGroupCtr < perStudentGroups) {
										    studentGroup = j['studentGroupDetails'][studentId][perGroupCtr]['groupId'];
										    groupParentSubjectId = j['studentGroupDetails'][studentId][perGroupCtr]['subjectId'];
										    if(groupId == studentGroup && groupParentSubjectId == childSubjectId) {
											    checked = ' checked';
											    tdClass = ' class = "highlightPermission" ';
										    }
										    perGroupCtr++;
									    }
								    }
								    tableData += '<td '+tdClass+' width="'+spacePerGroup+'" id="'+tdId+'"><input type="checkbox" '+checked+' id="'+checkBoxId+'" name="'+checkBoxName+'" value="'+studentId+'" onclick=\"return multipleSelection(this.id);\" ></td>';
								    groupCtr++;
							    }
							    parentSubjectCtr++
						    }
						    tableData += '</tr>';
						    studentCtr++;
					    }
				    }
				    tableData += "</table>";

				    document.getElementById("groupRow").style.display='';
				    document.getElementById("nameRow").style.display='';
				    document.getElementById("nameRow2").style.display='';
				    document.getElementById("resultRow").style.display='';
				    document.getElementById("resultsDiv").innerHTML = tableData;
				    //document.getElementById("groupRow").innerHTML = "<b>Group Wise Student Counter:</b><table border='0' rules='rows' cellspacing='5' cellpadding='3' style='border:1px solid #000000;'>"+groupRowData+"</table>";
				    countSelection();
			    },
			    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		    }
	    );
}


function makeSelection(eleId) {
	eleIdArray = eleId.split('_');
	var parentSubjectId = eleIdArray[1];
	var childSubjectId = eleIdArray[2];
	var groupId = eleIdArray[3];
	var groupCounter = eleIdArray[4];
	var groupCounter2 = groupCounter;
	if (shiftPressed == true) {
		preEleId = "chk_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+groupCounter2;
		while (document.getElementById(preEleId)) {
			preEleId = "chk_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+groupCounter2;
			if (document.getElementById(preEleId)) {
				if (document.getElementById(preEleId).checked == false) {
					document.getElementById(preEleId).checked = true;
					tdId = "td_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+groupCounter2;
					if (document.getElementById(tdId)) {
						document.getElementById(tdId).className = 'highlightPermission';
					}
				}
				else {
					break;
				}
			}
			groupCounter2--;
		}
		shiftPressed = false;
	}
	if (document.getElementById(eleId).checked == false) {
		tdId = "td_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = '';
		}
	}
	else {
		tdId = "td_"+parentSubjectId+"_"+childSubjectId+"_"+groupId+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = 'highlightPermission';
		}
	}
	return true;
}


function multipleSelection(eleId) {
	eleIdArray = eleId.split('_');
	var parentSubjectId = eleIdArray[1];
	var groupId = eleIdArray[2];
	var groupCounter = eleIdArray[3];
	var groupCounter2 = groupCounter;
	if (shiftPressed == true) {
		preEleId = "chk_"+parentSubjectId+"_"+groupId+"_"+groupCounter2;
		while (document.getElementById(preEleId)) {
			preEleId = "chk_"+parentSubjectId+"_"+groupId+"_"+groupCounter2;
			if (document.getElementById(preEleId)) {
				if (document.getElementById(preEleId).checked == false) {
					document.getElementById(preEleId).checked = true;
					tdId = "td_"+parentSubjectId+"_"+groupId+"_"+groupCounter2;
					if (document.getElementById(tdId)) {
						document.getElementById(tdId).className = 'highlightPermission';
					}
				}
				else {
					break;
				}
			}
			groupCounter2--;
		}
		shiftPressed = false;
	}
	if (document.getElementById(eleId).checked == false) {
		tdId = "td_"+parentSubjectId+"_"+groupId+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = '';
		}
	}
	else {
		tdId = "td_"+parentSubjectId+"_"+groupId+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = 'highlightPermission';
		}
	}
	return true;
}


function saveSelectedStudents() {
	pars = generateQueryString('assignGroup');
	url = '<?php echo HTTP_LIB_PATH;?>/Student/initAssignOptionalSubjectsToStudents.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: pars,
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
                //alert(transport.responseText);
			    j = trim(transport.responseText);

				if (j == "<?php echo SUCCESS;?>") {
					hideResults();
					messageBox(j);
				}
				else {
					//alert("fail");
					displayFloatingDiv("groupSave",100,200);
					document.getElementById("groupSaveDiv").innerHTML = j;
				}

	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
	  //showStudentGroups();
}


function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
	document.getElementById('groupRow').style.display='none';
}

document.onkeydown = function (e) {
	var ev = e|| window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '16') {
		shiftPressed = true;
	}
}


function doAll(para){

	var str = para+"[]";
    formx = document.assignGroup;
	alert(formx.checkbox2.checked);
	return false;
	if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="text" && formx.elements[i].name==str){
				id = formx.elements[i].value;
                eval("document.getElementById('"+id+"').checked=true");
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="text" && formx.elements[i].name==str){
				id = formx.elements[i].value;
                eval("document.getElementById('"+id+"').checked=false");
            }
        }
    }
}



</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listAssignGroupOptionalSubject.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
//$History: assignGroupAdvanced.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/17/10    Time: 12:41p
//Updated in $/LeapCC/Interface
//done coding for print & csv, FCNS No. 1427
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/17/10    Time: 10:53a
//Updated in $/LeapCC/Interface
//put export to excel & print buttons
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 2:28p
//Updated in $/LeapCC/Interface
//resolved issue 1966
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:13p
//Created in $/LeapCC/Interface
//file added for assign groups advanced


?>
