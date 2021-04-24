$(function () {
    $(".header li a").each(function () {
      var target = $(this).attr("href");
      if (location.href.match(target)) {
        $(this).parent().addClass("current");
      } else {
        $(this).parent().removeClass("current");
      }
    });
  });