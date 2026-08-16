var pmw_helper = {
	pmw_loader:function(isShow){
		if (isShow){
	    jQuery("#pmw_loader").addClass("is_loading");
	  }else{
	  	jQuery("#pmw_loader").removeClass("is_loading");
	  }
	},
	addEventBindings:function(){
	},
	add_message:function(type, title, msg, is_close = true){
	  let pmw_popup_box = document.getElementById('pmw_form_message');
	  pmw_popup_box.classList.add("active");
	  title = (title)?"<h4>"+title+"</h4>":"";
	  if(type == "success"){
	    document.getElementById('pmw_form_message').innerHTML = "<div class='toaster-box tvc-alert-success'>"+ title +"<p>"+ msg +"</p></div>";
	  }else if(type == "error"){
	    document.getElementById('pmw_form_message').innerHTML = "<div class='toaster-box tvc-alert-error'>"+ title +"<p>"+ msg+"</p></div>";
	  }else if(type == "warning"){
	    document.getElementById('pmw_form_message').innerHTML = "<div class='toaster-box tvc-alert-warning'>"+ title +"<p>"+ msg+"</p></div>";
	  }
	  if(is_close){
	    pmw_time_out = setTimeout(function(){
	    	pmw_popup_box.classList.remove("active");
	    }, 4000);
	  } 
	},
	pmw_ajax_call:function(form_data) {
		var f_data = {};
		if(form_data.length > 0 ){
	    for (var i = 0; i < form_data.length; i++){
				let feild_name = form_data[i]['name'].replace('[]','');
				if(f_data[feild_name]){
					f_data[feild_name] += ',' + form_data[i]['value'];
				}else{
	      	f_data[feild_name] = form_data[i]['value'];
				}
	    }
	  }else{
	  	return;
	  }
		var this_var = this;
		jQuery.ajax({
      type: "POST",
      dataType: "json",
      url: pmw_ajax_url,
      data: f_data,
      beforeSend: function(){
        this_var.pmw_loader(true);
      },
      success: function (response) {
      	if( f_data.action == "pmw_pixels_save" ){ //remove disabled save button
      		document.getElementById("pixels_save").disabled = false;
      	}
      	if (response.error === false && response.hasOwnProperty('message') && response.message != "" ) {
	        this_var.add_message("success", "Success",  response.message);
          //clean debug logs html
          if(f_data.action == "pmw_clean_debug_logs"){
            var logContainer = document.getElementById("pmw-log-list");
            if (logContainer) {
                logContainer.remove();
            }
          }

	      }else if(response.hasOwnProperty('message') && response.message != ""){
	      	this_var.add_message("error", "Error", response.message);
	      }else{
	      	this_var.add_message("error", "Error", "while save data.");
	      }
      	this_var.pmw_loader(false);
      }
    });
	},
	clean_debug_logs:function(){
		if(!confirm("Are you sure you want to delete all conversion API logs? This action cannot be undone.")){
			return false;
		}
		var data = [{
      name: "action",
      value: "pmw_clean_debug_logs"
    }, {
      name: "pmw_ajax_nonce",
      value: document.getElementById('pmw_ajax_nonce').value
    }];    
		this.pmw_ajax_call(data);
	}
};

(function( $ ){
	jQuery(document).ready(function(){
		/* pixels page */
		jQuery("#pmw-pixels").on("submit", function( event ){
			event.preventDefault();
			document.getElementById("pixels_save").disabled = true;
      var data = jQuery("#pmw-pixels").serializeArray();
      pmw_helper.pmw_ajax_call(data);
		});
		jQuery(".toggle_title-text").on("click", function () {
      jQuery(this).toggleClass("active");
      jQuery(this).next('.pmw_slide-down-area').slideToggle();
    });
    // Clean Debug Logs button click handler
    jQuery(document).on("click", ".clean_debug_logs", function (event) {
      // Prevent multiple calls
      if (event.target._cleanLogsCalled) {
        return false;
      }
      event.target._cleanLogsCalled = true;
      
    	event.preventDefault();
    	pmw_helper.clean_debug_logs();
      
      // Reset flag after a delay
      setTimeout(function() {
        event.target._cleanLogsCalled = false;
      }, 1000);
    });
		/*function pmwToggleGoogleTagLegacyFields(){
			var isGoogleTagMethod = jQuery('#google_tag_method_is_enable').is(':checked');
			var googleTagSelectors = ['.google_tag_id'];
			googleTagSelectors.forEach(function(selector){
				var $row = jQuery(selector);
				if(!$row.length){
					return;
				}
				$row.toggle(isGoogleTagMethod);
			});
		}
		var $googleTagSwitch = jQuery('#google_tag_method_is_enable');
		if($googleTagSwitch.length){
			pmwToggleGoogleTagLegacyFields();
			$googleTagSwitch.on('change', pmwToggleGoogleTagLegacyFields);
		}	*/	
		/* Activate License Key- Account Page */
		jQuery("#pmw-pixels-licensekey").on("submit", function( event ){
			event.preventDefault();
			var data = jQuery("#pmw-pixels-licensekey").serializeArray();
			pmw_helper.pmw_ajax_call(data);
		});
	});
})( jQuery );