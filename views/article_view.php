<article id="article-<?php echo $article->id; ?>" class="article preview">
    <header>
        <h1><?php echo $article->title; ?></h1>
    </header>
    <div class="article-content">
        <?php echo $article->content; ?>
    </div>
    <footer>
        <p>Published: <time pubdate="pubdate" datetime="<?php echo $article->local_pubdate('Y-m-d'); ?>"><?php echo $article->local_pubdate(); ?></time></p>
    </footer>
</article>