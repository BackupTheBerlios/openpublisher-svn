<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

include_once('Zend/Filter/Input.php');

class JapaHttpRequest implements JapaInterfaceRequest 
{
    private $parameters = array();
    
    public function __construct() 
    {

    }

    public function issetParameter( $name, $context = 'request' ) 
    {
        return isset($this->parameters[$context][$name]);
    }

    public function getParameter( $name, $context = 'request', $type = false, $data = false ) 
    {
        switch($context)
        {
            case 'request':
                return $this->getRequest( $name, $type, $data );
            case 'get':
                return $this->getGet( $name, $type, $data );
            case 'post':
                return $this->getPost( $name, $type, $data );    
            case 'files':
                return $this->getFiles( $name, $type, $data );
            case 'cookie':
                return $this->getCookie( $name, $type, $data );
            default:
                return null;
        }
    }

    private function getRequest( $name, $type, $data )
    {
        if(!isset($this->filterRequest))
        {
            $this->filterRequest = new Zend_Filter_Input( $_REQUEST, false );
        }
        return $this->validate( $this->filterRequest, $name, $type, $data );
    }
    
    private function getGet( $name, $type, $data )
    {
        if(!isset($this->filterGet))
        {
            $this->filterGet = new Zend_Filter_Input( $_GET, false  );
        }
        return $this->validate( $this->filterGet, $name, $type, $data );
    }
    
    private function getPost( $name, $type, $data )
    {
        if(!isset($this->filterPost))
        {
            $this->filterPost = new Zend_Filter_Input( $_POST, false  );
        }
        return $this->validate( $this->filterPost, $name, $type, $data );
    }
    
    private function getCookie( $name, $type, $data )
    {
        if(!isset($this->filterCookie))
        {
            $this->filterCookie = new Zend_Filter_Input( $_COOKIE, false  );
        }
        return $this->validate( $this->filterCookie, $name, $type, $data );
    }
    
    private function getFiles( $name, $type, $data )
    {
        if(!isset($this->filterFiles))
        {
            $this->filterFiles = new Zend_Filter_Input( $_FILES, false );
        }
        return $this->validate( $this->filterFiles, $name, $type, $data );
    }

    public function getParameterNames( $context = 'request' ) 
    {
        return array_keys($this->parameters[$context]);
    }

    public function getHeader( $name ) 
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (isset($_SERVER[$name])) 
        {
            return $_SERVER[$name];
        }
        return null;
    }
    
    public function validate( & $filter, $name, $type, $data ) 
    {
        switch( $type )
        {
            case 'int': 
                return $filter->getInt($name);
            case 'alnum': 
                return $filter->getAlnum($name);
            case 'alpha': 
                return $filter->getAlpha($name);
            case 'digits': 
                return $filter->getDigits($name);
            case 'raw': 
                return $filter->getRaw($name);
            case 'email': 
                return $filter->testEmail($name);
            case 'regex': 
                return $filter->testRegex($name, $data);
            default:
                return false;
        }
    }
    
    public function getRequestUri() 
    { 
            if (isset($_SERVER['REQUEST_URI'])) { 
                return $_SERVER['REQUEST_URI']; 
            } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) { 
                return $_SERVER['HTTP_X_REWRITE_URL']; 
            } else { 
                return false; 
            } 
    } 
    
    public function getBaseUrl() 
    { 
            $filename = basename($_SERVER['SCRIPT_FILENAME']); 
             
            if (basename($_SERVER['SCRIPT_NAME']) === $filename) { 
                $baseUrl = $_SERVER['SCRIPT_NAME']; 
            } elseif (basename($_SERVER['PHP_SELF']) === $filename) { 
                $baseUrl = $_SERVER['PHP_SELF']; 
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) { 
                $baseUrl = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility 
            } else { 
                // Backtrack up the script_filename to find the portion matching 
                // php_self
                $path    = $_SERVER['PHP_SELF'];
                if (false !== ($pos = strpos($path, '?'))) {
                    $path = (substr($path, 0, $pos));
                }
                $segs    = explode('/', trim($filename, '/'));
                $index   = $count($segs) - 1;
                $baseUrl = ' ';
                do {
                    $last = $segs[$index];
                    $baseUrl = '/' . $last . $baseUrl;
                    --$index;
                } while ((-1 < $index) && (false !== ($pos = strpos($path, $last))) && (0 != $pos));

                if ('' == $baseUrl) {
                    return false; 
                }
            } 
             
            if (null === ($requestUri = $this->getRequestUri())) { 
                return false; 
            } 
             
            // If using mod_rewrite or ISAPI_Rewrite strip the script filename 
            // out of baseUrl. $pos !== 0 makes sure it is not matching a value 
            // from PATH_INFO or QUERY_STRING 
            if ((false === ($pos = strpos($requestUri, $baseUrl))) || ($pos !== 0)) { 
                $baseUrl = dirname($baseUrl); 
            } 
         
        return rtrim($baseUrl, '/'); 

    } 
    
    public function setRequest( $name, $value )
    {
        if(!isset($this->filterRequest))
        {
            $this->filterRequest = new Zend_Filter_Input( $_REQUEST );
        }
        $this->filterRequest->_source[$name] = $value; 
        
        if(!isset($this->filterGet))
        {
            $this->filterGet = new Zend_Filter_Input( $_GET );
        }
        $this->filterGet->_source[$name] = $value; 
 
         if(!isset($this->filterPost))
        {
            $this->filterPost = new Zend_Filter_Input( $_POST );
        }
        $this->filterPost->_source[$name] = $value;        
    }
}
?>