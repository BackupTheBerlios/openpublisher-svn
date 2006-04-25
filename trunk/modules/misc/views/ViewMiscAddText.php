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
 * ViewMiscAddText
 *
 */
 
class ViewMiscAddText extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'addtext';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/misc/templates/';
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template array to fill with node data
        $this->tplVar['title'] = '';
        // Init template form field values
        $this->tplVar['error'] = array();
        
        // add node
        if( isset($_POST['addtext']) )
        {
            if(FALSE !== ($id_text = $this->addText()))
            {
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=misc&view=editText&id_text='.$id_text);
                exit;
            }
        }
        
        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->viewVar['loggedUserRole'] <= 40)
        {
            $this->tplVar['showAddNodeLink'] = TRUE;
        }
        else
        {
            $this->tplVar['showAddNodeLink'] = FALSE;
        }
    }   
   /**
    * add new node
    *
    * @param int $id_parent parent node of the new node
    */    
    private function addText()
    {
        if(!isset($_POST['title']) || empty($_POST['title']))
        {
            $this->tplVar['error'][] = 'Title is empty';
            return FALSE;
        }
        
        return $this->model->action('misc', 'addText', 
                             array('error'  => &$this->tplVar['error'],
                                   'fields' => array('title'   => SmartCommonUtil::stripSlashes(strip_tags((string)$_POST['title'])),
                                                     'status'  => 1)));        
    }
}

?>