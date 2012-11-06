/**
 * Custom scripts to load on DOM ready
 * 
 * @param  jQuery $
 * @return void
 */
(function($) {
    // Apply custom dropdowns
    $(document).foundationCustomForms();

    // Expand scorecard form
    $('#expand-scoreboard').on('click', function(event) {
        event.preventDefault();
        // Get scoreboard
        var $scoreboard = $(this).closest('.your-scorecard');
        // Remove "expand" button
        $scoreboard.find($(this)).remove();
        // Add to content area
        $scoreboard.hide()
                   .appendTo('#your-score-area')
                   .slideDown('slow');
    });

    // Add grade class to selected item in custom dropdown
    $('.score-form select').on('change', function() {
        var gradeClass    = $(this).val().toLowerCase().replace(/[^a-z]/g, ''),
            $currentValue = $(this).next('.dropdown').find('.current'); 

        $currentValue.attr('class', 'current');
        $currentValue.addClass(gradeClass);        
    });
    
    // Add grade class to custom dropdown items
    $('.dropdown li').each(function() {
        $(this).addClass($(this).text().toLowerCase().replace(/[^a-z]/g, ''));
    });

    // Custom function to make popovers stay on mouseover
    $.fn.persistantPopover = function() {
        var popoverTimeout;

        function delay() {
            popoverTimeout = setTimeout(function() {
                $('.popover').hide();
            }, 200);
        }

        return this.each(function() {
            var $this = $(this);
            $this.popover({
                trigger   : 'manual',
                placement : 'right',
                animation : false,
                content   : $this.data('excerpt') + '<a class="more-link" href="' + $this.attr('href') + '">Read more and had out grades</a>'
            });
        })
        .mouseenter(function() {
            clearTimeout(popoverTimeout);
            $('.popover').remove();
            $(this).popover('show');
        })
        .mouseleave(function() {
            delay();
            $('.popover').mouseenter(function() {
                clearTimeout(popoverTimeout) ;
            }).mouseleave(function() {
                delay();
            });
        });
    };

    // Article popovers
    $('.group-list a').persistantPopover();

}(jQuery));