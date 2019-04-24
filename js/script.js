

$('#sm-box').delay(1500).fadeOut();


$('#avatar').change(function () {
  $('#submitAva').click();
})


$('#inputGroupFile04').on('change', function () {
  //get the file name
  var fileName = $(this).val();
  //replace the "Choose a file" label
  $(this).next('.custom-file-label').html(fileName);
})



// Animations init
new WOW().init();