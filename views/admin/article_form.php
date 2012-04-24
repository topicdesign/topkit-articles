<form action="<?php echo current_url(); ?>" method="post" class="form-inline" accept-charset="utf-8">
    <div class="row-fluid">
        <div class="span8">
            <fieldset>
                <div class="control-group">
                    <label for="article-form-title" class="control-label text"><?php echo lang('article-field-title'); ?></label>
                    <div class="controls">
                        <input id="article-form-title" name="title"
                            type="text"
                            value="<?php echo set_value('title',$article->title); ?>"
                            class="text"
                            >
                    </div>
                </div>
                <div class="control-group">
                    <label for="article-form-content" class="control-label textarea"><?php echo lang('article-field-content'); ?></label>
                    <div class="controls">
                        <textarea id="article-form-content" name="content"
                            rows="8" cols="40"
                            data-role="editor"
                            style="width:100%"
                            ><?php echo set_value('content', $article->content); ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="span4">
            <fieldset class="well">
                <div class="control-group btn-toolbar">
                    <label for="article-form-publish" class="text"><?php echo lang('article-field-published_at'); ?></label>
                    <?php if ( ! $article->published_at || $article->published_at > date_create()): ?>
                    <div class="controls">
                        <input id="article-form-publish" name="publish-date"
                            type="text"
                            value="<?php echo set_value('publish-date',local_date_format($article->published_at, 'Y/m/d')); ?>"
                            class="text input-small"
                            data-role="datepicker"
                            >
                        <input id="article-form-publish-time" name="publish-time"
                            type="text"
                            value="<?php echo set_value('publish-time',local_date_format($article->published_at, 'g:i A')); ?>"
                            class="text input-mini"
                            data-role="timepicker"
                            >
                    </div>
                    <?php else: ?>
                    <p>Published on <?php echo local_date_format($article->published_at, 'Y/m/d g:i A'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="control-group">
                    <label for="article-form-category" class="select"><?php echo lang('article-field-category'); ?></label>
                    <select id="article-form-category" name="category">
                        <option>Choose a category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo $c->id; ?>" <?php if ($c->id == $article->category_id) echo 'selected="selected"'; ?>><?php echo $c->category; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="control-group">
                    <label for="article-form-tags" class="text"><?php echo lang('article-field-tags'); ?></label>
                    <div class="controls">
                        <input id="article-form-tags" name="tags"
                            type="text"
                            value="<?php echo set_value('tags',$article_tags); ?>"
                            class="text"
                            data-role="tagcomplete" 
                            data-source="<?php echo htmlentities($tags); ?>"
                            >
                    </div>
                </div>
                <div class="control-group">
                    <label for="article-form-preview" class="textarea"><?php echo lang('article-field-preview'); ?></label>
                    <div class="controls">
                        <textarea id="article-form-preview" name="preview"
                            rows="8" cols="40"
                            style="width:100%"
                            ><?php echo set_value('preview', $article->preview); ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <fieldset class="form-actions">
            <input id="article-form-save"
                type="submit"
                value="<?php echo lang('form-btn-save'); ?>"
                class="submit btn btn-primary btn-large"
                >
            <input id="article-form-reset"
                type="reset"
                value="<?php echo lang('form-btn-reset'); ?>"
                class="reset btn"
                >
        </fieldset>
    </div>
</form>
