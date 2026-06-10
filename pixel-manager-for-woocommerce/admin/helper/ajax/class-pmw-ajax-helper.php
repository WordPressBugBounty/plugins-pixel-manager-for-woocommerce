<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 */

if(!defined('ABSPATH')){
  exit; //Exit if accessed directly
}
if(!class_exists('PMW_AjaxHelper')):
  class PMW_AjaxHelper extends PMW_AdminHelper{
    protected $PMW_API;
    public function __construct(){
      $this->includes();
      $this->PMW_API = new PMW_AdminAPIHelper();
      add_action('wp_ajax_pmw_pixels_save', array($this,'pmw_pixels_save') );
      add_action('wp_ajax_pmw_check_privecy_policy', array($this,'pmw_check_privecy_policy') );
      add_action('wp_ajax_pmw_notice_dismiss', array($this,'pmw_notice_dismiss') );
      add_action('wp_ajax_pmw_clean_debug_logs', array($this,'pmw_clean_debug_logs') );
    }

    public function includes(){
      if(!class_exists('PMW_AdminAPIHelper')){
        require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-api-helper.php');
      }
    }
    
    public function get_post_pmw_pixels_option_sanitize(){
      //$pixels = array("facebook_pixel", "pinterest_pixel", "snapchat_pixel");
      $return = array(
        "user" => array(
          "email_id" => isset($_POST["email_id"])?sanitize_email($_POST["email_id"]):""
        ),
        "generate_lead_from" => isset($_POST["generate_lead_from"])?sanitize_text_field($_POST["generate_lead_from"]):"",
        "gtm_container" => array(
          "load_mode" => isset($_POST["gtm_container_load_mode"]) ? sanitize_text_field($_POST["gtm_container_load_mode"]) : "default_ptm",
          "custom_container_id" => isset($_POST["gtm_container_custom_id"]) ? sanitize_text_field($_POST["gtm_container_custom_id"]) : ""
        ),
        "google_ads_conversion" => array(
          "id" => isset($_POST["google_ads_conversion_id"])?sanitize_text_field($_POST["google_ads_conversion_id"]):"",
          "label" => isset($_POST["google_ads_conversion_label"])?sanitize_text_field($_POST["google_ads_conversion_label"]):"",
          "is_enable" => isset($_POST["google_ads_conversion_is_enable"])?sanitize_text_field($_POST["google_ads_conversion_is_enable"]):false
        ),
        "google_tag" => array(
          "for" => isset($_POST["google_tag_for"]) ? sanitize_text_field($_POST["google_tag_for"]) : "",
          "id" => isset($_POST["google_tag_id"])?sanitize_text_field($_POST["google_tag_id"]):"",
          "is_enable" => isset($_POST["google_tag_is_enable"])?sanitize_text_field($_POST["google_tag_is_enable"]):false
        ),
        "google_ads_form_conversion" => array(
          "id" => isset($_POST["google_ads_form_conversion_id"])?sanitize_text_field($_POST["google_ads_form_conversion_id"]):"",
          "label" => isset($_POST["google_ads_form_conversion_label"])?sanitize_text_field($_POST["google_ads_form_conversion_label"]):"",
          "selector" => isset($_POST["google_ads_form_conversion_selector"])?sanitize_text_field($_POST["google_ads_form_conversion_selector"]):"",
          "is_enable" => isset($_POST["google_ads_form_conversion_is_enable"])?sanitize_text_field($_POST["google_ads_form_conversion_is_enable"]):false
        ),
        "fb_conversion_api" => array(
          "api_token" => isset($_POST["fb_conversion_api_token"]) ? sanitize_text_field($_POST["fb_conversion_api_token"]) : "",
          "is_enable" => isset($_POST["fb_conversion_api_is_enable"]) ? sanitize_text_field($_POST["fb_conversion_api_is_enable"]) : false,
          "test_event_code" => isset($_POST["test_event_code"]) ? sanitize_text_field($_POST["test_event_code"]) : ""
        ),
        "tiktok_conversion_api" => array(
          "api_token" => isset($_POST["tiktok_conversion_api_token"]) ? sanitize_text_field($_POST["tiktok_conversion_api_token"]) : "",
          "is_enable" => isset($_POST["tiktok_conversion_api_is_enable"]) ? sanitize_text_field($_POST["tiktok_conversion_api_is_enable"]) : false
        ),
        "pinterest_conversion_api" => array(
          "ad_account_id" => isset($_POST["pinterest_conversion_api_ad_account_id"]) ? sanitize_text_field($_POST["pinterest_conversion_api_ad_account_id"]) : "",
          "api_token" => isset($_POST["pinterest_conversion_api_token"]) ? sanitize_text_field($_POST["pinterest_conversion_api_token"]) : "",
          "is_enable" => isset($_POST["pinterest_conversion_api_is_enable"]) ? sanitize_text_field($_POST["pinterest_conversion_api_is_enable"]) : false,
          "test_events" => isset($_POST["pinterest_conversion_api_test_events"]) ? sanitize_text_field($_POST["pinterest_conversion_api_test_events"]) : ""
        ),
        /*"twitter_conversion_api" => array(
          "api_token" => isset($_POST["twitter_conversion_api_token"]) ? sanitize_text_field($_POST["twitter_conversion_api_token"]) : "",
          "is_enable" => isset($_POST["twitter_conversion_api_is_enable"]) ? sanitize_text_field($_POST["twitter_conversion_api_is_enable"]) : false
        ),
        "snapchat_conversion_api" => array(
          "api_token" => isset($_POST["snapchat_conversion_api_token"]) ? sanitize_text_field($_POST["snapchat_conversion_api_token"]) : "",
          "is_enable" => isset($_POST["snapchat_conversion_api_is_enable"]) ? sanitize_text_field($_POST["snapchat_conversion_api_is_enable"]) : false
        ),*/
        "google_ads_enhanced_conversion" => array(
          "is_enable" => isset($_POST["google_ads_enhanced_conversion_is_enable"])?sanitize_text_field($_POST["google_ads_enhanced_conversion_is_enable"]):false
        ),
        "google_ads_dynamic_remarketing" => array(
          "is_enable" => isset($_POST["google_ads_dynamic_remarketing_is_enable"])?sanitize_text_field($_POST["google_ads_dynamic_remarketing_is_enable"]):false
        ),
        "integration" => array(           
          "exclude_tax_ordertotal" => isset($_POST["exclude_tax_ordertotal"])?sanitize_text_field($_POST["exclude_tax_ordertotal"]):false,
          "exclude_shipping_ordertotal" => isset($_POST["exclude_shipping_ordertotal"])?sanitize_text_field($_POST["exclude_shipping_ordertotal"]):false,
          "exclude_fee_ordertotal" => isset($_POST["exclude_fee_ordertotal"])?sanitize_text_field($_POST["exclude_fee_ordertotal"]):false,
          "send_product_sku" => isset($_POST["send_product_sku"])?sanitize_text_field($_POST["send_product_sku"]):false,
          "roles_exclude_tracking" => isset($_POST["roles_exclude_tracking"])?sanitize_text_field($_POST["roles_exclude_tracking"]):'',
          "stop_send_user_data_ptm" => isset($_POST["stop_send_user_data_ptm"])?sanitize_text_field($_POST["stop_send_user_data_ptm"]):false,
          "conversion_api_logs" => isset($_POST["conversion_api_logs"])?sanitize_text_field($_POST["conversion_api_logs"]):false,
          "conversion_api_browser_debug" => isset($_POST["conversion_api_browser_debug"])?sanitize_text_field($_POST["conversion_api_browser_debug"]):false,
          "conversion_api_logs_payload" => ( isset($_POST["conversion_api_logs"]) && isset($_POST["conversion_api_logs_payload"]) )?sanitize_text_field($_POST["conversion_api_logs_payload"]):false
        ),
        "tracking" => array(
          "purchase_event_trigger" => isset($_POST["purchase_event_trigger"])?sanitize_text_field($_POST["purchase_event_trigger"]):''
        ),
        "axeptio" => array(
          "project_id" => isset($_POST["axeptio_project_id"])?sanitize_text_field($_POST["axeptio_project_id"]):"",
          "is_enable" => isset($_POST["axeptio_is_enable"])?sanitize_text_field($_POST["axeptio_is_enable"]):false,
          "cookies_version" => isset($_POST["axeptio_cookies_version"])?sanitize_text_field($_POST["axeptio_cookies_version"]):"",
          "cookies_consent_us" => isset($_POST["axeptio_cookies_consent_us"])?sanitize_text_field($_POST["axeptio_cookies_consent_us"]):"",
          "cookies_consent_uk" => isset($_POST["axeptio_cookies_consent_uk"])?sanitize_text_field($_POST["axeptio_cookies_consent_uk"]):"",
          "cookies_consent_cn" => isset($_POST["axeptio_cookies_consent_cn"])?sanitize_text_field($_POST["axeptio_cookies_consent_cn"]):""
        ),
        "privecy_policy" => array(
          "privecy_policy" => 1
        )
      );
      $pixels = array("google_analytics_4_pixel","facebook_pixel","pinterest_pixel","snapchat_pixel","bing_pixel","twitter_pixel","tiktok_pixel");
      if(!empty($pixels)){
        foreach($pixels as $val){
          $return[$val] = array(
            "pixel_id" => isset($_POST[$val."_id"]) ? sanitize_text_field($_POST[$val."_id"]) : "",
            "is_enable" => isset($_POST[$val."_is_enable"]) ? sanitize_text_field($_POST[$val."_is_enable"]) : false
          );
        }
      }
      return $return;
    }
    /**
     * Save Pixel data
     **/
    public function pmw_pixels_save(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $pixels_option = $this->get_post_pmw_pixels_option_sanitize();
        $validate = $this->validate_pixels($pixels_option);
        $validate_plan = $this->validate_pixels_plan($pixels_option);
        if(isset($validate["error"]) && $validate["error"] == true){
          echo wp_send_json( $validate );
          exit;
        }else if(isset($validate_plan["error"]) && $validate_plan["error"] == true){
          echo wp_send_json( $validate_plan );
          exit;
        }else{
          $store_data = array();
          $pixels_option = $this->filter_pixels_option($pixels_option);       
          $pixels_option = apply_filters("pmw_pixels_option_before_save", $pixels_option);
          $this->save_pmw_pixels_option($pixels_option);
          $api_rs = $this->PMW_API->save_product_store($pixels_option, 1);
          if (!empty($api_rs) && isset($api_rs->error) && $api_rs->error == '' && isset($api_rs->data) ) {
            $this->save_pmw_api_store((array)$api_rs->data);
          }                
          echo wp_send_json( array("error" => false, 'message' => __("Your pixel settings saved.", "pixel-manager-for-woocommerce")) );
          exit;
        }
      }else{
        echo wp_send_json( array("error" => true, 'message' => __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }
    /**
     * Check privecy policy base on user email
     **/
    public function pmw_check_privecy_policy(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $pixels_option = $this->get_post_pmw_pixels_option_sanitize();
        $validate = $this->validate_pixels($pixels_option);
        if(isset($validate["error"]) && $validate["error"] == true){
          echo wp_send_json( $validate );
          exit;
        }else{
          $pixels_option_old = $this->get_pmw_pixels_option();
          if( isset($pixels_option_old['privecy_policy']['privecy_policy']) && $pixels_option_old['privecy_policy']['privecy_policy'] == 1 && $pixels_option_old['user']['email_id'] ==  $pixels_option['user']['email_id']){
            echo wp_send_json( array( "error" => false ) );
            exit;
          }else{
            echo wp_send_json( array( "error" => true ) );
            exit;
          }
        }
      }else{
        echo wp_send_json( array("error" => true, 'message' => __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }

    /**
     * Check AJAX nonce
     **/
    protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
      // only return results when the user is an admin with manage options
      if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
        return true;
      } else {
        return false;
      }
    }
    /**
     * Genral sanitize function for post data
     **/
    /*protected function get_post_data_sanitize(array $data, string $field, string $default = null, string $field_type = "text"){
      if($field_type == "text" && isset($data[$field]) && $data[$field]){
        return sanitize_text_field( $data[$field] );
      }elseif($field_type == "email" && isset($data[$field]) && $data[$field]){
        return sanitize_email( $data[$field] );
      }else if($default != null){
        return $default;
      }
    }*/
    /**
     * validate the value of pixels
     **/
    public function validate_pixels(array $pixels_option){
      $return = array();      
      if(!isset($pixels_option["user"]["email_id"]) || $pixels_option["user"]["email_id"] == "" || !is_email($pixels_option["user"]["email_id"]) ){
        $return = array("error" => true, "message" => __("Check your email ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["google_tag"]["is_enable"]) && $pixels_option["google_tag"]["is_enable"] && !$this->is_google_tag_id($pixels_option["google_tag"]["id"])){
        $return = array("error" => true, "message" => __("Check your Google Tag ID (GT-).", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["google_tag"]["is_enable"]) && $pixels_option["google_tag"]["is_enable"] && ( !isset($pixels_option["google_tag"]["for"]) || $pixels_option["google_tag"]["for"] == "" ) ){
        $return = array("error" => true, "message" => __("While using Google Tag ID (GT-), Required to \"Use Google Tag for\".", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["google_analytics_4_pixel"]["pixel_id"]) && $pixels_option["google_analytics_4_pixel"]["pixel_id"] && !$this->is_google_analytics_4_measurement_id($pixels_option["google_analytics_4_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Google Analytics 4 Measurement ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["google_ads_conversion"]["id"]) && $pixels_option["google_ads_conversion"]["id"] && !$this->is_gads_conversion_id($pixels_option["google_ads_conversion"]["id"])){
        $return = array("error" => true, "message" => __("Check your Google Conversion ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["google_ads_conversion"]["label"]) && $pixels_option["google_ads_conversion"]["label"] && !$this->is_gads_conversion_label($pixels_option["google_ads_conversion"]["label"])){
        $return = array("error" => true, "message" => __("Check your Google Conversion label.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["facebook_pixel"]["pixel_id"]) && $pixels_option["facebook_pixel"]["pixel_id"] && !$this->is_facebook_pixel_id($pixels_option["facebook_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Facebook pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["pinterest_pixel"]["pixel_id"]) && $pixels_option["pinterest_pixel"]["pixel_id"] && !$this->is_pinterest_pixel_id($pixels_option["pinterest_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Pinterest pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["snapchat_pixel"]["pixel_id"]) && $pixels_option["snapchat_pixel"]["pixel_id"] && !$this->is_snapchat_pixel_id($pixels_option["snapchat_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Snapchat pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["bing_pixel"]["pixel_id"]) && $pixels_option["bing_pixel"]["pixel_id"] && !$this->is_bing_pixel_id($pixels_option["bing_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Bing pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["twitter_pixel"]["pixel_id"]) && $pixels_option["twitter_pixel"]["pixel_id"] && !$this->is_twitter_pixel_id($pixels_option["twitter_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Twitter pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["tiktok_pixel"]["pixel_id"]) && $pixels_option["tiktok_pixel"]["pixel_id"] && !$this->is_tiktok_pixel_id($pixels_option["tiktok_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Tiktok pixel ID.", "pixel-manager-for-woocommerce"));
      }else if((isset($pixels_option["google_ads_conversion"]["id"]) && $pixels_option["google_ads_conversion"]["id"] == "")|| (isset($pixels_option["google_ads_conversion"]["label"]) && $pixels_option["google_ads_conversion"]["label"] == "")){
        if(isset($pixels_option["google_ads_conversion"]["is_enable"]) && $pixels_option["google_ads_conversion"]["is_enable"]){
          $return = array("error" => true, "message" => __("Google Conversion Id and Label Required to Enable Google Ads Conversion Tracking.", "pixel-manager-for-woocommerce"));          
        }else if(isset($pixels_option["google_ads_enhanced_conversion"]["is_enable"]) && $pixels_option["google_ads_enhanced_conversion"]["is_enable"]){
          $return = array("error" => true, "message" => __("Google Conversion Id and Label Required to Enable Google Ads Enhanced Conversion Tracking.", "pixel-manager-for-woocommerce"));          
        }else if(isset($pixels_option["google_ads_conversion"]["id"]) && $pixels_option["google_ads_conversion"]["id"] == "" && isset($pixels_option["google_ads_dynamic_remarketing"]["is_enable"]) && $pixels_option["google_ads_dynamic_remarketing"]["is_enable"]){
          $return = array("error" => true, "message" => __("Google Conversion Id Required to Enable Google Ads Dynamic Remarketing Tracking.", "pixel-manager-for-woocommerce")); 
        }
      }
      /*else if((isset($pixels_option["google_ads_dynamic_remarketing"]["is_enable"]) && $pixels_option["google_ads_dynamic_remarketing"]["is_enable"]) || (isset($pixels_option["google_ads_conversion"]["is_enable"]) && $pixels_option["google_ads_conversion"]["is_enable"]) || (isset($pixels_option["google_ads_enhanced_conversion"]["is_enable"]) && $pixels_option["google_ads_enhanced_conversion"]["is_enable"])){
        if( isset($pixels_option["google_tag"]["method_is_enable"]) && $pixels_option["google_tag"]["method_is_enable"] ){
          if(( !isset($pixels_option["google_tag"]["is_enable"]) || !$pixels_option["google_tag"]["is_enable"]) || (!isset($pixels_option["google_tag"]["id"]) || $pixels_option["google_tag"]["id"] == "") ){
            $return = array("error" => true, "message" => __("Google Tag ID and enable is required to enable Google Ads Conversion and Remarketing Tracking.", "pixel-manager-for-woocommerce"));
          }
        }else if((isset($pixels_option["google_ads_conversion"]["id"]) && $pixels_option["google_ads_conversion"]["id"] == "") || (isset($pixels_option["google_ads_conversion"]["label"]) && $pixels_option["google_ads_conversion"]["label"] == "")){
          if(isset($pixels_option["google_ads_conversion"]["is_enable"]) && $pixels_option["google_ads_conversion"]["is_enable"]){
            $return = array("error" => true, "message" => __("Google Conversion Id and Label Required to Enable Google Ads Conversion Tracking.", "pixel-manager-for-woocommerce"));          
          }else if(isset($pixels_option["google_ads_enhanced_conversion"]["is_enable"]) && $pixels_option["google_ads_enhanced_conversion"]["is_enable"]){
            $return = array("error" => true, "message" => __("Google Conversion Id and Label Required to Enable Google Ads Enhanced Conversion Tracking.", "pixel-manager-for-woocommerce"));          
          }else if(isset($pixels_option["google_ads_conversion"]["id"]) && $pixels_option["google_ads_conversion"]["id"] == "" && isset($pixels_option["google_ads_dynamic_remarketing"]["is_enable"]) && $pixels_option["google_ads_dynamic_remarketing"]["is_enable"]){
            $return = array("error" => true, "message" => __("Google Conversion Id Required to Enable Google Ads Dynamic Remarketing Tracking.", "pixel-manager-for-woocommerce")); 
          }          
        }
      }*/
      return $return;
    }
    
    /**
     * validate the value of license
     **/
    public function validate_pixels_license(array $pixels_option){
      $return = array();      
      if(!isset($pixels_option["license_key"]) || $pixels_option["license_key"] == "" ){
        $return = array("error" => true, "message" => __("Enter your license key.", "pixel-manager-for-woocommerce"));
      }
      return $return;
    }
    /**
     * filter the pixels option
     **/
    public function filter_pixels_option(array $pixels_option){
      $return = array();
      $pixels = array("google_analytics_4_pixel","facebook_pixel","pinterest_pixel","snapchat_pixel","bing_pixel","twitter_pixel","tiktok_pixel");
      if(!empty($pixels)){
        foreach($pixels as $val){
          if(!isset($pixels_option[$val]["pixel_id"]) || $pixels_option[$val]["pixel_id"] ==""){
            $pixels_option[$val]["is_enable"] = false;
          }
          if(!isset($pixels_option[$val]["is_enable"]) || $pixels_option[$val]["is_enable"] == null){
            $pixels_option[$val]["is_enable"] = false;
          }
        }
      }
      if(!isset($pixels_option["fb_conversion_api"]["api_token"]) || $pixels_option["fb_conversion_api"]["api_token"] ==""){
        $pixels_option["fb_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["fb_conversion_api"]["is_enable"]) || $pixels_option["fb_conversion_api"]["is_enable"] ==""){
        $pixels_option["fb_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["tiktok_conversion_api"]["api_token"]) || $pixels_option["tiktok_conversion_api"]["api_token"] ==""){
        $pixels_option["tiktok_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["tiktok_conversion_api"]["is_enable"]) || $pixels_option["tiktok_conversion_api"]["is_enable"] ==""){
        $pixels_option["tiktok_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["google_tag"]["id"]) || $pixels_option["google_tag"]["id"] =="" || !isset($pixels_option["google_tag"]["is_enable"]) || $pixels_option["google_tag"]["is_enable"] == ""){
        $pixels_option["google_tag"]["is_enable"] = false;
        $pixels_option["google_tag"]["for"] = "";
      }
      // Twitter Conversion API
      /*if(!isset($pixels_option["twitter_conversion_api"]["api_token"]) || $pixels_option["twitter_conversion_api"]["api_token"] ==""){
        $pixels_option["twitter_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["twitter_conversion_api"]["is_enable"]) || $pixels_option["twitter_conversion_api"]["is_enable"] ==""){
        $pixels_option["twitter_conversion_api"]["is_enable"] = false;
      }*/
      // Snapchat Conversion API
      /*if(!isset($pixels_option["snapchat_conversion_api"]["api_token"]) || $pixels_option["snapchat_conversion_api"]["api_token"] ==""){
        $pixels_option["snapchat_conversion_api"]["is_enable"] = false;
      }
      if(!isset($pixels_option["snapchat_conversion_api"]["is_enable"]) || $pixels_option["snapchat_conversion_api"]["is_enable"] ==""){
        $pixels_option["snapchat_conversion_api"]["is_enable"] = false;
      }*/
      //axeptio
      if(!isset($pixels_option["axeptio"]["project_id"]) || $pixels_option["axeptio"]["project_id"] ==""){
        $pixels_option["axeptio"]["is_enable"] = false;
      }
      if(!isset($pixels_option["axeptio"]["is_enable"]) || $pixels_option["axeptio"]["is_enable"] ==""){
        $pixels_option["axeptio"]["is_enable"] = false;
      }
      return $pixels_option;
    }

    /**
     * Clean Debug Logs - Clear pmw_conversion_api_logs option
     **/
    public function pmw_clean_debug_logs(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        // Clear the conversion API logs option
        $result = update_option('pmw_conversion_api_logs', array());
        
        if($result !== false) {
          echo wp_send_json( array("error" => false, 'message' => __("All debug logs have been successfully deleted.", "pixel-manager-for-woocommerce")) );
        } else {
          echo wp_send_json( array("error" => true, 'message' => __("Failed to delete debug logs. Please try again.", "pixel-manager-for-woocommerce")) );
        }
      } else {
        echo wp_send_json( array("error" => true, 'message' => __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
      }
      exit;
    }
  }
endif;
new PMW_AjaxHelper();