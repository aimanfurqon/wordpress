jQuery(document).ready(function($) {

    $('#menu-button').click(function(){
        $('body').toggleClass('primary-menu-open');

        $('.btn-menu-close').click(function(){
            $('body').removeClass('primary-menu-open');
        });
    });

    
    
    $('.main-navigation ul .menu-item-has-children').prepend('<button class="submenu-opener"></button>');

    $('.main-navigation ul li .submenu-opener').click(function(){
        $('.main-navigation .sub-menu').slideToggle();
        $(this).toggleClass('active');
    });

    $('.main-navigation').append('<button class="btn-menu-close"></button>');
    
    $('.top-menu').meanmenu({
        meanScreenWidth: "767",
        meanRevealPosition: "center"
    });
                
    // Owl Carousal for Team Section 
    var owl;    

    owl = $('#team-slider');
    
    owl.owlCarousel({ 
        center: true,
        loop: true,
        autoWidth: true,
        nav: true,
        dots: false,
        URLhashListener: true,
        mouseDrag: false,
        autoplay: false,
        autoplayTimeout: 10000,
        onTranslated: doTranslated,
        onInitialized: doInitialized,
        onResized: doResized,
    });

    function doInitialized(e){
        setTimeout(function(){ carousel = owl.data('owlCarousel');
        carousel.invalidate('all');
        carousel.refresh(); 
        jQuery(".owl-stage").addClass("nomargin");}, 0);        
    }
    function doResized(e){
    
    }
    function doTranslated(e){
        carousel = owl.data('owlCarousel');
        if (carousel._current==3){
            jQuery(".owl-stage").addClass("nomargin");
        } else {
            jQuery(".owl-stage").removeClass("nomargin");
        }
    }
    
    /** Arrow Down */
    $(".arrow-down").click(function() {
        $('html, body').animate({
            scrollTop: $("#next_section").offset().top
        }, 2000);
    });
    
    /* Custom Scroll Bar */
    if( $('.testimonial .testimonial-holder .col-right').length > 0 ){
        $('.text-holder').each(function(){ 
            new PerfectScrollbar($(this)[0]); 
        });
    }

    if( $('.testimonial .testimonial-holder .col-left').length > 0 ){
        $('.text-holder').each(function(){ 
            new PerfectScrollbar($(this)[0]); 
        });
    }

    var winWidth = $( window ).width();
    if(winWidth > 768){
        $(".top-menu ul li a").focus(function() {
            $(this).parents("li").addClass("focus");
        }).blur(function() {
            $(this).parents("li").removeClass("focus");
        });
    }
});
