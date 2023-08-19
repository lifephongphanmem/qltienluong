<div id="tab2c" class="tab-pane">
    <h3 class="text-warning">Số liệu chỉ dành cho các đơn vị: Xã, phường, thị trấn</h3>
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Phân loại xã</label>
                    {!! Form::select('phanloaixa', getPhanLoaiXa(), session('admin')->phanloaixa, [
                        'class' => 'form-control',
                        'disabled',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Thôn thuộc xã biên giới, hải đảo</label>
                    {!! Form::text('sothonbiengioi_2d', null, [                        
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổ dân phố thuộc xã biên giới, hải đảo</label>
                    {!! Form::text('sotodanphobiengioi_2d', null, [                       
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Số thôn có 350 hộ gia đình trở lên</label>
                    {!! Form::text('sothon350hgd_2d', null, [
                        'id' => 'sothon350hgd_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổ dân phố có 500 hộ gia đình trở lên</label>
                    {!! Form::text('sotodanpho500hgd_2d', null, [
                        'id' => 'sotodanpho500hgd_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổ dân phố thuộc xã trọng điểm, phức tạp về an
                        ninh</label>
                    {!! Form::text('sothontrongdiem_2d', null, [
                        'id' => 'sothontrongdiem_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Tổ dân phố chuyển từ thôn có 350 HGD trở lên</label>
                    {!! Form::text('sochuyentuthon350hgd_2d', null, [
                        'id' => 'sochuyentuthon350hgd_2d',
                        'class' => 'form-control',
                        'data-mask' => 'fdecimal',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
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
