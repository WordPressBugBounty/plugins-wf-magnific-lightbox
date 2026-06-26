<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class WfMlightboxSettingsPage
{

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            __('WF Magnific Lightbox Settings','wf-magnific-lightbox'),
            __('WF Magnific Lightbox','wf-magnific-lightbox'),
            'manage_options',
            'wf-magnific-lightbox',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = array(
          'wf-magnific-lightbox-copyright' => get_option( 'wf-magnific-lightbox-copyright' ),
          'wf-magnific-lightbox-gallery' => get_option( 'wf-magnific-lightbox-gallery' )
        );

        ?>
        <div class="wrap">
            <h2><?php esc_html_e('WF Magnific Lightbox','wf-magnific-lightbox') ?></h2>
            <p><?php esc_html_e('Here you can change some stuff or leave the default options.','wf-magnific-lightbox') ?></p>
            <hr>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'wf-magnific-lightbox' );
                do_settings_sections( 'wf-magnific-lightbox' );
                submit_button();
            ?>
            </form>
            <hr>
            <small><?php echo wp_kses( __('Thanks to Dmitry Semenov for the original script (<a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank" title="Magnific Popup - Responsive jQuery Lightbox Plugin">Magnific Popup</a>) and his permission to use it in this wordpress plugin.','wf-magnific-lightbox'), array( 'a' => array( 'href' => array(), 'target' => array(), 'title' => array() ) ) ); ?></small>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        $languages = array();
        if (function_exists('wf_get_languages')) {
          $languages = wf_get_languages();
        }

        register_setting(
            'wf-magnific-lightbox', // Option group
            'wf-magnific-lightbox-copyright', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'wf-magnific-lightbox-copyright', // ID
            __('Copyright','wf-magnific-lightbox'), // Title
            array( $this, 'print_copyright_section_info' ), // Info Callback
            'wf-magnific-lightbox' // Page
        );

        add_settings_field(
            'showCopyright',
            __('Show Copyright Info','wf-magnific-lightbox'),
            array( $this, 'render_checkbox' ),
            'wf-magnific-lightbox',
            'wf-magnific-lightbox-copyright',
            array(
                'option' => 'wf-magnific-lightbox-copyright',
                'field' => 'showCopyright'
            )
        );

        //copyrightPrefix is a language depending option
        foreach ($languages as $lang) {
          add_settings_field(
              'copyrightPrefix' . '-' . $lang,
              /* translators: %s: language code shown in parentheses, e.g. (en). */
              count($languages)>1 ? sprintf( esc_html__('Copyright Prefix %s','wf-magnific-lightbox'),'('.$lang.')' ) : __('Copyright Prefix','wf-magnific-lightbox'),
              array( $this, 'render_text_input' ),
              'wf-magnific-lightbox',
              'wf-magnific-lightbox-copyright',
              array(
                  'option' => 'wf-magnific-lightbox-copyright',
                  'field' => 'copyrightPrefix',
                  'lang' => $lang
              )
          );
        }

        register_setting(
            'wf-magnific-lightbox', // Option group
            'wf-magnific-lightbox-gallery', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'wf-magnific-lightbox-gallery', // ID
            __('Gallery','wf-magnific-lightbox'), // Title
            array( $this, 'print_gallery_section_info' ), // Info Callback
            'wf-magnific-lightbox' // Page
        );

        add_settings_field(
            'presetMediaFilelink',
            __('"Link to Media File" as default value for new galleries','wf-magnific-lightbox'),
            array( $this, 'render_checkbox' ),
            'wf-magnific-lightbox',
            'wf-magnific-lightbox-gallery',
            array(
                'option' => 'wf-magnific-lightbox-gallery',
                'field' => 'presetMediaFilelink'
            )
        );

        add_settings_field(
            'forceMediaFilelink',
            __('Force the "Link to Media File" as default value for galleries','wf-magnific-lightbox'),
            array( $this, 'render_checkbox' ),
            'wf-magnific-lightbox',
            'wf-magnific-lightbox-gallery',
            array(
                'option' => 'wf-magnific-lightbox-gallery',
                'field' => 'forceMediaFilelink'
            )
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        if ( ! is_array( $input ) ) {
            return ( '1' === $input ) ? true : sanitize_text_field( $input );
        }
        $sanitized = array();
        foreach ( $input as $key => $value ) {
            // Keep keys intact (e.g. language codes and "copyrightPrefix") and sanitize values recursively.
            $sanitized[ $key ] = $this->sanitize( $value );
        }
        return $sanitized;
    }

    /**
     * Print the Copyright Section text
     */
    public function print_copyright_section_info()
    {
        print '';
    }

    /**
     * Print the Gallery Section text
     */
    public function print_gallery_section_info()
    {
        print '';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function render_checkbox($args)
    {
        printf(
            '<input type="checkbox" name="%s[%s]" %s value="1" />',
            esc_attr( $args['option'] ),
            esc_attr( $args['field'] ),
            isset( $this->options[$args['option']][$args['field']] ) ? 'checked' : ''
        );
    }

    /**
    * Get the settings option array and print one of its values
    * render_text_input is a language depending input field
    */
    public function render_text_input($args)
    {
      printf(
          '<input type="text" name="%s[%s][%s]" value="%s" />',
          esc_attr( $args['option'] ),
          esc_attr( $args['lang'] ),
          esc_attr( $args['field'] ),
          isset($this->options[$args['option']][$args['lang']][$args['field']]) ? esc_attr( $this->options[$args['option']][$args['lang']][$args['field']] ) : ''
      );
    }
}

if ( is_admin() ) {
    $my_settings_page = new WfMlightboxSettingsPage();
}
