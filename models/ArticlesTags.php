<?php

namespace Article;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sample
 *
 * @package     CodeIgniter
 * @subpackage  Models
 * @author      Topic Deisgn
 * @license     http://creativecommons.org/licenses/BSD/
 */
class ArticlesTags extends \ActiveRecord\Model {

    # explicit table name
    static $table_name = 'articles_tags';

    # explicit pk
    //static $primary_key = '';

    # explicit connection name
    //static $connection = '';

    # explicit database name
    //static $db = '';

    // --------------------------------------------------------------------
    // Associations
    // --------------------------------------------------------------------

    static $belongs_to = array(
        array(
            'tag',
            'class_name'    => '\Tag',
        ),
        array('article'),
    );

    // --------------------------------------------------------------------
    // Validations
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
}

/**
 * SQL for table

CREATE TABLE `articles_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

**/

// --------------------------------------------------------------------
/* End of file ArticlesTags.php */
/* Location: ./application/models/ArticlesTags.php */
