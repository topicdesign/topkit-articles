<section id="admin-articles-index">
    <header>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/articles'); ?>"><?php echo lang('articles-admin-title'); ?></a>
            </li>
        </ul>
    </header>
    <div class="section-content">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Tags</th>
                    <th>Published</th>
                    <th class="pull-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $a): ?>
                <tr>
                    <td><?php echo $a->title; ?></td>
                    <td><?php //echo $a->preview; ?></td>
                    <td><?php echo $a->is_published() ? local_pubdate($a->published_at, 'Y-m-d') : 'draft'; ?></td>
                    <td>
                        <div class="btn-group pull-right">
                            <a href="<?php echo site_url('admin/articles/edit/'.$a->id); ?>" class="btn"><i class="icon-edit"></i>Edit</a>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="icon-check"></i>Preview</a></li>
                                <li><a href="#"><i class="icon-trash"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
