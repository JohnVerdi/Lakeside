/**
 * Created by me664 on 1/14/15.
 */
(function($){


    var STNotice;
    STNotice = function () {
        var self = this;

        this.make = function (text, type,layout) {
            //console.log(layout);
            var n;
            if (typeof type == 'undefined') {
                type = 'infomation';
            }

            if(typeof layout=='undefined'){
                layout='topRight';
            }

            n = noty({
                text: text,
                layout: layout,
                type: type,
                animation: {
                    open: 'animated bounceInRight', // Animate.css class names
                    close: 'animated bounceOutRight', // Animate.css class names
                    easing: 'swing', // unavailable - no need
                    speed: 500 // unavailable - no need
                },
                theme: 'relax'
                ,timeout: 6000
            });
        };

        this.template = function (icon, html) {
            if (typeof icon != "undefined") {
                icon = "<i class='fa fa-" + icon + "'></i>";

            }
            return "<div class='st_notice_template'>" + icon + " <div class='display_table'>" + html + "</div>  </div>";
        }
    };

    STNotice=new STNotice;
    window.STNotice = STNotice;

    //if(typeof stanalytics!='undefined'){
    //
    //    for(i=0;i<stanalytics.noty.length;i++){
    //        var val=stanalytics.noty[i];
    //
    //        STNotice.make(STNotice.template(val.icon,val.message),val.type);
    //    }
    //}
    var i=0;
    function show_noty(i)
    {
        if(typeof stanalytics.noty=="undefined") return false;
        if(i>=stanalytics.noty.length) return false;

        window.setTimeout(function(){
            var val=stanalytics.noty[i];
            var layout=stanalytics.noti_position;
            STNotice.make(STNotice.template(val.icon,val.message),val.type,layout);

            i++;
            show_noty(i);

        },500*i);

    }
    if(typeof stanalytics!='undefined')
    show_noty(0);
})(jQuery);