<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Article Helpers
 *
 * @package		Article
 * @subpackage	Helpers
 * @author		Topic Design
 * @license		http://creativecommons.org/licenses/BSD/
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
 * @access	public
 * @param	object  $article
 *
 * @return  string
 */
if ( ! function_exists('article_url'))
{
	function article_url($a)
    {
        $segments = array(
            get_articles_url(),
            $a->local_pubdate('Y'),
            $a->local_pubdate('m'),
            $a->local_pubdate('d'),
            $a->slug
        );
        return site_url(implode('/', $segments));
	}
}

// --------------------------------------------------------------------

/* End of file article_helper.php */
/* Location: ./third_party/articles/helpers/article_helper.php */
