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
 * ActionLinkUpdateLink class 
 *
 * USAGE:
 * $model->action('link','updateLink',
 *                array('id_link' => int,
 *                      'fields'  => array('id_node'      => 'Int',
 *                                         'status'       => 'Int',
 *                                         'title'        => 'String',
 *                                         'description'  => 'String',
 *                                         'url'          => 'String',
                                           'hits'         => 'Int',)))
 */
 
class ActionLinkUpdateLink extends JapaAction
{
    /**
     * Allowed link fields and its type
     */
    protected $tblFields_link = array('id_link'     => 'Int',
                                      'id_node'     => 'Int',
                                      'status'      => 'Int',
                                      'title'       => 'String',
                                      'description' => 'String',
                                      'url'         => 'String',
                                      'hits'        => 'Int');
                                      
    /**
     * update link
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "
            UPDATE {$this->config->dbTablePrefix}link_links
                SET
                   $fields
                WHERE
                `id_link`={$data['id_link']}";
        
        $this->model->dba->query($sql);                               
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_link[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }
        
        if(isset($data['fields']['id_node']))
        {
            if(!is_int($data['fields']['id_node']))
            {
                throw new JapaModelException("'id_node' isnt from type int");
            }        
            elseif($data['fields']['id_node'] == 0)
            {
                $data['error'][] = "'id_node' can not be 0";
                return FALSE;
            }  
        }  
        
        if(!is_int($data['id_link']))
        {
            throw new JapaModelException('"id_link" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
