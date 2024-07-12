<div id="tab2h" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Biên chế hưởng phụ cấp UĐN đến 01/01/2023</label>
                    {!! Form::text('soluonghientai_2h', null, [
                        'id' => 'soluonghientai_2h',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng hệ số lương ngạch bậc</label>
                    {!! Form::text('hesoluong_2h', null, [
                        'id' => 'hesoluong_2h',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng phụ cấp chức vụ, vượt khung</label>
                    {!! Form::text('hesophucap_2h', null, [
                        'id' => 'hesophucap_2h',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng hệ số lương và phụ cấp</label>
                    {!! Form::text('tonghs_2h', null, [
                        'id' => 'tonghs_2h',
                        'class' => 'form-control',
                        'readonly' => 'true',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng chênh lệch hệ số UĐ (NĐ 61,64,19)</label>
                    {!! Form::text('tonghesophucapnd61_2h', null, [
                        'id' => 'tonghesophucapnd61_2h',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng chênh lệch hệ số UĐ (QĐ 244, 276)</label>
                    {!! Form::text('tonghesophucapqd244_2h', null, [
                        'id' => 'tonghesophucapqd244_2h',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

</div>
