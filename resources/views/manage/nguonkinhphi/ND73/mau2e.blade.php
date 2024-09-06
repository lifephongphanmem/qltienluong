<div id="tab2e" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Phân loại mức độ tự chủ tài chính</label>
                    {!! Form::select(null, getPhanLoaiNguon(), session('admin')->phanloainguon, [
                        'class' => 'form-control',
                        'disabled',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số lượng đơn vị đến 31/12/2023</label>
                    {!! Form::text('tongsodonvi1', null, [
                        'id' => 'tongsodonvi1',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số lượng đơn vị đến 31/12/2024</label>
                    {!! Form::text('tongsodonvi2', null, [
                        'id' => 'tongsodonvi2',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Kinh phí tiết kiệm được từ việc thay đổi cơ chế tự chủ trong 1
                        tháng</label>
                    {!! Form::text('quy_tuchu', null, ['class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Kinh phí tiết kiệm năm 2024</label>
                    {!! Form::text('hesoluong_2i', null, ['class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                </div>
            </div>
        </div>
    </div>

</div>
