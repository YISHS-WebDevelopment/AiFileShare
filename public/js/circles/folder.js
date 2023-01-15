$(() => {
    $(document)
        .on('click', '#rename', function () {
            $('#rename-modal #folder-input').val($('#rename').attr('data-title'));
        })
})
