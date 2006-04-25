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
 * Php engine Smart template container class
 *
 * Here we use php as template language
 */
 
class SmartTplContainerPhp extends SmartTplContainer
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
        // get reference of the template variables
        $tpl = & $this->vars;
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
                throw new SmartTplException("Template php constructs not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
            }
        }
        
        if ( !@file_exists( $template ) )
        {
            throw new SmartTplException("Template dosent exists: ".$template);
        }
        
        ob_start();
        include( $template );
        $this->tplBufferContent = ob_get_clean();      
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

        foreach($analyzer->usedVariables as $key => $val)
        {
            if(in_array($key, $this->config['disallowedVariables']))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = FALSE;
            }
        } 

        return $code_is_valide;
    } 
}

?>