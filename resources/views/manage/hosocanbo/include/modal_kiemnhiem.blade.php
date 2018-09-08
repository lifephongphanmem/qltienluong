
<!-- Kiêm nhiệm không chuyên trách -->
<div class="modal fade bs-modal-lg" id="kct-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin kiêm nhiệm cán bộ không chuyên trách</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!Form::hidden('phanloai_kct', 'KHONGCT', array('id' => 'phanloai_kct','class' => 'form-control'))!!}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control" name="mact_kct" id="mact_kct" required="required">
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php
                                        $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                        ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_kct',getChucVuCQ(false), null, array('id' => 'macvcq_kct','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_kct',getPhongBan(), null, array('id' => 'mapb_kct','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Hệ số phụ cấp</label>
                            {!!Form::text('hesopc_kct', null, array('id' => 'hesopc_kct','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <?php $a_kct = array('pcthni','pckn','pclt','pckct'); ?>
                    @foreach($model_pc as $pc)
                        @if(!in_array($pc->mapc,$a_kct))
                            @continue
                        @endif

                        @if($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_kct', null, array('id' =>$pc->mapc.'_kct', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_kct', null, array('id' =>$pc->mapc.'_kct', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    {!!Form::text($pc->mapc.'_kct', null, array('id' =>$pc->mapc.'_kct', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <input type="hidden" name="id_kct" id="id_kct" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat_kct" name="capnhat_kct" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Kiêm nhiệm đại biểu hội đồng nhân dân -->
<div class="modal fade bs-modal-lg" id="dbhdnd-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin kiêm nhiệm đại biểu hội đồng nhân dân</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!Form::hidden('phanloai_dbhdnd', 'DBHDND', array('id' => 'phanloai_dbhdnd','class' => 'form-control'))!!}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact_dbhdnd" id="mact_dbhdnd" required="required">
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php
                                        $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                        ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_dbhdnd',getChucVuCQ(false), null, array('id' => 'macvcq_dbhdnd','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_dbhdnd',getPhongBan(), null, array('id' => 'mapb_dbhdnd','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Hệ số phụ cấp</label>
                            {!!Form::text('hesopc_dbhdnd', null, array('id' => 'hesopc_dbhdnd','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <?php $a_kct = array('pckn'); ?>
                    @foreach($model_pc as $pc)
                        @if(!in_array($pc->mapc,$a_kct))
                            @continue
                        @endif

                        @if($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_dbhdnd', null, array('id' =>$pc->mapc.'_dbhdnd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_dbhdnd', null, array('id' =>$pc->mapc.'_dbhdnd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    {!!Form::text($pc->mapc.'_dbhdnd', null, array('id' =>$pc->mapc.'_dbhdnd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="id_dbhdnd" id="id_dbhdnd" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat_dbhdnd" name="capnhat_dbhdnd" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Kiêm nhiệm quân sự -->
<div class="modal fade bs-modal-lg" id="qs-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin kiêm nhiệm cán bộ quân sự</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!Form::hidden('phanloai_qs', 'QUANSU', array('id' => 'phanloai_qs','class' => 'form-control'))!!}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact_qs" id="mact_qs" required="required">
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php
                                        $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                        ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_qs',getChucVuCQ(false), null, array('id' => 'macvcq_qs','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_qs',getPhongBan(), null, array('id' => 'mapb_qs','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Hệ số phụ cấp</label>
                            {!!Form::text('hesopc_qs', null, array('id' => 'hesopc_qs','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <?php $a_kct = array('pcdbn','pcthni','pctn','pck'); ?>
                    @foreach($model_pc as $pc)
                        @if(!in_array($pc->mapc,$a_kct))
                            @continue
                        @endif

                        @if($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_qs', null, array('id' =>$pc->mapc.'_qs', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_qs', null, array('id' =>$pc->mapc.'_qs', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    {!!Form::text($pc->mapc.'_qs', null, array('id' =>$pc->mapc.'_qs', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="id_qs" id="id_qs" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat_qs" name="capnhat_qs" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Kiêm nhiệm cấp ủy viên -->
<div class="modal fade bs-modal-lg" id="cuv-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin kiêm nhiệm cấp ủy viên</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!Form::hidden('phanloai_cuv', 'CAPUY', array('id' => 'phanloai_cuv','class' => 'form-control'))!!}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact_cuv" id="mact_cuv" required="required">
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php
                                        $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                        ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_cuv',getChucVuCQ(false), null, array('id' => 'macvcq_cuv','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_cuv',getPhongBan(), null, array('id' => 'mapb_cuv','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Hệ số phụ cấp</label>
                            {!!Form::text('hesopc_cuv', null, array('id' => 'hesopc_cuv','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <?php $a_kct = array('pckn'); ?>
                    @foreach($model_pc as $pc)
                        @if(!in_array($pc->mapc,$a_kct))
                            @continue
                        @endif

                        @if($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_cuv', null, array('id' =>$pc->mapc.'_cuv', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_cuv', null, array('id' =>$pc->mapc.'_cuv', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    {!!Form::text($pc->mapc.'_cuv', null, array('id' =>$pc->mapc.'_cuv', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="id_cuv" id="id_cuv" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat_cuv" name="capnhat_cuv" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Cộng đồng -->
<div class="modal fade bs-modal-lg" id="cd-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin kiêm nhiệm trung tâm học tập cộng đồng</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!Form::hidden('phanloai_cd', 'CONGDONG', array('id' => 'phanloai_cd','class' => 'form-control'))!!}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact_cd" id="mact_cd" required="required">
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php
                                        $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac);
                                        ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_cd',getChucVuCQ(false), null, array('id' => 'macvcq_cd','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_cd',getPhongBan(), null, array('id' => 'mapb_cd','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <?php $a_cd = array('pckn'); ?>
                    @foreach($model_pc as $pc)
                        @if(!in_array($pc->mapc,$a_cd))
                            @continue
                        @endif

                        @if($pc->phanloai == 2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_cd', null, array('id' =>$pc->mapc.'_cd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($pc->phanloai == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    <div class="input-group bootstrap-touchspin">
                                        {!!Form::text($pc->mapc.'_cd', null, array('id' =>$pc->mapc.'_cd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">{{$pc->form}}</label>
                                    {!!Form::text($pc->mapc.'_cd', null, array('id' =>$pc->mapc.'_cd', 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="id_cd" id="id_cd" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat_cd" name="capnhat_cd" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function add_kct(){
        $('#id_kct').val(0);
        $('#kct-modal').modal('show');
    }

    function add_dbhdnd(){
        $('#id_dbhdnd').val(0);
        $('#dbhdnd-modal').modal('show');
    }

    function add_qs(){
        $('#id_qs').val(0);
        $('#qs-modal').modal('show');
    }

    function add_cuv(){
        $('#id_cuv').val(0);
        $('#cuv-modal').modal('show');
    }

    function add_cd(){
        $('#id_cd').val(0);
        $('#cd-modal').modal('show');
    }

    function add_mc(){
        $('#id_kn').val(0);
        $('#mc-modal').modal('show');
    }

    function deleteRow(id){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{$furl_kn}}'+'delete_kn',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                mahs: $('#mahs').val()
            },
            dataType: 'JSON',
            success: function (data) {
                toastr.success("Bạn đã xóa thông tin thành công!", "Thành công!");
                $('#dskn').replaceWith(data.message);
                jQuery(document).ready(function() {
                    //TableManaged.init().initTable4();
                });
                $('#kiemnhiem-modal').modal('hide');
            }
        })

    }

    function edit_kn(id,phanloai) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{$furl_kn}}'+'getinfor_kn',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                mahs: $('#mahs').val()
            },
            dataType: 'JSON',
            success: function (data) {
                //chia theo phân loại
                $('#id_kn').val(data.id);
                $('#hesopc_kn').val(data.hesopc);
                $('#phanloai_kn').val(data.phanloai).trigger('change');
                $('#mapb_kn').val(data.mapb).trigger('change');
                $('#pctn_kn').val(data.pctn);
                $('#pckn_kn').val(data.pckn);
                $('#macvcq_kn').val(data.macvcq).trigger('change');
                $('#pcthni_kn').val(data.pcthni);
                $('#pcdbn_kn').val(data.pcdbn);
                $('#pck_kn').val(data.pck);
            }
        })
    }

    jQuery(document).ready(function($) {
        $('button[name="capnhat_kct"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl_kn}}'+'store_kct',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mact : $('#mact_kct').val(),
                    mapb : $('#mapb_kct').val(),
                    id: $('#id_kct').val(),
                    hesopc: $('#hesopc_kct').val(),
                    phanloai: $('#phanloai_kct').val(),
                    pckn: $('#pckn_kct').val(),
                    pclt: $('#pclt_kct').val(),
                    pckct: $('#pckct_kct').val(),
                    pcthni:$('#pcthni_kct').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#kct-modal').modal('hide');
                    }
                    else {
                        toastr.error("Bạn cần kiểm tra lại thông tin vừa nhập!", "Lỗi!");
                    }
                }
            })
        });

        $('button[name="capnhat_dbhdnd"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl_kn}}'+'store_dbhdnd',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mact : $('#mact_dbhdnd').val(),
                    mapb : $('#mapb_dbhdnd').val(),
                    id: $('#id_dbhdnd').val(),
                    hesopc: $('#hesopc_dbhdnd').val(),
                    phanloai: $('#phanloai_dbhdnd').val(),
                    pckn: $('#pckn_dbhdnd').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#dbhdnd-modal').modal('hide');
                    }
                    else {
                        toastr.error("Bạn cần kiểm tra lại thông tin vừa nhập!", "Lỗi!");
                    }
                }
            })
        });

        $('button[name="capnhat_qs"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_kn}}'+'store_qs',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mact : $('#mact_qs').val(),
                    mapb : $('#mapb_qs').val(),
                    id: $('#id_qs').val(),
                    hesopc: $('#hesopc_qs').val(),
                    phanloai: $('#phanloai_qs').val(),
                    pctn: $('#pctn_qs').val(),
                    pcdbn: $('#pcdbn_qs').val(),
                    pcthni: $('#pcthni_qs').val(),
                    pck: $('#pck_qs').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#qs-modal').modal('hide');
                    }
                    else {
                        toastr.error("Bạn cần kiểm tra lại thông tin vừa nhập!", "Lỗi!");
                    }
                }
            })
        });

        $('button[name="capnhat_cuv"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl_kn}}'+'store_cuv',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mact : $('#mact_cuv').val(),
                    mapb : $('#mapb_cuv').val(),
                    id: $('#id_cuv').val(),
                    hesopc: $('#hesopc_cuv').val(),
                    phanloai: $('#phanloai_cuv').val(),
                    pckn: $('#pckn_cuv').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#cuv-modal').modal('hide');
                    }
                    else {
                        toastr.error("Bạn cần kiểm tra lại thông tin vừa nhập!", "Lỗi!");
                    }
                }
            })
        });

        $('button[name="capnhat_cd"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl_kn}}'+'store_cd',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mact : $('#mact_cd').val(),
                    mapb : $('#mapb_cd').val(),
                    id: $('#id_cd').val(),
                    phanloai: $('#phanloai_cd').val(),
                    pckn: $('#pckn_cd').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#cd-modal').modal('hide');
                    }
                    else {
                        toastr.error("Bạn cần kiểm tra lại thông tin vừa nhập!", "Lỗi!");
                    }
                }
            })
        });

    }(jQuery));
</script>
<!--end form5  -->