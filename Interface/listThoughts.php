<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Thoughts ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Parveen Sharma
// Created on : (18.3.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ThoughtsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Thoughts </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), 
new Array('thought','Thoughts','width="85%"','',true),
new Array('action','Action','width="10%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddThoughts';   
editFormName   = 'EditThoughts';
winLayerWidth  = 405; //  add/edit form width
winLayerHeight = 220; // add/edit form height
deleteFunction = 'return deleteThoughts';
divResultName  = 'results';
page=1; //default page
sortField = 'thought';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}


function showThoughtsDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateThoughtsValues(id);
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("thought","<?php echo ENTER_THOUGHTS; ?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(act=='Add') {
        addThoughts();
        return false;
    }
    else if(act=='Edit') {
        editThoughts();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Thoughts
//
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addThoughts() {
         url = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {thought: (document.AddThoughts.thought.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                        else if("<?php echo THOUGHTS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo THOUGHTS_ALREADY_EXIST ;?>"); 
                         document.AddThoughts.thought.focus();
                        }  
                         else {
                             hiddenFloatingDiv('AddThoughts');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Parveen Sharma
// Created on : 16.01.09
// Copyright 2009-2010 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateThoughtsValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxGetValues.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {thoughtId: id},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divThoughts');
                        messageBox("<?php echo THOUGHTS_NOT_EXIST; ?>");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                          //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('thoughtInfo').innerHTML= j.thought;    
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A Thoughts
//  id=busRouteId
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteThoughts(id) {
         
         if(false==confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {thoughtId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });

         }    
           
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddThought" DIV
//
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddThoughts.thought.value = '';
   document.AddThoughts.thought.focus();
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Thought
//
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editThoughts() {
       
         url = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {thought: (document.EditThoughts.thought.value),
                          thoughtId: (document.EditThoughts.thoughtId.value) 
              },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditThoughts');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo THOUGHTS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo THOUGHTS_ALREADY_EXIST ;?>"); 
                         document.EditThoughts.thought.focus();
                        }   
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditThought" DIV
//
//Author : Parveen Sharma
// Created on : (18.3.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Thoughts/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {thoughtId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditThoughts');
                        messageBox("<?php echo THOUGHTS_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditThoughts.thought.value = j.thought;
                   document.EditThoughts.thoughtId.value = j.thoughtId;
                   document.EditThoughts.thought.focus();
             },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print thought report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listThoughtsPrint.php?'+qstr;
    window.open(path,"ThoughtsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

</script>

</head>
<body>
    <?php 
        require_once(TEMPLATES_PATH . "/header.php");
	    require_once(TEMPLATES_PATH . "/Thoughts/listThoughtsContents.php");
        require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listThoughts.php $ 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/20/09    Time: 12:06p
//Updated in $/LeapCC/Interface
//code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:08a
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 6:30p
//Created in $/Leap/Source/Interface
//file added
//

?>