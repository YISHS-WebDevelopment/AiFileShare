$(() => {
    $(document)
        .on('click', '#rename', function () {
            $('#rename-modal #folder-input').val($(this).attr('data-title'));
        })
        .on('change', '.file-input', function() {
            if($('.file-input')[0].files.length > 10) {
                alert('파일은 최대 10개까지만 업로드 할 수 있습니다.');
                $('.file-input').val('');
            }
        })
})
