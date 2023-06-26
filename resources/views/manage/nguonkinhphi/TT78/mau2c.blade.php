<div id="tab2c" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số đối tượng QT thu 2022</label>
                    {!! Form::text('soluongqt_2c', null, [  
                        'id' => 'soluongqt_2c',                      
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số tiền QT thu 2022</label>
                    {!! Form::text('sotienqt_2c', null, [
                        'id' => 'sotienqt_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>  

        {{-- 2023.06.24 nôi dung này lấy những cán bộ đóng BHTN trong mẫu 2a --}}
        {{-- <div class="row">
             <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số cán bộ đóng BHTN</label>
                    {!! Form::text('soluongcanbo_2c', null, [
                        'id' => 'soluongcanbo_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>         
            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng mức lương theo ngạch bậc, chức vụ</label>
                    {!! Form::text('hesoluong_2c', null, [
                        'id' => 'hesoluong_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng phụ cấp chức vụ</label>
                    {!! Form::text('phucapchucvu_2c', null, [
                        'id' => 'phucapchucvu_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng phụ cấp vượt khung</label>
                    {!! Form::text('phucapvuotkhung_2c', null, [
                        'id' => 'phucapvuotkhung_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổng phụ cấp thâm niên nghề</label>
                    {!! Form::text('phucaptnn_2c', null, [
                        'id' => 'phucaptnn_2c',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>   --}}
              
    </div>

</div>
