(function($) {

  var attrs = ['for', 'id', 'name'];
  function resetAttributeNames(section) {
      var tags = section.find('input, label'), idx = section.index();
      tags.each(function() {
        var $this = jQuery(this);
        jQuery.each(attrs, function(i, attr) {
          var attr_val = $this.attr(attr);
          if (attr_val) {
              $this.attr(attr, attr_val.replace(/\[items\]\[\d+\]\[/, '\[items\]\['+(idx + 1)+'\]\['))
          }
        })
      })
  }

  $(function() {
    if ($(".style-switcher-items-wrapper").length > 0) {
      $(document).on("click", "#style-switcher-addnew-button, .style-switcher-repeat-add", function(e) {
        e.preventDefault();
        if ($(this).attr("id") == "style-switcher-addnew-button") {
          var currentKey = $("#switcher-item-key").val()*1;
          var nextKey = currentKey + 1;
          var wrapper = $(".style-switcher-items-wrapper");
          var switcherHTML = $(".style-switcher-items-wrapper .style-switcher-item-section:first-of-type").clone(true);
          wrapper.append('<div class="style-switcher-item-section new-switcher-item">' + switcherHTML.html() + '</div>');
        }
        else {

          var wrapper = $(this).parent();
          var lastItem = wrapper.find(".style-switcher-item-field:last-of-type");
          var currentKey = lastItem.data('repeat-number')*1;
          var nextKey = currentKey + 1;
          var switcherHTML = lastItem.clone(true);
          var fieldKey = lastItem.data('field-key');
          $(this).before('<div class="style-switcher-item-field new-switcher-item" data-repeat-number="' + nextKey + '" data-field-key="' + fieldKey + '">' + switcherHTML.html() + '</div>');
        }
        var newItem = $(".new-switcher-item");

        if ($(this).attr("id") == "style-switcher-addnew-button") {
          // ONLY KEEP FIRST OF REPEATING OPTIONS
          newItem.find(".style-switcher-repeat-field-wrapper .style-switcher-item-field:not(:first-of-type)").remove();
          newItem.html(newItem.html().replace(/\[items\]\[\d+\]\[/g, '\[items\]\['+(nextKey)+'\]\['));
          $("#switcher-item-key").val(nextKey);
        }
        else {
          newItem.html(newItem.html().replace(/\[repeatitems\]\[\d+\]/g, '\[repeatitems\]\['+(nextKey)+'\]'));
        }

        newItem.find('input').val('');
        newItem.find('select').val('');
        newItem.find('input:radio, input:checkbox').attr('checked', false);
        newItem.removeClass('new-switcher-item');

      });
    }

    $(document).on("click", ".style-switcher-remove-item a", function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to remove this item?")) {
        var thisID = $(this).attr("id").replace('style-switcher-remove-item-', '');
        var thisType = $(this).data('type');
        var itemID = $(this).data("itemid");
        var thisField = $(this).data("field");

        if (thisType == "item") {
          $(this).parents(".style-switcher-item-section").fadeOut('normal', function() {$(this).remove();});
        }
        else if (thisType == "option") {
          $(this).parents(".style-switcher-item-field").fadeOut('normal', function() {$(this).remove();});
        }


        var data = {
          'action': 'style_switcher_remove_item',
          'type': thisType,
          'id': thisID,
          'itemID': itemID,
          'field': thisField
        };
        $.post(ajaxurl, data, function(response) {

        });



      }
    });

  });
})( jQuery );
