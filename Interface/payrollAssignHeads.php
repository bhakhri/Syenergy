<?php 
//-------------------------------------------------------------------
// This File contains the payroll assign heads functionality
// Author :Abhiraj Malhotra
// Created on : 19-04-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Payroll Assign Heads </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2();
require_once(TEMPLATES_PATH .'/autoSuggest.php'); 
echo UtilityManager::includeAutosuggest();
?> 

<script language="javascript"> 

function clearAllHeads()
{
    for(var k=0;k<document.headsList.headMenu.options.length;k++)
    {
        document.headsList.headMenu.options[k].selected=false; 
    }
    refreshHeadList(document.headsList.headMenu);
}

function selectAllHeads()
{
    for(var k=0;k<document.headsList.headMenu.options.length;k++)
    {
        document.headsList.headMenu.options[k].selected=true; 
    }
    refreshHeadList(document.headsList.headMenu); 
}  

// get already assigned heads and populate their values
function checkAlreadyAssigned()
{
    clearAllHeads();
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetAssignedHeads.php';
    var varTxtDate;
    if(document.getElementById('chkIn1').value!="" && document.getElementById('chkIn1'))
    {
        varTxtDate=document.getElementById('chkIn1').value;
    }
    else
    {
        varTxtDate=<?php echo date('Y-m')."-01";?>
    }
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {employeeCode: document.headsMapping.searchfield.value,txtDate:varTxtDate, sid:Math.random()},
        asynchronous:false,
             onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            
            hideWaitDialog(true);
            if(trim(transport.responseText)!=0)
            {
            var j = eval('(' + transport.responseText + ')');
            for(var i=0;i<j.length;i++)
            {
                for(var k=0;k<document.headsList.headMenu.options.length;k++)
                {
                    //alert(j[i].headId);
                    if(document.headsList.headMenu.options[k].value==j[i].headId)
                    {
                        document.headsList.headMenu.options[k].selected=true;
                        //document.getElementById('chkIn1').value=j[i].withEffectFrom;
                        
                    }
                }
            }
                
            //document.getElementById('empCode').value=j.employeeName;
            //refreshHeadList(document.headsList.headMenu);
                
            }
            else
            {
               for(var k=0;k<document.headsList.headMenu.options.length;k++)
                {
                   document.headsList.headMenu.options[k].selected=false;
                  // document.getElementById('chkIn1').value='<?php// echo date('Y-m')."-01";?>';
                   //refreshHeadList(document.headsList.headMenu);     
                }
            }
            
         refreshHeadList(document.headsList.headMenu);
         for(var i=0;i<j.length;i++)
                {
                    document.getElementById(j[i].headId).value=j[i].headValue;
                }
                updateOnlyTotal();   
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
    });
    
}
    
// display the employ details when show list button is clicked for the first time
function showData(frm)
{
    
    if(document.headsMapping.searchfield.value=="")
    {
        alert("<?php echo ENTER_EMPLOYEE_NAME; ?>");
        return;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetEmployeeDetails.php';                        
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {employeeCode: document.headsMapping.searchfield.value, sid:Math.random()},
        asynchronous:false,
             onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            
            hideWaitDialog(true);
            
            if(trim(transport.responseText)=="" || trim(transport.responseText)==0 || trim(transport.responseText)==-1)
            {
                alert("<?php echo NO_MATCH;?>"); 
                document.getElementById('nameRow').style.display='none';     
            }
            else if(trim(transport.responseText)==-1)
            {
               alert("<?php echo EMPLOYEE_CODE_BLANK;?>"); 
               document.getElementById('nameRow').style.display='none'; 
            }
            else
            {
                truncateTable();//Clearing the session with the array tempHeadsArray 
                var j = eval('(' + transport.responseText + ')');
                document.getElementById('nameRow').style.display='';
                document.getElementById('empName').innerHTML=j.employeeName;
                document.getElementById('empCode').innerHTML=j.employeeCode;
                document.getElementById('empDesignation').innerHTML=j.designationName;
                document.getElementById('empDepartment').innerHTML=j.departmentName;
                document.getElementById('empPF').innerHTML=j.panNo;
                document.getElementById('empPAN').innerHTML=j.providentFundNo;
                document.getElementById('empESI').innerHTML=j.esiNumber;
                //document.getElementById('empCode').value=j.employeeName;
                checkAlreadyAssigned();
            }
            
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
    });
     
        
    
}

//Every time a head is selected ot deselected this updates the temp array
function refreshHeadList(obj){
    var arSelected = new Array();
    var flag=0;
    
     for (var i = 0; i < obj.options.length; i++) 
     { 
         
         if (obj.options[i].selected==true && obj.options[i].value!=-1 && obj.options[i].value!='undefined') 
         {
             //alert(obj.options[i].value);
             //arSelected.push(obj.options[i].value);
             //alert(arSelected[i]);
             var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxUpdateHeadMappingTemp.php';
             var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {headId: obj.options[i].value, sid:Math.random()},
                     asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });
             
         }
         else
         {
             var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxClearTable.php';
             var j;               
             new Ajax.Request(url,
             {
                method:'post',
                parameters: {headId: obj.options[i].value, sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            }); 
         }
     } 
      
     getHeadList();
     updateOnlyTotal(); 
}

