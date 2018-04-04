$(document).ready(function() {
    $(".textoTop").css({"position":"relative","opacity": 0, "top":"+=100"});
    $(".right-move").css({"position":"relative","opacity": 0, "right":"-=100"});
    $(".left-move").css({"position":"relative","opacity": 0, "left":"-=100"});
    $(".textoTop").animate({top:0, opacity:1}, { duration: 1300, queue: false});
    $(".left-move").animate({left:0, opacity:1}, { duration: 1300, queue: false});
    $(".right-move").animate({right:0, opacity:1}, { duration: 1300, queue: false });
  });