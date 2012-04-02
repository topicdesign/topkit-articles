<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Top_articles_init extends CI_Migration {

    /**
     * add articles table
     *
     * @access  public
     * @param   void
     *
     * @return  void
     **/
    public function up()
    {
        add_articles();
        add_categories();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * add_articles
     *
     * @access  public 
     * 
     * @return void
     **/
    public function add_articles()
    {   
        $this->dbforge->add_field(array(
            'id' => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'title' => array(
                'type'              => 'VARCHAR',
                'constraint'        => '120',
            ),
            'slug' => array(
                'type'              => 'VARCHAR',
                'constraint'        => '120',
            ),
            'category_id' => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'null'              => TRUE
            ),
            'preview' => array(
                'type'              => 'TEXT',
            ),
            'content' => array(
                'type'              => 'TEXT',
            ),
            'published_at' => array(
                'type'              => 'DATETIME',
                'null'              => TRUE
            ),
            'created_at' => array(
                'type'              => 'DATETIME',
                'null'              => TRUE
            ),
            'updated_at' => array(
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
     * @access  public 
     * 
     * @return void
     **/
    public function add_categories()
    {   
        $this->dbforge->add_field(array(
            'id' => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'category' => array(
                'type'              => 'VARCHAR',
                'constraint'        => '50',
                'null'              => FALSE,
            ),
            'parent_category_id' => array(
                'type'              => 'INT',
                'constraint'        => '11',
                'unsigned'          => TRUE,
                'null'              => TRUE,
            ),
        ));
        $this->dbforge->add_key('id');
        $this->dbforge->create_table('categories');
    }

    // --------------------------------------------------------------------

    /**
     * drop articles table
     *
     * @access  public
     * @param   void
     *
     * @return  void
     **/
    public function down()
    {
        $this->dbforge->drop_table('articles');
        $this->dbforge->drop_table('categories');
    }

    // --------------------------------------------------------------------

}
/* End of file 001_top_articles_init.php */
/* Location: ./third_party/articles/migrations/001_top_articles_init.php */
