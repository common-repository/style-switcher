(function($) {
  $(function() {
    $(document).on("click", ".style-switcher-open a", function(e) {
      e.preventDefault();
      if ($(".style-switcher-content").hasClass('open')) {
        $("#style-switcher-toggle-text").html("Show");
        $(".style-switcher-content").removeClass('open');
      }
      else {
        $("#style-switcher-toggle-text").html("Hide");
        $(".style-switcher-content").addClass('open');
      }
    });
  });
})( jQuery );
