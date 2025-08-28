<?php
require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/common/class-pmw-header.php');
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelsGrowInsights360')){
  class PMW_PixelsGrowInsights360 extends PMW_AdminHelper{
    public function __construct( ) {
      $this->load_html();
    }
    protected function load_html(){
      $this->page_html();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      $api_store = (object)$this->get_pmw_api_store();
      $store_id = isset($api_store->store_id)?$api_store->store_id:"";
      $store_data = array(
        "store_id" => sanitize_text_field($store_id),
        "product_id" => ( defined( 'PMW_PRODUCT_ID' ) )?PMW_PRODUCT_ID:1
      );
      $iframe_url = "https://growinsights360.growcommerce.io/login?".http_build_query( $store_data );
      ?>
      <div class="pmw_page">
        <div class="grow-doc-header grow-custom-header">
          <button class="grow-doc-toggle" id="toggle-menu"><?php echo esc_attr__('☰ WP Menu', 'pixel-manager-for-woocommerce'); ?></button>
          <?php 
          $pmw_header = new PMW_Header();
          $pmw_header->header_menu();
          ?>                
        </div>
        <div class="grow-doc-iframe grow-growinsights360-iframe"> 
          <iframe src="<?php echo esc_url_raw($iframe_url); ?>"></iframe>
        </div>
      </div>
      <script>
        (function($){
            // Apply hidden menu by default
            $('body').addClass('pmw-menu-hidden');

            $('#toggle-menu').on('click', function(){
                $('body').toggleClass('pmw-menu-hidden');
            });
        })(jQuery);
      </script>
      <?php
    }
  }
}
