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
 * ControllerMiscAddText
 *
 */
 
class ControllerMiscAddText extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
 
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template array to fill with node data
        $this->viewVar['title'] = '';
        // Init template form field values
        $this->viewVar['error'] = array();
        
        $addtext = $this->httpRequest->getParameter('addtext', 'post', 'alnum');
        
        // add node
        if( !empty($addtext) )
        {
            if(FALSE !== ($id_text = $this->addText()))
            {
                @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/misc/cntr/editText/id_text/'.$id_text);
                exit;
            }
        }
        
        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->controllerVar['loggedUserRole'] <= 40)
        {
            $this->viewVar['showAddNodeLink'] = TRUE;
        }
        else
        {
            $this->viewVar['showAddNodeLink'] = FALSE;
        }
    }   
   /**
    * add text
    * @return int id of new text
    */    
    private function addText()
    {
        $title = trim($this->httpRequest->getParameter('addtext', 'post', 'raw'));
        
        if(!empty($title))
        {
            $this->viewVar['error'][] = 'Title is empty';
            return FALSE;
        }
        
        return $this->model->action('misc', 'addText', 
                             array('error'  => &$this->viewVar['error'],
                                   'fields' => array('title'   => JapaCommonUtil::stripSlashes(strip_tags((string)$title)),
                                                     'status'  => 1)));        
    }
}

?>