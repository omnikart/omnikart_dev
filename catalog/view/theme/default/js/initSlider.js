/*
 * Opencart Blog Extension v1.0 - initSlider.js
 * This file is part of Opencart Blog Extension, an Admin template build for sale sale at Evanto Marketplace.
 * All copyright to this file is hold by techbuz <techbuzz69@gmail.com>.
 * Last Updated:
 * April 09, 2015
 *
 */

// Post thumbnail slider initializer
$(document).ready(function(){
	$('.thumbslider') .cycle({
		fx: 'fade', //'scrollLeft,scrollDown,scrollRight,scrollUp',blindX, blindY, blindZ, cover, curtainX, curtainY, fade, fadeZoom, growX, growY, none, scrollUp,scrollDown,scrollLeft,scrollRight,scrollHorz,scrollVert,shuffle,slideX,slideY,toss,turnUp,turnDown,turnLeft,turnRight,uncover,ipe ,zoom
		speed:  'slow', 
   		timeout: 2000 
	});
});	

