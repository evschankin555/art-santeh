<?php

use Elementor\Base_Data_Control;

defined( 'ABSPATH' ) || die();
if (!class_exists('Fmtpl_Select2')) {
    class Fmtpl_Select2 extends Base_Data_Control {

        /**
         * Control identifier
         */
        const TYPE = 'fmtpl-select2';

        /**
         * Set control type.
         */
        public function get_type() {
            return self::TYPE;
        }

        public function enqueue() {
            wp_enqueue_script(
                'fmtpl-select2',
                FAMI_TPL_URL.'assets/js/select2.js',
                ['jquery'],
                FAMI_TPL_VER
            );
            $localize_data = [
                'select2Secret' => wp_create_nonce( 'Fmtpl_Select2_Secret' ),
            ];
            wp_localize_script(
                'fmtpl-select2',
                'fmtpl_var',
                $localize_data
            );
        }

        /**
         * Get select2 control default settings.
         *
         * Retrieve the default settings of the select2 control. Used to return the
         * default settings while initializing the select2 control.
         *
         * @access protected
         *
         * @return array Control default settings.
         */
        protected function get_default_settings() {
            return [
                'options' => [],
                'multiple' => false,
                'select2options' => [],
            ];
        }

        /**
         * Render select2 control output in the editor.
         *
         * Used to generate the control HTML in the editor using Underscore JS
         * template. The variables for the class are available using `data` JS
         * object.
         *
         * @access public
         */
        public function content_template() {
            $control_uid = $this->get_control_uid();
            ?>
            <div class="elementor-control-field">
                <# if ( data.label ) {#>
                <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
                <# } #>
                <div class="elementor-control-input-wrapper">
                    <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                    <select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
                        <# _.each( data.options, function( option_title, option_value ) {
                        var value = data.controlValue;
                        if ( typeof value == 'string' ) {
                        var selected = ( option_value === value ) ? 'selected' : '';
                        } else if ( null !== value ) {
                        var value = _.values( value );
                        var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
                        }
                        #>
                        <option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
                        <# } ); #>
                    </select>
                </div>
            </div>
            <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>
            <?php
        }
    }
}

