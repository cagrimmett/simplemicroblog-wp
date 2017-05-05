<?php
class MicroBlogVerificationPage
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
            'Settings Admin', 
            'Micro.blog Verification', 
            'manage_options', 
            'microblogverification-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h1>Micro.blog Verification</h1>
            <form method="post" action="microblog_verification.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'microblogverification-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Micro.blog Verification', // Title
            array( $this, 'print_section_info' ), // Callback
            'microblogverification-admin' // Page
        );  

        add_settings_field(
            'microblog_username', // username
            'Micro.blog Username', // Title 
            array( $this, 'microblog_username_callback' ), // Callback
            'microblogverification-admin', // Page
            'setting_section_id' // Section           
        );        
    }

      /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['microblog_username'] ) )
            $new_input['microblog_username'] = sanitize_text_field( $input['microblog_username'] );


        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
     	print 'This allows you to easily <a href="http://help.micro.blog/2017/web-site-verification/" target="_blank">verify this website for your Micro.blog account</a>. '; 
        print 'Enter your Micro.blog username below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function microblog_username_callback()
    {
        printf(
            '<input type="text" id="microblog_username" name="my_option_name[microblog_username]" value="%s" />',
            isset( $this->options['microblog_username'] ) ? esc_attr( $this->options['microblog_username']) : ''
        );
    }

}
if( is_admin() )
    $my_settings_page = new MicroBlogVerificationPage();
    
function microblog_verification_header_output() {
	$microblog_verification = get_option( 'my_option_name' );
	$microblog_username = $microblog_verification['microblog_username'];
	if ( ! strlen($microblog_username) == 0 ) {
		echo "<link href=\"https://micro.blog/$microblog_username\" rel=\"me\" />";		
	}
}
add_action('wp_head', 'microblog_verification_header_output');