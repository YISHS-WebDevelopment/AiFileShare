<script>
    $(() => {
        $(document)
            //폴더 이름 바꿀 때
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
                        rename.attr('data-title', res.title);
                        if (!res) return alert('중복되는 폴더 이름이 있습니다.');

                        $('#rename-modal').modal('hide');
                        $(`#folder_${res.id}`).html(res.title);
                        $('.date-td').html(res.created_at);
                    },
                    error: function (res) {
                        console.log(res);
                    }
                })
            })
            //다음으로 이동 누를 때
            .on('click', '.move-btn', function () {
                $('#move-modal').attr({'data-target': $(this).attr('data-tg'), 'data-type': $(this).attr('data-type')});
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
            //모달안에서 폴더 누를 때
            .on('click', '.folder-tr', function () {
                const modal = $('#move-modal');
                if (modal.attr('data-type') === 'folder' && modal.attr('data-target') === $(this).attr('data-id')) return alert('이동 시킬 폴더에는 이동시킬 수 없습니다.');
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
            //모달안에서 path 누를 때
            .on('click', '.path-piece', function () {
                modalMake($(this).attr('data-url'), $(this).attr('data-id'));
            })
            //이동시키는 버튼 누를 때
            .on('click', '.confirmed-btn', function () {
                const modal = $('#move-modal');
                //폴더인지 파일인지
                const type = modal.attr('data-type');
                //이동이 될 폴더 아이디
                const id = modal.attr('data-id');
                //폴더나 파일의 id
                const target = modal.attr('data-target');

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{route('folder.move.action')}}',
                    type: 'post',
                    data: {'type': type, 'id': id, 'target': target},
                    success: function (res) {
                        alert(res.msg);
                        if (res.state) return location.reload();
                    },
                    error: function (res) {
                        console.log(res);
                    }
                })
            })
    })
    const modalMake = (url, id) => {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('folder.move')}}',
            type: 'post',
            data: {'url': url, 'id': id, 'detail': '{{$detail}}', 'category': '{{$category}}'},
            success: function (res) {
                //modal에 계속 data를 업데이트 해주는 이유 -> 이동시킬 때 modal에 저장된 정보로 이동 시키려고
                $('#move-modal').attr({
                    'data-url': url,
                    'data-id': id,
                    'data-detail': '{{$detail}}',
                    'data-category': '{{$category}}'
                });

                //path 보여주기
                if (res.path) {
                    let path = res.path.reduce((acc, cur) => {
                        acc += `<a class="path-piece" data-id="${cur.id}" data-url="${cur.url}">/${cur.title}</a>`;
                        return acc;
                    }, '');
                    let parentUrl = !res.parent ? res.parent : res.parent.url;
                    let parentId = !res.parent ? res.parent : res.parent.id;
                    $('#move-modal .modal-title').html(`<a class="path-piece" data-id="${parentId}" data-url="${parentUrl}">..</a>${path}`);
                } else {
                    $('#move-modal .modal-title').html(`<a class="path-piece">..</a>`);
                }

                //list 뿌려주기
                let list = res.html.reduce((acc, cur) => {
                    //extension 은 파일인지 폴더인지 구별해주기 위해서 써줬다
                    if (!cur.extension) acc += `<tr class="folder-tr" data-url="${cur.url}" data-id="${cur.id}">
                                                <td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>`;
                    else acc += `<tr>
                                <td><img src="{{asset('/public/images/txt_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>`;
                    acc += `
                            <td>${cur.title}</td>
                            <td>${cur.created_at}</td>
                            <td>${getFileSize(cur.size)}</td>
                            <td>${cur.user.student_id + cur.user.username}</td>
                            </tr>
                           `;
                    return acc;
                }, '');
                $('#move-modal tbody').html(list);
            },
            error: function (res) {
                console.log(res);
            }
        });
    }
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
</script>
