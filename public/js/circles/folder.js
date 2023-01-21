$(() => {
    $(document)
        .on('click', '#rename', function () {
            $('#rename-modal #folder-input').val($(this).attr('data-title'));
        })
})
