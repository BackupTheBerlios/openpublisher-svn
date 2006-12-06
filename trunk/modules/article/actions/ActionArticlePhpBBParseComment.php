<?php
// ----------------------------------------------------------------------
// Japa3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionArticleBBParseComment class 
 * USAGE:
 *
 * $model->action('article','parseComments',
                  array('content' => & (string)));
 *
 */

// needed for error checking
require_once JAPA_LIBRARY_DIR . 'PEAR/PEAR.php';
// base class
require_once JAPA_MODULES_DIR . 'common/includes/PEAR/Text/BBCodeParser.php';

class ActionArticlePhpBBParseComment extends JapaAction
{
    /**
     * phpBB parser
     *
     * @param array $data
     * @return bool true or false on error
     * @todo Options caching + customizing option values
     */
    public function perform( $data = FALSE )
    {
        if( !isset($this->model->phpBBParser) || !is_object($this->model->phpBBParser) )
        {
            $options = HTML_BBCodeParser::parseIniFile(JAPA_MODULES_DIR . 'common/includes/PEAR/Text/BBCodeParser_V2.ini');
            // set system charset
            $options['format']['Xhtml']['charset'] = $this->config->getModuleVar('common','charset');
            $this->model->phpBBParser = new HTML_BBCodeParser($options);
        }

        $this->model->phpBBParser->setText( $data['content'] );
        $this->model->phpBBParser->parse();
        $data['content'] = $this->model->phpBBParser->getParsed();
    }    
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        return TRUE;
    }
}

?>
