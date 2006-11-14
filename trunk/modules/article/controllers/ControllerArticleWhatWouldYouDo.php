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
 * ViewArticleWhatWouldYouDo class
 *
 */

class ControllerArticleWhatWouldYouDo extends JapaControllerAbstractPage
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
        // init article template variable 
        $this->viewVar['article'] = array();   
        
        // add articles which are finaly displayed
        // at the main admin page
        $this->viewVar['article']['wwyd'][] = array('article' => $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/article/cntr/addArticle/disableMainMenu/1',
                                                   'text'    => 'Add Article');
    }     
}

?>