<div class="modal fade" id="move-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex flex-column">
                <h3 class="font-weight-bold">{{$detail}}({{$category === 'all' ? '전체' : $category.'학년'}})</h3>
                <hr class="w-100">
                <h3 class="modal-title d-flex">..</h3>
                <div class="d-flex">
                    <span class="important-icon text-danger">*</span><span id="read-text">상위 폴더로 이동하려면 ..이나 폴더 이름을 클릭해주세요.</span>
                </div>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th><i id="file-icon" class="fa-sharp fa-file" style="font-size: 1.3rem"></i></th>
                        <th>이름</th>
                        <th>수정한 날짜</th>
                        <th>파일 크기</th>
                        <th>작성자</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary confirmed-btn">여기로 이동</button>
                <button class="btn btn-outline-dark" data-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>
