<h2><?php esc_html_e( 'Login', 'cenos' ); ?></h2>

<form class="woocommerce-form woocommerce-form-login login" method="post">

    <?php do_action( 'woocommerce_login_form_start' ); ?>
    <?php echo ! empty( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="username"><?php esc_html_e( 'Username or email address', 'cenos' ); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php esc_attr_e( 'Email address *', 'cenos' ); ?>" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="password"><?php esc_html_e( 'Password', 'cenos' ); ?>&nbsp;<span class="required">*</span></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php esc_attr_e( 'Password *', 'cenos' ); ?>" type="password" name="password" id="password" autocomplete="current-password" />
    </p>

    <?php do_action( 'woocommerce_login_form' ); ?>
    <p class="woocommerce-LostPassword lost_password">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'cenos' ); ?></span>
        </label>
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'cenos' ); ?></a>
    </p>
    <p class="form-row">
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'cenos' ); ?>"><?php esc_html_e( 'Log in', 'cenos' ); ?></button>
    </p>
    <?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
