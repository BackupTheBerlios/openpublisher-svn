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
 * ActionArticleGetArticle class 
 *
 * USAGE:
 * $model->action('article','getArticle',
 *                array('id_article' => int, 
 *                      'result'     => & array, 
 *                      'status'     => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'get_view'   => bool,
 *                      'fields      => array('id_node','id_article','status','rank',
 *                                            'activedate','inactivedate','pubdate',
 *                                            'lang','title','overtitle',
 *                                            'subtitle','header','description',
 *                                            'body','ps','fulltextbody',
 *                                            'format','media_folder') ));
 *
 */


include_once 'Text/Diff.php';
//include_once 'Text/Diff/Renderer.php';
include_once 'Text/Diff/Renderer/inline.php';

class ActionArticleCreateDiff extends JapaAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'articledate'  => 'String',
                                         'pubdate'      => 'String',
                                         'changedate'   => 'String',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'fulltextbody' => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String',
                                         'allow_comment' => 'Int',
                                         'close_comment' => 'Int',
                                         'timezone'      => 'Int');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                *
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_article`={$data['id_article']}";
        
        $res = $this->model->dba->query($sql);
        
        if($res->numRows() == 0)
        {
            throw new JapaModelException('No article with id: '.$data['id_article']);
        }
        
        $old_article = $res->fetchAssoc();
        
        $this->createDiff( $old_article, $data['new_article'], $data['result'] ); 
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt defined');        
        }
        elseif(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }
        
        return TRUE;
    }
    
    private function createDiff( & $old_article, & $new_article, &$result )
    {
        $old = "";
        $new = "";
        
        foreach( $old_article as $key => $value )
        {
            if(isset($new_article[$key]))
            {
                $v1 = preg_split("[\n\r]",$value);
                $v2 = preg_split("[\n\r]",$new_article[$key]);

                /* Create the Diff object. */
                $diff = new Text_Diff($v1, $v2);                
    
                /* Output the diff in unified format.*/ 
                $renderer = new Text_Diff_Renderer_inline();
                $result[] = $renderer->render($diff);
            }
        }
    }
}

?>
