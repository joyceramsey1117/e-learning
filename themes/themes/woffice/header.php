<?php
/**
 * The Header of WOFFICE
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!-- MAKE IT RESPONSIVE -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<?php $hide_seo = woffice_get_settings_option('hide_seo'); 
		echo ($hide_seo == 'yep') ? '<meta name="robots" content="noindex">' : ''; ?>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php // GET FAVICONS
		woffice_favicons();
		?>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
		<![endif]-->
		<?php wp_head(); ?>
	</head>
	
	<?php // We add a class if the navigation horizontal : 
	$menu_layout = woffice_get_settings_option('menu_layout');
	$menu_class = ($menu_layout == "horizontal" || isset($_GET['horizontal_menu'])) ? "menu-is-horizontal" : "menu-is-vertical";

    /**
     * Menu layout for Woffice
     *
     * @param string
     *
     * @return string - must be either "menu-is-horizontal" or "menu-is-vertical"
     */
    $menu_class = apply_filters('woffice_menu_layout', $menu_class);

    //IF Fixed we add a nav class
    $header_fixed = woffice_get_settings_option('header_fixed');
    $extra_navbar_class = ( $header_fixed == "yep" ) ? 'has_fixed_navbar' :'';

    $nav_opened_state = woffice_get_navigation_state();
    $sidebar_state = woffice_get_sidebar_state();
    $sidebar_show_class = ($sidebar_state != 'show') ? 'sidebar-hidden' : '';

	$design_update = woffice_get_settings_option('design_update');
	$design_update_class = ($design_update == "2.X") ? "woffice-2-5" : "";

    /**
     * Filter to change the design version
     *
     * @param string $design_update_class - you can use "woffice-2-x"
     */
    $design_update_class = apply_filters('woffice_design_version', $design_update_class);

	$is_blank_template = woffice_is_current_page_using_blank_template();
	$blank_template_class = ($is_blank_template) ? 'is-blank-template' : '';


    /**
     * SEO hentry class applied to the container
     *
     * @param string
     */
    $hentry_class = apply_filters('woffice_hentry_class', 'hentry');
	?>

	<!-- START BODY -->
	<body <?php body_class($menu_class . ' ' . $sidebar_show_class . ' ' . $extra_navbar_class .' '.$design_update_class . ' ' . $blank_template_class); ?>>

		<?php // If Unyson isn't enabled :
		if(!function_exists('fw_print')) :
			woffice_unyson_is_required();
		endif; ?>

		<?php // We add a class if the menu is closed by default
        $navigation_hidden_class = woffice_get_navigation_class();
		?>
	
		<div id="page-wrapper" <?php echo (!$nav_opened_state) ? 'class="menu-is-closed"':''; ?>>

			<?php
            /*
             * The header part is removed on the blank template
             */
			if(!$is_blank_template): ?>

                <!-- STARTING THE MAIN NAVIGATION (left side) -->
                <nav id="navigation" class="<?php echo esc_attr($navigation_hidden_class); ?> mobile-hidden">
                    <?php
                    /*
                     * Display the menu
                     */
                    if ( !is_user_logged_in() && has_nav_menu('public')) :
                            $settings_menu_public = array('theme_location' => 'public','menu_class' => 'main-menu', 'menu' => '','container' => '','menu_id' => 'main-menu');
                            wp_nav_menu( $settings_menu_public );
                    else :
                        if ( has_nav_menu('primary') ) :
                            $settings_menu_on = array('theme_location' => 'primary','menu_class' => 'main-menu', 'menu' => '','container' => '','menu_id' => 'main-menu');
                            wp_nav_menu( $settings_menu_on );
                        else :
                            wp_page_menu(array('menu_id' => 'main-menu', 'menu_class'  => 'main-menu', 'show_home' => true));
                        endif;
                    endif; ?>
                </nav>
                <!-- END MAIN NAVIGATION -->


                <!-- START HEADER -->
                <?php // CHECK FROM OPTIONS
                $header_user = woffice_get_settings_option('header_user');
                $header_user_class = ($header_user == "yep") ? 'has-user': 'user-hidden';
                ?>
                <header id="main-header" class="<?php echo esc_attr($navigation_hidden_class) . ' ' . esc_attr($header_user_class ).' '. esc_attr($sidebar_show_class); ?>">

                    <nav id="navbar" class="<?php echo esc_attr($extra_navbar_class ); ?>">
                        <div id="nav-left">
                            <!-- NAVIGATION TOGGLE -->
                            <?php $nav_trigger_icon = (!$nav_opened_state) ? 'fa-bars' : 'fa-arrow-left' ;?>
                            <a href="javascript:void(0)" id="nav-trigger"><i class="fa <?php echo esc_attr($nav_trigger_icon) ?>"></i></a>
                            <?php $val = get_option('runnig_type_option');
                            if (get_option('runnig_type_option')) {
                                echo '<marquee behavior="" direction="" width="" style="text-transform: uppercase;font-weight: bolder;font-size: 35px;color:#fbe528;">'.trim($val).'</marquee>';
                            } ?>

                            <?php $val = get_option('runnig_type_option');
                            // if (current_user_can('administrator')) {
                            //     echo '
                            //     <button class="button btn btn-default" id="editRunnigType">Edit</button>
                            //     <form id="typeForm" method="POST" action="" id="ajax_form" style="display: none;width:100%;">
                            //     <input  type="text" id="runnigType" name="runnigType" value="'.trim($val).'"><input class="button btn btn-default" type="button" id="btn" value="Save" /></form>';
                            // }
                            ?> 
                            <script>

