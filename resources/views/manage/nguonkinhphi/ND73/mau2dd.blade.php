<!--form1 thông tin cơ bản -->
<div id="tab2dd" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Phân loại mức độ tự chủ tài chính</label>
                    {!! Form::select(null, getPhanLoaiNguon(), session('admin')->phanloainguon, [                        
                        'class' => 'form-control', 'disabled',
                    ]) !!}
                </div>
            </div>
           
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tổng số có mặt đến 01/07/2023</label>
                    {!! Form::text('tongsonguoi2015', null, [
                        'id' => 'tongsonguoi2015',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Tổng số có mặt đến 01/07/2024</label>
                    {!! Form::text('tongsonguoi2017', null, [
                        'id' => 'tongsonguoi2017',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Quỹ lương, phụ cấp tháng 07/2023</label>
                    {!! Form::text('quyluong', null, ['id' => 'quyluong', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                </div>
            </div>            

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Quỹ lương, phụ cấp tháng 07/2024</label>
                    {!! Form::text('quyluonghientai_2dd', null, [
                        'id' => 'quyluonghientai_2dd',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Kinh phí NSNN tiết kiệm được năm 2024</label>
                    {!! Form::text('kinhphitietkiem_2dd', null, [
                        'id' => 'kinhphitietkiem_2dd',
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
