<?php

//Item & Supplier Mapping Master
define("ENTER_ITMES_ISSUED","Enter items issued");
define('ENTER_ITEM_PRICE','Enter item price');
define('ENTER_ITEM_PRICE_IN_NUMERIC','Enter numeric value for item price');
define('ENTER_ITEM_PRICE_GREATER_THAN_ZERO','Item price must be greater than zero');
define('ISSUE_DATE_VALIDATION1','Date of issue cannot be smaller than indent date');
define('ISSUE_DATE_VALIDATION2','Date of issue cannot be greater than current date');
define('ENTER_ITMES_ISSUED_GREATER_THAN_ZERO','Items issued cannot be less than zero');
define('ITEM_ISSUE_RESTRICTION','Stock availibility');
define('INVALID_INDENT_NO','Invalid indent no.');



//Issue Master
define("ENTER_ITMES_ISSUED","Enter items issued");
define('ENTER_ITMES_ISSUED_IN_NUMERIC','Enter numeric values for items issued');
define('ITMES_ISSUED_VALIDATION_WITH_REQUESTED','Items issued cannot be greater than items requested');
define('ENTER_INDENT_NO','Enter indent no.');
define('ISSUE_DATE_VALIDATION1','Date of issue cannot be smaller than indent date');
define('ISSUE_DATE_VALIDATION2','Date of issue cannot be greater than current date');
define('ENTER_ITMES_ISSUED_GREATER_THAN_ZERO','Items issued cannot be less than zero');
define('ITEM_ISSUE_RESTRICTION','Stock availibility');
define('INVALID_INDENT_NO','Invalid indent no.');



//Indent Master
define("INDENT_DATE_VALIDATION","Indent data cannot be greater than current date");
define("ENTER_INDENT_ITEM_QUANTITY","Enter qunatity");
define('ENTER_INDENT_ITEM_QUANTITY_NUMERIC','Enter numeric values for quantity');
define('DISPATCH_DATE_VALIDATION','Date of dispatch cannot be smaller than date of order');
define('ENTER_REQUESTED_TO_EMPLOYEE','Enter employee code');
define('INDENT_NOT_EXIST','This record does not exists');
define('INDENT_DELETE_RESTRICTION','You cannot delete this record');
define('INDENT_EDIT_RESTRICTION','You cannot edit this record');
define('SELECT_INDENT_DATE','Enter date of indent');
define('DUPLICATE_INDENT_NO','Duplicate indent no');
define('INDENT_NOT_EDIT','Cancelled/Generated PO Indent cannot be edited');
define('INDENT_ALREADY_EXIST','Indent Already Exist');
define('ONLY_PENDING_INDENT_CANCELLED','Only Pending Issues can be Cancelled');


//Order Master
define('ENTER_PO_NO','Enter PO Number');
define("SELECT_SUPPLIER","Select a supplier");
define("ORDER_DATE_VALIDATION","Date of order cannot be greater than current date");
define('ENTER_ITEM_CODE','Enter item code'); 
define('INVALID_ITEM_CODE','Invalid item code');
define('ENTER_ITEM_QUANTITY','Enter quantity');
define('DUPLICATE_ITEM_CODE_RESTRICTION','Duplicate item code');
define('ENTER_ITEM_QUANTITY_NUMERIC','Enter numeric values for quantity');
define('DISPATCH_DATE_VALIDATION','Date of dispatch cannot be smaller than date of order');
define('BLANK_QUANTITY','Enter quantity');
define('INVALID_QUANTITY','Enter values greater than 0 for quantity');
define('ORDER_NOT_EXIST','This order does not exists');
define('DISPATCHED_EDIT_RESTRICTION','You cannot edit this record');
define('DISPATCHED_DELETE_RESTRICTION','You cannot delete this record');
define('SELECT_ORDER_DATE','Enter date of order');
define('PO_CANCEL_CONFIRM','Do you want to cancel Generated PO');

