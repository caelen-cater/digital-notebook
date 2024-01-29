$(document).ready(function() {
  $('.form').find('input, textarea').on('keyup blur focus', function (e) {
    var $this = $(this),
        label = $this.prev('label');

    if (e.type === 'keyup') {
      if ($this.val() === '') {
        label.removeClass('active highlight');
      } else {
        label.addClass('active highlight');
      }
    } else if (e.type === 'blur') {
      if( $this.val() === '' ) {
        label.removeClass('active highlight'); 
      } else {
        label.removeClass('highlight');   
      }   
    } else if (e.type === 'focus') {
      if( $this.val() === '' ) {
        label.removeClass('highlight'); 
      } else if( $this.val() !== '' ) {
        label.addClass('highlight');
      }
    }
  });

  $('.tab a').on('click', function (e) {
    e.preventDefault();
  
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
  
    target = $(this).attr('href');

    $('.tab-content > div').not(target).hide();
  
    $(target).fadeIn(600);
  });

  $('#signupForm').on('submit', function(e) {
    e.preventDefault();

    var password = $(this).find('input[type="password"]').val();
    var confirmPassword = $(this).find('input[type="password"]').val();

    if (password !== confirmPassword) {
      alert('Passwords do not match');
      return;
    }

    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: $(this).serialize(),
      success: function(response) {
        if (response === 'Email is already in use') {
          alert(response);
        } else {
        }
      }
    });
  });

  $('#loginForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: $(this).serialize(),
      success: function(response) {
        if (response === 'Email not found') {
          alert(response);
        } else {
        }
      }
    });
  });
});