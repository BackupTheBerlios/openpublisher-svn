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
 * Write php file with db connection array data
 *
 * USAGE:
 * $model->action( $config['base_module'],'setDbConfig', // usually 'common' is base module
 *                 array( 'dbConnect' => & array) );     // array with db connect data     
 *
 */

class ActionCommonSetDbConfig extends JapaAction
{
    /**
     * Write php file with db connection array data
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if(!$fp = @fopen($this->config->getVar('config_path') . 'dbConnect.php', 'w'))
        {
           throw new JapaModelException("Cant open file to write: ". $this->config->getVar('config_path') . "dbConnect.php");
        }
         
        $connect_str = $this->buildContent( $data );
        
        if( !@fwrite($fp, $connect_str) )
        {
            throw new JapaModelException("Cant write file: ". $this->config->getVar('config_path') . "dbConnect.php");      
        }
        
        @fclose($fp);
        
        return TRUE;
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if( !@is_writeable($this->config->getVar('config_path')) )
        {
            throw new JapaModelException("Config folder isnt writeable. Check permission on: ". $this->config->getVar('config_path'));            
        }

        return TRUE;
    }    
    
    /**
     * Build the php file content with db connect data
     *
     * @param array $data
     */    
    private function buildContent( & $data )
    {
        $str = "<?php \n\n";
        
        $str .= '$db'." = array();\n\n";
        
        $str .= '$db["dbhost"]'       ." = '{$data['dbConnect']['dbhost']}';\n";
        $str .= '$db["dbport"]'       ." = '{$data['dbConnect']['dbport']}';\n";
        $str .= '$db["dbcharset"]'    ." = '{$data['dbConnect']['charset']}';\n";
        $str .= '$db["dbuser"]'       ." = '{$data['dbConnect']['dbuser']}';\n";
        $str .= '$db["dbpasswd"]'     ." = '{$data['dbConnect']['dbpasswd']}';\n";
        $str .= '$db["dbname"]'       ." = '{$data['dbConnect']['dbname']}';\n";
        $str .= '$db["dbTablePrefix"]'." = '{$data['dbConnect']['dbtablesprefix']}';\n\n";
        
        $str .= "?>";
        
        return $str;
    }
}

?>