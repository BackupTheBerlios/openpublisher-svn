<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >


// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * custom Error Handler
 *
 */
class JapaErrorHandler
{   
    private $config;

   /**
    * constructor
    *
    * set php error handler callback function
    */    
    function __construct( & $config )
    {
        set_error_handler (array( &$this, '_php_error_handler' ), $config['error_reporting']);
        
        $this->config = & $config;
    }  
    
   /**
    * php error handler
    *
    */    
    function _php_error_handler( $errno, $errstr, $errfile, $errline )
    {        
        $errtype = array (
               E_ERROR           => "E_ERROR",
               E_WARNING         => "E_WARNING",
               E_PARSE           => "E_PARSE",
               E_NOTICE          => "E_NOTICE",
               E_CORE_ERROR      => "E_CORE_ERROR",
               E_CORE_WARNING    => "E_CORE_WARNING",
               E_COMPILE_ERROR   => "E_COMPILE_ERROR",
               E_COMPILE_WARNING => "E_COMPILE_WARNING",
               E_USER_ERROR      => "E_USER_ERROR",
               E_USER_WARNING    => "E_USER_WARNING",
               E_USER_NOTICE     => "E_USER_NOTICE",
               E_STRICT          => "E_STRICT"
               );
               
        // set of errors for which a var trace will be saved  
        $message  = "\nPHP_ERROR: "    . date("Y-m-d H:i:s", time()) . "\n";
        $message .= "\nPHP_ERRNO: "    . $errno . "\n";
        $message .= "PHP_ERROR_TYPE: " . $errtype[$errno] . "\n";
        $message .= "FILE: "           . $errfile . "\n";
        $message .= "LINE: "           . $errline . "\n";
        $message .= "MESSAGE: "        . $errstr . "\n";

        $this->_log( $message );
    }   
    
   /**
    * logging
    *
    * @param string $message
    */     
    function _log( & $message )
    {
        // Log this message to file
        if(strstr($this->config['message_handle'], 'LOG'))
        {
            error_log($message."\n\n", 3, $this->config['logs_path'] . 'japa_error.log');
        }  
        // Print this message
        if(strstr($this->config['message_handle'], 'SHOW'))
        {
            if(preg_match("/web|admin/", $this->config['controller_type']))
            {        
                echo '<pre style="font-family: Verdana, Arial, Helvetica, sans-serif;
                              font-size: 10px;
                              color: #990000;
                              background-color: #CCCCCC;
                              padding: 5px;
                              border: thin solid #666666;">'.$message.'</pre><br />';
            }
            elseif(preg_match("/cli/", $this->config['controller_type']))
            {
                fwrite(STDERR, $message, strlen($message));
            }
            elseif(preg_match("/xml_rpc/", $this->config['controller_type']))
            {
                return new XML_RPC_Response(0, $GLOBALS['XML_RPC_erruser'], $message);
            }              
        }    
        // email this message
        if(strstr($this->config['message_handle'], 'MAIL') && !empty($this->config['system_email']))
        {
            $header  = "From: Japa System <{$this->config['system_email']}>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
            $header .= "Content-Transfer-Encoding: 8bit";
            
            @mail($this->config['system_email'], "Japa System Message", $message, $header);
        }           
    }
}
?>