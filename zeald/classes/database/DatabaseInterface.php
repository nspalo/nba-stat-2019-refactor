<?php

namespace Zeald\nba2019\classes\database;

/**
 * Interface DatabaseInterface
 * @package Zeald\nba2019\classes\database
 * @author Noel Palo
 * @license
 */
interface DatabaseInterface {
	public function connect( $hostname, $username, $password, $database );
	public function close();	
	public function query( $query );	
	public function getRows();	
	public function getRowNext();	
	public function getConnection();	
	public function getAffectedRows();	
	public function getLastInsertId();	
	public function getError();	
	public function sanitizeQueryResult( $query );
}