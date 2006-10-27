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
 * ViewError class
 */

class ViewCommonError extends JapaControllerAbstractPage
{
     /**
     * Template of this view
     * @var string $template
     */
    public $template = 'error';

     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/common/templates/';

    /**
     * The end user error view.
     *
     */
    function perform()
    {
        // assign template error var
        $this->tplVar['error'] = & $this->viewData;
    }
}

?>