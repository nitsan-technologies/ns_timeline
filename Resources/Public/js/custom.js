$(document).ready(function () {
    var count = $(".frst-timeline-style-15 .frst-timeline-block").length;
    var stepSize = $(".frst-timeline-style-15 .frst-timeline-block").eq(1).outerWidth();
    var timeline_width = (count * stepSize) + 120 + "px";
    $(".frst-timeline-style-15").css("width", timeline_width);
});
$(function () {
    $("#frst-carousel").overscroll({
        direction: 'horizontal'
    });
    // $("#frst-carousel>div").remove();
});

$(document).ready(function () {
    var count = $(".frst-timeline-style-5 .frst-timeline-block").length - 2;
    var stepSize = $(".frst-timeline-style-5 .frst-timeline-block").eq(1).width();
    var timeline_width = (count * stepSize) + 120 + "px";
    console.log(timeline_width);
    $(".frst-timeline-style-5").css("width", timeline_width);
});
$(function () {
    $("#frst-carousel").overscroll({
        direction: 'horizontal'
    });
    // $("#frst-carousel>div").remove();
});
    
$('.content-title').click(function (e) {
    $(this).next().slideToggle();
    $(this).next().next().next().slideToggle();
   
})