<?php

class ActionTestInit extends JapaAction
{
    public function perform( $data = false )
    {
        $this->config['initModuleConfig']['test'] = 
                      "Hi from of the 'test' module. <br>See:  
                      /modules/test/action/ActionTestInit.php";
    }   
    public function validate( $data = false )
    {
        return true;
    }  
}

?>