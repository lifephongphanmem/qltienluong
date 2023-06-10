<div id="tab2k" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Biên chế hưởng PC thu hút đến 01/01/2023</label>
                    {!! Form::text('soluonghientai_2k', null, [                        
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                        'readonly' => 'true'
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số lượng cán bộ giảm</label>
                    {!! Form::text('soluonggiam_2k', null, [
                        'id' => 'soluonggiam_2k',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Quỹ lương và phụ cấp giảm trong 01 tháng</label>
                    {!! Form::text('quyluonggiam_2k', null, [
                        'id' => 'quyluonggiam_2k',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>            
        </div>        
    </div>

</div>
