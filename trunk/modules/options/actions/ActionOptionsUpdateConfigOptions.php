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
 * ActionOptionsUpdateConfigOptions class 
 *
 */
 
class ActionOptionsUpdateConfigOptions extends JapaAction
{
    protected $tblFields_config = 
                      array('site_url'            => 'String',
                            'controllers_folder'  => 'String',
                            'styles_folder'       => 'String',
                            'views_folder'        => 'String',
                            'disable_cache'       => 'Int',
                            'recycler_time'       => 'Int',
                            'max_lock_time'       => 'Int',
                            'session_maxlifetime' => 'Int',
                            'textarea_rows'       => 'Int',
                            'server_gmt'          => 'Int',
                            'default_gmt'         => 'Int',
                            'rejected_files'      => 'String');
    /**
     * update common config values
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        // id no fields defined do nothing
        if(!is_array($data['fields']) || (count($data['fields']) == 0))
        {
            return TRUE;
        }
        
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config->dbTablePrefix}common_config
                SET
                   $fields";
        
        $this->model->dba->query($sql);                             
        
        return TRUE;
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
            if(!isset($this->tblFields_config[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }
        
        return TRUE;
    }
}

?>
