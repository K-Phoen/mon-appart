import '../css/app.scss';
import $ from 'jquery';
import 'bootstrap';

$('#commentModal').on('show.bs.modal', function (event) {
    const modal = $(this);
    const commentUri = $(event.relatedTarget).data('comment-uri');
    const offerId = $(event.relatedTarget).data('offer-id');
    const offer = $('#offer-'+offerId);

    modal.find('form').attr('action', commentUri);
    modal.find('form textarea').val(offer.find('.comment').text());
});