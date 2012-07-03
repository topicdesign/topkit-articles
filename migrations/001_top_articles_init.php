<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Top_articles_init extends CI_Migration {

    /**
     * add articles tables
     *
     * @access  public
     * @param   void
     * @return  void
     **/
    public function up()
    {
        $this->add_articles();
        $this->add_categories();
        $this->add_tags();
    }

    // --------------------------------------------------------------------

    /**
     * drop articles tables
     *
     * @access  public
     * @param   void
     * @return  void
     **/
    public function down()
    {
        $tables = array(
            'articles',
            'article_categories',
            'images',
        );
        foreach ($tables as $table)
        {
            $this->dbforge->drop_table($table);
        }
    }

    // --------------------------------------------------------------------

    /**
     * add_articles
     *
     * @access  private
     * @param   void
     * @return  void
     **/
    private function add_articles()
    {
        $this->dbforge->add_field(array(
            'id'            => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'title'         => array(
                'type'              => 'VARCHAR',
                'constraint'        => '120',
            ),
            'slug'          => array(
                'type'              => 'VARCHAR',
                'constraint'        => '120',
            ),
            'category_id'   => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'null'              => TRUE
            ),
            'preview'       => array(
                'type'              => 'TEXT',
            ),
            'content'       => array(
                'type'              => 'TEXT',
            ),
            'published_at'  => array(
                'type'              => 'DATETIME',
                'null'              => TRUE
            ),
            'created_at'    => array(
                'type'              => 'DATETIME',
                'null'              => TRUE
            ),
            'updated_at'    => array(
                'type'              => 'DATETIME',
                'null'              => TRUE
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('articles');
    }

    // --------------------------------------------------------------------

    /**
     * add_categories
     *
     * @access  private
     * @param   void
     * @return  void
     **/
    private function add_categories()
    {
        $this->dbforge->add_field(array(
            'id'            => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'category'      => array(
                'type'              => 'VARCHAR',
                'constraint'        => '50',
                'null'              => FALSE,
            ),
            'slug'          => array(
                'type'              => 'VARCHAR',
                'constraint'        => '120',
            ),
            'parent_category_id' => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'null'              => TRUE,
            ),
        ));
        $this->dbforge->add_key('id',TRUE);
        $this->dbforge->create_table('article_categories');
    }

    // --------------------------------------------------------------------

    /**
     * add_tags
     *
     * @access  private
     * @param   void
     * @return  void
     **/
    private function add_tags()
    {
        $this->dbforge->add_field(array(
            'id'            => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'article_id'            => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
            ),
            'tag_id'            => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
            ),
        ));
        $this->dbforge->add_key('id',TRUE);
        $this->dbforge->create_table('article_tags');
    }

    // --------------------------------------------------------------------

}
/* End of file 001_top_articles_init.php */
/* Location: ./third_party/articles/migrations/001_top_articles_init.php */
