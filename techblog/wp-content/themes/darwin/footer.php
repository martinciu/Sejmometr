</div>
<hr />
<div id="footer">
	<p>
		<?php bloginfo('name'); ?> is evolved by
		<a href="http://wordpress.org/">WordPress</a> <?php bloginfo('version'); ?>,
		adapted by <a href="http://phocks.org/category/themes/darwin/">Darwin</a> theme.					
		<br /><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>
		and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.
		<?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds.
	</p>
</div>
<!-- Evolution in theme design -->
<?php /* "Natural selection rules!" --Charles Darwin */ ?>
		<?php wp_footer(); ?>
</div> <!-- End div for #block -->
</body>
</html>