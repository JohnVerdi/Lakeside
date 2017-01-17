jQuery(document).ready(function($) {
  var owl = $("#testimonial_s2");
  owl.owlCarousel({
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem:true,
      navigation: true,
      navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],
      pagination: false ,
 
      // "singleItem:true" is a shortcut for:
      // items : 1, 
      // itemsDesktop : false,
      // itemsDesktopSmall : false,
      // itemsTablet: false,
      // itemsMobile : false
 
  });
 
});
jQuery(document).ready(function($) { 
  var owl = $(".our_room");
 
  owl.owlCarousel({
     
      itemsCustom : [
        [0, 1],
        [450, 1],
        [600, 1],
        [700, 3],
        [1000, 3],
        [1200, 4],
        [1400, 5],
      ],
      pagination : false,
      navigation: true,
      navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],
 
  });
  $(".owl_carousel_style2").owlCarousel({
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem:true,
      navigation: true,
      navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],
      pagination: false ,
  });
  $(".testimonial_").owlCarousel({
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem:true,
      navigation: false,
      navigationText: ["",""],
      pagination: true ,
  });
  jQuery(window).bind("load", function($) {
    fix_weather_();
  });
  jQuery(window).resize(function($){
    fix_weather_();
  });
  function fix_weather_(){
      // coppy and paste into header  
      var e = $(".top-user-area").parent(".get_location_weather");
      e.remove();
      if($(window).width() <=992){
        $(".menu_div").after(e); 
      }else {
        $(".slimmenu-menu-collapser").parent(".nav").parent(".col-lg-8").after(e);
      }    
    }

}); 
