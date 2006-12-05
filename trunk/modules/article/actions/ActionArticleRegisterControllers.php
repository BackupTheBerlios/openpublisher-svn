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
 * ActionArticleRegisterControllers
 *
 * USAGE:
 *
 * $model->action('article','registerControllers',
 *                array('action'  => string,    // 'register' or 'unregister'
 *                      'id_controller' => int,       // if action = 'unregister'
 *                      'name'    => string))   // if action = 'register'
 *
 */
 
class ActionArticleRegisterControllers extends JapaAction
{
    /**
     * register/unregister a node related view
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if($data['action'] == 'register')
        {
            $sql = "
                SELECT `id_controller` FROM {$this->config->dbTablePrefix}article_public_controller
                 WHERE `name`='{$data['name']}'";

            $rs = $this->model->dba->query($sql);    
      
            if($rs->numRows() == 0)
            {    
                $sql = "
                    INSERT INTO {$this->config->dbTablePrefix}article_public_controller
                     (`name`)
                    VALUES
                     ('{$data['name']}')";

                $rs = $this->model->dba->query($sql);
            }
        }
        elseif($data['action'] == 'unregister')
        {
            $sql = "
                DELETE FROM {$this->config->dbTablePrefix}article_public_controller
                WHERE
                   `id_controller`={$data['id_controller']}";

            $this->model->dba->query($sql);
            
            $sql = "
                DELETE FROM {$this->config->dbTablePrefix}article_node_controller_rel
                WHERE
                   `id_controller`={$data['id_controller']}";

            $this->model->dba->query($sql);            
        }        
    } 
    
    public function validate( $data = FALSE )
    {
        if( !isset($data['action']) || !is_string($data['action']) )
        {        
            throw new JapaModelException ('"action" isnt defined or isnt from type string'); 
        }
        
        if(($data['action'] != 'register') && ($data['action'] != 'unregister'))
        {
            throw new JapaModelException ('"action" value must be "register" or "unregister"');         
        }

        if( ($data['action'] == 'register') && (!isset($data['name']) || empty($data['name'])))
        {
            throw new JapaModelException ('"name" isnt defined or is empty');                 
        }
        elseif( ($data['action'] == 'unregister') && (!isset($data['id_controller']) || !is_int($data['id_controller'])))
        {
            throw new JapaModelException ('"id_controller" isnt defined or is not from type int');                 
        }        
        return TRUE;
    }
}

?>
