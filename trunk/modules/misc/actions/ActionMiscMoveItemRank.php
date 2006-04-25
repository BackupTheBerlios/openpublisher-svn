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
 * ActionMiscMoveItemRank class 
 *
 * move rank of misc text pictures or files
 *
 * USAGE:
 *
 * $model->action('misc','moveItemRank',
 *                array('id_pic'  => int,     // one of both
 *                      'id_file' => int,     //
 *                      'dir'     => string)) // 'up' or 'down'
 *
 */
class ActionMiscMoveItemRank extends SmartAction
{                          
    /**
     * 
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        // set table name and item reference
        if(isset($data['id_file']))
        {
            $this->table = 'misc_text_file';
            $this->id_item = 'id_file';
        }
        else
        {
            $this->table = 'misc_text_pic';
            $this->id_item = 'id_pic';
        }
        
        // switch to dir methode
        switch($data['dir'])
        {
            case 'up':
                    $this->up($data);
                break;
            case 'down':
                    $this->down($data);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if( !isset($data['id_text']) )
        {        
            throw new SmartModelException ('"id_text" must be defined'); 
        } 
        elseif(!is_int($data['id_text'])  )
        {        
            throw new SmartModelException ('"id_text" must be an integer'); 
        } 
        
        if( !isset($data['id_file']) && !isset($data['id_pic']) )
        {        
            throw new SmartModelException ('"id_file" or "id_pic" must be defined'); 
        }
        
        if( isset($data['id_file']) && !is_int($data['id_file'])  )
        {        
            throw new SmartModelException ('"id_file" must be an integer'); 
        }  
        
        if( isset($data['id_pic']) && !is_int($data['id_pic'])  )
        {        
            throw new SmartModelException ('"id_pic" must be an integer'); 
        }  
        
        if( !isset($data['dir']) )
        {        
            throw new SmartModelException ('"dir" must be defined'); 
        }  
        elseif( ($data['dir'] != 'up') && ($data['dir'] != 'down'))
        {        
            throw new SmartModelException ('"dir" value must be "up" or "down"'); 
        }   
        
        return TRUE;
    }
    /**
     * move file rank up
     *
     * @param array $data
     */  
    private function up($data)
    {
        // get rank of neighbour file
        $sql = "SELECT 
                    `rank`-1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_text={$data['id_text']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // move rank of neighbour file
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET `rank`=`rank`+1
            WHERE
                `rank`={$row['rank']}
            AND
                `id_text`={$data['id_text']}";

        $this->model->dba->query($sql);   

        if($this->model->dba->affectedRows() == 1)
        {
            // update the file rank to move
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`-1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_text`={$data['id_text']}";

            $this->model->dba->query($sql);           
        }
    }
    /**
     * move file rank down
     *
     * @param array $data
     */     
    private function down(&$data)
    {
         // get rank of neighbour file
        $sql = "SELECT 
                    rank+1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_text={$data['id_text']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // move rank of neighbour file
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET rank=rank-1
            WHERE
                rank={$row['rank']}
            AND
                id_text={$data['id_text']}";

        $this->model->dba->query($sql);   
        
        if($this->model->dba->affectedRows() == 1)
        {
            // update the file rank to move
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`+1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_text`={$data['id_text']}";

            $this->model->dba->query($sql);           
        } 
    }    
}

?>
