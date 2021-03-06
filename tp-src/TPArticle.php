<?php
/**
 * @package TinyPortal
 * @version 2.0.0
 * @author tinoest - http://www.tinyportal.net
 * @founder Bloc
 * @license MPL 2.0
 *
 * The contents of this file are subject to the Mozilla Public License Version 2.0
 * (the "License"); you may not use this package except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Copyright (C) 2018 - The TinyPortal Team
 *
 */

if (!defined('SMF'))
	die('Hacking attempt...');


class TPArticle extends TPBase 
{

    private $dBStructure = array();

    public function __construct() {{{
        parent::__construct();

        $this->dBStructure = array ( 
            'id'            => 'int',
            'date'          => 'int',
            'body'          => 'string',
            'intro'         => 'string',
            'useintro'      => 'int',
            'category'      => 'int',
            'frontpage'     => 'int',
            'subject'       => 'string',
            'author_id'     => 'int',
            'author'        => 'string',
            'frame'         => 'string',
            'approved'      => 'int',
            'off'           => 'int',
            'options'       => 'string',
            'parse'         => 'int',
            'comments'      => 'int',
            'comments_var'  => 'string',
            'views'         => 'int',
            'rating'        => 'string',
            'voters'        => 'string',
            'id_theme'      => 'int',
            'shortname'     => 'string',
            'sticky'        => 'int',
            'fileimport'    => 'string',
            'topic'         => 'int',
            'locked'        => 'int',
            'illustration'  => 'string',
            'headers'       => 'string',
            'type'          => 'string',
            'featured'      => 'int',
            'pub_start'     => 'int',
            'pub_end'       => 'int',
        );

    }}}

