<?php
if (defined('INCLUDE_INVENTORY_MANAGEMENT') and (INCLUDE_INVENTORY_MANAGEMENT === true)) {
$inventoryArray = array();
$menuCreationManager->addToAllMenus($fineMenu);
$menuCreationManager->setMenuHeading("Inventory");
$itemCategoryMasterArray = Array(
												 'moduleName' => 'ItemCategoryMaster',						//not there
                                                 'moduleLabel' =>'Item Type',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/listItemCategory.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($itemCategoryMasterArray);

$itemMaster = Array(
												 'moduleName' => 'ItemsMaster',							//not there
                                                 'moduleLabel' =>'Items Description',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/itemsMaster.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($itemMaster);

$openingStockArray = Array(
												 'moduleName' => 'OpeningStock',						//not there
                                                 'moduleLabel' =>'Opening Stock Master',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/listOpeningStock.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                      );
$menuCreationManager->makeSingleMenu($openingStockArray);

$requisitionMapping = Array(
												 'moduleName' => 'RoleToInventory',							//not there
                                                 'moduleLabel' =>'Requisition Approver Mapping',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/roleToInventory.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                      );
$menuCreationManager->makeSingleMenu($requisitionMapping);


$requisitionMater = Array(
												 'moduleName' => 'RequisitionMaster',									//not there
                                                 'moduleLabel' =>'Create Requisition',
																 'moduleLink'  => INVENTORY_UI_HTTP_PATH . '/requisitionMaster.php',
                                                 'accessArray' =>ARRAY(VIEW,ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($requisitionMater);
$approvedRequisition = Array(
												 'moduleName' => 'ApprovedRequisitionMaster',									//not there
                                                 'moduleLabel' =>'Approve Requisition',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/approvedRequisitionMaster.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($approvedRequisition);



$issueMasterArray = Array(
												 'moduleName' => 'RequisitionIssueMaster',						//not there
                                                 'moduleLabel' =>'Issue Items',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/issueMaster.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($issueMasterArray);

$partyMasterArray = Array(
												 'moduleName' => 'PartyMaster',						//not there
                                                 'moduleLabel' =>'Create Suppliers',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/listPartyMaster.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($partyMasterArray);
$indentMasterArray = Array(
												 'moduleName' => 'IndentMaster',						//not there
                                                 'moduleLabel' =>'Indent Master',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/indentMaster.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($indentMasterArray);
$generatePOArray = Array(
												 'moduleName' => 'InventoryGeneratePO',						//not there
                                                 'moduleLabel' =>'Generate PO',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/inventoryGeneratePO.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($generatePOArray);

$approveGeneratedPOArray = Array(
												 'moduleName' => 'ApproveGeneratedPO',						//not there
                                                 'moduleLabel' =>'Approve Generated PO',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/approvePOContents.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($approveGeneratedPOArray);
$grnMasterArray = Array(
												 'moduleName' => 'InventoryGRN',						//not there
                                                 'moduleLabel' =>'Recieve Goods',
                                                 'moduleLink' => INVENTORY_UI_HTTP_PATH . '/inventoryGRN.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );

												
$menuCreationManager->makeSingleMenu($grnMasterArray);
//	$allMenus[] = $inventoryMenu;
$allMenus = $menuCreationManager->getAllMenus();

}
?>
