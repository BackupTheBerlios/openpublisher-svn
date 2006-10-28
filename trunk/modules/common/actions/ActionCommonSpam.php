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
 * ActionCommonAkismetSpamDetector
 *
 * USAGE:
 *
 * $isCommentSpam = $this->model->action('common', 'spamDetector',
 *                      array('url'       => string
 *                            'key'       => string
 *                            'permaLink' => string
 *                            'user'      => array('name'    => string
 *                                                 'email'   => string
 *                                                 'url'     => string
 *                                                 'comment' => string)) );
 *
 */

/**
 * 
 */
class ActionCommonAkismetSpamDetector extends SmartAction
{
    /**
     * check if a comment is spam through Akismet
     *
     * @param mixed $data Data passed to this action
     * @return bool TRUE if comment is spam else FALSE
     */
    public function perform( $data = FALSE )
    {
        include_once(SMART_BASE_DIR . 'modules/common/includes/Akismet.class.php');
        $akismet = new Akismet(          $data['url'], $data['key']);
        
        $akismet->setCommentAuthor(      $data['user']['name']);
        $akismet->setCommentAuthorEmail( $data['user']['email']);
        $akismet->setCommentAuthorURL(   $data['user']['url']);
        $akismet->setCommentContent(     $data['user']['comment']);
        $akismet->setPermalink(          $data['permaLink']);
        
        return $akismet->isCommentSpam();
    }  
}

?>