    public function getArticle($article) {{{

        if(empty($article)) {
            return;
        }

        $now        = time();
        if(is_array($article)) {
            $where      = 'art.id IN ({array_string:page})';
        }
        else {
            $where      = is_numeric( $article ) ? 'art.id = {int:page}' : 'art.shortname = {string:page}';
            $article    = is_numeric( $article ) ? (int)$article : $article;
        }

        $request    = $this->dB->db_query('', '
            SELECT 
                art.*, art.author_id AS author_id, art.id_theme AS id_theme, var.value1, var.value2,
                var.value3, var.value4, var.value5, var.value7, var.value8, art.type AS rendertype, mem.email_address AS email_address,
                COALESCE(mem.real_name,art.author) AS real_name, mem.avatar, mem.posts, mem.date_registered AS date_registered, mem.last_login AS last_login,
                COALESCE(a.id_attach, 0) AS id_attach, a.filename, a.attachment_type AS attachement_type, var.value9, mem.email_address AS email_address
            FROM {db_prefix}tp_articles AS art
            LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = art.author_id)
            LEFT JOIN {db_prefix}attachments AS a ON (a.id_member = art.author_id AND a.attachment_type != 3)
            LEFT JOIN {db_prefix}tp_variables AS var ON (var.id= art.category)
            WHERE '. $where . 
            (
                !allowedTo( 'tp_articles' ) ? '
                    AND ((art.pub_start = 0 AND art.pub_end = 0)
                    OR (art.pub_start != 0 AND art.pub_start < '.$now.' AND art.pub_end = 0)
                    OR (art.pub_start = 0 AND art.pub_end != 0 AND art.pub_end > '.$now.')
                    OR (art.pub_start != 0 AND art.pub_end != 0 AND art.pub_end > '.$now.' AND art.pub_start < '.$now.')) ' 
                : ' ' 
            ),
            array ( 
                'page' => $article
            )
        );

        $articles = array();

        if($this->dB->db_num_rows($request) > 0) {
            while ( $row = $this->dB->db_fetch_assoc($request)) {
                $articles[] = $row;
            }
            $this->dB->db_free_result($request);
        }

        return $articles;

    }}}

    public function getArticleComments($user_id, $item_id) {{{
       return parent::getComments('1', $user_id, $item_id);
    }}}

    public function getArticleComment($comment_id) {{{
       return parent::getComment($comment_id, 'article_comment');
    }}}

    public function insertArticleComment($user_id, $item_id, $comment, $title) {{{
        return parent::insertComment('1', $user_id, $item_id, $comment, $title);
    }}}

    public function deleteArticleComment($comment_id) {{{
        return parent::deleteComment($comment_id, 'article_comment');
    }}}

    public function updateArticleViews($article_id) {{{

        // update views
        $this->dB->db_query('', '
            UPDATE {db_prefix}tp_articles
            SET views = views + 1
            WHERE ' . (is_numeric($article_id) ? 'id = {int:page}' : 'shortname = {string:page}'),
            array (
                'page' => $article_id
            )
        );

    }}}

    public function updateArticle($article_id, $article_data) {{{

        $update_data = $article_data;
        array_walk($update_data, function(&$update_data, $key) {
                $update_data = $key.' = {'.$this->dBStructure[$key].':'.$key.'}';
            }
        );
        $update_query = implode(', ', array_values($update_data));
        $article_data['article_id'] = (int)$article_id;
        $this->dB->db_query('', '
            UPDATE {db_prefix}tp_articles
            SET '.$update_query.'
            WHERE id = {int:article_id}',
            $article_data
        );

    }}}

    public function insertArticle($article_data) {{{
        $insert_data = array();
        foreach(array_keys($article_data) as $key) {
            $insert_data[$key] = $this->dBStructure[$key];
        }

        $this->dB->db_insert('INSERT',
            '{db_prefix}tp_articles',
            $insert_data,
            array_values($article_data),
            array ('id')
        );
			
        return $this->dB->db_insert_id('{db_prefix}tp_articles', 'id');

    }}}

    public function deleteArticle($article_id) {{{
			$this->dB->db_query('', '
				DELETE FROM {db_prefix}tp_articles
				WHERE id = {int:article_id}',
				array (
                    'article_id' => $article_id
                )
			);
    }}}

    public function getTotalAuthorArticles($author_id, $off = false, $approved = true) {{{

        $where          = '';
        $num_articles   = 0;

        if($off == true) {
            $where .= ' AND off = 0 ';
        }

        if($approved == false) {
            $where .= ' AND approved = 0 ';
        }

        $request        = $this->dB->db_query('', '
            SELECT COUNT(id) AS articles FROM {db_prefix}tp_articles
            WHERE author_id = {int:author}
            '.$where,
            array(
                'author' => $author_id
            )
        );
        if($this->dB->db_num_rows($request) > 0) {
            $num_articles = $this->dB->db_fetch_assoc($request)['articles'];
            $this->dB->db_free_result($request);
        }

        return $num_articles;
    }}}

    public function getTotalArticles( $group = '' ) {{{
        $num_articles   = 0;
        $now            = time();

		$request =  $this->dB->db_query('', '
			SELECT COUNT(art.id) AS num_articles
			FROM {db_prefix}tp_articles AS art
            INNER JOIN  {db_prefix}tp_variables AS var
			ON var.id = art.category
			WHERE art.off = 0
			' . $group . '
			AND art.category > 0
			AND ((art.pub_start = 0 AND art.pub_end = 0)
			OR (art.pub_start != 0 AND art.pub_start < '.$now.' AND art.pub_end = 0)
			OR (art.pub_start = 0 AND art.pub_end != 0 AND art.pub_end > '.$now.')
			OR (art.pub_start != 0 AND art.pub_end != 0 AND art.pub_end > '.$now.' AND art.pub_start < '.$now.'))
			AND art. approved = 1
			AND art.frontpage = 1'
		);

        if($this->dB->db_num_rows($request) > 0) {
            $num_articles = $this->dB->db_fetch_assoc($request)['num_articles'];
            $this->dB->db_free_result($request);
        }
       
        return $num_articles;
    }}}

    public function getArticlesInCategory( $category ) {{{

        if(is_array($category)) {
            $where = 'category IN ({array_int:cat})';
        }
        else {
            $where = 'category = {int:cat}';
        }

        $articles   = array();
        $request    =  $this->dB->db_query('', '
            SELECT id, subject, date, category, author_id, shortname, author
            FROM {db_prefix}tp_articles
            WHERE 1=1 AND '.$where.'
            ORDER BY date DESC',
            array(
                'cat' => $category,
            )
        );

        if($this->dB->db_num_rows($request) > 0) {
            while($row = $this->dB->db_fetch_assoc($request)) {
                if(empty($row['shortname'])) {
                    $row['shortname'] = $row['id'];
                }
                $articles[] = $row;
            }
        }
        $this->dB->db_free_result($request);

        return $articles; 

    }}}

}

?>