//Items Master
define("ENTER_AVA_QUANTITY_END","Enter available quantity(to)");
define("ENTER_CORRECT_AVA_QUANTITY","From value of available quantity cannot be greater than to value");
define('ENTER_AVA_QUANTITY_START','Enter available quantity(from)'); 
define('ENTER_ITEM_CODE','Enter item code');
define('ENTER_ITEM_NAME','Enter item name');
define('ENTER_ITEM_DESC','Enter item description');
define('SELECT_UNIT_OF_MEASUREMENT','Select unit of measurement');
define('SELECT_PACKAGING','Select packaging');
define('ENTER_AVAILABLE_QTY','Enter available quantity');
define('ENTER_MIN_QTY','Enter minimum quantity');
define('ENTER_NUMERIC_AQ','Enter numeric value for available quantity');
define('ENTER_NUMERIC_MQ','Enter numeric value for minimum quantity');
define('ITEM_NAME_LENGTH_VALIDATION','Item name cannot less than 3 characters');
define('ITEM_DESC_LENGTH_VALIDATION','Item description cannot less than 5 characters');
define('ITEM_NAME_ALREADY_EXIST','This item name already exists');
define('ITEM_CODE_ALREADY_EXIST','This item code already exists');
define('INVALID_ITEM_RECORD','This item does not exists');
define('ENTER_NUMERIC_AQ_FROM','Enter numeric value');
define('ENTER_NUMERIC_AQ_TO','Enter numeric value');
define('SELECT_CATEGORY_CODE','Select Category Code');
define('ENTER_REORDER_LEVEL','Enter Reorder Level');
define('ENTER_NUMERIC_RL','Enter Numeric Reorder Level');
define('SELECT_UNIT','Select Unit');


//SUPPLIER module
define('ENTER_COMPANY_NAME','Enter Company Name');
define('ENTER_ADDRESS1','Enter Address');
define('ENTER_CONTACT_PERSON_NAME','Enter Contact Person Name');
define('ENTER_CONTACT_PERSON_PHONE','Enter Contact Person Phone');
define('ENTER_COMPANY_PHONE','Enter Company Phone Number');
define('COMPANY_EXIST','Company Name Already Exist');
define('SUPPLIER_NOT_EXIST','Company Do Not Exist ');
define('ENTER_SUPPLIER_CODE','Enter Supplier Code ');
define('SUPPLIER_CODE_EXIST','Supplier Code Already Exist ');

//Item Category Module
define('ENTER_CATEGORY','Enter Item Category');
define('ENTER_CATEGORY_CODE','Enter Category Code');
define('ENTER_CATEGORY_TYPE','Enter Category Type');
define('ENTER_ABBR','Enter Abbreviation');
define('ITEM_CATEGORY_EXIST','Item Category already exist');
define('CATEGORY_NAME_EXIST','Category Name already exist');
define('CATEGORY_CODE_EXIST','Category Code already exist');
define('ABBR_EXIST','Abbreviation already exist');
define('ENTER_ALPHABETS_NUMERIC2',"Enter following (a-z,A-Z,0-9,/,-) characters only");


//Receive Master
define("INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER","This order no. does not exists or not dispatched or already received");
define("ENTER_NUMERIC_VALUE_FOR_RECEIVED","Enter numeric value for items received");
define('ORDER_RECEIVED_VALIDATION','Order received cannot be greater than ordered'); 
define('RECEIVE_DATE_VALIDATION1','Receive date cannot be smaller than dispatch date');
define('RECEIVE_DATE_VALIDATION2','Receive date cannot be greater than current date');
define('ENTER_ORDER_NO','Enter order no.');
define('ITEM_INFO_MISSING','No data to submit');
define('ENTER_ITEMS_RECIVED','Enters items received');
define('INVALID_ITEM_RECEIVED','Invalid value for items received');
define('ENTER_RECEIVE_DATE','Enter receive date');
define('ENTER_TAX_AMOUNT','Enter tax amount');
define('ENTER_TAX_AMOUNT_IN_NUMERIC','Enter numeric value for tax amount');
define('ENTER_PRICE_AMOUNT','Enter items  price');
define('ENTER_PRICE_AMOUNT_IN_NUMERIC','Enter numeric value for item price');
define('ENTER_TOTAL_AMOUNT','Enter total amount');
define('TOTAL_AMOUNT_VALIDATION1','Total amount cannot be blank or less than zero');
define('ENTER_PRICE_AMOUNT_GREATER_THAN_ZERO','Item price cannot be less than zero');
define('TOTAL_AMOUNT_VALIDATION2','Total amount must be equal to sum of tax and item prices');


