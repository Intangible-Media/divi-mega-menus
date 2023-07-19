jQuery(document).ready(function ($) {
  $(".menu-item").on("click", function (event) {
    event.stopPropagation(); // prevent event from bubbling up to parents
    var submenu = $(this).find(".mega-menu-content");

    if (submenu.is(":visible")) {
      // if submenu is visible, hide it
      if (window.innerWidth <= 768) {
        submenu.animate({ left: "-100%" }, 200); // slide to the left on mobile
      } else {
        submenu
          .stop(true, true)
          .slideUp(200)
          .fadeOut({ queue: false, duration: "slow" });
      }
    } else {
      // if submenu is hidden, show it
      if (window.innerWidth <= 768) {
        submenu.css({ left: "-100%" }).show().animate({ left: "0" }, 200); // slide from the left on mobile
      } else {
        submenu
          .stop(true, true)
          .slideDown(200)
          .fadeIn({ queue: false, duration: "slow" });
      }
    }
  });

  // Hide submenu when clicking outside
  $(document).on("click", function (e) {
    if (
      $(".mega-menu-content").is(":visible") &&
      !$(e.target).closest(".menu-item").length
    ) {
      if (window.innerWidth <= 768) {
        $(".mega-menu-content").animate({ left: "-100%" }, 200); // slide to the left on mobile
      } else {
        $(".mega-menu-content")
          .stop(true, true)
          .slideUp(200)
          .fadeOut({ queue: false, duration: "slow" });
      }
    }
  });
});
