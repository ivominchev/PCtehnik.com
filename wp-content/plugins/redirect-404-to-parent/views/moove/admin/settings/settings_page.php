<div class="wrap moove-redirect-settings-plugin-wrap">

	<h1><?php _e('Redirect 404 Plugin Settings','moove'); ?></h1>

    <?php
        $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
        if ( isset( $current_tab ) &&  $current_tab !== '' ) :
            $active_tab = $current_tab;
        else :
            $active_tab = "moove_redirect";
        endif; // end if

    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=moove-redirect-settings&tab=moove_redirect" class="nav-tab <?php echo $active_tab == 'moove_redirect' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Redirect Rules','moove'); ?>
        </a>

        <a href="?page=moove-redirect-settings&tab=moove_redirect_new" class="nav-tab <?php echo $active_tab == 'moove_redirect_new' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Add New Redirect Rule','moove'); ?>
        </a>

        <a href="?page=moove-redirect-settings&tab=plugin_documentation" class="nav-tab <?php echo $active_tab == 'plugin_documentation' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Documentation','moove'); ?>
        </a>
    </h2>
    <div class="moove-form-container <?php echo $active_tab; ?>">
        <a href="http://mooveagency.com" target="blank" title="WordPress agency"><span class="moove-logo"></span></a>
        <?php
        if( $active_tab == 'moove_redirect' ) : ?>
            <?php echo Moove_Redirect_View::load( 'moove.admin.settings.redirects' , true ); ?>
        <?php elseif( $active_tab == 'moove_redirect_new' ): ?>
            <?php echo Moove_Redirect_View::load( 'moove.admin.settings.add_new_redirect' , true ); ?>
        <?php elseif( $active_tab == 'plugin_documentation' ): ?>
            <?php echo Moove_Redirect_View::load( 'moove.admin.settings.documentation' , true ); ?>
        <?php endif; ?>
    </div>
    <!-- moove-form-container -->
</div>
<!-- wrap -->