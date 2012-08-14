<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Article Helpers
 *
 * @package     Article
 * @subpackage  Helpers
 * @author      Topic Design
 * @license     http://creativecommons.org/licenses/BSD/
 */

// --------------------------------------------------------------------

/**
 * get the URL used for the Articles controller
 *
 * @access  public
 * @param   void
 *
 * @return  string
 **/
if ( ! function_exists('get_articles_url'))
{
    function get_articles_url()
    {
        $CI = get_instance();
        $CI->config->load('articles');
        return $CI->config->item('articles_base_url');
    }
}

// --------------------------------------------------------------------

/**
 * get url for Article
 *
 * @access  public
 * @param   object  $article
 *
 * @return  string
 */
if ( ! function_exists('article_url'))
{
    function article_url($a)
    {
        $segments = array(
            get_articles_url(),
            local_date_format($a->published_at, 'Y/m/d'),
            $a->slug
        );
        return site_url(implode('/', $segments));
    }
}

// --------------------------------------------------------------------

/**
 * get the URL used for the Articles controller
 *
 * @access  public
 * @param   void
 *
 * @return  string
 **/
if ( ! function_exists('get_latest_articles'))
{
    function get_latest_articles($limit = 1, $category = NULL)
    {
        $options = array(
            'limit' => $limit,
            'order' => 'published_at desc'
        );
        if ($category) 
        {
            $category = Category::find_by_category($category);
            $options['conditions'] = array('category_id = ?',$category->id);
        }
        $articles = Article::all($options);
        return $articles;
    }
}

// --------------------------------------------------------------------

/**
 * get_page_header
 *
 * @access  public 
 * 
 * @return void
 **/
if ( ! function_exists('get_articles_page_title'))
{
    function get_articles_page_title($categories = array())
    {   
        if (empty($categories)) 
        {
            return config_item('articles_title');             
        }
        $options = array(
            'conditions' => array(''),
        );
        $cat_queries = array();
        //build query string to get categories
        foreach ($categories as $category)
        {   
            $cat_queries[] = 'slug = ?';
            $options['conditions'][] = $category;
        }
        $options['conditions'][0] = implode(' OR ',$cat_queries);
        //get all specified category names
        $cat_result = Category::all($options);
        //build array of category titles
        $cat_titles = array();
        foreach ($cat_result as $cat)
        {   
            $cat_titles[] = $cat->title;
        }
        //update page title with categories
        return implode(' | ',$cat_titles);
    }
}

// --------------------------------------------------------------------

/* End of file article_helper.php */
/* Location: ./third_party/articles/helpers/article_helper.php */
