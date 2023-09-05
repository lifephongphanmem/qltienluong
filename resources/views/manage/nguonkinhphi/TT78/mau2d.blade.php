<div id="tab2d" class="tab-pane">
    <h3 class="text-warning">Số liệu chỉ dành cho các đơn vị: Xã, phường, thị trấn</h3>
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Phân loại xã</label>
                    {!! Form::select('', getPhanLoaiXa(), session('admin')->phanloaixa, [
                        'class' => 'form-control',
                        'disabled',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số lượng cán bộ định biên (NĐ34/2019/NĐ-CP)</label>
                    {!! Form::text('soluongdinhbien_2d', null, [
                        'class' => 'form-control',
                        // 'readonly',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số lượng cán bộ, công chức xã năm 2022</label>
                    {!! Form::text('soluongcanbo_2d', null, [                        
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số lương bình quân năm 2022</label>
                    {!! Form::text('hesoluongbq_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số phụ cấp bình quân năm 2022</label>
                    {!! Form::text('hesophucapbq_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tỷ lệ phụ cấp tính các khoản đóng góp năm 2022</label>
                    {!! Form::text('tyledonggop_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>



            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số lượng cán bộ định biên theo NĐ 33/2023/NĐ-CP</label>
                    {!! Form::text('', getSoLuongCanBoDinhMuc(getNghiDinhPLXaPhuong(session('admin')->phanloaixa), session('admin')->phanloaixa), [                        
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                        'readonly'
                    ]) !!}
                </div>
            </div>                
        </div>        
    </div>
</div>
