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
 * ViewMiscMain class
 *
 */

class ViewMiscMain extends JapaControllerAbstractPage
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'main';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/misc/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        $this->tplVar['textes'] = array();
        $this->tplVar['error']  = array();
        // set template variable to show edit links        
        $this->tplVar['showLink'] = $this->allowModify();   
        
        // get all textes
        $this->model->action('misc', 
                             'getTextes', 
                             array('result'  => & $this->tplVar['textes'],
                                   'order'   => array('title','asc'),
                                   'error'   => & $this->tplVar['error'],
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
        
        foreach($this->tplVar['textes'] as $text)
        {
            // lock the user to edit
            $result = $this->model->action('misc','lock',
                                     array('job'        => 'is_textlocked',
                                           'id_text'    => (int)$text['id_text'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['textes'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['textes'][$row]['lock'] = FALSE;  
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
        if($this->viewVar['loggedUserRole'] <= 40 )
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