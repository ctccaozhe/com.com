$(document).ready(function () {
  $(window).scroll(function () {
    let scroTop = $(window).scrollTop();
    let isShown = $(".acgTop").css("display") !== "none";
    if (scroTop > 593) {
      if (isShown) return;
      $(".acgTop").fadeIn("600", function () {
        $(this).addClass("active in").removeAttr("style");
      });
    } else {
      if (!isShown) return;
      $(".acgTop").fadeOut("800", function () {
        $(this).removeClass("active in");
      });
    }
  });
  $(".acgTop").click(function () {
    $("body,html").animate({ scrollTop: 0 }, 1000);
    return false;
  });
});
