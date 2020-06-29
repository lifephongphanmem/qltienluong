
<div class="form-horizontal">
    <div class="row">
        <div class="col-md-12">
            <label class="control-label">Khối/Tổ công tác</label>
            <select name="mapb" id="mapb" class="form-control select2me">
                @foreach(getPhongBan(true) as $key=>$val)
                    <option value="{{$key}}">{{$val}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label class="control-label">Chức vụ</label>
            {!!Form::select('macvcq',getChucVuCQ(true), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
        </div>

        <div class="col-md-12">
            <label class="control-label">Phân loại công tác</label>
            <select class="form-control select2me" name="mact" id="mact">
                <option value="">-- Tất cả các phân loại công tác --</option>
                @foreach($model_nhomct as $kieuct)
                    <optgroup label="{{$kieuct->tencongtac}}">
                        <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                        @foreach($mode_ct as $ct)
                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label class="control-label">Nguồn kinh phí</label>
            <select class="form-control" id="manguonkp" name="manguonkp[]" multiple="multiple">
                @foreach($a_nguonkp_bl as $key=>$value)
                    <option value="{{$key}}" selected>{{$value}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label class="control-label">Cỡ chữ</label>
            {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
        </div>

        <div id="tl_intl" class="col-md-offset-3 col-md-8">
            <input type="checkbox" name="in_truylinh" id="in_truylinh" />
            <label for="in_truylinh" class="control-label">Bao gồm cả bảng truy lĩnh lương</label>
        </div>
        <div id="tl_intruc" class="col-md-offset-3 col-md-8">
            <input type="checkbox" name="in_bltruc" id="in_bltruc" />
            <label for="in_bltruc" class="control-label">Bao gồm cả bảng phụ cấp độc hại</label>
        </div>
    </div>
    <input type="hidden" id="thang_th" name="thang_th" value="{{$inputs['thang']}}"/>
    <input type="hidden" id="nam_th" name="nam_th" value="{{$inputs['nam']}}"/>
</div>