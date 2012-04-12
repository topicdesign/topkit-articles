<section id="articles-index" class="page">
    <header>
        <h1><?php echo get_articles_page_title($categories); ?></h1>
    </header>
    <div class="section-content">
        <?php if ( ! count($articles)): ?>
        <p>No articles on file.</p>
        <?php else: foreach ($articles as $a) $this->load->view('articles/article_preview', array('article'=>$a)); endif; ?>
        <?php echo $this->pagify->get_links(); ?>
    </div>
</section>
