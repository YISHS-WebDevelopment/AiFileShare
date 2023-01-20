<script>
    $(() => {
        $(document)
            .on('click', '#rename-btn', function () {
                const rename = $('#rename');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('folder.rename')}}',
                    type: 'post',
                    data: {'id': rename.attr('data-id'), 'title': $('#rename-modal #folder-input').val()},
                    success: function (res) {
                        console.log(res);
                        rename.attr('data-title', res.title);
                        if (!res) return alert('중복되는 폴더 이름이 있습니다.');

                        $('#rename-modal').modal('hide');
                        $(`#folder_${res.id}`).html(res.title);

                    },
                    error: function (res) {
                        console.log(res);
                    }
                })
            })
            //다음으로 이동 누를 때
            .on('click', '.move-btn', function () {
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
            //모달안에서 폴더 누를 때
            .on('click', '.folder-tr', function() {
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
            //모달안에서 path 누를 때
            .on('click', '.path-piece', function() {
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
    })
    const getFileSize = (size) => {
        let kb = size / 1024;
        let mb = kb / 1024;
        let gb = mb / 1024;

        let result;

        if (mb >= 1) result = String(mb.toFixed(1)) + 'MB';
        else if (gb >= 1) result = String(gb.toFixed(1)) + 'GB';
        else result = String(kb.toFixed(1)) + 'KB';

        return result;
    }
    const getUserName = (id) => {
        let result;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : '{{route('get.username')}}',
            type : 'post',
            data : {'id' : id},
            success : function(res) {
                result = res.student_id + res.username;
                return result;
            }
        })
    }
    const modalMake = (url, id) => {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : '{{route('folder.move')}}',
            type : 'post',
            data : {'url' : url, 'id' : id, 'detail' : '{{$detail}}', 'category' : '{{$category}}'},
            success : function(res) {
                //path 보여주기
                if(res.path) {
                    let path = res.path.reduce((acc,cur) => {
                        acc += `<a class="path-piece" data-id="${cur.id}" data-url="${cur.url}">/${cur.title}</a>`;
                        return acc;
                    }, '');
                    let parentUrl = !res.parent ? res.parent : res.parent.url;
                    $('#move-modal .modal-title').html(`<a class="path-piece" data-id="${res.current.id}" data-url="${parentUrl}">..</a>${path}`);
                } else {
                    $('#move-modal .modal-title').html(`<a class="path-piece">..</a>`);
                }

                //list 뿌려주기
                let list = res.html.reduce((acc, cur) => {
                    acc += `<tr class="folder-tr" data-url="${cur.url}">`;
                    if(!cur.extension) acc += `<td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>`;
                    else acc += `<td><img src="{{asset('/public/images/txt_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>`;
                    acc += `
                                <td>${cur.title}</td>
                                <td>${cur.created_at}</td>
                                <td>${getFileSize(cur.size)}</td>
                                <td>${getUserName(cur.user_id)}</td>
                            </tr>`;
                    return acc;
                },'');
                $('#move-modal tbody').html(list);
            },
            error : function(res) {
                console.log(res);
            }
        });
    }
</script>
