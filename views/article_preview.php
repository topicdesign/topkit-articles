<article id="article-<?php echo $article->id; ?>" class="article preview">
    <header>
        <h1><?php echo anchor(article_url($article), $article->title); ?></h1>
    </header>
    <div class="article-content">
        <?php echo $article->preview ?: html_word_limiter($article->content, 20); ?>
    </div>
    <footer>
        <p class="pubdate"><?php echo local_pubdate($article->published_at); ?></p>
    </footer>
</article>
