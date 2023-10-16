<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$show_only_register = false;
if (isset($_GET['from_type']) && $_GET['from_type'] == 'register'){
    $show_only_register = true;
}
do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ('yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) :
    $show_only_register = false;
?>
<div class="u-columns row" id="customer_login">
	<div class="u-column1 col-md">
<?php
endif;

if ($show_only_register){
    get_template_part('woocommerce/myaccount/fm-register');
} else {
    get_template_part('woocommerce/myaccount/fm-login');
}
if ('yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
	</div>
	<div class="u-column2 col-md">
        <?php get_template_part('woocommerce/myaccount/fm-register');?>
	</div>
</div>
<?php endif; ?>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>