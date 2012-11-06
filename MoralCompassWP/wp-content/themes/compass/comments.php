<section class=comment-form>
    <div class="padded-title clearfix">
        <h6 class=underline-title>SUBMIT A COMMENT</h6>

        <?php
            $fields = array(
                'author' => '<p><input type="text" name="author" id="author" placeholder="Name"></p>',
                'email'  => '<p><input type="text" name="email" id="email" placeholder="Email"></p>'
            );

            $comment_field = '<p><textarea name="comment" id="comment" cols="69" rows="6" placeholder="Message/Comment"></textarea></p>';

            comment_form(array(
                'fields'               => $fields,
                'comment_field'        => $comment_field,
                'label_submit'         => 'Submit',
                'title_reply'          => '',
                'comment_notes_before' => '',
                'comment_notes_after'  => ''
            ));
        ?>

    </div>
</section>

<section class="comments-area clearfix">
    <div class=padded-title>
        <h6 class=underline-title><?php comments_number('NO COMMENTS', '1 COMMENT', '% COMMENTS'); ?></h6>
        <?php if (have_comments()) : ?>
        <ol class=comment-list>
            <?php
                wp_list_comments( array('callback' => 'jvs_comments', 'max_depth' => 2) );
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav class="comment-nav-below right">
            <div class=nav-meta>
                Showing <?php echo get_option('comments_per_page') ?> comments of <?php comments_number('0', '1', '%'); ?> &gt;
                <?php previous_comments_link( __('see more') ); ?>
            </div>
        </nav>
        <?php endif; ?>

        <div class="post-comment clear">
            <a href="#respond" class="green-btn right">Submit a Comment</a>
        </div>

        <?php endif; ?>
    </div>
</section>