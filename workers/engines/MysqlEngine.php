<?php

class ezMysqlConnectionError extends Exception{};
class ezMysqlDisconnectError extends Exception{};
class ezMysqlChangeDbError extends Exception{};
class ezMysqlQueryError extends Exception{};
class ezMysqlConstructError extends Exception{};
class ezMysqlInsertedIdError extends Exception{};

class MysqlEngine 
{

    /**
    @var MysqlEngine $instance
    */    
    private static $instance;

    /**
    @var MysqlConnect $connection
    */
    private $connection;

    /**
    @params String $server
    @params String $user
    @params String $pass
    @params String $db
    @return MysqlEngine
    **/
    public static function getconnection($server, $user = '', $pass = '', $db = '')
    {
        if( is_null(self::$instance) ) {
            self::$instance = new self($server, $user, $pass, $db);
        }
        return self::$instance;
    }

    /**
    @params string $server
    @params String $user
    @params String $pass
    @params String $db
    @return ezMysql
    */
    protected function __construct($server, $user, $pass, $db)
    {
        if (empty($server) or empty($user)) {
            throw new ezMysqlConstructError("Data connection is required");
        }
        $this->connection = new mysqli($server, $user, $pass, $db);
        if($this->connection->connect_errno){
            throw new ezMysqlConnectionError("Connection error (". $this->connection->connect_errno .") : " . $this->connection->connect_error);
        }
    }

    /**
    @return void
    */
    public function Disconnect()
    {
        if(!$this->connection->close()){
            throw new ezMysqlDisconnectError(self::$resource->error); 
        }
    }
    
    /**
    @params String $db
    @return void
    */
    public function ChangeDb($db)
    {
        if (!$this->connection->select_db($db)) {
            throw new ezMysqlChangeDbError($this->connection->error); 
        }
    }
     

    /**
    @params String $query
    @return array for ("SELECT, SHOW, DESC"), false or true for ("INSERT, DELETE, UPDATE")
    */
    public function Query($query)
    {
        $result = $this->connection->query($query);
        if (is_object($result)) 
        {
            if ($result->num_rows > 0) {
                return $this->FetchAssoc($result);
            }
        }
        else if (!$result) {
            throw new ezMysqlQueryError($this->connection->error); 
        }
    }

    /**
    @params String $db
    @return String
    */
    public function InsertedId()
    {
        $inserted_id = $this->connection->insert_id;
        if (empty($inserted_id)) {
            throw new ezMysqlInsertedIdError("Not found any ID generated automatically.");    
        }
        return $inserted_id;
    }

    /**
    @params QueryResult $result
    @return Array
    */ 
    protected function FetchAssoc($result)
    {
        $array = array();
        $num = 0;
        while ($row = $result->fetch_assoc()) {
            $array[$num] = $row;
            $num++;
        }
        return $array;
    }
}