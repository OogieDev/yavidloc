(function () {

  $(document).ready(function () {
    if (window.innerWidth >= 992) {
      $('.navbar__catalog-wrapper').on('mouseenter', function () {
        var catalog = $(this).find('.navbar__catalog');

        if ($(catalog).hasClass('opened')) {
          $(catalog).removeClass('opened');
          $(catalog).stop().fadeOut(300);
        } else {
          $(catalog).addClass('opened');
          $(catalog).stop().fadeIn(300);
        }
      });

      $('.navbar__catalog-wrapper').on('mouseleave', function () {
        var catalog = $(this).find('.navbar__catalog');

        $(catalog).removeClass('opened');
        $(catalog).stop().fadeOut(300);
      });
    } else {

    }

    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      infinite: false,
      asNavFor: '.slider-nav'
    })
    $('.slider-nav').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      centerMode: false,
      focusOnSelect: true,
      infinite: false,
      prevArrow: $('.kitchen__slider-prev'),
      nextArrow: $('.kitchen__slider-next')
    })

    $('[data-fancybox="images"]').fancybox({
      loop: false,
      infobar: false,
      smallBtn: true
    });

  });

  $('#uploader').on('change', function () {
    var file = this.files[0];

    var span = $(this).parent().find('.filename');

    $(span).text(file.name);

  });

  $('.calc-params__target').click(function () {
    if (!$(this).hasClass('opened')) {
      $(this).addClass('opened');
      $(this).parent().siblings('.calc-params__content').stop().slideDown(300);
    } else {
      $(this).removeClass('opened');
      $(this).parent().siblings('.calc-params__content').stop().slideUp(300);
    }
  });

  $('.calc-params__right-block input[type="checkbox"], .calc-params__right-block .checkbox__label').click(function () {
    var input = $(this).parent().find('input[type="checkbox"]')[0];

    if (input.checked) {
      var radios = $(this).closest('.calc-params__right-block').find('input[type="radio"]');
      radios.each(function () {
        $(this).removeAttr('disabled');
      });
    } else {
      var radios = $(this).closest('.calc-params__right-block').find('input[type="radio"]');
      radios.each(function () {
        $(this).attr('disabled', true);
        this.checked = false;
      });
    }

  });

  $('.calc input[type="radio"]').click(function () {
    if (!$(this).hasClass('checked')) {
      $(this).addClass('checked');
    } else {
      $(this).removeClass('checked');
      this.checked = false;
    }
  });

})();