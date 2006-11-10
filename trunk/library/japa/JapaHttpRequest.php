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

    public function getParameter( $name, $context = 'request', $type = false ) 
    {
        switch($context)
        {
            case 'request':
                return $this->getRequest( $name, $type );
            case 'get':
                return $this->getGet( $name, $type );
            case 'post':
                return $this->getPost( $name, $type );    
            case 'files':
                return $this->getFiles( $name, $type );
            case 'cookie':
                return $this->geCookie( $name, $type );
            default:
                return null;
        }
    }

    private function getRequest( $name, $type )
    {
        if(!isset($this->filterRequest))
        {
            $this->filterRequest = new Zend_Filter_Input( $_REQUEST );
        }
        return $this->validate( $this->filterRequest, $name, $type );
    }
    
    private function getGet( $name, $type )
    {
        if(!isset($this->filterGet))
        {
            $this->filterGet = new Zend_Filter_Input( $_GET );
        }
        return $this->validate( $this->filterGet, $name, $type );
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
    
    public function validate( $filter, $name, $type ) 
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
}
?>