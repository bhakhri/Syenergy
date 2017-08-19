<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Books Form
//
// Author : Nancy Puri
// Created on : 04-October-2010
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BookMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Book Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('bookName','Book Name','width="15%"','',true) , 
                               new Array('bookAuthor','Book Author','width="15%"','',true), 
                               new Array('uniqueCode','Unique Code ','width="15%"','',true), 
                               new Array('instituteBookCode','Institute Book Code ','width="20%"','',true),  
                               new Array('isbnCode','ISBN Code ','width="15%"','',true),
                               new Array('noOfBooks','No. of books ','width="15%"','align="right"',true),  
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Book/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBook';   
editFormName   = 'EditBook';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteBook';
divResultName  = 'results';
page=1; //default page
sortField = 'bookName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}
//This function Validates Form 


function validateAddForm(frm, act) {
     
   
    var fieldsArray = new Array(new Array("bookName","<?php echo ENTER_BOOK_NAME;?>"),
                                new Array("bookAuthor","<?php echo ENTER_BOOK_AUTHOR;?>"),
                                new Array("uniqueCode","<?php echo ENTER_UNIQUE_CODE;?>"),
                                new Array("instituteBookCode","<?php echo ENTER_INSTITUTE_BOOK_CODE;?>"), 
                                new Array("isbnCode","<?php echo ENTER_ISBN_CODE;?>"),
                                new Array("noOfBooks","<?php echo ENTER_NO_OF_BOOKS;?>"));                       
    if(act=='Add') {    
       document.addBook.bookName.value = trim(document.addBook.bookName.value);
       document.addBook.bookAuthor.value = trim(document.addBook.bookAuthor.value);
       document.addBook.uniqueCode.value = trim(document.addBook.uniqueCode.value);
       document.addBook.instituteBookCode.value = trim(document.addBook.instituteBookCode.value); 
       document.addBook.isbnCode.value = trim(document.addBook.isbnCode.value); 
       document.addBook.noOfBooks.value = trim(document.addBook.noOfBooks.value); 
    }
    else {
       document.editBook.bookName.value = trim(document.editBook.bookName.value);
       document.editBook.bookAuthor.value = trim(document.editBook.bookAuthor.value);
       document.editBook.uniqueCode.value = trim(document.editBook.uniqueCode.value);
       document.editBook.instituteBookCode.value = trim(document.editBook.instituteBookCode.value);
       document.editBook.isbnCode.value = trim(document.editBook.isbnCode.value);
       document.editBook.noOfBooks.value = trim(document.editBook.noOfBooks.value);  
    }                                

    var len = fieldsArray.length;
    
   
    for(i=0;i<len;i++) {
       if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='bookName' ) {     
                messageBox("<?php echo BOOK_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
        }
        if((fieldsArray[i][0]=='noOfBooks') && (!isNumeric(trim(eval("frm."+(fieldsArray[i][0])+".value")))))  {      
            messageBox("<?php echo ENTER_VALID_NO_OF_BOOKS; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;         
        }
    }
    if(act=='Add') {      
        addBook();
        return false;
    }
    else if(act=='Edit') {
        editBook();    
        return false;
    }
}
//This function adds form through ajax                                                                 
function addBook() {
          
         url = '<?php echo HTTP_LIB_PATH;?>/Book/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bookName: (document.addBook.bookName.value), bookAuthor: (document.addBook.bookAuthor.value), uniqueCode: (document.addBook.uniqueCode.value), instituteBookCode: (document.addBook.instituteBookCode.value), isbnCode: (document.addBook.isbnCode.value), noOfBooks: (document.addBook.noOfBooks.value)},
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
                         else {
                             hiddenFloatingDiv('AddBook');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
  
                    },
                     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }         
           }
     );
}

function blankValues() {
   document.addBook.bookName.value = '';
   document.addBook.bookAuthor.value = '';
   document.addBook.uniqueCode.value = ''; 
   document.addBook.instituteBookCode.value = ''; 
   document.addBook.isbnCode.value = ''; 
   document.addBook.noOfBooks.value = '';   
   document.addBook.bookName.focus();
}

//This function edit form through ajax                   

function editBook() {
         url = '<?php echo HTTP_LIB_PATH;?>/Book/ajaxInitEdit.php';
        
         new Ajax.Request(url,
           {    
             method:'post',
             parameters: { bookId: (document.editBook.bookId.value), bookName: (document.editBook.bookName.value), bookAuthor: (document.editBook.bookAuthor.value), uniqueCode: (document.editBook.uniqueCode.value), instituteBookCode: (document.editBook.instituteBookCode.value), isbnCode: (document.editBook.isbnCode.value), noOfBooks: (document.editBook.noOfBooks.value)} ,
            onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
            
                     hideWaitDialog(true);
                 
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {   
                         hiddenFloatingDiv('EditBook');
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

//This function calls delete function through ajax

function deleteBook(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Book/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bookId: id},
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

//This function populates values in edit form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Book/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bookId: id},
            onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
          
                    hideWaitDialog(true);
                   j = eval('('+transport.responseText+')');
                   document.editBook.bookId.value = id;
                   document.editBook.bookName.value = j.bookName;
                   document.editBook.bookAuthor.value = j.bookAuthor; 
                   document.editBook.uniqueCode.value = j.uniqueCode; 
                   document.editBook.instituteBookCode.value = j.instituteBookCode;
                   document.editBook.isbnCode.value = j.isbnCode; 
                   document.editBook.noOfBooks.value = j.noOfBooks;  
                   document.editBook.bookName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

 function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listBookPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"BookReport","status=1,menubar=1,scrollbars=1, width=900");
}

 /* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);   
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listBookCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Book/listBookContents.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>