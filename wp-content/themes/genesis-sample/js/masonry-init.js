(function($) {
  // init Masonry
  var $grid = $(".content").masonry({
    // use outer width of grid-sizer for columnWidth
    columnWidth: ".grid-sizer",

    // adds horizontal space between item elements
    gutter: ".gutter-sizer",

    // specifies which child elements will be used as item elements in the layout
    itemSelector: ".entry",

    // sets item positions in percent values, rather than pixel values
    percentPosition: true,

    // lays out items to (mostly) maintain horizontal left-to-right order
    horizontalOrder: true
  });

  // layout Masonry after each image loads
  $grid.imagesLoaded().progress(function() {
    $grid.masonry();
  });
})(jQuery);