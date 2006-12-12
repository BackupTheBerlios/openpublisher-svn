<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * JapaRouter
 *
 *
 */
class JapaRouterWeb extends JapaRouter
{
    protected $_rewriteBase = null;
    
    public function _old_getBase()
    {
        // Set magic default of RewriteBase:
        $filename = basename($_SERVER['SCRIPT_FILENAME']);
        $base = $_SERVER['SCRIPT_NAME'];
        if (strpos($_SERVER['REQUEST_URI'], $filename) === false)
        {
            // Default of '' for cases when SCRIPT_NAME doesn't contain a filename (ZF-205)
            $base = (strpos($base, $filename) !== false) ? dirname($base) : '';
        }
        return rtrim($base, '/');
    }

    public function getBase()
    {
        if ($this->config->getVar('rewrite_base') === null)
        {
            $this->_rewriteBase = $this->_getBase();
        }
        return $this->config->getVar('rewrite_base');
    }

    private function _getBase()
    {
        $base = '';
        if (empty($_SERVER['PATH_INFO'])) $base = $_SERVER['REQUEST_URI'];
        else if ($pos = strpos($_SERVER['REQUEST_URI'], $_SERVER['PATH_INFO'])) {
            $base = substr($_SERVER['REQUEST_URI'], 0, $pos);
        }
        return rtrim($base, '/');
    }
    
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }
    
    public function redirect( $path = '' )
    {
        @header('Location: '.$this->getBase().'/'.$path);
        exit;  
    }
    
    protected function run()
    {
        $path = explode('/', trim($_SERVER['REQUEST_URI'],'/'));

        foreach ($path as $pos => $pathPart)
        {
            $next_pos = $pos + 1;
            
            if (preg_match("/^[a-z0-9\-\._]+$/i", $pathPart)) 
            {
                if(in_array($pathPart, $this->applicationControllers))
                {
                    $this->application_controller = 'JapaController' . $pathPart . 'Application';
                }
                elseif(isset($path[$next_pos]))
                {
                    $this->request[$pathPart] = $path[$next_pos];
                    $_GET[$pathPart]          = $path[$next_pos];
                    $_REQUEST[$pathPart]      = $path[$next_pos];
                }
            }
        }
    }
}

?>