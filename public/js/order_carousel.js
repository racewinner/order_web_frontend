class OrderCarousel {
    constructor(el, options) {
        const theThis = this;
        this.carousel_el = el;
        const carousel_body = $(el).find(".carousel")[0];

        const paddingL = options?.paddingL ?? 0;
        const carousel_width = carousel_body.clientWidth;
        const itemWidth = $(el).find('.carousel-item').first().innerWidth();
        const itemCount = parseInt((carousel_width - paddingL * 2)  / itemWidth);
        const padding = itemCount > 1 ? 
            Math.floor((carousel_width - itemWidth * itemCount - paddingL * 2) / (itemCount - 1)) : 
            (carousel_width - itemWidth) / 2 + 10;
        
        this.carousel_instance = M.Carousel.init(carousel_body, {
            paddingX: paddingL,
            numVisible: itemCount,
            dist: 0,
            padding: padding,
            noWrap: false,
            duration: 500,
            indicators: options?.indicators ?? false,
            autoCarousel: {
                enable: options?.autoPlay ?? true,
                interval: options?.dwell_time ?? 5000
            }
        });

        $(el).on('click', "a.carousel-left", function() {
            theThis.carousel_instance.prev();
            theThis.carousel_instance.pauseAutoCarousel(5000);
        })
        $(el).on('click', "a.carousel-right", function() {
            theThis.carousel_instance.next();
            theThis.carousel_instance.pauseAutoCarousel(5000);
        })
    }
}

var OrderCarouselManager = {
    _carousels : [],
    init: function(el, options) {
        let instance = new OrderCarousel(el, options);
        this._carousels.push(instance);
        return instance;
    }
}

class BannerCarousel {
    _initialized = false;
    _instance = null;
    _el = null;
    _options = {};

    constructor(el, options) {
        const theThis = this;
        this._el = el;
        this._options = {
            ...this._options,
            ...options
        };

        const banner_images = $(el).find(".carousel-item:first img");
        banner_images?.each(function() {
            if(this.complete) {
                console.log('banner image already loaded', this);
                theThis._bannerImageLoaded(this);
            } else {
                $(this).on('load', function(e) {
                    console.log('banner image was loaded', e.target)
                    theThis._bannerImageLoaded(e.target);
                })
            }
        })
    }
    _bannerImageLoaded (img) {
        const carouselBody = $(img).closest(".carousel")[0];
        const carouselItem = $(img).closest(".carousel-item")[0];
        
        console.log('carousel-item height', carouselItem.offsetHeight);
        console.log('carousel-body height', carouselBody.offsetHeight);

        if(!carouselBody.offsetHeight && carouselItem.offsetHeight > 0) {
            carouselBody.style.setProperty('height', `${carouselItem.offsetHeight}px`, 'important');
        }

        if(!this._instance) {
            this._initCarousel();
        }
    }
    _initCarousel() {
        console.log('initializing carousel...');
        this._instance = OrderCarouselManager.init(this._el, this._options);
    }
}

var BannerCarouselManager = {
    _carousels: [],
    init: function(el, options) {
        let instance = new BannerCarousel(el, options);
        this._carousels.push(instance);
        return instance;
    }
}