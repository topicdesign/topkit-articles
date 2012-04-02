<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Category Class
 */
class Category extends ActiveRecord\Model
{
    # explicit table name  
    static $table_name = 'categories';

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
            'parent',
            'class_name' => 'Category',
            'foreign_key' => 'parent_category_id'
        )
    );

    static $has_many = array(
        array('articles'),
        array(
            'children',
            'class_name' => 'Category',
            'foreign_key' => 'parent_category_id'
        )
    );
    
    // --------------------------------------------------------------------
    // Validations
    // --------------------------------------------------------------------
    
    // --------------------------------------------------------------------
    // Setter/Getter Methods
    // --------------------------------------------------------------------

}

/**
 * SQL for table
 
 CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL DEFAULT '',
  `parent_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

**/

/* End of file Category.php */
/* Location: ./application/models/Category.php */
