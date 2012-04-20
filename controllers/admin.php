<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

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
                'label' => 'lang:page-field-title',
                'rules' => 'required'
            ),
            array(
                'field' => 'publish-time',
                'label' => 'lang:page-field-publish_time',
                'rules' => 'required'
            ),
            array(
                'field' => 'publish-date',
                'label' => 'lang:page-field-published_at',
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
            $data['article'] = $article;
            $this->document->build('articles/admin/article_edit.php', $data);
        }
        else
        {
            $article->title = $this->input->post('title');
            $article->slug = url_title($article->title, 'underscore', TRUE);
            $article->preview = $this->input->post('preview');
            $article->content = $this->input->post('content');
            $article->published_at = date_create(
                $this->input->post('publish-date') . ' ' .
                $this->input->post('publish-time') . ' ' .
                $this->input->post('publish-time-ampm'),
                new DateTimeZone(config_item('site_timezone'))
            );
            $article->published_at->setTimezone(new DateTimeZone('GMT'));

            if ( ! $article->save())
            {
                foreach ($article->errors->full_messages() as $e)
                {
                    set_status('error', $e);
                }
                redirect(uri_string());
            }
            set_status('success', 'Article Updated');
            redirect('admin/articles');
        }
    }

    // --------------------------------------------------------------------

}
/* End of file admin.php */
/* Location: ./application/controllers/admin/admin.php */
