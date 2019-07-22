<!--form1 thông tin cơ bản -->
<div id="tab7" class="tab-pane" >
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Tình trạng hôn nhân </label>
                    {!!Form::text('tthn', null, array('id' => 'tthn','class' => 'form-control','autofocus'=>'autofocus'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Số sổ BHXH </label>
                    {!!Form::text('soBHXH', null, array('id' => 'soBHXH','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Số tài khoản ngân hàng </label>
                    {!!Form::text('sotk', null, array('id' => 'sotk','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Tên ngân hàng </label>
                    {!!Form::text('tennganhang', null, array('id' => 'tennganhang','class' => 'form-control','autofocus'=>'autofocus'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày vào TSCSHCM</label>
                    <input type="date" class="form-control" name="ngayctctxh" id="ngayctctxh" value="{{!isset($model)?'':$model->ngayctctxh}}"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Chức vụ đoàn thể </label>
                    {!!Form::text('cvtcxh', null, array('id' => 'cvtcxh','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày vào Đảng </label>
                    <input type="date" class="form-control" name="ngayvd" id="ngayvd" value="{{!isset($model)?'':$model->ngayvd}}"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Ngày vào chính thức </label>
                    <input type="date" class="form-control" name="ngayvdct" id="ngayvdct" value="{{!isset($model)?'':$model->ngayvdct}}"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Nơi kết nạp Đảng</label>
                    {!!Form::text('noikn', null, array('id' => 'noikn','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Quân hàm cao nhất</label>
                    {!!Form::text('qhcn', null, array('id' => 'qhcn','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Danh hiệu cao nhất </label>
                    {!!Form::text('dhpt', null, array('id' => 'dhpt','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Tình trạng sức khỏe</label>
                    {!!Form::text('ttsk', null, array('id' => 'ttsk','class' => 'form-control'))!!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Chiều cao </label>
                    {!!Form::text('chieucao', null, array('id' => 'chieucao','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Cân nặng</label>
                    {!!Form::text('cannang', null, array('id' => 'cannang','class' => 'form-control'))!!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Nhóm máu </label>
                    <select name="nhommau" id="nhommau" class="form-control select2me">
                        <option value="Nhóm máu A">Nhóm máu A</option>
                        <option value="Nhóm máu AB">Nhóm máu AB</option>
                        <option value="Nhóm máu B">Nhóm máu B</option>
                        <option value="Nhóm máu O">Nhóm máu O</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>

<!--end form5  -->