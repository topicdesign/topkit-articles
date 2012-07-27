<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends Admin_Controller {

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
        $this->lang->load('article');
    }

    // --------------------------------------------------------------------

    /**
     * Default method
     *
     * @access  public
     * @param   void
     *
     * @return  void
     **/
    public function index()
    {
        $data['articles'] = Article::all();
        $this->document->build('articles/admin/articles_index', $data);
    }

    // --------------------------------------------------------------------

    /**
     * allow user to create/edit article record
     *
     * @access  public
     * @param   integer     $id     Article.id
     * @return  void
     **/
    public function edit($id = NULL)
    {
        $article = admin_edit_object('article', $id);

        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('', '');

        $rules = array(
            array(
                'field' => 'title',
                'label' => 'lang:article-field-title',
                'rules' => 'required'
            ),
            array(
                'field' => 'content',
                'label' => 'lang:article-field-content',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE)
        {
            if ($e = validation_errors())
            {
                set_status('error', $e);
            }
            $tags = \Tag::all();
            $tag_list = array();
            foreach ($tags as $tag)
            {
                $tag_list[] = $tag->title;
            }
            $data['tags'] = json_encode($tag_list);
            $article_tags = array();
            if ($article->tags)
            {
                foreach ($article->tags as $tag)
                {
                    $article_tags[] = $tag->title;
                }
            }
            $data['article_tags'] = implode(', ', $article_tags);
            $data['article'] = $article;
            $data['categories'] = Category::all();
            $this->document->build('articles/admin/article_edit', $data);
        }
        else
        {
            $article->title       = $this->input->post('title');
            $article->preview     = $this->input->post('preview');
            $article->content     = $this->input->post('content');
            // $article->category_id = $this->input->post('category');

            if ($this->input->post('category'))
            {
                $category = Category::find('all', array('conditions'=>array('title = ?', $this->input->post('category'))));
                if ($category)
                {
                    $article->category_id = $category[0]->id;
                }
                else
                {
                    $category = new Category();
                    $category->title = $this->input->post('category');
                    $category->slug = url_title($category->title, '_', TRUE);
                    $category->save();
                    $article->category_id = $category->id;
                }
            }
            else
            {
                $article->category_id = NULL;
            }

            if ($this->input->post('publish-date'))
            {
                // convert published datetime to GMT
                $date = $this->input->post('publish-date');
                if ($this->input->post('publish-time')) 
                {
                    $date .= $this->input->post('publish-time') 
                        . ' ' . $this->input->post('publish-time-ampm');
                }
                $article->published_at = date_create(
                    $date,
                    new DateTimeZone(config_item('site_timezone'))
                );
                $article->published_at->setTimezone(new DateTimeZone('GMT'));
            }
            else if ( ! $article->is_published() && ! empty($article->published_at))
            {
                // clear published date if user emptied field
                $article->published_at = NULL;
            }

            if ( ! $article->save())
            {
                foreach ($article->errors->full_messages() as $e)
                {
                    set_status('error', $e);
                }
                redirect(uri_string());
            }

            // tags
            if ($article->tags)
            {
                \Article\ArticlesTags::table()->delete(array('article_id' => $article->id));
            }
            $new_tags = explode(',', $this->input->post('tags'));
            if ( ! empty($new_tags) && ! in_array('', $new_tags))
            {
                foreach ($new_tags as $new_tag)
                {
                    $new_tag = trim($new_tag);
                    $tag = \Tag::find('first', array(
                        'conditions' => array('title = ?', $new_tag))
                    );
                    if ( ! $tag)
                    {
                        $tag = new \Tag();
                        $tag->title = $new_tag;
                        $tag->save();
                    }
                    $relation = new \Article\ArticlesTags();
                    $relation->tag_id = $tag->id; 
                    $relation->article_id = $article->id;
                    $relation->save();
                }
            }

            set_status('success', 'Article Updated');
            redirect('admin/articles');
        }
    }

    // --------------------------------------------------------------------

    /**
     * delete
     *
     * @access  public
     * @param   $id
     * @return  void
     **/
    public function delete($id)
    {
      if ( ! $article = Article::find_by_id($id))
      {
          set_status('error', sprintf(lang('not_found'), 'article'));
      }
      else if (cannot('delete', $article))
      {
          set_status('error', lang('not_authorized'));
      }
      else if ( ! $article->delete())
      {
          set_status('error', 'Unable to delete requested article.');
      }
      else
      {
          set_status('success', 'Article deleted');
      }
      $this->history->back();
    }

    // --------------------------------------------------------------------

}
/* End of file articles.php */
/* Location: ./application/controllers/admin/articles.php */
