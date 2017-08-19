<script>
function testFn() {

    guiders.hideAll();
    addNewBread("menuLookup");
}			


function addNewBread(moduleName) {
   url = '<?php echo HTTP_LIB_PATH;?>/ajaxGuiders.php';

   new Ajax.Request(url,
   {
     method:'post',
     parameters: { moduleName: moduleName},
     onCreate: function(){
        showWaitDialog(true);
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
     },
     onFailure: function(){ }
     });
}
function changeDefaultTextOnClick()
{
    if(document.getElementById('menuLookup').value=="Menu Lookup..")
    {
        document.getElementById('menuLookup').value="";
        document.getElementById('menuLookup').className="text_class";
    }
}
function changeDefaultTextOnBlur()
{
    if(document.getElementById('menuLookup').value=="")
    {
        document.getElementById('menuLookup').className="fadeMenuText"; 
        document.getElementById('menuLookup').value="Menu Lookup..";
    }
}
//This script throws a ajax request to populate autosuggest menu
function getMenuLookup()
{
    document.getElementById('menuLookupContainer').style.display="none";
    if(document.getElementById('menuLookup').value.length>1)
    {
        url = '<?php echo HTTP_LIB_PATH;?>/menuLookup.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {txt: document.getElementById('menuLookup').value},
             onCreate: function() {
                 
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                    if((transport.responseText)!="") {
                        var display="<ul style='list-style:none'>";
                        var obj=transport.responseText.evalJSON() 
                        if(obj)
                        {
                            var objSize=10;
                            if(obj.length<10)
                            {
                                objSize=obj.length;
                            }
                            
                            for(var arrayIndex=0;arrayIndex<objSize;arrayIndex++)
                            {
                                display+="<li style='padding:3px'><a href='"+obj[arrayIndex]['link']+"'>"+obj[arrayIndex]['data']+"</a></li>";
                            }       
                        }
                        display+="</ul>";
                        document.getElementById('menuLookupContainer').style.display="";
                        document.getElementById('menuLookupContainer').style.display="block";
                        document.getElementById('menuLookupContainer').innerHTML=display;
                        return false;
                    }
             },
             onFailure: function(){ 
                 //messageBox("<?php echo TECHNICAL_PROBLEM;?>") 
             }
           });  
     }
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr height="5">
		<td colspan="2"></td>
	</tr> 
    <tr>
    <td colspan=2>
    <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
	    <tr height="18">
		    <td align="left" class="title" valign="middle" style="padding-top:7px;background-color:#FDFDDD; 
            border:1px solid #FFC142;border-right:0px;
            <?php // if ($menuCreationManager->showHelpBar(MODULE) == true) { ?>border-right:0px; <?php // 	} ?>">
		    <?php
                $breadCrumbHeading = getBreadCrumb(); 
                if(htmlspecialchars($breadCrumbHeading, ENT_QUOTES)==htmlspecialchars("<font color='black'></font>",ENT_QUOTES)) {
                  echo "Home&nbsp;&raquo;&nbsp;Dashboard"; 
                }
                else {
                  echo $breadCrumbHeading; 
                }
            ?>
		    </td>
	        <?php if ($menuCreationManager->showHelpBar(MODULE) == true) { ?> 
	       	<td align="right" style="background-color:#FDFDDD; 
            padding-right:3px; border:1px solid #FFC142;border-left:0px;">&nbsp;
            <?php   echo getHelpLinks(); ?>&nbsp;
            <input type="text" name="menuLookup" class="fadeMenuText" style="width:200px" id="menuLookup" 
            onkeyup="getMenuLookup();" onclick="changeDefaultTextOnClick();" onblur="changeDefaultTextOnBlur();" 
            value="Menu Lookup.." autocomplete="off"/>

<?php
   $status=0;
   require_once(MODEL_PATH . "/GuidersManager.inc.php");
   $returnStatus=GuidersManager::getInstance()->checkGuidersEntry("menuLookup");
   if(count($returnStatus)>0) {
     $status=1;
   }
if($status==0){
?>
<script type="text/javascript">
/*
	      guiders.createGuider({
	      attachTo: "#menuLookup",
	      buttons: [{name: "Close", onclick:testFn}],
	      description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu \
		                lookup automatially guides you..",
	      id: "fourth",
	      next: "fifth",
	      position: 5,
	      title: "Find Menu Options Quickly!",
	      width: 400
	    }).show();
*/        
	</script>
<?php }?>
            <div id="menuLookupContainer" style="position:absolute;z-index:100;padding:0px 0px 0px 0px; text-align:left; 
            display:none; border:1px solid #7F9DB9; margin-right:3px;"></div>
            </td>
	        <?php 	}
             else
             {
             ?>
                <td align="right" style="background-color:#FDFDDD; border:1px solid #FFC142;border-left:0px;">
                <input type="text" name="menuLookup" class="fadeMenuText" style="width:200px" id="menuLookup" 
                 onfocus="changeDefaultTextOnClick();" onkeyup="getMenuLookup();" onblur="changeDefaultTextOnBlur();" 
                 value="Menu Lookup.." autocomplete="off" />&nbsp;
<?php
		if($status==0){
		?>
		<script type="text/javascript">
			    /*
                  guiders.createGuider({
			      attachTo: "#menuLookup",
			      buttons: [{name: "Close", onclick:testFn}],
			      description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu \
						lookup automatially guides you..",
			      id: "sixth",
			      next: "seventh",
			      position: 7,
			      title: "Find Menu Options Quickly!",
			      width: 400
			    }).show();
                */
			</script>
<?php }?>

                <div id="menuLookupContainer" style="position:absolute;z-index:100;padding:0px 0px 0px 0px; text-align:left; 
                display:none; border:1px solid #7F9DB9; margin-right:3px;"></div>
             </td> 
             <?php
             }
             ?>
        </tr>
        </table>
        </td>
        </tr>
	<?php
		if (is_array($menuCreationManager->showLinkedModules(MODULE)) and count($menuCreationManager->showLinkedModules(MODULE)) != 0) {
	?>
			<tr height='1'>
				<td valign="top" colspan="2" class="">
					&nbsp;
				</td>
			</tr>
			<tr height="18">
			<td valign="middle" colspan="2" class="title" style="padding-top:7px;background-color:#F3F3F3; border:1px solid #EC4D00;"><b>Go to:</b>&nbsp;
				<?php
					$linkedModuleArray = $menuCreationManager->showLinkedModules(MODULE);
					foreach($linkedModuleArray as $linkedModule) {
						echo "[<a class=\"redLink2\" href=".$menuCreationManager->getModuleLink($linkedModule).">".$menuCreationManager->getModuleLabel($linkedModule)."</a>]&nbsp;&nbsp;";
					}
				?> 
			</td>
			</tr>
	<?php
		}
		?>

