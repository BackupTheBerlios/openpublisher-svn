<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ControllerMiscMain class
 *
 */

class ControllerMiscMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        $this->viewVar['textes'] = array();
        $this->viewVar['error']  = array();
        // set template variable to show edit links        
        $this->viewVar['showLink'] = $this->allowModify();   
        
        // get all textes
        $this->model->action('misc', 
                             'getTextes', 
                             array('result'  => & $this->viewVar['textes'],
                                   'order'   => array('title','asc'),
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_text','status','description')));

        // assign lock var for each text
        $this->getLocks();
    }    
     /**
     * assign template variables with lock status of each node
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['textes'] as $text)
        {
            // lock the user to edit
            $result = $this->model->action('misc','lock',
                                     array('job'        => 'is_textlocked',
                                           'id_text'    => (int)$text['id_text'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['textes'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['textes'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }   
    
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] <= 40 )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }    
}

?>