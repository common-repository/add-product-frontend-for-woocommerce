(function($){
	if(!window.codecabin)
		window.codecabin = {};
	
	if(codecabin.DeactivateFeedbackForm)
		return;
	
	codecabin.DeactivateFeedbackForm = function(plugin){
		var self = this;
		var strings = codecabin_deactivate_feedback_form_strings;
		
		this.plugin = plugin;
		
		// Dialog HTML
		var element = $('\
			<div class="codecabin-deactivate-dialog" data-remodal-id="' + plugin.slug + '">\
				<form>\
					<input type="hidden" name="plugin"/>\
					<h1>' + strings.quick_feedback + '</h1>\
					<p>\
						' + strings.foreword + '\
					</p>\
					<ul class="codecabin-deactivate-reasons"></ul>\
					<textarea name="comments" placeholder="' + strings.brief_description + '" rows="4" cols="50"></textarea>\
					<br>\
					<p class="codecabin-deactivate-dialog-buttons">\
						<input type="submit" class="button confirm" value="' + strings.skip_and_deactivate + '"/>\
						<button data-remodal-action="cancel" class="button button-primary">' + strings.cancel + '</button>\
					</p>\
				</form>\
			</div>\
		')[0];
		this.element = element;
		
		$(element).find("input[name='plugin']").val(JSON.stringify(plugin));
		
		$(element).on("click", "input[name='reason']", function(event){
			$(element).find("input[type='submit']").val(
				strings.submit_and_deactivate
			);
		});
		
		$(element).find("form").on("submit", function(event){
			self.onSubmit(event);
		});
		
		// Reasons list
		var ul = $(element).find("ul.codecabin-deactivate-reasons");
		for(var key in plugin.reasons){
			var li = $("<li><input type='radio' name='reason'/> <span></span></li>");
			$(li).find("input").val(plugin.reasons[key]);
			$(li).find("span").html(plugin.reasons[key]);
			$(ul).append(li);
		}
		
		// Listen for deactivate
		$("#the-list [data-slug='" + plugin.slug + "'] .deactivate>a").on("click", function(event){
			self.onDeactivateClicked(event);
		});
	}
	
	codecabin.DeactivateFeedbackForm.prototype.onDeactivateClicked = function(event){
		this.deactivateURL = event.target.href;
		event.preventDefault();
		if(!this.dialog)
			this.dialog = $(this.element).remodal();
		this.dialog.open();
	}
	
	codecabin.DeactivateFeedbackForm.prototype.onSubmit = function(event){
		var element = this.element;
		var strings = codecabin_deactivate_feedback_form_strings;
		var self = this;
		var formdata = new FormData($(element).find("form")[0]); // get form data
		formdata.append("action", "bytes_deactivate_feedback_form"); // add action

		$(element).find("button, input[type='submit']").prop("disabled", true);
		
		if($(element).find("input[name='reason']:checked").length){
			$(element).find("input[type='submit']").val(strings.thank_you);
			jQuery.ajax({
			    method: "POST",
			    dataType: "json",
			    url: strings.deactivate_ajax_url,
			    data: formdata,
			    processData: false,
			    contentType: false,
			    success: function(response){
			        window.location.href = self.deactivateURL;
			    }
			});
		}
		else{
			$(element).find("input[type='submit']").val(strings.please_wait);
			jQuery.ajax({
                method: "POST",
                dataType: "json",
                url: strings.deactivate_ajax_url,
                data: formdata,
                processData: false,
                contentType: false,
                success: function(response){
                    window.location.href = self.deactivateURL;
                }
            });
		}		
		event.preventDefault();
		return false;
	}
	
	$(document).ready(function(){
		for(var i = 0; i < codecabin_deactivate_feedback_form_plugins.length; i++){
			var plugin = codecabin_deactivate_feedback_form_plugins[i];
			new codecabin.DeactivateFeedbackForm(plugin);
		}
	});
})(jQuery);