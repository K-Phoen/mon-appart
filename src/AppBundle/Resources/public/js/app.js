(function() {

    function initCommentLinks() {
        $('a.comment').on('click', function(e) {
            e.preventDefault();

            $('#commentModal').foundation('reveal', 'open');
            $('#commentModal form').attr('action', Routing.generate('comment', {
                'id': $(this).closest('.offer').data('offer-id')
            }))
        });
    }

    function initStarActionLinks() {
        $('a.star-action').on('click', function(e) {
            var offerElm = $(this).closest('.offer');

            e.preventDefault();

            toggleStar(offerElm.data('offer-id'), offerElm);
        });
    }

    function initFlagAsViewedLinks() {
        $('a.view-action').on('click', function(e) {
            var offerElm = $(this).closest('.offer');

            e.preventDefault();

            flagAsView(offerElm.data('offer-id'), offerElm);
        });
    }

    function toggleStar(id, offerElm) {
        var starred = isStarred(id, offerElm);

        $.ajax({
            url: Routing.generate(starred ? 'api_offer_unstar' : 'api_offer_star', {
                id: id
            }),
            method: 'POST'
        });

        $('.star-action i', offerElm).toggleClass('fa-star');
        $('.star-action i', offerElm).toggleClass('fa-star-o');
    }

    function isStarred(id, offerElm) {
        return $('.star-action i', offerElm).hasClass('fa-star');
    }

    function flagAsView(id, offerElm) {
        $.ajax({
            url: Routing.generate('api_offer_flag_viewed', {
                id: id
            }),
            method: 'POST'
        });

        $(offerElm).remove();
    }

    $(function() {
        $(document).foundation();

        initCommentLinks();
        initStarActionLinks();
        initFlagAsViewedLinks();
    });
})(jQuery);
