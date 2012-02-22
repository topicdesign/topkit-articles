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
        $this->dbforge->add_field(array(
            'id'    => array(
                'type'          => 'INT',
                'constraint'    => '11',
                'unsigned'      => TRUE,
                'auto_increment'    => TRUE
            ),
            'title' => array(
                'type'          => 'VARCHAR',
                'constraint'    => '120',
            ),
            'slug' => array(
                'type'          => 'VARCHAR',
                'constraint'    => '120',
            ),
            'content'   => array(
                'type'          => 'TEXT',
            ),
            'published_at'  => array(
                'type'          => 'DATETIME',
                'null'          => TRUE
            ),
            'created_at'  => array(
                'type'          => 'DATETIME',
                'null'          => TRUE
            ),
            'updated_at'  => array(
                'type'          => 'DATETIME',
                'null'          => TRUE
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('articles');
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
    }

    // --------------------------------------------------------------------

}
/* End of file 001_top_articles_init.php */
/* Location: ./third_party/articles/migrations/001_top_articles_init.php */
