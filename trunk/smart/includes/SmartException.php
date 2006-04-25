<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * Exception handler
 *
 */
class SmartException extends Exception
{
    public $flag = array();
    
    /**
     * Constructor
     *
     * set exception message and code number
     * @param string $message
     * @param string $code
     */     
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);
        
        $this->setName('SmartException');
    }
    /**
     * get exception name
     */     
    public function getName ()
    {
        return $this->name;
    }
    
    /**
     * set exception name
     * @param string $name
     */    
    protected function setName ($name)
    {
        $this->name = $name;
    }  

    /**
     * run exception stack trace
     *
     */
    public function performStackTrace()
    {
        $this->exceptionMessage  = "EXCEPTION: ".date("Y-m-d H:i:s", time())."\n";     
        $this->exceptionMessage .= "NAME: "     .$this->getName()."\n";
        $this->exceptionMessage .= "MESSAGE: "  .$this->getMessage()."\n";
        $this->exceptionMessage .= "CODE: "     .$this->getCode()."\n"; 
        $this->exceptionMessage .= "FILE: "     .$this->getFile()."\n"; 
        $this->exceptionMessage .= "LINE: "     .$this->getLine()."\n";
        $this->exceptionMessage .= "TRACE: \n"  .var_export($this->getTrace(), TRUE)."\n";

        $this->_log();
    }
    
   /**
    * logging
    *
    * @param string $message
    */     
    function _log()
    {
        // write this message to file
        if(strstr($this->flag['message_handle'], 'LOG'))
        {
            error_log($this->exceptionMessage."\n\n", 3, $this->flag['logs_path'] . 'smart_error.log');
        }  
        // Print this message
        if(strstr($this->flag['message_handle'], 'SHOW') && ($this->flag['debug'] == TRUE ))
        {
            if(preg_match("/web|admin/", $this->flag['controller_type']))
            {
                echo '<pre style="font-family: Verdana, Arial, Helvetica, sans-serif;
                                  font-size: 10px;
                                  color: #990000;
                                  background-color: #CCCCCC;
                                  padding: 5px;
                                  border: thin solid #666666;">'.$this->exceptionMessage.'</pre><br />';
            }
            elseif(preg_match("/cli/", $this->flag['controller_type']))
            {
                fwrite(STDERR, $this->exceptionMessage, strlen($this->exceptionMessage));
            }
            elseif(preg_match("/xml_rpc/", $this->flag['controller_type']))
            {
                return new XML_RPC_Response(0, $GLOBALS['XML_RPC_erruser']+1, $this->exceptionMessage);
            }            
        }  
        // email this message
        if(strstr($this->flag['message_handle'], 'MAIL') && !empty($this->flag['system_email']))
        {
            $header  = "From: Smart3 System <{$this->flag['system_email']}>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
            $header .= "Content-Transfer-Encoding: 8bit";
            
            mail($this->flag['system_email'], "Smart3 System Message", $this->exceptionMessage, $header);
        }          
    }
}

class SmartExceptionLog
{
    public static function log( $e )
    {
        $message  = "EXCEPTION: ".date("Y-m-d H:i:s", time())."\n";       
        $message .= "MESSAGE: "  .$e->getMessage()."\n";
        $message .= "CODE: "     .$e->getCode()."\n"; 
        $message .= "FILE: "     .$e->getFile()."\n"; 
        $message .= "LINE: "     .$e->getLine()."\n";
        $message .= "TRACE: \n"  .var_export($e->getTrace(), TRUE)."\n";
        
        @error_log($message."\n\n", 3, $e->flag['logs_path'] . 'error.log');
    }
}

class SmartTplException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartTplException' );
    }
}

class SmartViewException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartViewException' );
    }
}

class SmartModelException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartModelException' );
    }
}

class SmartInitException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName('SmartInitException');
    }
}

class SmartContainerException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartContainerException' );
    }
}

class SmartCacheException extends SmartException
{
    public function __construct ($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartCacheException' );
    }
}

class SmartDbException extends SmartException
{
    public function __construct ($message = null, $code = 0 )
    {
        parent::__construct($message, $code);

        $this->setName( 'SmartDbException' );
    }
}

class SmartForwardAdminViewException extends Exception
{
    public $view;
    public $data;
    public $constructorData;
    public $broadcast;
    
    public function __construct ($module, $view = 'index', $data = FALSE, $constructorData = FALSE, $broadcast = FALSE)
    {
        parent::__construct(NULL,0);

        $this->broadcast = $broadcast;

        $this->view = ucfirst($module).ucfirst($view);
        $this->data = & $data;
        $this->constructorData = & $constructorData;
        ob_clean();
    }   
}

class SmartForwardPublicViewException extends Exception
{
    public $view;
    public $data;
    public $constructorData;
    
    public function __construct ($view = 'index', $data = FALSE, $constructorData = FALSE)
    {
        parent::__construct(NULL,0);

        $this->view = ucfirst($view);
        $this->data = & $data;
        $this->constructorData = & $constructorData;
        
        ob_clean();
    }   
}
?>