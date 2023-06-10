<div id="tab2d" class="tab-pane">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số thôn thuộc xã biên giới, hải đảo</label>
                    {!! Form::text('sothonbiengioi_2d', null, [  
                        'id' => 'sothonbiengioi_2d',                      
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số thôn trọng điểm, phức tạp về an ninh</label>
                    {!! Form::text('sothontrongdiem_2d', null, [
                        'id' => 'sothontrongdiem_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số thôn còn lại</label>
                    {!! Form::text('sothonconlai_2d', null, [
                        'id' => 'sothonconlai_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>         
            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số tổ dân phố còn lại</label>
                    {!! Form::text('sotoconlai_2d', null, [
                        'id' => 'sotoconlai_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>            
        </div>        
    </div>

</div>
