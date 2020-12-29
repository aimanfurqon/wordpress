<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mudita
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#acc-content"><?php esc_html_e( 'Skip to content (Press Enter)', 'mudita' ); ?></a>
	<header id="masthead" class="site-header" role="banner">
        <div class="header-t">
			<div class="container">
				<?php 
                if( get_theme_mod( 'mudita_ed_social_header' ) ) do_action( 'mudita_social_links' );
                
                ?>
                <div class="right">
                    <nav class="top-menu">
                        <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'secondary-menu', 'fallback_cb' => false ) ); ?>
                    </nav>
                    <?php 
                    $mudita_ed_header_info = get_theme_mod( 'mudita_ed_header_info' );
                    if( $mudita_ed_header_info ){
                        $mudita_header_tel = get_theme_mod( 'mudita_header_phone' );
                        if( $mudita_header_tel ){ ?>
                        <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $mudita_header_tel ) ); ?>" class="tel-link"><?php echo esc_html( $mudita_header_tel );?></a>
                    <?php } 
                    } ?>
                    
                </div>
			</div>
		</div>
        
        <div class="header-b">
			<div class="container">
                <div class="site-branding">
        			<?php 
                        if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                            the_custom_logo();
                        } 
                    if( is_front_page() ){ ?>
                        <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php 
                    } else { ?>
                        <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                    <?php } 
            			$description = get_bloginfo( 'description', 'display' );
            			if ( $description || is_customize_preview() ) : ?>
            				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
        			<?php
            			endif; 
                    ?>
        		</div><!-- .site-branding -->

                <button type="button" id="menu-button" href="#button-main"><?php esc_html_e( 'Menu', 'mudita' ); ?></button>
        
        		<nav id="site-navigation" class="main-navigation" role="navigation">
        			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
        		</nav><!-- #site-navigation -->

			</div>
		</div>
        
	</header><!-- #masthead -->
    
    <?php
    $ed_breadcrumb = get_theme_mod( 'mudita_ed_breadcrumb' );
    //Banner Section
    do_action( 'mudita_banner' );
    echo '<div id="acc-content">';
    $enabled_sections = mudita_get_sections();

    if( is_home() || ! $enabled_sections ||  ! ( is_front_page()  || is_page_template( 'template-home.php' ) ) ){ 
        echo '<div class="container">';
    }
    // Bread Crumb
    if( !( is_front_page() || is_page_template( 'template-home.php' ) ) && !is_404() && $ed_breadcrumb ) do_action( 'mudita_breadcrumbs' );
    
    if( is_home() || ! $enabled_sections ||  ! ( is_front_page()  || is_page_template( 'template-home.php' ) ) ){
        echo  '<div id="content" class="site-content"><div class="row">';
    }
            
