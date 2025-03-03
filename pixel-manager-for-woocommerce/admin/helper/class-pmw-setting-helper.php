<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_SettingHelper')):
  class PMW_SettingHelper {
    public function add_form_fields(array $fields, array $form){
      if(!empty($fields)){
        $name = $this->get_array_val($form, "name");
        $id = $this->get_array_val($form, "id");
        $method = $this->get_array_val($form, "method");
        $class = $this->get_array_val($form, "class");
        ?>
        
        <form name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" method="<?php echo esc_attr($method); ?>" class="<?php echo esc_attr($class); ?>">
        <?php
        foreach($fields as $key => $pixel_fields){
          if(empty($pixel_fields)){
            continue;
          }

          /**
           * Manage tab HTML
           */
          if(isset($pixel_fields['type']) && $pixel_fields['type'] == "tab") {
            $this->add_tab($pixel_fields);
          }else if(isset($pixel_fields['type']) && $pixel_fields['type'] == "tab_end") {
            echo "</div>";
          }

          if(isset($pixel_fields[0]["type"]) && $pixel_fields[0]["type"] != "hidden") {
            $active_class ="";
          ?>
          <div class="pmw_form-row">
            <div class="pmw_form-group ">
            <?php
          }
            foreach($pixel_fields as $key => $value){
              if(isset($value['type'])){
                if($value['type'] == "section") {
                  $this->add_section($value);
                }else if($value['type'] == "sub_section") {
                  $this->add_sub_section($value);
                }else if($value['type'] == "text") {
                  $this->add_text_fiels($value);
                }else if($value['type'] == "textarea") {
                  $this->add_textarea_fiels($value);
                }else if($value['type'] == "switch") {
                  $this->add_switch_fiels($value);
                }else if($value['type'] == "checkbox") {
                  $this->add_checkbox_fiels($value);
                }else if($value['type'] == "multi_checkbox") {
                  $this->add_multi_checkbox_fiels($value);
                }else if($value['type'] == "multi_text") {
                  $this->add_multi_text_fiels($value);
                }else if($value['type'] == "text_with_switch") {
                  $this->add_text_fiels_with_switch($value);
                }else if($value['type'] == "textarea_with_switch") {
                  $this->add_textarea_fiels_with_switch($value);
                }else if($value['type'] == "switch_with_text") {
                  $this->add_switch_fiels_with_text($value);
                }else if($value['type'] == "button") {
                  $this->add_button($value);
                }else if($value['type'] == "hidden") {
                  $this->add_hidden_fiels($value);
                }else if($value['type'] == "freevspro_features") {
                  $this->add_freevspro_features($value);
                }else if($value['type'] == "axeptio_setting") {
                  $this->add_axeptio_setting($value);
                }/*else if($value['type'] == "line_item") {
                  $this->add_line_item($value);
                }*/

              }
            } 
          if(isset($pixel_fields[0]["type"]) && $pixel_fields[0]["type"] != "hidden") {?>
            </div>
          </div>
          <?php
          }
        }
        ?>
        <input type="hidden" name="pmw_ajax_nonce" id="pmw_ajax_nonce" value="<?php echo wp_create_nonce( 'pmw_ajax_nonce' ); ?>">
        </form>
        <?php
      }
    }
    public function get_array_val(array $vals, string $key, string $default = null){
      if(isset($vals[$key]) ){ //&& $vals[$key]
        return $vals[$key];
      }else if ($default != "") {
        return $default;
      }
    }
    public function add_tab(array $args){
      $name = $this->get_array_val($args, "name");
      ?>
      <div class="pmw_form-wrapper" id="sec-<?php echo esc_attr($name); ?>">
      <?php
    }
    public function add_section(array $args){
      $class = $this->get_array_val($args, "class");
      $label = $this->get_array_val($args, "label");
      ?>
      <div class="pmw-section-row">
        <h3 class="pmw-section <?php echo esc_attr($class); ?>"><?php echo esc_attr($label); ?></h3>
      </div>
      <?php
    }
    public function add_sub_section(array $args){
      $class = $this->get_array_val($args, "class");
      $label = $this->get_array_val($args, "label");
      $label_img = $this->get_array_val($args, "label_img");
      ?>
      <div class="pmw-sub-section-row">
        <h4 class="pmw-sub-section <?php echo esc_attr($class); ?>">
          <?php if($label_img){
            echo "<img class='pmw-setting-icon' src='".esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/".$label_img)."'>";
          }?>
          <span><?php echo esc_attr($label); ?></span>
          </h4>
      </div>
      <?php
    }
    public function add_freevspro_features(array $args){      
      $class = $this->get_array_val($args, "class");
      ?>
      <div class="pmw_row-title pmw_row-title-absolute ml-2"></div>
      <div class="plan-list">
        <span class="pmw_show" data-title='FREE VS PRO comparison'><?php esc_attr_e('FREE VS PRO comparison','pixel-manager-for-woocommerce'); ?></span>
        <div id="show-all-features" class="show-all-features pmw_price-table-wrapper">
          <table>
            <thead>
              <tr>
                <th></th>
                <th>
                  <div class="heading"><?php esc_attr_e('FREE','pixel-manager-for-woocommerce'); ?></div>
                </th>
                <th>
                  <div class="heading"><?php esc_attr_e('PRO','pixel-manager-for-woocommerce'); ?></div>
                </th>
              </tr>
              <tr>
                <td><?php esc_attr_e('All Pixels','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-yes"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Page Views','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-yes"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Item Views','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Select Item','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Item List Views','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Add to Cart','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('View Cart','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Remove from Cart','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Checkout Steps','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-no"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
              <tr>
                <td><?php esc_attr_e('Purchases','pixel-manager-for-woocommerce'); ?></td>
                <td><span class="free plan-yes"></span></td>
                <td><span class="paid1-plan-yes"></span></td>
              </tr>
            </thead>
          </table>
        </div>                
      </div>         
      <?php      
    }
    /*public function add_line_item(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        $tooltip = $this->get_array_val($args, "tooltip");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        ?>
        <label class="pmw_row-title pmw_row-title-absolute ml-2"><?php echo esc_attr($label); ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):""; ?></label>
        <div class="form-input-inline ml-2">
          <div class="pmw_input-col-lg">
            <span <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>"><?php echo esc_attr($value); ?></span>
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>
          <div class="im_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>            
            <?php }?>
            </div>
          </div>
        </div>         
        <?php
      }
    }*/
    public function add_axeptio_setting(array $args){      
      $label = $this->get_array_val($args, "label");
      $class = $this->get_array_val($args, "class");
      $axeptio_option = $this->get_array_val($args, "value");
      $region_consent_mode = [
        "us" => "United States",
        "uk" => "United Kingdom",
        "cn" => "China"
      ];
      foreach ($region_consent_mode as $key => $value) {
        $consent_val = isset($axeptio_option["cookies_consent_$key"])?$axeptio_option["cookies_consent_$key"]:"";
        $fields["axeptio_cookies_consent_.$key"] = [    
          [
            "type" => "text",
            "label" => __("Granted Consent for Region $value", "pixel-manager-for-woocommerce"),
            "note"  => __("Granted Consent - Ex. analytics_storage, ad_storage, ad_user_data, ad_personalization", "pixel-manager-for-woocommerce"),
            "name" => "axeptio_cookies_consent_$key",
            "id" => "axeptio_cookies_consent_$key",
            "value" => $consent_val,
            "is_pro_featured" => true,
            "is_pro_only" => true,
            "is_pro_text" => "Upgrade to Pro",
            "pro_utm_text"=> "PRO+Enable+Form+Submission+Tracking+Pixel+Settings",
            "placeholder" => __("analytics_storage, ad_storage, ad_user_data, ad_personalization", "pixel-manager-for-woocommerce"),
            "class" => "axeptio_cookies_consent_$key",
            "tooltip" =>[
              "title" => __("Granted Consent Types (comma separated)", "pixel-manager-for-woocommerce")
            ]
          ]
        ];
      }
      ?>
      <div class="pmw_row-title pmw_row-title-absolute ml-2"></div>
      <div class="plan-list">
        <span class="pmw_show" data-title="Axeptio more setting"><?php esc_attr_e('Axeptio more setting','pixel-manager-for-woocommerce'); ?></span>
        <div id="show-all-features" class="show-all-features pmw-sub-table-wrapper">
          <h3 class="pmw-title ml-2 mt-2"><?php esc_attr_e('Google Consent Mode V2 - Default Granted Consent Settings','pixel-manager-for-woocommerce'); ?></h3>
          <?php
          foreach($fields as $key => $pixel_fields){
            if(empty($pixel_fields)){
              continue;
            }
            ?>
            <div class="pmw_form-row">
              <div class="pmw_form-group ">
              <?php
              foreach($pixel_fields as $key => $value){
                if(isset($value['type'])){
                  if($value['type'] == "section") {
                    $this->add_section($value);
                  }else if($value['type'] == "sub_section") {
                    $this->add_sub_section($value);
                  }else if($value['type'] == "text") {
                    $this->add_text_fiels($value);
                  }
                }
              }
              ?>
              </div>
            </div>
            <?php
          }
          ?>
        </div>                
      </div>         
      <?php      
    }
    public function add_text_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        $tooltip = $this->get_array_val($args, "tooltip");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        $is_disable = $this->get_array_val($args, "is_disable");
        ?>
        <label class="pmw_row-title pmw_row-title-absolute ml-2"><?php echo esc_attr($label); ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):""; ?></label>
        <div class="form-input-inline ml-2">
          <div class="pmw_input-col-lg">
            <input type="text" <?php echo ($is_disable)?"disabled":""; ?> <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>
          <div class="im_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>            
            <?php }?>
            </div>
          </div>
        </div>         
        <?php
      }
    }
    public function add_hidden_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $value = $this->get_array_val($args, "value");        
        ?>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">        
        <?php
      }
    }
    public function add_textarea_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        $tooltip = $this->get_array_val($args, "tooltip");
        ?>
        <label class="pmw_row-title"><?php echo esc_attr($label); ?></label>
        <div class="form-input-inline">
          <div class="pmw_input-col-lg">
            <textarea name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>"  class="pmw_form-control <?php echo esc_attr($class); ?>"><?php echo esc_attr($value); ?></textarea>
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>
          <div class="im_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>            
            <?php }?>
            </div>
          </div>
        </div>         
        <?php
      }
    }
    public function add_multi_text_fiels(array $args){
      $text_fields = $this->get_array_val($args, "text_fields");
      ?>
      <div class="form-input-inline">
      <?php
      foreach($text_fields as $key => $args){
        $name = $this->get_array_val($args, "name");
        if($name != ""){
          $id = $this->get_array_val($args, "id");
          $placeholder = $this->get_array_val($args, "placeholder");
          $class = $this->get_array_val($args, "class");
          $label = $this->get_array_val($args, "label");
          $label_img = $this->get_array_val($args, "label_img");
          $value = $this->get_array_val($args, "value");
          $note = $this->get_array_val($args, "note");

          $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
          $pro_text = $this->get_array_val($args, "is_pro_text");
          $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
          $is_pro_only = $this->get_array_val($args, "is_pro_only");
          ?>
          <div class="form-multi-input-inline">
            <?php
              if($label != ""){
                ?>          
                <label class="pmw_row-title pmw_row-title-absolute ml-2 lbl-<?php echo esc_attr($id); ?>" for="<?php echo esc_attr($id); ?>">
                  <?php if($label_img){
                    echo "<img class='pmw-setting-icon' src='".esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/".$label_img)."'>";
                  }?>
                  <span><?php echo esc_attr($label); 
                  ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
                  ?></span>           
                </label>
                <?php 
              }
            ?>
            <div class="pmw_input-col-lg ml-2">
              <input type="text" <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
              <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
            </div>
          </div>          
          <?php
        }
      }
      ?>
      </div>
      <?php
    }
    public function add_multi_text_fiels_with_switch(array $args){
      $text_fields = $this->get_array_val($args, "text_fields");
      foreach($text_fields as $key => $args){
        $name = $this->get_array_val($args, "name");
        if($name != ""){
          $id = $this->get_array_val($args, "id");
          $placeholder = $this->get_array_val($args, "placeholder");
          $class = $this->get_array_val($args, "class");
          $label = $this->get_array_val($args, "label");
          $label_img = $this->get_array_val($args, "label_img");
          $value = $this->get_array_val($args, "value");
          $note = $this->get_array_val($args, "note");

          $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
          $pro_text = $this->get_array_val($args, "is_pro_text");
          $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
          $is_pro_only = $this->get_array_val($args, "is_pro_only");
          if($key == 0){
            echo '<div class="form-input-inline">';
          }
          ?>
          <div class="form-multi-input-inline">
            <?php
              if($label != ""){
                ?>          
                <label class="pmw_row-title pmw_row-title-absolute ml-2 lbl-<?php echo esc_attr($id); ?>" for="<?php echo esc_attr($id); ?>">
                  <?php if($label_img){
                    echo "<img class='pmw-setting-icon' src='".esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/".$label_img)."'>";
                  }?>
                  <span><?php echo esc_attr($label); 
                  ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
                  ?></span>           
                </label>
                <?php 
              }
            ?>
            <div class="pmw_input-col-lg ml-2">
              <input type="text" <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
              <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
            </div>
          </div>          
          <?php
        }
      }
    }
    public function add_text_fiels_with_switch(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $label_img = $this->get_array_val($args, "label_img");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");

        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        ?>
        <label class="pmw_row-title pmw_row-title-absolute ml-2 lbl-<?php echo esc_attr($id); ?>" for="<?php echo esc_attr($id); ?>">
          <?php if($label_img){
            echo "<img class='pmw-setting-icon' src='".esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/".$label_img)."'>";
          }?>
          <span><?php echo esc_attr($label); 
          ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
          ?></span>           
        </label>
        <div class="form-input-inline ml-2">
          <div class="pmw_input-col-lg">
            <input type="text" <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>          
        <?php
      }
    }
    public function add_switch_fiels_with_text(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $value = $this->get_array_val($args, "value");
        $checked = ($value ==1)?"checked":"";
        $tooltip = $this->get_array_val($args, "tooltip");

        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        $disable = ($is_pro_only)?$this->is_disable_pro_featured():"";
        ?>
        <div class="pmw_input-col-sm offspace-top-1">
          <div class="alert-wrapper">
          <?php if( !empty($tooltip) && isset($tooltip['title']) ){
            $title = $this->get_array_val($tooltip, "title");
            $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
            $link = $this->get_array_val($tooltip, "link");
            ?>
            <div class="pmw-alert-btn"><i class="alert-icon"></i></div>
            <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
              <?php if($link){?>
                <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
              <?php } ?>
            </div>          
          <?php }?>
          </div>
          <div class="custom-control custom-switch <?php echo esc_attr($disable); echo esc_attr($class); ?>">
            <input type="checkbox"  <?php echo esc_attr($disable); echo esc_attr($checked); ?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="1" class="pmw_custom-control-input pmw_switch">
            <label class="pmw_custom-control-label" for="<?php echo esc_attr($id); ?>"></label>            
          </div>
        </div>
        </div>
        <?php
      }
    }
    public function add_textarea_fiels_with_switch(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $label_img = $this->get_array_val($args, "label_img");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");

        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        ?>
        <label class="pmw_row-title pmw_row-title-absolute ml-2 lbl-<?php echo esc_attr($id); ?>" for="<?php echo esc_attr($id); ?>">
          <?php if($label_img){
            echo "<img class='pmw-setting-icon' src='".esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/".$label_img)."'>";
          }?>
          <span><?php echo esc_attr($label); 
          ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
          ?></span>           
        </label>
        <div class="form-input-inline ml-2">
          <div class="pmw_input-col-lg">
            <textarea <?php echo ($is_pro_only)?esc_attr($this->is_disable_pro_featured()):"";?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>"><?php echo esc_attr($value); ?></textarea>
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>          
        <?php
      }
    }
    public function add_checkbox_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $label = $this->get_array_val($args, "label");
        $class = $this->get_array_val($args, "class");
        $value = $this->get_array_val($args, "value");
        $checked = ($value ==1)?"checked":"";
        $tooltip = $this->get_array_val($args, "tooltip"); 
        //$note = $this->get_array_val($args, "note");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        $disable = ($is_pro_only)?$this->is_disable_pro_featured():"";
        ?>
        <div class="form-input-inline pmw_checkbox-with-title ml-2">
          <div class="pmw_input-col-lg">
            <label class="pmw_custom-control-label " for="<?php echo esc_attr($id); ?>">
              <input type="checkbox" <?php echo esc_attr($disable); echo esc_attr($checked); ?>  name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="1" class="pmw_custom-control-input pmw_switch">
              <?php echo esc_attr($label); 
              ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
              ?>
            </label>
          </div>
          <div class="pmw_input-col-sm">
            <div class="alert-wrapper">
              <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn pmw-checkbox-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>          
            <?php }?>
            </div>            
          </div>
        </div>
        <?php
      }
    }
    public function add_multi_checkbox_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      $options = $this->get_array_val($args, "options");
      if($name != "" && count($options) > 0){
        $id = $this->get_array_val($args, "id");
        $label = $this->get_array_val($args, "label");
        //$class = $this->get_array_val($args, "class");
        $value = explode(",", $this->get_array_val($args, "value"));
        $tooltip = $this->get_array_val($args, "tooltip");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        $disable = ($is_pro_only)?$this->is_disable_pro_featured():"";
        ?>
        <div class="form-input-inline pmw_checkbox-with-title ml-2">
          <div class="pmw_input-col-lg">
            <label class="pmw_custom-control-label " for="<?php echo esc_attr($id); ?>">
              <?php echo esc_attr($label); 
              ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
              ?>
            </label>
          </div>
          <div class="pmw_input-col-sm">
            <div class="alert-wrapper">
              <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn pmw-checkbox-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>          
            <?php }?>
            </div>            
          </div>
          <div class="pmw-multi_checkbox_list">
            <?php foreach($options as $key => $option ){
              $checked = (is_array($value) && in_array($key, $value)) ? "checked" : "";
              ?>
            <div class="pmw_options_checkbox">
              <label class="pmw_custom-control-label " for="<?php echo esc_attr($key); ?>">
                <input type="checkbox" <?php echo esc_attr($disable); echo esc_attr($checked); ?>  name="<?php echo esc_attr($name); ?>[]" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($key); ?>" class="pmw_custom-control-input pmw_switch">
                <?php echo esc_attr($option); 
                ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
                ?>
              </label>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php
      }
    }
    public function add_switch_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $label = $this->get_array_val($args, "label");
        $class = $this->get_array_val($args, "class");
        $value = $this->get_array_val($args, "value");
        $checked = ($value ==1)?"checked":"";
        $tooltip = $this->get_array_val($args, "tooltip"); 
        //$note = $this->get_array_val($args, "note");

        $is_pro_featured = $this->get_array_val($args, "is_pro_featured");
        $pro_text = $this->get_array_val($args, "is_pro_text");
        $pro_utm_text = $this->get_array_val($args, "pro_utm_text");
        $is_pro_only = $this->get_array_val($args, "is_pro_only");
        $disable = ($is_pro_only)?$this->is_disable_pro_featured():"";
        ?>
        <div class="form-input-inline pmw_switch-with-title ml-2">
          <div class="pmw_input-col-lg">
            <label class="pmw_row-title">
              <?php echo esc_attr($label); 
              ($is_pro_featured)?$this->display_proplan_with_link($pro_text, $pro_utm_text):"";
              ?>
            </label>
          </div>
          <div class="pmw_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
              <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <div class="pmw-alert-btn"><i class="alert-icon"></i></div>
              <div class="pmw-alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>          
            <?php }?>
            </div>
            <div class="custom-control custom-switch <?php echo esc_attr($disable); echo esc_attr($class); ?>">
              <input type="checkbox" <?php echo esc_attr($disable); echo esc_attr($checked); ?>  name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="1" class="pmw_custom-control-input pmw_switch">
              <label class="pmw_custom-control-label" for="<?php echo esc_attr($id); ?>"></label>            
            </div>
          </div>
        </div>
        <?php
      }
    } 

    public function add_button(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label", "Save");
        ?>
        <div class="action_button">
          <button name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="pmw_btn pmw_btn-fill <?php echo esc_attr($class); ?>"><div id="pmw_loader"></div><?php echo esc_attr($label); ?></button>
        </div>
        <?php
      }
    }  
  }
endif;