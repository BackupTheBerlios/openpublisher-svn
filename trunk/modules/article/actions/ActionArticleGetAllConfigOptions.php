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
 * ActionArticleGetAllConfigOptions  class 
 *
 * USAGE:
 *
 * $model->action('article','getAllConfigOptions',
 *                array('result' => & array));
 */
 
class ActionArticleGetAllConfigOptions extends JapaAction
{
    /**
     * get all article module config options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}article_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("No 'result' defined");
        }

        return TRUE;
    }
}

?>
