<div id="tab2d" class="tab-pane">
    <h3 class="text-warning">Số liệu chỉ dành cho các đơn vị: Mầm non, Tiểu học, THCS, THPT</h3>
    <div class="form-body">
        <div class="row">            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số lượng biên chế giao bổ sung năm 2024</label>
                    {!! Form::text('soluongdinhbien_2d', null, [
                        'class' => 'form-control',
                        // 'readonly',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Số tháng thực tế hưởng</label>
                    {!! Form::text('soluongcanbo_2d', null, [                        
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số lương theo ngạch bậc</label>
                    {!! Form::text('hesoluongbq_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số phụ cấp khu vực</label>
                    {!! Form::text('hesophucapbq_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số phụ cấp ưu đãi nghề</label>
                    {!! Form::text('tyledonggop_2d', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số phụ cấp thu hút</label>
                    {!! Form::text('quyluonggiam_2k', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Hệ số phụ cấp đặc biệt</label>
                    {!! Form::text('soluonggiam_2k', null, [
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>        
    </div>
</div>
