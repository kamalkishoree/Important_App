if (window.matchMedia('(max-width: 991px)').matches)
{
jQuery(document).ready(function () {


	setTimeout(function(){
		console.log(jQuery(".get_div_height").height());
		jQuery("#map_canvas").height(jQuery(window).height() - parseInt(jQuery(".get_div_height").height()+50));
	},1000);
	jQuery(".show_attr_classes").click(function () {
		// jQuery("#map_canvas").height(jQuery(window).height() - jQuery(".get_div_height").height());
		// jQuery("#map_canvas").width(jQuery(window).width());
	
		jQuery(".attrbute_classes").slideToggle("slow","swing", function(){
			var block_height = jQuery(window).height() - parseInt(jQuery(".get_div_height").height()+50);
			jQuery("#map_canvas").animate({height:block_height});
        });

		$(this).toggleClass("arrow_down");
		// setTimeout(function(){
		// 	console.log(jQuery(".get_div_height").height());
			
		// },1000);
		
	});
	
    $(window).on('resize', function(){
		jQuery("#map_canvas").height(jQuery(window).height() - parseInt(jQuery(".get_div_height").height()+50));
	});


});





}

