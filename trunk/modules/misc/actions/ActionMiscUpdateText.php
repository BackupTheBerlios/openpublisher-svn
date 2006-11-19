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
 * ActionMiscUpdateText class 
 *
 * USAGE:
 * $model->action('misc','updateText',
 *                array('id_text' => int,
 *                      'fields'  => array('status'       => 'Int',
 *                                         'format'       => 'Int',
 *                                         'media_folder' => 'String',
 *                                         'lang'         => 'String',
 *                                         'title'        => 'String',
 *                                         'description'  => 'String',
 *                                         'body'         => 'String')))
 *
 */
 
class ActionMiscUpdateText extends JapaAction
{
    /**
     * Fields and the format of each of the db table
     *
     */
    protected $tblFields_text = 
                      array('id_text'      => 'Int',
                            'status'       => 'Int',
                            'format'       => 'Int',
                            'media_folder' => 'String',
                            'title'        => 'String',
                            'description'  => 'String',
                            'body'         => 'String');
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
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
            UPDATE {$this->config['dbTablePrefix']}misc_text
                SET
                   $fields
                WHERE
                `id_text`={$data['id_text']}";
        
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
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_text[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['id_text']))
        {
            throw new JapaModelException('"id_text" isnt defined');        
        }

        if(!is_int($data['id_text']))
        {
            throw new JapaModelException('"id_text" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
