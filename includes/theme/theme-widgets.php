<?php 
/**
 * Recent Posts
 * Anaglyph widget
 *
 */
class Anaglyph_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'recent-posts', 'description' => __( "Your site&#8217;s most recent Posts.", 'anaglyph-lite') );
		parent::__construct('anaglyph-wrp', __('Anaglyph Recent Posts', 'anaglyph-lite'), $widget_ops);
		$this->alt_option_name = 'anaglyph-wrp';
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'anaglyph-lite' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number ) $number = 10;
		$show_date		 = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_comments	 = isset( $instance['show_comments'] ) ? $instance['show_comments'] : false;
		

		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
		<?php 
			$total_sount = 0;
			$comments_count = wp_count_comments(get_the_ID());
			$total_sount = $comments_count->total_comments;
		?>
			<li class="recent-post">
				<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<div class="meta">
				<?php if ( $show_date ) { ?>
					<div class="date">
						<i class="icon icon_calendar"></i>
						<span><?php echo get_the_date(); ?></span>
					</div>
				<?php } ?>		
			
				<?php if (($total_sount > 0) && ($show_comments)) { ?>
					<div class="comments">
						<i class="icon icon_comment_alt"></i>
						<span><?php echo $total_sount; ?></span>
					</div>
				<?php } ?>
				</div>	
				
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
		
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] 		= isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_comments'] 	= isset( $new_instance['show_comments'] ) ? (bool) $new_instance['show_comments'] : false;

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['anaglyph-wrp']) )
			delete_option('anaglyph-wrp');

		return $instance;
	}

	function form( $instance) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_comments 	= isset( $instance['show_comments'] ) ? (bool) $instance['show_comments'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'anaglyph-lite' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'anaglyph-lite' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'anaglyph-lite' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_comments ); ?> id="<?php echo $this->get_field_id( 'show_comments' ); ?>" name="<?php echo $this->get_field_name( 'show_comments' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_comments' ); ?>"><?php _e( 'Display post comments count?', 'anaglyph-lite' ); ?></label></p>
<?php
	}
}


/**
 * Tag cloud
 * Anaglyph widget
 * 
 */
class Anaglyph_Widget_Tag_Cloud extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( "A cloud of your most used tags.", 'anaglyph-lite') );
		parent::__construct('anaglyph_tc', __('Anaglyph Tag Cloud', 'anaglyph-lite'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags', 'anaglyph-lite');
			} else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$tags = get_terms( $current_taxonomy, array( 'orderby' => 'count', 'order' => 'DESC' ));
		if ( empty( $tags ) || is_wp_error( $tags ) ) return;
		
		echo '<div class="tags">';
			foreach ($tags as $key => $tag ) {
				$link = get_term_link( intval($tag->term_id), $tag->taxonomy );		
				echo '<a href="'.esc_url($link).'"><div class="tag">'.$tag->name.'</div></a>';
			}	
		echo "</div>\n";
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		return $instance;
	}

	function form( $instance ) {
		$current_taxonomy = $this->_get_current_taxonomy($instance);
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'anaglyph-lite') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'anaglyph-lite') ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
	<?php foreach ( get_taxonomies() as $taxonomy ) :
				$tax = get_taxonomy($taxonomy);
				if ( !$tax->show_tagcloud || empty($tax->labels->name) )
					continue;
	?>
		<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
	<?php endforeach; ?>
	</select></p><?php
	}

	function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];

		return 'post_tag';
	}
}

function anaglyph_register_widgets() { 
	register_widget( 'Anaglyph_Widget_Recent_Posts' );
	register_widget( 'Anaglyph_Widget_Tag_Cloud' );
}
add_action( 'widgets_init', 'anaglyph_register_widgets' );