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
class JapaRouterAjax extends JapaRouter
{

    public function getBase()
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
    
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }
    
    protected function run()
    {
        $this->application_controller = 'JapaControllerAjaxApplication';
    }
}

?>