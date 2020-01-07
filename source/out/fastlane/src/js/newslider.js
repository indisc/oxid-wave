$(window).on('load', function() {
    var $window = $(this),
        $productSlide = $('#productSlider .flexslider'),
        flexslider;

    function getGridSize() {
        return ($productSlide.width() < 600) ? 2 :
            ($productSlide.width() < 900) ? 3 : 4;
    }

    flexslider = $productSlide.flexslider(
        {
            animation: "slide",
            animationLoop: false,
            itemWidth: 210,
            itemMargin: 80,
            minItems: getGridSize(), // use function to pull in initial value
            maxItems: getGridSize(), // use function to pull in initial value
            controlNav: true
        }
    );

    // check grid size on resize event
    $window.resize( function()
        {
            var gridSize = getGridSize();

            flexslider.data().flexslider.vars.minItems = gridSize;
            flexslider.data().flexslider.vars.maxItems = gridSize;
        }
    );

});