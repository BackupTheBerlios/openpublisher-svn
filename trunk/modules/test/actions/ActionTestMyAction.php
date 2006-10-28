<?php

class ActionTestMyAction extends JapaAction
{
    public function perform( $data = false )
    {
        $data['result'] = 'This message comes from the module "test" action "myAction"';
    }   
    public function validate( $data = false )
    {
        return true;
    }  
}

?>