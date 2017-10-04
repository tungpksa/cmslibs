jQuery(".edusite-carousel").each(function(){
            var al = jQuery(this);
            if (!al.data("owl-carousel-initialized")) {
                if (al.hasClass("exclude-carousel")) {
                    return
                }
                var ar = al.data("owl-options");

                if (ar !== undefined) {
                    var an = ("true" == ar.autoplay) ? true : false,
                        ac = (ar.autoplayTimeout) ? ar.autoplayTimeout : "5000",
                        ad = ar.items,
                        ai = ("true" == ar.nav) ? true : false,
                        at = ("true" == ar.dots) ? true : false,
                        aq = ("true" == ar.autoplayHoverPause) ? true : false,
                        ae = ar.desktop,
                        ao = (ar.tablet) ? ar.tablet : "2",
                        ats = (ar.ades) ? ar.ades : ar.items,
                        ak = (ar.mobile) ? ar.mobile : "1",
                        am = ar.sm_mobile,
                        ag = ar.custom_responsive,
                        ah = ("true" == ar.rtl) ? true : false,
                        aj = (ar.loop) ? ar.loop : true,
                        ab = ("true" == ar.autoHeight) ? true : false,
                        aa = (ar.animateIn) ? ar.animateIn : "",
                        af = (ar.animateOut) ? ar.animateOut : "",
                        ap = {
                            items: 1,
                            autoplay: an,
                            autoplayTimeout: ac,
                            autoplayHoverPause: aq,
                            nav: ai,
                            dots: at,
                            loop: aj,
                            autoHeight: ab,
                            smartSpeed: 400,
                            navText: ['<i class="fa fa-arrow-left"></i>', '<i class="fa fa-arrow-right"></i>'],
                            rtl: ah
                        };

                    ap.items = ad;                    

                    if (!aa && !ab && "1" != ad && "true" != ag) {
                        ap.responsive = {
                            0: {
                                items: ak
                            },
                            584: {
                                items: ao
                            },
                            784: {
                                items: ats
                            },
                            992: {
                                items: ad
                            }
                        }
                    } else {
                        ap.responsive = {
                            0: {
                                items: am
                            },
                            376: {
                                items: ak
                            },
                            601: {
                                items: ao
                            },
                            769: {
                                items: ae
                            },
                            993: {
                                items: ad
                            }
                        }
                    }
                    if (aa) {
                        ap.animateIn = aa
                    }
                    if (af) {
                        ap.animateOut = af
                    }
                    
                    if(ar.margin !== undefined){                        
                        ap.margin = parseInt(ar.margin);                    
                    }else{ ap.margin=30; }
                    
                    al.owlCarousel(ap)
                }
                al.data("owl-carousel-initialized", true)
            }
            
});