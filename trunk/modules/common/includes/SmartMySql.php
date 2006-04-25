<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006, 2005
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ---------------------------------------------

/*
 * Mysqli classes
 *
 */


class DbMysql
{    
    protected $user;
    protected $pass;
    protected $dbhost;
    protected $dbname;
    protected $dbport;
    protected $dbsocket;
    protected $dbflags;
    private   $dbh = NULL;

    public function __construct($dbhost, $user, $pass, $dbname, $dbport = FALSE, $dbsocket = FALSE, $dbflags = FALSE) 
    {
        $this->user     = $user;
        $this->pass     = $pass;
        $this->dbhost   = $dbhost;
        $this->dbname   = $dbname;
        $this->dbport   = $dbport;
        $this->dbsocket = $dbsocket;
        $this->dbflags  = $dbflags;
    }

    public function __destruct()
    {
        // php version 5.0.5 eject an E_WARNING if we dont do this
        session_write_close();
        mysql_close( $this->dbh );
    }
  
    public function connect( $options = FALSE ) 
    {        
        $this->dbh = mysql_connect($this->dbhost, $this->user, $this->pass, false, $this->dbflags);
    
        if(FALSE == $this->dbh) 
        {
            throw new SmartDbException( $this->error() );
        }
        $this->selectDatabase( $this->dbname );
    }

    /**
     * Select a database
     *
     * @param string $db_name Name of a database to select
     */
    public function selectDatabase( $db_name )
    {       
        if( !mysql_select_db( $db_name, $this->dbh ) )
        {
            throw new SmartDbException( $this->error() );
        }
    }

    public function query( $query, $mode = '' ) 
    {
        $result = mysql_query($query, $this->dbh); 

        if(FALSE === $result) 
        {
            throw new SmartDbException($this->error( 'QUERY: ' . $query ));
        }
        else 
        {
            $stmt = new DbMysqlStatement;
            $stmt->result = $result;
            return $stmt;
        }
    }
    
    public function affectedRows() 
    {
        return mysql_affected_rows( $this->dbh );
    }        
    
    public function prepare($query) 
    {
         throw new SmartDbException('Prepare statement not yet supported');
    }   
    
    public function escape( $str )
    {
        return mysql_real_escape_string( $str, $this->dbh );
    }
    
    public function close()
    {
        return mysql_close( $this->dbh );
    }   

    public function serverVersion()
    {
        return mysql_get_server_info( $this->dbh );
    }   
    
    public function queryInfo()
    {
        return FALSE;
    }  
    
    public function setCharset( $charset )
    {
        throw new SmartDbException('setCharset() not supported');
    }  
    
    public function lastInsertID()
    {
        return mysql_insert_id( $this->dbh );
    } 
    
    /**
     * Get the MySql error message text
     *
     * @return string MySql error message text else false.
     */
    private function error( $message = '' )
    {
        if(mysql_errno() > 0)
        {
            return 'SQL-ERROR: '.chr(10).mysql_error().chr(10).'SQL_ERROR_NO: '.chr(10).mysql_errno().chr(10).chr(10).$message;
        }
        else
        {
            return FALSE;
        }
    }    
}

class DbMysqlStatement
{
    public  $result;

    private $fetchResultType = array('ASSOC' => MYSQL_ASSOC,
                                    'NUM'   => MYSQL_NUM,
                                    'BOTH'  => MYSQL_BOTH);
        
    public function fetchRow() 
    {
        if(!$result = mysql_fetch_row( $this->result ))
        {
            throw new SmartDbException( $this->error() );
        }
        return $result;
    }
    
    public function fetchAssoc() 
    {
        return mysql_fetch_assoc( $this->result );
    }

    public function fetchObject() 
    {
        return mysql_fetch_object( $this->result );
    }

    public function fetchArray( $type = 'ASSOC' ) 
    {
        return mysql_fetch_array( $this->result, $this->fetchResultType[$type] );
    }

    public function numRows() 
    {
        return mysql_num_rows( $this->result );
    }    
   
    public function fetchAll( $type = 'fetchAssoc' ) 
    {
        $retval = array();
        while($row = $this->$type()) 
        {
            $retval[] = $row;
        }
        return $retval;
    }
      
    public function free()
    {
        return mysql_free_result( $this->result );
    }       
}

class DbMysqlBindStatement extends DbMysqlStatement
{
    private $bindParamArray   = array();
    private $bindParamCounter = 0;        

    public function __construct()
    {
            $this->bindReset();
    }
 
    private function bindParam() 
    {
        call_user_func_array(array(& $this->result ,'bind_param'), $this->bindParamArray);
    }  
    
    public function bindResult( $arg = array() ) 
    {
        call_user_func_array(array(& $this->result ,'bind_result'), $arg);
    } 

    public function setInt( $val )
    {
        $this->bindParamArray[0] .= 'i';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }

    public function setString( $val )
    {
        $this->bindParamArray[0] .= 's';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }
    
    public function setBlob( $val )
    {
        $this->bindParamArray[0] .= 'b';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }  
    
    public function setDouble( $val )
    {
        $this->bindParamArray[0] .= 'd';       
        $this->bindParamArray[$this->bindParamCounter++] = $val;        
    }    

    private function bindReset()
    {
        // init bind variables
        $this->bindParamArray    = array();
        $this->bindParamArray[0] = '';
        $this->bindParamCounter  = 1;    
    }
    
    public function execute() 
    {
        if($this->bindParamCounter > 0)
        {
            $this->bindParam();
            $this->bindReset();
        }
        
        if( FALSE ==  $this->result->execute() )
        {
            throw new SmartDbException($this->dbh->error);
        }
    } 
    
    public function fetch() 
    {
        return $this->result->fetch();
    }   
}

class DbMysqlResult 
{
    protected $stmt;
    protected $result = array();
    private $rowIndex = 0;
    private $currIndex = 0;
    private $done = false;
    private $type;
 
    public function __construct(DbMysqlStatement $stmt, $type = 'fetchAssoc' ) 
    {
        $this->stmt = $stmt;
        $this->type = $type;
    } 
  
    public function first() 
    {
        if(!$this->result) 
        {
            $this->result[$this->rowIndex++] = $this->stmt->$this->type();
        }
        $this->currIndex = 0;
        return $this;
    }
    
    public function last()
    {
        if(!$this->done) 
        {
            array_push($this->result, $this->stmt->fetchAll( $this->type ));
        }
        $this->done = true;
        $this->currIndex = $this->rowIndex = count($this->result) - 1;
        return $this;
    }
    
    public function next()
    {
        if($this->done) 
        {
            return false;
        }
        $offset = $this->currIndex + 1;
        if(!$this->result[$offset]) 
        {
            $row = $this->stmt->$this->type();
            if(!$row) 
            {
              $this->done = true;
              return false;
            }
            $this->result[$offset] = $row;
            ++$this->rowIndex;
            ++$this->currIndex;
            return $this;
        }
        else 
        {
            ++$this->currIndex;
            return $this;
        }
    }
    
    public function prev()
    {
        if($this->currIndex == 0) 
        {
          return false;
        }
        --$this->currIndex;
        return $this;
    }
    
    public function __get($value) 
    {
        if(array_key_exists($value, $this->result[$this->currIndex])) 
        {
          return $this->result[$this->currIndex][$value];
        }
    }
} 

?>