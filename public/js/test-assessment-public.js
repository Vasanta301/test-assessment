(function( $ ){
	
	jQuery(document).ready(function($) {
		
		$('.custom_date').datepicker({
			dateFormat : 'yy-mm-dd'
		});
		
		var experienced_field_count = $('.experience-fields-inner-container').find('.experience-field-custom-un').length;
		var experienceCounter = experienced_field_count > 0 ?experienced_field_count:1;

		$(document).on("focus", ".custom_date", function(){
			$(this).datepicker();
		});

		function addExperienceField() {
			var template = wp.template('experience-field-template');
			$('#experience-fields-container').append(template({ counter: experienceCounter }));
			//var selectTemplate = wp.template("occupation-type-select-field");
			//$(".form-group-"+experienceCounter).append(selectTemplate({ counter: experienceCounter }));
			experienceCounter++;
		}

		function removeExperienceField(event) {
			$(event.target).closest('.experience-field').remove();
		}

		$(document).on('click', '#add-experience-field', function(e) {
			e.preventDefault();
			addExperienceField();
		});
		
		$(document).on('click', '.remove-experience-field', removeExperienceField);
		
	});

})( jQuery );