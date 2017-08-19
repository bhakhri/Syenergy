<?php 

//-------------------------------------------------------
//  This File contains Bussiness Logic of the Bus Stop Head
// Author :Nishu Bindal
// Created on : 21-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class BusStopCityManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	public function addBusStopCity($headName=''){
		$query = "INSERT INTO `bus_stop_city` (busStopCityId,cityName) VALUES ('','$headName')";
		
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}
	public function editBusStopCity($cityName,$busCityId) {
		$query ="UPDATE `bus_stop_city` SET cityName = '$cityName' WHERE busStopCityId = '$busCityId'";
		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}
	
	public function checkInBusStop($busStopCityId){
		$query ="SELECT busStopCityId from `bus_stop_new` WHERE busStopCityId = '$busStopCityId'";
		
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function checkInBusFees($busStopCityId){
		$query ="SELECT busStopCityId from `bus_fees` WHERE busStopCityId = '$busStopCityId'";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}   
    
    public function getBusStopCity($conditions=''){
        $query = "SELECT busStopCityId,cityName
       			FROM `bus_stop_city`
       			 $conditions";
       			
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    //Gets the country table fields
      
    public function getBusStopCityList($conditions='', $limit = '', $orderBy='cityName') {
     
        $query = "SELECT busStopCityId, cityName FROM `bus_stop_city`
        $conditions                   
        ORDER BY $orderBy $limit";
          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

   
    public function getTotalBusStopCity($conditions='') {
    
        $query = "SELECT COUNT(busStopCityId) AS totalRecords 
        FROM `bus_stop_city`
        $conditions ";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // deletes the country
     public function deleteBusStopCity($busStopCityId) {
     
        $query = "DELETE 
        FROM `bus_stop_city`
        WHERE busStopCityId='$busStopCityId'";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>

