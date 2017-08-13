$(document).ready(function(){
    // cache container
    var $container = $('.grid');
    // initialize isotope
    $container.isotope({
      masonry: {
        itemSelector: '.grid-item',
        //columnWidth: 200,
        gutter: 15
      }
    });
})