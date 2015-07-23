jQuery(document).ready(function($) {
	
	var pressapps_ajax_url = "http://pressapps.co/wp-admin/admin-ajax.php";
	/**
	 * API callback sample
	 * @return Object from API callback. 
	 * category: "wordpress/media"  
	 * cost: "10.00"
	 * id: "11178480"
	 * item: "Video Sortable Extension For Layers Builder"
	 * last_update: "Tue May 19 00:47:08 +1000 2015"
	 * live_preview_url: "https://0.s3.envato.com/files/132193285/preview_590x300.jpg"
	 * rating: "0.0"
	 * rating_decimal: "0.00"
	 * sales: "0"
	 * tags: "embed videos, layers builder extension, sortable videos, video extension"
	 * thumbnail: "https://0.s3.envato.com/files/132193280/thumbnail_80x80.png"
	 * uploaded_on: "Sat May 02 05:38:11 +1000 2015"
	 * url: "http://codecanyon.net/item/video-sortable-extension-for-layers-builder/11178480"
	 * user: "PressApps"
	 */
	$.ajax({
	  url: pressapps_ajax_url,
	  data:{
		action: 'pressapps_api',
		type: 'codecanyon' // change this category to retrieve other products
	  }, 
	  method: "post",
	  dataType: 'jsonp',

	})
	.done(function( d ) {
		
		var products = [];

	  	jQuery.each(d['pressapps_products'],function(i,dd){
	  		
		  		var item_large = [
		  			'<div class="pa-item-wrapper"><div class="pa-item">',
		  			'<h3>',
		  			dd.item,
		  			//'<span class="marked-purchased">Purchased</span>',
		  			'</h3>',
		  			'<a href="'+dd.url+'" target="_blank">',
		  			'<img src="'+dd.live_preview_url+'"/>',
		  			'</a><br/>',
			  			'<p>',
			  			'<a href="'+dd.url+'" target="_blank" class="button pullright">Live Demo</a> ',
			  			'<a href="'+dd.url+'" target="_blank" class="button-primary pullright">Buy $'+dd.cost+'</a>',
			  			'</p>',
		  			'</div></div>'

		  		];
		  		products.push(item_large.join(""));
		  
	  	})


	    jQuery("#pa-products").append(products.join(""));
	     
	   
	});

	// Show products from themeforest
	$.ajax({
	  url: pressapps_ajax_url,
	  data:{
		action: 'pressapps_api',
		type: 'themeforest' // change this category to retrieve other products
	  }, 
	  method: "post",
	  dataType: 'jsonp',

	})
	.done(function( d ) {
		console.log(d);
		var products = [];
	
	  	jQuery.each(d['pressapps_products'],function(i,dd){
	  		
		  		var item_large = [
		  			'<div class="pa-item-wrapper"><div class="pa-item">',
		  			'<h3>',
		  			dd.item,
		  			//'<span class="marked-purchased">Purchased</span>',
		  			'</h3>',
		  			'<a  href="'+dd.url+'" target="_blank">',
		  			'<img src="'+dd.live_preview_url+'"/>',
		  			'</a><br/>',
			  			'<p>',
			  			'<a href="'+dd.url+'" target="_blank" class="button pullright">Live Demo</a> ',
			  			'<a href="'+dd.url+'" target="_blank" class="button-primary pullright">Buy $'+dd.cost+'</a>',
			  			'</p>',
		  			'</div></div>'

		  		];
		  		products.push(item_large.join(""));
		  
	  	})

	  	
	    jQuery("#pa-products").append(products.join(""));
	     
	   
	})
});

// Faqs
jQuery(document).ready(function($) {
	$('.pa-question').each(function() {
		var tis = $(this), state = false, answer = tis.next('.pa-answer').slideUp();
		tis.click(function() {
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
		});
	});
});

