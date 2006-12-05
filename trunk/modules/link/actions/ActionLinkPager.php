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
 * ActionLinkPager class 
 *
 * USAGE:
 *
 * $model->action('link','pager',
 *                array('id_node'    => int,      // id node
                        'result'     => & string, // result string ref. with pager links
                        'numItems'   => int,      // total items
                        'perPage'    => int,      // items per page
                        'numPage'    => int,      // current page
                        'delta'      => int,      // delta range number
                        'url'        => string,   // the url for each link
                        'var_prefix' => string,   // prefix for the pager link var
                        'css_class'  => string    // css class for the links 
                        ));
 *
 *
 *
 */
include_once(JAPA_BASE_DIR . 'modules/common/includes/JapaPager.php');
 
class ActionLinkPager extends JapaAction
{
    /**
     * build pager links
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // check pager variables
        if(!isset($data['perPage']) || ($data['perPage'] == 0))
        {
            $data['perPage'] = 10;
        }
        if(!isset($data['numPage']) || ($data['numPage'] == 0))
        {
            $data['numPage'] = 1;
        }      
        if(!isset($data['delta']))
        {
            $data['delta'] = 5;
        }  
        if(!isset($data['css_class']))
        {
            $data['css_class'] = 'smart_pager';
        }      
    
        if(isset($data['status']))
        {
            $sql_where = " AND `status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        $table = "";

        if(isset($data['id_node']))
        {
            $where = "`id_node`={$data['id_node']}";
        }
        elseif(isset($data['search']))
        {
            $search_string = $this->model->dba->escape( $data['search'] );
            $where = "MATCH (`title`,`description`)
                      AGAINST ('{$search_string}' IN BOOLEAN MODE)";
        }        
        
        $sql = "SELECT SQL_CACHE
                    count(`id_link`) AS numLinks
                FROM 
                    {$this->config->dbTablePrefix}link_links
                WHERE
                   {$where}
                   $sql_where";
                   
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();    

        $config = array('numItems'   => (int)$row['numLinks'],
                        'perPage'    => (int)$data['perPage'],
                        'numPage'    => (int)$data['numPage'],
                        'delta'      => (int)$data['delta'],
                        'result'     => & $data['result'],
                        'url'        => (string)$data['url'],
                        'var_prefix' => (string)$data['var_prefix'],
                        'css_class'  => (string)$data['css_class']);        

        new JapaPager( $config );   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(isset($data['id_node']))
        {
            if(!is_int($data['id_node']))
            {
                throw new JapaModelException('"id_node" isnt from type int');        
            }     
        }    

        if(isset($data['search']))
        {
            if(!is_string($data['search']))
            {
                throw new JapaModelException('"search" isnt from type string');        
            }     
        }

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new JapaModelException('"status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new JapaModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
                {
                    throw new JapaModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }
       
        if(!isset($data['result']))
        {
            throw new JapaModelException('"result" isnt defined'); 
        }          
        if(!is_string($data['result']))
        {
            throw new JapaModelException('"result" isnt from type string'); 
        }    
        if(!isset($data['url']))
        {
            throw new JapaModelException('"url" isnt defined'); 
        }          
        if(!is_string($data['url']))
        {
            throw new JapaModelException('"url" isnt from type string'); 
        }   
        if(isset($data['var_prefix']))
        {
            if(!is_string($data['var_prefix']))
            {
                throw new JapaModelException('"var_prefix" isnt from type string'); 
            }  
        }          
       
        return TRUE;
    }
}

?>
