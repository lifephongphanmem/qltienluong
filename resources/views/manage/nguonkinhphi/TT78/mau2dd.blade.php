<!--form1 thông tin cơ bản -->
<div id="tab2dd" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng số có mặt đến 31/12/2015</label>
                    {!! Form::text('tongsonguoi2015', null, [
                        'id' => 'tongsonguoi2015',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng số có mặt đến 01/07/2017</label>
                    {!! Form::text('tongsonguoi2017', null, [
                        'id' => 'tongsonguoi2017',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Quỹ lương, phụ cấp tháng 07/2017</label>
                    {!! Form::text('quyluong', null, ['id' => 'quyluong', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng số có mặt đến 01/07/2023</label>
                    {!! Form::text('soluonghientai_2dd', null, [
                        'id' => 'soluonghientai_2dd',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Quỹ lương, phụ cấp tháng 07/2023</label>
                    {!! Form::text('quyluonghientai_2dd', null, [
                        'id' => 'quyluonghientai_2dd',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Kinh phí tiết kiệm được trong 01 tháng</label>
                    {!! Form::text('kinhphitietkiem_2dd', null, [
                        'id' => 'kinhphitietkiem_2dd',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Quỹ lương tiết kiệm được trong năm 2023</label>
                    {!! Form::text('quyluongtietkiem_2dd', null, [
                        'id' => 'quyluongtietkiem_2dd',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>


</div>

<script></script>
<!--end form1 Thông tin cơ bản -->