// Items Master
define('SELECT_ITEM_TYPE','Select Item Type');
define('ENTER_ITEM_NAME','Enter Item Name');
define('ENTER_ITEM_DESC','Enter Item Description');
define('SELECT_ITEM_CATEGORY','Select Item Category');
define('SELECT_STORE','Select Deptt./Store');
define('SELECT_UNIT_OF_MEASUREMENT','Select Unit of Measurement');
define('ENTER_AVAILABLE_QTY','Enter Available Quantity');
define('ENTER_MIN_QTY','Enter Minimum Quantity');
define('ENTER_ITEM_PREFIX','Enter Item Prefix');
define('NOT_LESS_THAN_ZERO','Value cannot be less than or equal to zero');
define('DATE_NOT_LESS','Date cannot be less than issued date');
define('NOT_GREATER_REORDER_LEVEL','Reorder level should not be greater than No. of Items');
define('INVALID_VALUE','Invalid value has been entered at Req. Quantity');


// Inventory Non-consumable Issue Items 
define('ISSUE_SUCCESSFULLY','Items Issued Successfully');
define('TRANSFERRED_SUCCESSFULLY','Items Transferred Successfully');
define('RETURNED_SUCCESSFULLY','Items Returned Successfully');
define('SELECT_ISSUED_STATUS','Select Issue Status');
define('SELECT_ISSUED_TO','Select Issue To');
define('SELECT_TRANSFER_TO','Select Transfer To');
define('SELECT_RETURN_TO','Select Return To');
define('CANT_ISSUE_ITEMS','Items Cannot be Issued');
define('CANT_ITEMS_RETURN','Only Issued Items can return');
define('INVENTORY_DEPT_NAME_EXIST','Inventory Department Name already exists');
define('INVENTORY_DEPT_ABBR_EXIST','Abbreviation already exists');
define('INVENTORY_DEPTT_NOT_EXIST','Inventory Department does not exist');
define('CANT_TRANSFER_ITEMS','Items cannot be transferred');
define('CANT_ISSUED_ITEMS','Items cannot be issued');
define('CANT_RETURN_ITEMS','Items cannot be returned');
define('SELECT_DEPTT_STORE','Please select Deptt./Store');
define('SELECT_ITEM_CATEGORY','Please select Item Category');
define('SELECT_ITEM_TYPE','Please select Item Type');
define('SELECT_ATLEAST_ONE_ITEM','Select at least one Item');


// Inventory Consumable Issue Items 
define('CANCEL_ITEMS','Items have been cancelled against this issue');
define('CANCEL_CONFIRM','Do you want to Cancel these items against this issue?');
define('AVAILABLE_QUANTITY_NOT_MORE','Issuing Quantity should not more than available quantity');
define('ISSUE_DEPARTMENT','Select Issuing Deptt./Store');
define('ISSUE_ITEMS','Select Issuing Item');
define('SELECT_ITEM_NAME','Please Select Item Name');
define('FILL_VALUE','Please fill the value in Req. Quantity');
define('SELECT_ISSUED_TO','Please Select Issued To');

// Requisition Master
define('ONLY_PENDING_STATUS_CANCELLED','Requisition has been already cancelled');
define('REQUISITION_NOT_EDIT','Cannot edit Requisition because it has been cancelled');

//Requisition Mapping
define('MAPPED_SUCCESS','Successfully Mapped');

//GRN Master
define('SELECT_PARTY_CODE','Select Party Code');
define('BILL_ALREADY_EXIST','Bill No. Already Exists');
define('GRN_CANCEL','GRN Cancelled Successfully');

//Party Master
define('ENTER_PARTY_NAME','Enter Party Name');
define('ENTER_PARTY_CODE','Enter Party Code');
define('PARTY_NOT_EXIST','Party does not exist');
define('PARTY_CODE_ALREADY_EXIST','Party code already exist');
define('PARTY_NAME_ALREADY_EXIST','Party name already exist');
?>