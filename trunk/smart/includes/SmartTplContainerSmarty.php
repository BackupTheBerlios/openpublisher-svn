<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smarty engine template container class
 *
 * Here we use Smarty as template language
 */

define('SMARTY_DIR', SMART_BASE_DIR . 'smart/includes/smarty/');
include_once(SMARTY_DIR . 'Smarty.class.php');
 
class SmartTplContainerSmarty extends SmartTplContainer
{
    /**
     * Tokens found
     */                                    
    private $disallowedItems = array();
                                    
    /**
     * render the template
     *
     */
    public function renderTemplate()
    {
        if(!isset($this->viewVar['smarty']))
        {
            $this->viewVar['smarty'] = new Smarty;
            $this->viewVar['smarty']->compile_dir  = SMART_BASE_DIR . 'cache/smartyCompiled/';
            $this->viewVar['smarty']->template_dir = SMART_BASE_DIR . $this->templateFolder;
            $this->viewVar['smarty']->config_dir   = SMART_BASE_DIR . 'config/';
            $this->viewVar['smarty']->php_handling = SMARTY_PHP_QUOTE;
        }

        // assign smarty template variables
        $this->viewVar['smarty']->assign_by_ref("tpl", $this->vars);

        // get reference of the view loader methode to include
        // nested views in templates
        $viewLoader = & $this->viewLoader;

        // build the whole file path to the TEMPLATE file
        $template = SMART_BASE_DIR . $this->templateFolder . 'tpl.' . $this->template . '.php';
        
        if($this->config['useCodeAnalyzer'] == TRUE)
        {
            $this->disallowedItems = array();
            
            if(FALSE == $this->analyze($template))
            {
                throw new SmartTplException("Template php function(s) not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
            }
        }
        
        if ( !@file_exists( $template ) )
        {
            throw new SmartTplException("Template dosent exists: ".$template);
        }

        $content = $this->viewVar['smarty']->fetch( 'tpl.' . $this->template . '.php' );
        $this->write($content, $this->viewVar['smarty']->compile_dir.$this->template.'.php');
        
        ob_start();
        include($this->viewVar['smarty']->compile_dir.$this->template.'.php');
        $this->tplBufferContent = ob_get_clean(); 
    } 

    /**
     * Write smarty compiled template
     *
     * @param array $data
     */
    public function write( &$content , $d)
    {
        if(!$fp = @fopen($d, 'w'))
        {
           throw new SmartModelException("Cant open file to write: ". $d);
        }
         
        
        if( !@fwrite($fp, $content) )
        {
            throw new SmartModelException("Cant write file: ". $d);      
        }
        
        @fclose($fp);
        
        return TRUE;
    }
    
    /**
     * analyze allowed php tokens in template 
     *
     */
    private function analyze( & $template )
    {
        $code_is_valide = TRUE;

        include_once(SMART_BASE_DIR . "smart/includes/phpca/PHPCodeAnalyzer.php");
        $analyzer = new PHPCodeAnalyzer();
        $analyzer->source = file_get_contents( $template );
        $analyzer->analyze();
        
        foreach($analyzer->calledConstructs as $key => $val)
        {
            if(!in_array($key, $this->config['allowedConstructs']))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = FALSE;
            }
        }  

        return $code_is_valide;
    } 
}

?>