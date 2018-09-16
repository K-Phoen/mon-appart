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

$('.star').on('click', function (event) {
    const offerId = $(this).data('offer-id');
    const isStarred = $(this).hasClass('fas');

    event.preventDefault();

    console.log(offerId);

    $(this).removeClass('fas');
    $(this).removeClass('far');
    $(this).addClass('fas fa-spinner');

    $.ajax({
        method: 'POST',
        url: isStarred ? '/' + offerId + '/unstar' : '/' + offerId + '/star'
    }).done(function () {
        $(this).removeClass('fas');
        $(this).removeClass('spinner');
        $(this).addClass(isStarred ? 'far' : 'fas');
    });
});