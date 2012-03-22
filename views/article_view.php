<article id="article-<?php echo $article->id; ?>" class="page article view">
    <header>
        <h1><?php echo $article->title; ?></h1>
    </header>
    <div class="article-content">
        <?php echo $article->content; ?>
    </div>
    <footer>
        <p class="pubdate"><?php echo local_pubdate($article->published_at); ?></p>
    </footer>
</article>
