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
 * ActionCommonRssOutput
 *
 * USAGE:
 *    $this->model->action('common','rssBuilderOutput',
                           array('format'  => (string) output|save|get,
                                 'version' => (string) 2.0|1.0|0.91,
                                 'path'    => (string)  ));
 *
 */
 
/**
 * 
 */
class ActionArticleFeedCreator extends SmartAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        switch( $data['format'] )
        {
            case 'rss':
              if($this->checkCache( $data ) == FALSE)
              {
                  $this->rss( $data);
              }
        }
    }
    /**
     */
    public function validate( & $data )
    {
        if(!isset( $data['format'] ))
        {
            throw new SmartModelException("No RSS 'format' defined");  
        }
        else
        {
            if(!preg_match("/rss|atom/", $data['format']))
            {
                throw new SmartModelException("Wrong RSS 'format' definition");  
            }   
        }

        return TRUE;
    }  
    
    private function rss( & $data )
    {
        if(!isset($this->model->rssGenerator))
        {
            include_once (SMART_BASE_DIR . 'modules/common/includes/rssgenerator/RssGenerator.php');
            $this->model->rssGenerator = new RssGenerator();
        }
        $rss = & $this->model->rssGenerator;
        
        $rss->addChannel( $data['channel']['title'],
                          $data['channel']['link'],
                          $data['channel']['desc'],
                          $data['channel']['about'] );

        if(isset($data['image']))
        {
        $rss->addImage( $data['image']['title'],
                        $data['image']['logo'],
                        $data['image']['link'] );        
        }
        if(isset($data['search']))
        {        
        $rss->addSearch( $data['search']['title'],
                         $data['search']['url'],
                         $data['search']['desc'],
                         $data['search']['var'] );
        }
        
        foreach( $data['items'] as $item )
        {
            if(isset($item['description']))
            {
                $description = $item['description'];
            }
            else
            {
                $description = '';
            }
            
            $rss->addItem( $item['title'],
                           $data['baseUrl'] . $item['id_article'],
                           $description);
        }
        
        if($data['output'] == 'save')
        {
            return $this->save( $data );
        }
        
        $data['result'] = $rss->toString();
    }
    
    private function checkCache( & $data )
    {
        $this->rssFile = SMART_BASE_DIR . 'data/article/rss/'.$data['id'].'.xml';
        $data['rssfile'] = 'data/article/rss/'.$data['id'].'.xml';

        // check expire time
        if( file_exists($this->rssFile)  )
        {
            $cachetime = filemtime($this->rssFile);

            if( ($cachetime != FALSE) && ((time() - $data['expire']) < $cachetime)  )
            {
                return TRUE;
            } 
        }
        
        return FALSE;
    }
    
    private function save()
    {
        $cacheFileTmp = $this->rssFile.'.'.getmypid();
        $cachefp = fopen($cacheFileTmp, 'w');
        
        $os = strtoupper(substr(PHP_OS, 0, 3));
        
        if($cachefp)
        {
            fwrite($cachefp, $this->model->rssGenerator->toString());
            fclose($cachefp);
            if( ($os === 'WIN') && file_exists($this->rssFile)  )
            {
                unlink($this->rssFile);
            }                
            @rename($cacheFileTmp, $this->rssFile);
            
            return TRUE;
        }
        
        return FALSE;
    }    
}

?>