jQuery(document).ready(function ($) {
  $(".menu-item").on("click", function (event) {
    event.stopPropagation(); // prevent event from bubbling up to parents
    var submenu = $(this).find(".mega-menu-content");

    if (submenu.is(":visible")) {
      // if submenu is visible, hide it
      submenu
        .stop(true, true)
        .slideUp(200)
        .fadeOut({ queue: false, duration: "slow" });
    } else {
      // hide other mega menus
      $(".mega-menu-content")
        .stop(true, true)
        .slideUp(200)
        .fadeOut({ queue: false, duration: "slow" });

      // show current submenu
      submenu
        .stop(true, true)
        .slideDown(200)
        .fadeIn({ queue: false, duration: "slow" });
    }
  });

  // Hide submenu when clicking outside
  $(document).on("click", function (e) {
    if (
      $(".mega-menu-content").is(":visible") &&
      !$(e.target).closest(".menu-item").length
    ) {
      $(".mega-menu-content")
        .stop(true, true)
        .slideUp(200)
        .fadeOut({ queue: false, duration: "slow" });
    }
  });
});
