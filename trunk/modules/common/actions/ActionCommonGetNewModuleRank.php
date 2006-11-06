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
 * ActionCommonGetNewModuleRank class 
 *
 * USAGE:
 *
 * $model->action('common','getNewModuleRank');
 */

class ActionCommonGetNewModuleRank extends JapaAction
{
    /**
     * get last rank of articles of a given id_node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `rank`
            FROM
                {$this->config['dbTablePrefix']}common_module
            ORDER BY `rank` DESC
            LIMIT 1";
    
        $rs = $this->model->dba->query($sql);
        
		if($rs->numRows() > 0)
        {
		  	$row = $rs->fetchAssoc(); 
		  	return $row['rank'] + 1;
		}
        
        return 0;
    } 
}

?>
