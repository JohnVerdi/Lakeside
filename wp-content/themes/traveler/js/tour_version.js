/**
 * Created by Administrator on 10/03/2016.
 */
jQuery(document).ready(function($){
    // tour review carousel
    var st_tour_review = $(".st_tour_ver_review");
    st_tour_review.each(function(){
        $(this).owlCarousel({
            navigation : false,
            pagination:false,
            autoPlay:true,
            autoplayTimeout: 100,
            items : 1, //10 items above 1000px browser width
            itemsDesktop : false,
            itemsDesktopSmall : false,
            itemsTablet: false,
            itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
        });
        var left= ($(this).parent().parent().children(".div_review_inner").find(".div_review_half.left"));
        var right = ($(this).parent().parent().children(".div_review_inner").find(".div_review_half.right"));
        left.on("click",function(){
            st_tour_review.trigger('owl.prev');
        });
        right.on("click",function(){
            st_tour_review.trigger('owl.next');
        });
    });

});