$(function() {
  $('.js-formContent-text').on('keyup', function() {
    const count = $(this).val().length;
    console.log(count);
    $('.js-countView').text(count);
  });
});