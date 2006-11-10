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

/**
 * Japa DB Session Handler
 *
 */
 
final class JapaSessionHandler 
{
    private $db;
    private $dbTablePrefix;

    function __construct( & $db, & $tablePrefix )
    {
        $this->db = & $db;
        $this->dbTablePrefix = & $tablePrefix;
        
        session_set_save_handler(
            array(& $this, 'open'),
            array(& $this, 'close'),
            array(& $this, 'read'),
            array(& $this, 'write'),
            array(& $this, 'destroy'),
            array(& $this, 'gc'));
    }
  
    public function open($save_path, $session_name) 
    {
        return TRUE;
    }
  
    public function close() 
    {
        return TRUE;
    }
  
    public function read($id) 
    {
        $result = $this->db->query("SELECT SQL_CACHE session_data 
                                           FROM {$this->dbTablePrefix}common_session 
                                           WHERE session_id = '$id'");

        $f = $result->fetchAssoc();

        if( isset($f['session_data']) )
        {
            return $f['session_data'];
        }
        
        return "";        
    }
  
    public function write($id, $sess_data) 
    {
        $id        = $this->db->escape($id);
        $sess_data = $this->db->escape( $sess_data );
        $modtime   = time();
    
        $this->db->query("REPLACE INTO {$this->dbTablePrefix}common_session
                                       (session_id, session_data, modtime) 
                                  VALUES('{$id}','{$sess_data}',{$modtime})");
        return TRUE;
    }
  
    public function destroy($id) 
    {
        $this->db->query("DELETE FROM {$this->dbTablePrefix}common_session
                            WHERE session_id = '$id'");
        return TRUE;
    }
  
    public function gc($maxlifetime) 
    {
        $ts = time() - $maxlifetime;
        $this->db->query("DELETE FROM {$this->dbTablePrefix}common_session
                                  WHERE `modtime`<{$ts}");
        
        return TRUE;
    }
}
?>