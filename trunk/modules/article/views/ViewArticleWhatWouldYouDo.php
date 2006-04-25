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

class ViewArticleWhatWouldYouDo extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'whatWouldYouDo';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/article/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // init article template variable 
        $this->tplVar['article'] = array();   
        
        // add articles which are finaly displayed
        // at the main admin page
        $this->tplVar['article']['wwyd'][] = array('article' => '?mod=article&view=addArticle&disableMainMenu=1',
                                                   'text'    => 'Add Article');
    }     
}

?>