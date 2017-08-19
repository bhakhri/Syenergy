<?php
if (defined('INCLUDE_INVENTORY_MANAGEMENT') and (INCLUDE_INVENTORY_MANAGEMENT === true)) {

	define("INVENTORY_LIB_PATH", LIB_PATH . '/INVENTORY' );
	define("INVENTORY_HTTP_PATH", HTTP_PATH . '/INVENTORY' );
	define("INVENTORY_BL_PATH", BL_PATH ."/INVENTORY");
	define("INVENTORY_HTTP_LIB_PATH", HTTP_LIB_PATH ."/INVENTORY");
	define("INVENTORY_MODEL_PATH", MODEL_PATH . "/INVENTORY");
	define("INVENTORY_UI_PATH", UI_PATH ."/INVENTORY");
	define("INVENTORY_UI_HTTP_PATH", UI_HTTP_PATH ."/INVENTORY");
	define("INVENTORY_TEMPLATES_PATH", TEMPLATES_PATH . "/INVENTORY");

	
	//used for generating item codes
	define('ITEM_CODE_PREFIX','I');
	define('ITEM_CODE_LENGTH',10);
	define('APPROVED_PO_CANCELLED','Approved PO Could Not Be Cancelled');

	// Unit Of Measurement array
	$UnitOfMeasurementArray = array("0"=>"Kilogram", "1"=>"Litre","2"=>"Number");

	//Status array of Requisition Master
	define('PENDING',1);
	define('APPROVED',2);
	define('CANCELLED',3);
	define('ISSUED',4);
	define('CANCELLEDBYHOD',5);
	define('CANCELLEDBYSTORE',6);
	define('INCOMPLETE',7);

	$requisitionStatusArray = array(PENDING => "Pending", APPROVED => "Approved", CANCELLED => "Cancelled", ISSUED => "Issued", CANCELLEDBYHOD => "CancelledByHOD", CANCELLEDBYSTORE => "CancelledByStore", INCOMPLETE => "Incomplete");


	//Status array of Issue Items
	define('ITEM_PENDING',1);
	define('ITEM_APPROVED',2);
	$itemStatus = array(ITEM_PENDING => "pending" , ITEM_APPROVED => "Approved");


	//Status array of Category Type
	define('CONSUMABLE',1);
	define('NON_CONSUMABLE',2);
	$categoryTypeStatus = array(CONSUMABLE => "consumable" , NON_CONSUMABLE => "non-consumable");


	//Status array of Indent Master
	$indentStatusArray= array("0"=>"Pending","1"=>"Cancelled","2"=>"GeneratedPO");
	//Status array of  Generated PO
	$poStatusArray= array("0"=>"Pending","1"=>"Approved","2"=>"Cancelled");

	// Packaging array
	$packagingArray = array("1"=>"Packs","2"=>"General");

	// include centralized messages file path for inventory management
	require_once(BL_PATH . '/inventoryMessages.inc.php'); 
}
?>