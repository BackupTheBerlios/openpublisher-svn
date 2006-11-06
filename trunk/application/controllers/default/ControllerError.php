<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ViewError class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ControllerError extends JapaControllerAbstractPage
{
     /**
     * Template of this view
     * @var string $template
     */
    public $template = 'error';

    /**
     * Does nothing. The end user error page
     * is static.
     *
     */
    function perform( $data = FALSE )
    {
        // template var with charset used for the html pages
        $this->viewVar['charset'] = $this->config['charset'];

        // assign error message template var
        $this->viewVar['message'] = & $this->viewData;
    }
}

?>