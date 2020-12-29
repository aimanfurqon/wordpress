<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Mudita
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function mudita_body_classes( $classes ) {
	
    global $post;
    
    // Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
	if( ! is_active_sidebar( 'right-sidebar' ) || is_page_template( 'template-home.php' ) || is_search() ) {
		$classes[] = 'full-width';	
	}
    
    if( is_page() ){
		$sidebar_layout = get_post_meta( $post->ID, 'mudita_sidebar_layout', true );
        if( $sidebar_layout == 'no-sidebar' )
		$classes[] = 'full-width';
	}
    
	return $classes;
}
add_filter( 'body_class', 'mudita_body_classes' );

/**
 * Custom Bread Crumb
 *
 * @link http://www.qualitytuts.com/wordpress-custom-breadcrumbs-without-plugin/
 */
 
function mudita_breadcrumbs_cb() {    
    global $post;
    
    $post_page   = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front  = get_option( 'show_on_front' ); //What to show on the front page
    $showCurrent = get_theme_mod( 'mudita_ed_current', '1' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $delimiter   = get_theme_mod( 'mudita_breadcrumb_separator', __( '>', 'mudita' ) ); // delimiter between crumbs
    $home        = get_theme_mod( 'mudita_breadcrumb_home_text', __( 'Home', 'mudita' ) ); // text for the 'Home' link
    $before      = '<span class="current" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
      
    $depth = 1;    
    echo '<div id="crumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( home_url() ) . '" class="home_crumb"><span itemprop="name">' . esc_html( $home ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
        if( is_home() && ! is_front_page() ){            
            $depth = 2;
            if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( single_post_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;          
        }elseif( is_category() ){            
            $depth = 2;
            $thisCat = get_category( get_query_var( 'cat' ), false );
            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $post_page ) ) . '"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;  
            }

            if ( $thisCat->parent != 0 ) {
                $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                $parent_categories = explode( ',', $parent_categories );

                foreach ( $parent_categories as $parent_term ) {
                    $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                    if( is_object( $parent_obj ) ){
                        $term_url    = get_term_link( $parent_obj->term_id );
                        $term_name   = $parent_obj->name;
                        echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                        $depth ++;
                    }
                }
            }

            if( $showCurrent ) echo $before . '<span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;

        }elseif( is_tag() ){            
            $queried_object = get_queried_object();
            $depth = 2;

            if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( single_tag_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;    
        }elseif( is_author() ){            
            $depth = 2;
            global $author;
            $userdata = get_userdata( $author );
            if( $showCurrent ) echo $before . '<span itemprop="name">' . esc_html( $userdata->display_name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;  
        }elseif( is_day() ){            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'mudita' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'mudita' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
            $depth ++;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'mudita' ) ), get_the_time( __( 'm', 'mudita' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'mudita' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
            $depth ++;
            if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'd', 'mudita' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
             
        }elseif( is_month() ){            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'mudita' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'mudita' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
            $depth++;
            if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'F', 'mudita' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
        }elseif( is_year() ){            
            $depth = 2;
            if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'mudita' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
        }elseif( is_single() && !is_attachment() ) {
            //For Post                
            $cat_object       = get_the_category();
            $potential_parent = 0;
            $depth            = 2;
            
            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';  
                $depth++;
            }
            
            if( is_array( $cat_object ) ){ //Getting category hierarchy if any
    
                //Now try to find the deepest term of those that we know of
                $use_term = key( $cat_object );
                foreach( $cat_object as $key => $object ){
                    //Can't use the next($cat_object) trick since order is unknown
                    if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                        $use_term = $key;
                        $potential_parent = $object->term_id;
                    }
                }
                
                $cat = $cat_object[$use_term];
          
                $cats = get_category_parents( $cat, false, ',' );
                $cats = explode( ',', $cats );

                foreach ( $cats as $cat ) {
                    $cat_obj = get_term_by( 'name', $cat, 'category' );
                    if( is_object( $cat_obj ) ){
                        $term_url    = get_term_link( $cat_obj->term_id );
                        $term_name   = $cat_obj->name;
                        echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                        $depth ++;
                    }
                }
            }

            if ( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;        
        }elseif( is_page() ){            
            $depth = 2;
            if( $post->post_parent ){            
                global $post;
                $depth = 2;
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                
                while( $parent_id ){
                    $current_page  = get_post( $parent_id );
                    $breadcrumbs[] = $current_page->ID;
                    $parent_id     = $current_page->post_parent;
                }
                $breadcrumbs = array_reverse( $breadcrumbs );
                for ( $i = 0; $i < count( $breadcrumbs); $i++ ){
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $breadcrumbs[$i] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[$i] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>';
                    if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                    $depth++;
                }

                if ( $showCurrent ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" /></span>'. $after;      
            }else{
                if ( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
            }
        }elseif( is_search() ){            
            $depth = 2;
            if( $showCurrent ) echo $before .'<span itemprop="name">'. esc_html__( 'Search Results for "', 'mudita' ) . esc_html( get_search_query() ) . esc_html__( '"', 'mudita' ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
        }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {            
            $depth = 2;
            $post_type = get_post_type_object(get_post_type());
            if( get_query_var('paged') ){
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />';
                echo ' <span class="separator">' . $delimiter . '</span></span> ' . $before . sprintf( __('Page %s', 'mudita'), get_query_var('paged') ) . $after;
            }elseif( is_archive() ){
                echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            }else{
                echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            }              
        }elseif( is_attachment() ){            
            $depth  = 2;
            $parent = get_post( $post->post_parent );
            $cat    = get_the_category( $parent->ID );
            if( $cat ){
                $cat = $cat[0];
                echo get_category_parents( $cat, TRUE, ' <span class="separator">' . $delimiter . '</span> ');
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $parent ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $parent->post_title ) . '<span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . ' <span class="separator">' . $delimiter . '</span></span>';
            }
            if( $showCurrent ) echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;   
        }elseif ( is_404() ){
            if( $showCurrent ) echo $before . esc_html__( '404 Error - Page not Found', 'mudita' ) . $after;
        }
        if( get_query_var('paged') ) echo __( ' (Page', 'mudita' ) . ' ' . get_query_var('paged') . __( ')', 'mudita' );        
        echo '</div>';
}// end mudita_breadcrumbs()
add_action( 'mudita_breadcrumbs', 'mudita_breadcrumbs_cb' );

/**
 * Social Links Callback 
 */
function mudita_social_links_cb( $show_title = false ){
    
    $title     = get_theme_mod( 'mudita_social_section_title' );
    $facebook  = get_theme_mod( 'mudita_facebook' );
    $twitter   = get_theme_mod( 'mudita_twitter' );
    $pinterest = get_theme_mod( 'mudita_pinterest' );
    $linkedin  = get_theme_mod( 'mudita_linkedin' );
    $gplus     = get_theme_mod( 'mudita_gplus' );
    $instagram = get_theme_mod( 'mudita_instagram' );
    $youtube   = get_theme_mod( 'mudita_youtube' );
    
    if( $title && $show_title ) echo '<span>' . esc_html( $title ) . '</span>'; 
    
    if( $facebook || $twitter || $pinterest || $linkedin || $gplus || $instagram || $youtube ){
    
    ?>
	<ul class="social-networks">
		<?php if( $facebook ){ ?>
        <li><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" title="<?php esc_attr_e( 'Facebook', 'mudita' );?>"><i class="fa fa-facebook"></i></a></li>
		<?php } if( $twitter ){ ?>
        <li><a href="<?php echo esc_url( $twitter ); ?>" target="_blank" title="<?php esc_attr_e( 'Twitter', 'mudita' );?>"><i class="fa fa-twitter"></i></a></li>
        <?php } if( $pinterest ){ ?>
        <li><a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" title="<?php esc_attr_e( 'Pinterst', 'mudita' );?>"><i class="fa fa-pinterest"></i></a></li>
		<?php } if( $linkedin ){ ?>
        <li><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" title="<?php esc_attr_e( 'LinkedIn', 'mudita' );?>"><i class="fa fa-linkedin"></i></a></li>
        <?php } if( $gplus ){ ?>
        <li><a href="<?php echo esc_url( $gplus ); ?>" target="_blank" title="<?php esc_attr_e( 'Gooble Plus', 'mudita' );?>"><i class="fa fa-google-plus"></i></a></li>
        <?php } if( $instagram ){ ?>
        <li><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" title="<?php esc_attr_e( 'Instagram', 'mudita' );?>"><i class="fa fa-instagram"></i></a></li>
		<?php } if( $youtube ){ ?>
        <li><a href="<?php echo esc_url( $youtube ); ?>" target="_blank" title="<?php esc_attr_e( 'YouTube', 'mudita' );?>"><i class="fa fa-youtube-play"></i></a></li>
        <?php } ?>
	</ul>
    <?php
    }
}
add_action( 'mudita_social_links' , 'mudita_social_links_cb' );

if( ! function_exists( 'mudita_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function mudita_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'mudita' ) : __( 'Name', 'mudita' ) );
    $email    = ( $req ? __( 'Email*', 'mudita' ) : __( 'Email', 'mudita' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'mudita' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'mudita' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'mudita' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'mudita' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'mudita_change_comment_form_default_fields' );

if( ! function_exists( 'mudita_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function mudita_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'mudita' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'mudita' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'mudita_change_comment_form_defaults' );

/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function mudita_theme_comment($comment, $args, $depth) {
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	
    <footer class="comment-meta">
    
        <div class="comment-author vcard">
    	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    	<?php printf( __( '<b class="fn">%s</b>', 'mudita' ), get_comment_author_link() ); ?>
    	</div>
    	<?php if ( $comment->comment_approved == '0' ) : ?>
    		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'mudita' ); ?></em>
    		<br />
    	<?php endif; ?>
    
    	<div class="comment-metadata commentmetadata">
            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
                <time datetime="<?php comment_date(); ?>"><?php echo get_comment_date(); ?></time>
            </a>
            <?php edit_comment_link( __( '(Edit)', 'mudita' ), '  ', '' ); ?>
    	</div>
    </footer>
    
    <div class="comment-content"><?php comment_text(); ?></div>

	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/**
 * Fuction to get Sections 
 */
function mudita_get_sections(){
    
    $sections = array( 
        'featured-section' => array(
            'class' => 'features',
            'id'    => 'featured'    
        ),
        'service-section' => array(
            'class' => 'two-columns',
            'id'    => 'service'
        ),
        'team-section' => array(
            'class' => 'our-team',
            'id'    => 'team'
        ),
        'blog-section' => array(
            'class' => 'blog-section',
            'id'    => 'blog'
        ),
        'testimonial-section' => array(
            'class' => 'testimonial',
            'id'    => 'testimonial'
        )              
    );
    
    
    $enabled_section = array();
    foreach ( $sections as $section ) {
        
        if ( esc_attr( get_theme_mod( 'mudita_ed_' . $section['id'] . '_section' ) ) == 1 ){
            $enabled_section[] = array(
                'id'    => $section['id'],
                'class' => $section['class']
            );
        }
    }
    return $enabled_section;
}
 
/**
 * CallBack function for Banner 
 */
function mudita_banner_cb(){
    
    $ed_banner_section = get_theme_mod( 'mudita_ed_banner_section' );
    $banner_post       = get_theme_mod( 'mudita_banner_post' );
    $banner_read_more  = get_theme_mod( 'mudita_banner_read_more' );
    $enabled_sections  = mudita_get_sections();
    
    $banner_class = '';
    if( ! ( ( is_front_page() && ! is_home() ) || is_page_template( 'template-home.php' ) ) || ! $ed_banner_section || ! $banner_post ) $banner_class = ' banner-inner';
    
    ?>
    <div class="banner<?php echo esc_attr( $banner_class ); ?>">
        <?php 
            if( $ed_banner_section && $banner_post && ( is_page_template( 'template-home.php' ) || ( is_front_page() && ! is_home() ) ) ){
                
                $banner_qry = new WP_Query( array( 'p' => $banner_post ) );
                
                if( $banner_qry->have_posts() ){
                    while( $banner_qry->have_posts() ){
                        $banner_qry->the_post();
                        $categories_list = get_the_category_list( esc_html__( ', ', 'mudita' ) );
                        if( has_post_thumbnail() ){
                            the_post_thumbnail( 'mudita-banner' );
                        ?>
                    		<div class="banner-text">
                    			<div class="container">
                    				<div class="text">
                    					<?php 
                                            if( $categories_list && mudita_categorized_blog() ) {
                                                echo '<span class="category">' . $categories_list . '</span>'; // WPCS: XSS OK.
                                            }
                                        ?>
                    					<strong class="title"><?php the_title(); ?></strong>
                    					<?php if( has_excerpt() ) the_excerpt(); ?>
                    					<div class="btn-holder">
                    						<a href="<?php the_permalink(); ?>" class="btn-learnmore"><?php echo esc_html( $banner_read_more ); ?><span class="icon"></span></a>
                    					</div>
                    				</div>
                    			</div>
                    		</div>
                        <?php
                        if( $enabled_sections ) echo '<button type="button" class="arrow-down"></button>';
                        }
                    }
                    wp_reset_postdata();
                }                
            }
        ?>
    </div>
    <?php
}
add_action( 'mudita_banner', 'mudita_banner_cb' );

/**
 * Custom CSS
*/
if ( function_exists( 'wp_update_custom_css_post' ) ) {
    // Migrate any existing theme CSS to the core option added in WordPress 4.7.
    $css = get_theme_mod( 'mudita_custom_css' );
    if ( $css ) {
        $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
        $return = wp_update_custom_css_post( $core_css . $css );
        if ( ! is_wp_error( $return ) ) {
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            remove_theme_mod( 'mudita_custom_css' );
        }
    }
} else {
    function mudita_custom_css(){
        $custom_css = get_theme_mod( 'mudita_custom_css' );
        if( !empty( $custom_css ) ){
    		echo '<style type="text/css">';
    		echo wp_strip_all_tags( $custom_css );
    		echo '</style>';
    	}
    }
    add_action( 'wp_head', 'mudita_custom_css', 100 );
}

if ( ! function_exists( 'mudita_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function mudita_excerpt_more( $more ){
	return is_admin() ? $more : ' &hellip; ';
}
add_filter( 'excerpt_more', 'mudita_excerpt_more' );
endif;

if ( ! function_exists( 'mudita_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function mudita_excerpt_length( $length ) {
	return is_admin() ? $length : 60;
}
add_filter( 'excerpt_length', 'mudita_excerpt_length', 999 );
endif;

/**
 * Footer Credits 
*/
function mudita_footer_credit(){

  $copyright_text = get_theme_mod( 'mudita_footer_copyright_text' );
        
    $text  = '<div class="site-info">';
    if( $copyright_text ){
      $text .= wp_kses_post( $copyright_text ) . '&nbsp &verbar; ';
    }else{
      $text .=  esc_html__( 'Copyright &copy; ', 'mudita' ) . date_i18n( esc_html__( 'Y', 'mudita' ) ); 
      $text .= ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. &verbar; ';
    }
    $text .= esc_html__( 'Mudita | Developed By ', 'mudita' );
    $text .= '<a href="' . esc_url( 'https://rarathemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Rara Theme', 'mudita' ) .'</a> &verbar; ';
    $text .= sprintf( esc_html__( 'Powered by: %s', 'mudita' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'mudita' ) ) .'" target="_blank">WordPress</a>' );
    if( function_exists( 'get_the_privacy_policy_link' ) ){
        $text .= ' &verbar; ' . get_the_privacy_policy_link();    
    } 
    $text .= '</div>';
    echo apply_filters( 'mudita_footer_text', $text );    
}
add_action( 'mudita_footer', 'mudita_footer_credit' );

/**
 * Return sidebar layouts for pages
*/
function mudita_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'mudita_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'mudita_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
}

/**
 * Strip specific tags from string
 * @link http://www.altafweb.com/2011/12/remove-specific-tag-from-php-string.html
*/
function mudita_strip_single( $tag, $string ){
    $string = preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
    $string = preg_replace('/<\/'.$tag.'>/i', '', $string);
    return $string;
} 

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;

if( ! function_exists( 'mudita_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function mudita_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if ( ! function_exists( 'mudita_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function mudita_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = mudita_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#e0dfdf;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

if( ! function_exists( 'mudita_fonts_url' ) ) :
/**
 * Register custom fonts.
 */
function mudita_fonts_url() {
    $fonts_url = '';

    /*
    * translators: If there are characters in your language that are not supported
    * by Source Sans Pro, translate this to 'off'. Do not translate into your own language.
    */
    $source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'mudita' );
    
    if ( 'off' !== $source_sans_pro ) {
        $font_families = array();
        
        $font_families[] = 'Source Sans Pro:300,400,400i,600,700';        

        $query_args = array(
            'family'  => urlencode( implode( '|', $font_families ) ),
            'display' => urlencode( 'fallback' ),
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url( $fonts_url );
}
endif;