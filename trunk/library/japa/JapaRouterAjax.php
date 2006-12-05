<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * JapaRouter Ajax
 *
 *
 */
class JapaRouterAjax extends JapaRouter
{

    public function getBase()
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
    
    protected function run()
    {
        $this->application_controller = 'JapaControllerAjaxApplication';
    }
}

?>