<?php
/**
 * Featured Section
 * 
 * @package Mudita
 */
 
$section_title   = get_theme_mod( 'mudita_featured_section_title' );
$section_content = get_theme_mod( 'mudita_featured_section_content' );
$post_one        = get_theme_mod( 'mudita_featured_post_one' );
$post_two        = get_theme_mod( 'mudita_featured_post_two' );
$post_three      = get_theme_mod( 'mudita_featured_post_three' );
$post_four       = get_theme_mod( 'mudita_featured_post_four' );
$read_more       = get_theme_mod( 'mudita_featured_read_more' );
 
?>

<div class="container">
	<?php if( $section_title || $section_content ){ ?>
    <header class="header">
		<?php 
        
        if( $section_title ) echo '<h2 class="main-title">' . esc_html( $section_title ) . '</h2>'; 
        
        if( $section_content ) echo wpautop( esc_html( $section_content ) );
        
        ?>
	</header>
	<?php } 
    
        if( $post_one || $post_two || $post_three || $post_four ){
            
            $featured_posts = array( $post_one, $post_two, $post_three, $post_four );
            $featured_posts = array_diff( array_unique( $featured_posts ), array('') );
                    
            $featured_qry = new WP_Query( array( 
                'post_type'             => 'post',
                'posts_per_page'        => -1,
                'post__in'              => $featured_posts,
                'orderby'               => 'post__in',
                'ignore_sticky_posts'   => true
            ) );
            if( $featured_qry->have_posts() ){
        ?>
        <div class="row">
            <?php
                while( $featured_qry->have_posts() ){
                    $featured_qry->the_post();
                ?>
                <article class="post">
                    <div class="img-holder">
            			<?php 
                        if( has_post_thumbnail() ){ 
                            the_post_thumbnail( 'mudita-featured' ); 
                        }else{
                            mudita_get_fallback_svg( 'mudita-featured' );
                        } ?>
                    </div>
        			
                    <div class="text-holder">
        				<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
        				<?php
                            echo wpautop( wp_kses_post( wp_trim_words( strip_shortcodes( get_the_content() ), 15, '...' ) ) );
                            if( $read_more ){ ?>
                                <a href="<?php the_permalink(); ?>" class="readmore"><?php echo esc_html( $read_more ); ?></a>
                                <?php 
                            } 
                        ?>
        			</div>
        		</article>  
                <?php
                }
                wp_reset_postdata();
            ?>
        </div>
        <?php }   
        }
    ?>
</div>