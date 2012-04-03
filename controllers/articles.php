<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends Public_Controller {

    /**
     * Constructor
     *
     * @access  public
     * @param   void
     *
     * @return  void
     **/
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('article');
        $this->config->load('articles');
        $this->page->title(config_item('articles_title'));
    }

    // --------------------------------------------------------------------

    /**
     * re-route all URIs to internal methods
     *
     * @access  public
     * @param   string  $method     original requested method
     * @param   array   $params     passed paramaters
     *
     * @return  void
     **/
    public function _remap($method, $params = array())
    {
        // allow calling defined methods
        if (method_exists($this, $method))
        {
            // only call public methods
            $reflection = new ReflectionMethod($this, $method);
            if ( ! $reflection->isPublic())
            {
                show_404();
            }
            return call_user_func_array(array($this, $method), $params);
        }
        // $method is just the first param
        array_unshift($params, $method);
        // strip off page segment(s)
        $page = 1;
        $page_key = array_search('page', $params);
        if ($page_key !== FALSE)
        {
            $page = isset($params[$page_key + 1])
                ? $params[$page_key + 1]
                : 1;
            $params = array_slice($params, 0, $page_key);
        }
        // params should now be (YYYY,MM,DD,title)
        if (count($params) == 4 && is_numeric($params[0]))
        {
            return $this->view($params);
        }
        //check for category
        $categories = array();
        if ( ! is_numeric($params[0]) && $params[0] != 'index') 
        {
            $categories[] = array_shift($params); 
        }
        // clear params if default method call
        if ( ! empty($params) && $params[0] == 'index')
        {
            $params = array();
        }
        return $this->paginated($categories, $params, $page);
    }

    // --------------------------------------------------------------------

    /**
     * display an specifc article
     *
     * @access  private
     * @param   array       $params     passed params
     *
     * @return  void
     **/
    private function view($params)
    {
        // localize params (YYYY,MM,DD,title)
        $slug = array_pop($params);
        $TZ = new DateTimeZone(config_item('site_timezone'));
        $date = date_create(implode('-', $params), $TZ);
        // allow unpublished article?
        if (cannot('manage', 'article') && date_create(NULL, $TZ) < $date)
        {
            show_404();
        }
        // get the article
        $params = array(
            'pubdate'   => $date,
            'slug'      => $slug
        );
        if ( ! $article = Article::find_article($params))
        {
            show_404();
        }
        $this->page
            ->title($article->title) 
            ->data('article', $article)
            ->build('articles/article_view');
    }
    
    // --------------------------------------------------------------------

    /**
     * categories
     *
     * @access  public 
     * 
     * @return void
     **/
    public function categories()
    {   
        $params = $this->uri->segment_array();
        array_shift($params);
        $page = 1;
        $page_key = array_search('page', $params);
        if ($page_key !== FALSE)
        {
            $page = isset($params[$page_key + 1])
                ? $params[$page_key + 1]
                : 1;
            $params = array_slice($params, 0, $page_key);
        }
        $cat_key = array_search(config_item('articles_categories_url'),$params);
        $categories = array_slice($params,$cat_key+1);
        $this->paginated($categories,array(),$page);
    }

    // --------------------------------------------------------------------

    /**
     * get an page of articles to display as an index
     *
     * @access  private
     * @param   string      $page       page number
     * @param   array       $params     passed params
     *
     * @return  void
     **/
    private function paginated($categories, $dates = array(), $page = 1)
    {
        $TZ = new DateTimeZone(config_item('site_timezone'));
        // set date limits based on params
        switch (count($dates))
        {
            case 1:
                // limit by year
                $dates += array(1,1);
                $start = date_create(implode('-', $dates), $TZ);
                $end = clone $start;
                $end->modify('+1 year');
                
                $this->page->title($start->format('Y'));
                break;
            case 2:
                // limit by month
                $dates += array(1);
                $start = date_create(implode('-', $dates), $TZ);
                $end = clone $start;
                $end->modify('+1 month');

                $this->page->title($start->format('F, Y'));
                break;
            case 3:
                // limit by day
                $start = date_create(implode('-', $dates), $TZ);
                $end = clone $start;
                $end->modify('+1 day');

                $this->page->title($start->format('F j, Y'));
                break;
            default:
                // no limit
                $start = $end = NULL;
                break;
        }
        // get a page of articles 
        $per_page = config_item('articles_per_page');
        $config = array(
            'start'     => $start,
            'end'       => $end,
            'published' => cannot('manage', 'article'),
            'per_page'  => $per_page,
            'page'      => $page,
            'categories'  => $categories
        );
        $result = Article::paginated($config);
        // setup pagination
        $segments = $this->uri->segment_array();
        if ($key = array_search('page', $segments))
        {
            $segments = array_slice($segments, 0, $key - 1);
        }
        array_push($segments, 'page');
        $this->load->library('pagify');
        $config = array(
            'total'    => $result->total_rows,
            'url'      => site_url($segments),
            'page'     => $page,
            'per_page' => $per_page
        );
        $this->pagify->initialize($config);
        $options = array(
            'conditions' => array(''),
        );
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
            $cat_titles[] = $cat->category;
        }
        //update page title with categories
        $this->page->title($cat_titles);
        // output the index
        $this->page
            ->data('articles',$result->articles)
            ->build('articles/articles_index');
    }

    // --------------------------------------------------------------------

}
/* End of file articles.php */
/* Location: ./third_party/articles/controllers/articles.php */
