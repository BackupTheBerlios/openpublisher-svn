<?php

class ActionDefaultInit extends JapaAction
{
    public function perform( $data = false )
    {
        $this->config['initModuleConfig']['default'] = 
                      "Hi from of the 'default' module. <br>See:  
                      /modules/default/action/ActionDefaultInit.php";
    }   
    public function validate( $data = false )
    {
        return true;
    }  
}

?>