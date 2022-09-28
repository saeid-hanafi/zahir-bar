<?php
/**
 * Created by PhpStorm.
 * User: alireza azami (hamyar.co)
 * Date: 5/29/2021
 * Time: 12:59 AM
 */


class activator
{
    protected static $_instance = null;
    private $api_url='https://themesazan.ir/themes/907/907org/wp-json/ninezeroseven_api/v1';
//    private $api_url='http://ninezeroseven.test/api.php';
    private $old_api_url='http://support.webcreations907.com/wp-admin/admin-ajax.php';
    private $token='00000000-0000-0000-0000-000000000000';

    public function __construct()
    {
        $this->define_hooks();
        $this->set_option();
    }

    public function define_hooks()
    {
        add_filter('pre_http_request',[$this,'change_response'],10,3);

        if (defined('DOING_AJAX') && DOING_AJAX){
            add_action('wp_ajax_wbc_register',[$this,'remove_all_action'],1);
            add_action('wp_ajax_wbc_deactivate_license',[$this,'remove_all_action'],1);
        }
        add_filter('wbc907_theme_plugins_filter',[$this,'change_plugin_download_link'],1,1);

//        add_filter('redux/wbc907_data/field/class/typography',[$this,'change_typography'],1,1);
        add_filter('redux/wbc907_data/field/typography/custom_fonts',[$this,'add_custom_font'],1,1);
    }

    public function add_custom_font($fonts){
        $fonts['فونت فارسی']=['IRANSans'=>'IRANSans','Yekan'=>'Yekan'];
        return $fonts;
    }

    //not need
    public function change_typography($field){
        return $field;
    }

    public function remove_all_action(){
        remove_all_actions('wp_ajax_wbc_register');
        remove_all_actions('wp_ajax_wbc_deactivate_license');
    }

    public function change_plugin_download_link($plugins){
        foreach ($plugins as $key => $plugin){
            if (isset($plugin['source']) && (strpos($plugin['source'],$this->old_api_url)!==false || $plugin['source']=='premium') ){
                if ($plugin['source']=='premium'){
                    $plugins[$key]['source']=add_query_arg(['action'=>'wbc_get_download','package'  => $plugin['slug']],$this->api_url);
                }else{
                    $plugins[$key]['source']=str_replace($this->old_api_url,$this->api_url,$plugin['source']);
                }
            }
        }
        remove_all_filters('wbc907_theme_plugins_filter');
        return $plugins;
    }

    public function change_response($false,$arg,$url){
        if (strpos($url,$this->old_api_url)===false || strpos($url,$this->token)===false || strpos($url,'wbc_get_download')!==false) return false;
        $url= str_replace($this->old_api_url,$this->api_url,$url);
         return wp_remote_get($url,$arg);
    }

    public function set_option()
    {
        if (get_option('wbc907_theme_registered')!=='1' || (int)get_option('_transient_timeout_wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION ))<time()){
            update_option('wbc907_theme_registered', true );
            update_option('wbc907_theme_token', $this->token );
            set_transient( 'wbc907_theme_token', $this->token , 10 * DAY_IN_SECONDS );
            if (defined('WBC907_CORE_PLUGIN_VERSION'))
                set_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION ), get_option('wbc907_theme_token'), 10 * DAY_IN_SECONDS );
        }

    }



    public static function get_instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}
activator::get_instance();