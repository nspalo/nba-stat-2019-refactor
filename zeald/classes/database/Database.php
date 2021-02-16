<?php

namespace Zeald\nba2019\classes\database;

use Zeald\nba2019\classes\AppConfigurationLoader;

/**
 * Class Database
 * @package Zeald\nba2019\classes\database
 * @author Noel Palo
 * @license
 */
class Database implements DatabaseInterface
{
    private $config;
    private $connection;
    private $sqlLastInserted;
    private $sqlAffectedRows;
    private $sqlResult;

    public function __construct()
    {
        $this->config          = (new AppConfigurationLoader('database'))->get();
        $this->connection	   = null;
        $this->sqlLastInserted = null;
        $this->sqlAffectedRows = null;
        $this->sqlResult	   = null;

        /**
         * Connect to the database
         */
        $this->connect(
            $this->config["hostname"],
            $this->config["username"],
            $this->config["password"],
            $this->config["database"]
        );
    }

    public function __destruct()
    {
        if( $this->sqlResult ) {
            mysqli_free_result( $this->sqlResult );
        }

        $this->close();

        unset(
            $this->config,
            $this->connection,
            $this->sqlLastInserted,
            $this->sqlAffectedRows,
            $this->sqlResult
        );
    }

    public function connect( $hostname, $username, $password, $database )
    {
        if( is_null( $this->connection ) ) {
            $this->connection = @mysqli_connect( $hostname, $username, $password, $database );
        }

//        if( ! $this->connection ) {
//            return $this->errorLog->logErrors( "database connection error!" );
//        }

        return $this->connection;
    }

    public function close()
    {
        return ( is_resource( $this->connection ) ) ? mysqli_close( $this->connection ) : false;
    }

    public function query( $query )
    {
        $sql_timerStart		   = microtime( TRUE );
        $this->sqlResult	   = mysqli_query( $this->connection, $query );
        $this->sqlLastInserted = mysqli_insert_id( $this->connection );
        $this->sqlAffectedRows = mysqli_affected_rows( $this->connection );
        $sql_timerEnd		   = microtime( TRUE );

//        if( ! $this->sqlResult ) {
//            return $this->errorLog->logErrors( $query );
//        }

        return $this->sqlResult;
    }

    public function getRows()
    {
        $data_fetch = array();
        while( $result_row = mysqli_fetch_assoc( $this->sqlResult ) ) {
            $data_fetch[] = $result_row;
        }

        return $data_fetch;
    }

    public function getRowNext()
    {
        return mysqli_fetch_assoc( $this->sqlResult );
    }

    public function getConnection()
    {
        if( empty( $this->connection ) ) {
            return false;
        }

        return $this->connection;
    }

    public function getAffectedRows()
    {
        $sqlAffectedRows = $this->sqlAffectedRows;
        return $sqlAffectedRows;
    }

    public function getLastInsertId()
    {
        $sqlLastInserted = $this->sqlLastInserted;
        return $sqlLastInserted;
    }

    public function getError()
    {
        return array(
            "error_no"		=> ( is_null( $this->connection ) || false === $this->connection ) ? mysqli_connect_errno() : mysqli_errno( $this->connection ),
            "error_message" => ( is_null( $this->connection ) || false === $this->connection ) ? mysqli_connect_error() : mysqli_error( $this->connection )
        );
    }

    public function sanitizeQueryResult( $query )
    {
        return mysqli_real_escape_string( $this->connection, $query );
    }
}

?>