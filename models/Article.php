<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Article
 *
 * @package     CodeIgniter
 * @subpackage  Models
 * @author      Topic Deisgn
 * @license     http://creativecommons.org/licenses/BSD/
 */
class Article extends ActiveRecord\Model {

    # explicit table name
    static $table_name = 'articles';

    # explicit pk
    //static $primary_key = '';

    # explicit connection name
    //static $connection = '';

    # explicit database name
    //static $db = '';

    static $before_save = array('generate_slug');

    // --------------------------------------------------------------------
    // Associations
    // --------------------------------------------------------------------

    static $belongs_to = array(
        array(
            'category',
            'class_name' => 'Category'
        )
    );

    static $has_many = array(
        array(
            'articlestags',
            'class_name'    => '\Article\ArticlesTags',
        ),
        array(
            'tags',
            'class_name'    => '\Tag',
            'through'       => 'articlestags'
        ),
    );

    // --------------------------------------------------------------------
    // Validations
    // --------------------------------------------------------------------

    static $validates_presence_of = array(
        array('title'),
        array('slug'),
        array('content')
    );

    static $validates_length_of = array(
        array('title','maximum' => 120),
        array('slug','maximum' => 120)
    );

    static $validates_uniqueness_of = array(
        array('slug')
    );

    // --------------------------------------------------------------------
    // Setter/Getter Methods
    // --------------------------------------------------------------------

    /**
     * set title and slug attributes
     *
     * @access  public
     * @param   string  $title
     *
     * @return void
     **/
    public function set_title($title)
    {
        $this->assign_attribute('title', trim($title));
        if (empty($this->slug))
        {
            $this->slug = url_title($this->title, 'underscore', TRUE);
        }
    }

    // --------------------------------------------------------------------
    // Public Methods
    // --------------------------------------------------------------------

    /**
     * get the most recent articles
     *
     * @return void
     * @author Jack Boberg
     **/
    public static function recent_published($limit)
    {
        $options = array(
            'limit' => $limit,
            'order' => 'published_at desc',
            'conditions' => array(
                'published_at < ?',
                date_create()
            )
        );
        return static::all($options);
    }

    // --------------------------------------------------------------------

    /**
     * find article with specified params
     *
     * @access  public
     * @param   array   $config     params to parse
     *
     * @return  object
     **/
    public static function find_article($config)
    {
        // support sending different params
        // only uses ($pubdate, $slug) for now
        extract($config);
        $pubdate->setTimezone(new DateTimeZone('GMT'));
        $cond = array(
            'slug = ? AND published_at >= ? AND published_at <= ?',
            $slug,
            $pubdate->format('Y-m-d H:i:s'),
            $pubdate->modify('+1 day')->format('Y-m-d H:i:s')
        );
        return static::first(array('conditions'=>$cond));
    }

    // --------------------------------------------------------------------

    /**
     * get a page of articles
     *
     * @access  public
     * @param   string  $slug   article.slug
     *
     * @return  array
     **/
    public static function paginated($config)
    {
        extract($config);
        // create result object
        $result = new stdClass();
        //init table joins
        $joins = array();
        // init conditions array
        $conditions = array('');
        $queries = array();
        // limit to published articles?
        if (isset($published))
        {
            if ($published)
            {
                $queries[] = 'articles.published_at < ?';
                $conditions[] = date_create()->format('Y-m-d H:i:s');
            }
        }
        if ( ! empty($categories))
        {
            $joins[] = 'category';
            $cat_queries = array();
            foreach ($categories as $category)
            {
                $cat_queries[] = 'categories.slug = ?';
                $conditions[] = $category;
            }
            $queries[] = implode(' OR ',$cat_queries);
        }
        // limit to timespan
        if ( ! empty($start) && ! empty($end))
        {
            // convert timezones
            $start->setTimezone(new DateTimeZone('GMT'));
            $end->setTimezone(new DateTimeZone('GMT'));
            // append condition
            $queries[] .= 'articles.published_at > ? AND articles.published_at < ?';
            //$cond[0] .= 'published_at > ? AND published_at < ?';
            $conditions[] = $start->format('Y-m-d H:i:s');
            $conditions[] = $end->format('Y-m-d H:i:s');
        }
        $conditions[0] = implode(' AND ', $queries);
        // setup finder options
        $options = array(
            'order'     => 'articles.published_at desc',
            'limit'     => $per_page,
            'offset'    => ($per_page * $page) - $per_page,
            'conditions'=> $conditions,
            'joins'     => $joins
        );
        // get the articles
        $result->articles = static::all($options);
        $result->total_rows = static::count($options);
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * check if published_at is in the past
     *
     * @access  public
     * @param   void
     *
     * @return  bool
     **/
    public function is_published()
    {
       return ($this->published_at && $this->published_at <= date_create());
    }

    // --------------------------------------------------------------------

    /**
     * generate a unique slug for this record
     *
     * @access  public
     * @param   int     $i      counter for non-unique slug
     *
     * @return  void
     **/
    public function generate_slug($i = 1)
    {
        $slug = url_title($this->title, 'underscore', TRUE);
        $cur = $this->slug;
        if ($slug == $cur)
        {
            return;
        }
        // check if slug exists
        if ( $i > 1)
        {
            $slug .= $i;
        }
        if (self::exists($slug))
        {
            return $this->generate_slug(++$i);
        }
        else
        {
            $this->slug = $slug;
        }
    }
    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
}
/**
 * SQL for table

CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `preview` text,
  `content` text NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

**/

/* End of file Article.php */
/* Location: ./third_party/articles/models/Article.php */
