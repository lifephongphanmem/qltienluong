<div id="tab2i" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Biên chế hưởng PC thu hút đến 01/01/2023</label>
                    {!! Form::text('soluonghientai_2i', null, [
                        'id' => 'soluonghientai_2i',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng hệ số lương ngạch bậc</label>
                    {!! Form::text('hesoluong_2i', null, [
                        'id' => 'hesoluong_2i',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng phụ cấp chức vụ, vượt khung</label>
                    {!! Form::text('hesophucap_2i', null, [
                        'id' => 'hesophucap_2i',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng hệ số lương và phụ cấp</label>
                    {!! Form::text('tonghs_2i', null, [
                        'id' => 'tonghs_2i',
                        'class' => 'form-control',
                        'readonly' => 'true',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>        
    </div>

</div>
