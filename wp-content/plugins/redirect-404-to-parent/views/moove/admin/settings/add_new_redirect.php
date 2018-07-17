<?php
    $message    = '';
    $alert_box  = '';
    $moove_controller = new Moove_Redirect_Controller();
    if ( isset( $_POST ) && ! empty( $_POST ) ) :
        $base       = sanitize_text_field( wp_unslash( $_POST['moove-redirect-base'] ) );
        $status     = intval( wp_unslash( $_POST['moove-redirect-status'] ) );
        $message    = '';
        $error      = false;
        if ( '' !== $base  && is_numeric( $status ) ) :
            $validated = $moove_controller->moove_validate_url( $base );
            if ( $validated['success'] ) :
                $options = get_option( 'moove_404_redirect_options' );
                $options = is_array( $options ) ? $options : array();
                if ( ! is_array( $options ) ) :
                    $options = array();
                endif;
                if ( "/" !== substr( $base, -1 ) ) :
                    $base = $base . '/';
                endif;
                $parts = explode( "/", $base );
                $new_entry = array(
                    'base'      =>  $base,
                    'status'    =>  $status
                );
                if (  isset( $options[ count( $parts ) ] ) &&  in_array( $new_entry, $options[ count( $parts ) ] ) ) :
                    $message    = '<h4 class="moove-error">'.__('This rule already exists! Please try another one.','moove').'</h4>';
                    $error      = true;
                else:
                    $options[ count( $parts ) ][] = $new_entry;
                    krsort( $options );
                    update_option( 'moove_404_redirect_options', $options );
                    $message    = '<h4 class="moove-success">'.__('The rule was added successfully!','moove').'</h4>';
                    $error      = false;
                endif;
            else:
                $message    = $validated['error'];
                $error      = true;
            endif;
        else:
            $message    = '<h4 class="moove-error">'.__('Please fill out the "BASE URL" field!','moove').'</h4>';
            $error      = true;
        endif;
        ob_start(); ?>
            <div class="moove-redirect-message-cnt <?php echo ( $error ) ? 'moove-error-box' : 'moove-success-box'; ?>">
                <?php echo $message; ?>
            </div>
            <!-- moove-redirect-message-cnt -->
        <?php
        $alert_box = ob_get_clean();
    endif; ?>

    <div class="moove-add-new-redirect-404">
        <h1><?php _e( 'Add new 404 Redirect rule', 'moove' ); ?></h1>
        <?php echo $alert_box; ?>
        <div class="moove-redirect-content-left">
            <form action="" class="moove-redirect-box" method="post">
                <h4><?php _e( 'Redirect Settings', 'moove' ); ?></h4>
                <hr>
                <label for="moove-redirect-base"><?php _e( 'Base url: ', 'moove' ); ?>
                    <br/>
                    <span class="moove-redirect-base"><?php echo home_url('/'); ?><span class="moove-base-url"></span>
                    </span>
                </label>
                <input type="text" value="" name="moove-redirect-base" id="moove-redirect-base" />
                <br/><br/><label for="moove-redirect-to"><?php _e( 'Status: ', 'moove' ); ?></label><br/>
                <select name="moove-redirect-status" id="moove-redirect-status">
                    <option value="301">301 Moved Permanently</option>
                    <option value="302">302 Found</option>
                    <option value="307">307 Temporary Redirect</option>
                </select>
                <br />
                <button type="submit" class="button moove-redirect-button"><?php _e( 'Add rule', 'moove' ); ?></button>
            </form>
        </div>
        <!-- moove-redirect-content-left -->
        <div class="moove-redirect-content-right">
            <div class="moove-redirect-information-box">
                <h4><i></i><?php _e( 'How it works', 'moove' ); ?></h4>
                <hr>
                <h4>Example:</h4>
                <br>
                <div class="moove-redirect-example">
                    <table>
                        <tr>
                            <td><?php _e( 'Base URL (set up in this plugin as a rule):', 'moove' ); ?></td>
                            <td><strong><?php echo home_url('/'); ?>sample-page/</strong></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'Target URL:', 'moove'); ?></td>
                            <td><strong><?php echo home_url('/'); ?>sample-page/non-existing-page</strong></td>
                        </tr>
                    </table>
                </div>
                <p><?php _e( "In this case if a visitor try to access the *TARGET URL*, WordPress returns a 404 error/page by default because the page/post doesn't exist. ", "moove"); ?></p>
                <p>
                    <?php printf( esc_html__( 'This plugin will automatically redirect the visitor to %s instead of letting the visitor end up on a 404 page.', 'moove' ), '<strong>'.home_url('/').'sample-page/'.'</strong>' ); ?>
                </p>
            </div>
            <!-- information-box -->
        </div>
        <!-- moove-redirect-content-right -->
    </div>
    <!-- moove-add-new-redirect-404 -->