//Clear out the temp array 
function truncateTable()
{
     
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxTruncateTable.php';
             var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                    showWaitDialog(true);
                 },
                onSuccess: function(transport){
                   hideWaitDialog(true);
                    //alert(transport.responseText); 
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });
    //refreshHeadList(obj1)
} 

//Create the grid with assigned heads details
function getHeadList()
{
     var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''),
     new Array('headName','Head Name','width="20%"',''),
     new Array('headType','Type','width="20%"',''),
     new Array('control','Amount','width="20%"',''));
     var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetHeadMappingTemp.php';
             var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="" && trim(transport.responseText)!=0)
                    {  
                      j= trim(transport.responseText).evalJSON();
                      //alert(j.salary_head_temp[0].userId);
                      printResultsNoSorting('results', j.info, tbHeadArray);   
                    }
                    else
                    {
                        document.getElementById('results').innerHTML="";
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });  
}

//Update total on changing any head value
function updateTotal(head,val)
{   
    if(trim(val)=="")
        {
            
            document.getElementById(head).value=0;
            //alert("hello"); 
        }
    var errFlag=0;  
    if(val<0)
    {
        alert("<?php echo INVALID_AMOUNT; ?>");
        document.getElementById(head).value=0;
        document.getElementById(head).focus();
        updateTotal(head,document.getElementById(head).value);
        return;
    }
    function trimNumber(s) {
    if(s!=0)
    {
    while (s.substr(0,1) == '0' && s.length>1) { s = s.substr(1,9999); }
    } 
    return s;
    }

    
    function addzeroes(s)
    {
        
        
        var flag=0;
        var j=s;
        while(s.length>=1 && s!=0 && s.indexOf('.')==-1)
        {
            if(s.substr(0,1)!=".")
            {
               flag++;
               s=s.substring(1,9999); 
            }
            else
            {
                flag=0;
                break;
            }    
        }
        if(flag!=0)
        {
            j=j+".00";
        }
        return j;
    }
    function removeDot(s)
    {       
        s=trimNumber(s);
        var j=s;
        var reducedLength=j.length-1;
        while (s.substr((s.length-1),s.length) == '.' && s.length>=1) { s = s.substr(0,s.length-1); }
        //alert(s);
        return addzeroes(s);
        //alert("hello");  
    }
    
    if(isNaN(val))
        {
            alert("<?php echo INVALID_AMOUNT ;?>");
            document.getElementById(head).value=0;
            document.getElementById(head).focus();
            updateTotal(head,document.getElementById(head).value);
            return;
        }
        
        //document.getElementById(head).value=trimNumber(val);
        //document.getElementById(head).value=addzeroes(val);

        document.getElementById(head).value=removeDot(val);

       
            if(trim(val)=="")
        {
            
            document.getElementById(head).value=0;
            val=0;
            //alert("hello"); 
        }
        var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxUpdateTotal.php';
        var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {headId:head, amount:val, sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="")
                    {  
                      j= trim(transport.responseText).evalJSON();
                      //alert(j.salary_head_temp[0].userId);
                      document.getElementById('totalAmount').innerHTML=j.total;  
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            }); 
          
}

//Update total for first time when assigned heads are populated
function updateOnlyTotal()
{     
        var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxUpdateTotal.php';
        var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {param:'none', sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="")
                    {  
                      j= trim(transport.responseText).evalJSON();
                      //alert(j.salary_head_temp[0].userId);
                      document.getElementById('totalAmount').innerHTML=j.total;  
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });   
}

//save heads mapped to the employee with all other details on the screen
function saveHeadMapping()
{
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxSaveHeadsMapping.php';
        var j;
        var confirmed;
        var found;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {param:'check', employeeCode: document.headsMapping.searchfield.value, wef:document.getElementById('chkIn1').value,sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)==1)
                    {  
                        found=1;
                        if(confirm("<?php echo CONFIRM_OVERWRITE;?>"))
                        {
                            confirmed=1;
                            var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxSaveHeadsMapping.php';
                            var j;               
                            new Ajax.Request(url,
                            {
                                method:'post',
                                parameters: {param:'remove', employeeCode: document.headsMapping.searchfield.value, wef:document.getElementById('chkIn1').value,sid:Math.random()},
                                asynchronous:false,
                                     onCreate: function(){
                                     showWaitDialog(true);
                                 },
                                onSuccess: function(transport){
                                    
                                    hideWaitDialog(true);
                                    //alert(transport.responseText);
                                    
                                    /*if(trim(transport.responseText)!=0)
                                    {  
                                        
                                    }*/
                                   
                                },
                                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
                            });
                        } 
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });
    
    if(found!=1 || (found==1 && confirmed))
    {
        var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxSaveHeadsMapping.php';               
                new Ajax.Request(url,
                {
                    method:'post',
                    parameters: {param:'save', employeeCode: document.headsMapping.searchfield.value, wef:document.getElementById('chkIn1').value,sid:Math.random()},
                    asynchronous:false,
                         onCreate: function(){
                         showWaitDialog(true);
                     },
                    onSuccess: function(transport){
                        
                        hideWaitDialog(true);
                        //alert(transport.responseText);
                        
                        if(trim(transport.responseText)!=0)
                        {  
                          alert("Data Saved Successfully");  
                        }
                        else
                        {
                            alert("No Data To Save");
                        }
                       
                    },
                    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
                });
    } 
}


</script> 
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/assignHeads.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?> 
</body>
</html>
