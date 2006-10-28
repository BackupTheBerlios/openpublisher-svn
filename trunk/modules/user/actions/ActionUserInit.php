<?php

class ActionUserInit extends JapaAction
{
    public function perform( $data = false )
    {
        $this->config['initModuleConfig']['user'] =  
                      "Hi from of the 'user' module. <br>See:  
                      /modules/user/action/ActionUserInit.php";
    }  
    public function validate( $data = false )
    {
        return true;
    }  
}

?>