$("#editRunnigType").click(function() { 

$("#typeForm").toggle();
});

                            </script>

                        <!-- EXTRA BUTTONS ABOVE THE SIDBAR -->
                        <div id="nav-buttons">

                            <?php // FETCHING SIDEBAR INFO
                            if($sidebar_state == 'show' || $sidebar_state == 'hide') :  ?>
                                <!-- SIDEBAR TOGGLE -->
                                <a href="javascript:void(0)" id="nav-sidebar-trigger"><i class="fa fa-arrow-right"></i></a>
                            <?php endif; ?>

                            <?php // CHECK FROM OPTIONS
                            $header_search = woffice_get_settings_option('header_search');
                            /**
                             * Override the search feature
                             *
                             * @param string $header_search Value allowed: yep|nope
                             */
                            // $header_search = apply_filters( 'woffice_header_search_enabled', $header_search);
                            // if ($header_search == "yep") :  ?>
                                <!-- SEACRH FORM -->
                                <!-- <a href="javascript:void(0)" id="search-trigger"><i class="fa fa-search"></i></a> -->
                            <?php // endif; ?>

                            <?php // WOOCOMMERCE CART TRIGGER
                            /**
                             * You can disable the minicart in the header form there
                             *
                             * @param bool
                             */
                            $minicart_header_enabled = apply_filters('woffice_show_minicart_in_header', true);

                            if (function_exists('is_woocommerce') && $minicart_header_enabled) : ?>
                                <?php //is cart empty ?
                                if ( WC()->cart->get_cart_contents_count() > 0 ) :
                                    $cart_url_topbar = "javascript:void(0)";
                                    $cart_classes = 'active cart-content';
                                else :
                                    $cart_url_topbar = get_permalink( wc_get_page_id( 'shop' ) );
                                    $cart_classes = "";
                                endif; ?>
                                <a href="<?php echo $cart_url_topbar; ?>" id="nav-cart-trigger" title="<?php _e( 'View your shopping cart', 'woffice' ); ?>" class="<?php echo $cart_classes; ?>">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php echo (WC()->cart->get_cart_contents_count() > 0) ? WC()->cart->get_cart_subtotal() : ''; ?>
                                </a>
                            <?php endif; ?>

                            <?php // Notification
                            // if ( woffice_bp_is_active( 'notifications' ) && is_user_logged_in() ) : ?>
                                <!-- <a href="javascript:void(0)" id="nav-notification-trigger" title="<?php // _e( 'View your notifications', 'woffice' ); ?>" class="<?php // echo (bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) >= 1) ? "active" : "" ?>">
                                    <i class="fa fa-bell"></i> -->
                                </a>
                            <?php// endif; ?>

                        </div>                            

                            <!-- USER INFORMATIONS -->
                            <?php
                            // CHECK FROM OPTIONS
                            $header_user = woffice_get_settings_option('header_user');
                            if (is_user_logged_in()) :
                                if ($header_user == "yep") : ?>
                                    <div id="nav-user" class="clearfix <?php echo (function_exists('bp_is_active')) ? 'bp_is_active' : ''; ?>">
                                                                            <a href="javascript:void(0);" id="user-thumb">
                                            <?php
                                            $name_to_display = woffice_get_name_to_display();
                                            printf( _x( 'Hi <strong class="font-weight-bold">%s</strong>!', "Hi USERNAME - It's the welcome message in the header", 'woffice' ), $name_to_display );
                                            ?>
                                            <?php // GET CURRENT USER ID
                                            $user_ID = get_current_user_id();
                                            echo get_avatar($user_ID);
                                            ?>
                                        </a>
                                        <?php if(function_exists('bp_is_active')) : ?>
                                            <a href="javascript:void(0)" id="user-close">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div id="nav-user" class="clearfix <?php echo (function_exists('bp_is_active')) ? 'bp_is_active' : ''; ?>">
                                        <a href="<?php echo wp_logout_url() ?>" id="user-login"><i class="fa fa-sign-out-alt"></i></a>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <div id="nav-user" class="clearfix <?php echo (function_exists('bp_is_active')) ? 'bp_is_active' : ''; ?>">
                                    <?php // SHOW LOGIN BUTTON
                                    $header_login = woffice_get_settings_option('header_login');
                                    if (!empty($header_login) && $header_login == "yep") {
                                        echo '<a href="'.wp_login_url().'" id="user-login"><i class="fa fa-sign-in-alt"></i></a>';
                                    } ?>
                                </div>
                            <?php endif; ?>

                            <?php // CHECK IF LOGO NEEDS TO BE SHOW
                            $header_logo_hide = woffice_get_settings_option('header_logo_hide');
                            if ($header_logo_hide == false) { ?>
                                <!-- START LOGO -->
                                <div id="nav-logo">

                                    <?php
                                    /**
                                    * The url of the logo in the header. By default, returns the home url
                                    *
                                    * @param string $url
                                    */
                                    $logo_link = apply_filters('woffice_logo_link_to', home_url( '/' ) );
                                    ?>

                                    <a href="<?php echo esc_url( $logo_link ); ?>">
                                        <?php
                                        $header_logo = woffice_get_settings_option('header_logo');
                                        // IF THERE IS A LOGO :
                                        if(!empty($header_logo)) :
                                            echo'<img src="'. esc_url($header_logo["url"]) .'" alt="Logo Image">';
                                        else:
                                            echo'<img src="'. get_template_directory_uri() .'/images/logo.png" alt="Logo Image">';
                                        endif; ?>
                                    </a>
                                </div>
                            <?php } ?>                            
                        </div>



                    </nav>

                    <!-- HIDDEN PARTS TRIGGERED BY JAVASCRIPT -->

                    <?php // CHECK FROM OPTIONS
                    $header_user = woffice_get_settings_option('header_user');
                    if ($header_user == "yep" && function_exists('bp_is_active')) :
                        woffice_user_sidebar();
                    endif; ?>

                    <?php // WOOCOMMERCE CART CONTENT
                    if (function_exists('is_woocommerce')) { Woffice_WooCommerce::print_mini_cart(); } ?>

                    <?php // Notification content :
                    if ( woffice_bp_is_active( 'notifications' ) && is_user_logged_in() ) { woffice_notifications_menu(); } ?>

                    <?php // CHECK FROM OPTIONS
                    $header_search = woffice_get_settings_option('header_search');
                    if ($header_search == "yep") :  ?>
                        <!-- START SEARCH CONTAINER - WAITING FOR FIRING -->
                        <div id="main-search">
                            <div class="container">
                                <?php //GET THE SEARCH FORM
                                get_search_form(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    /*
                     * We render the alerts
                     */
                    woffice_alerts_render(); ?>

                </header>
                <!-- END NAVBAR -->


                <!-- STARTING THE SIDEBAR (right side) + content behind -->
                <?php
                // FETCHING SIDEBAR POSITION
                if ($sidebar_state == "show"){
                    $class = 'with-sidebar';
                } elseif ($sidebar_state == "hide") {
                    /*We need to check if the user has already clicked the button*/
                    if( !isset($_COOKIE['Woffice_sidebar_position']) || ! apply_filters( 'woffice_cookie_sidebar_enabled', false ) ) {
                        $class = 'sidebar-hidden';
                    }
                    else {
                        $class = '';
                    }
                } else {
                    $class = 'full-width';
                }
                ?>

                <!-- START CONTENT -->
                <section id="main-content" class="<?php echo esc_attr($class) .' '.esc_attr($navigation_hidden_class) .' '. esc_attr($hentry_class); ?>">

                    <?php // GET SIDEBAR
                    if($sidebar_state == 'show' || $sidebar_state == 'hide') :
                        get_sidebar();
                    endif; ?>

                    <!-- END SIDEBAR -->

    <?php else:

		echo '<section id="main-content" class="full-width navigation-hidden '. esc_attr($hentry_class) .'">';

	endif;