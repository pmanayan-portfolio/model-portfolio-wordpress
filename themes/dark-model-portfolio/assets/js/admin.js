jQuery(function($) {
  $(document).on('click', '.dmp-select-image', function(e) {
    e.preventDefault();
    const button = $(this);
    const wrap = button.closest('.dmp-image-field');
    const input = wrap.find('.dmp-image-url');
    const preview = wrap.find('.dmp-image-preview');
    const frame = wp.media({ title: 'Choose image', button: { text: 'Use this image' }, multiple: false });
    frame.on('select', function() {
      const attachment = frame.state().get('selection').first().toJSON();
      input.val(attachment.url);
      preview.html('<img src="' + attachment.url + '" alt="">');
    });
    frame.open();
  });
});
