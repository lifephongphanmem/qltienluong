<!--form1 thông tin cơ bản -->
<div id="tab5" class="tab-pane" >
    <div class="form-horizontal">
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <button type="button" class="btn btn-default" onclick="add_kn()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
            </div>
        </div>
            </br>
        <div class="row" id="dskn">
            <div class="col-md-12">
                <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">STT</th>
                        <th class="text-center">Phân loại</th>
                        <th class="text-center">Chức vụ</br>kiêm nhiệm</th>
                        <th class="text-center">Hệ số</br>phụ cấp</th>
                        <th class="text-center">Phụ cấp</br>trách nhiệm</th>
                        <th class="text-center">Phụ cấp</br>kiêm nhiệm</th>
                        <th class="text-center">Phụ cấp</br>đặc thù</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                @if(isset($model_kn))
                    @foreach($model_kn as $key=>$value)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="text-center">{{$value->tenphanloai}}</td>
                            <td class="text-center">{{$value->tenchucvu}}</td>
                            <td class="text-right">{{$value->hesopc}}</td>
                            <td class="text-right">{{$value->pctn}}</td>
                            <td class="text-right">{{$value->pckn}}</td>
                            <td class="text-right">{{$value->pcdbn}}</td>
                            <td>
                                <button type="button" data-target="#kiemnhiem-modal" data-toggle="modal" class="btn btn-default btn-xs mbs" onclick="edit_kn({{$value->id}});"><i class="fa fa-edit"></i>&nbsp;Chỉnh sửa</button>
                                <button type="button" class="btn btn-default btn-xs mbs" onclick="deleteRow({{$value->id}})" ><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="kiemnhiem-modal" tabindex="-1" aria-labelledby="myModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin chức vụ kiêm nhiệm</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phân loại kiêm nhiệm<span class="require">*</span></label>
                            <div>
                                {!!Form::select('phanloai_kn',getPhanLoaiKiemNhiem(), null, array('id' => 'phanloai_kn','class' => 'form-control select2me'))!!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Chức vụ kiêm nhiệm</label>
                            {!!Form::select('macvcq_kn',getChucVuCQ(false), null, array('id' => 'macvcq_kn','class' => 'form-control select2me'))!!}
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Khối/Tổ công tác</label>
                            {!!Form::select('mapb_kn',getPhongBan(), null, array('id' => 'mapb_kn','class' => 'form-control select2me'))!!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Hệ số phụ cấp</label>
                            {!!Form::text('hesopc_kn', null, array('id' => 'hesopc_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phụ cấp trách nhiệm</label>
                            {!!Form::text('pctn_kn', null, array('id' => 'pctn_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phụ cấp kiêm nhiệm</label>
                            {!!Form::text('pckn_kn', null, array('id' => 'pckn_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phụ cấp thâm niên</label>
                            {!!Form::text('pcthni_kn', null, array('id' => 'pcthni_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phụ cấp đặc thù</label>
                            {!!Form::text('pcdbn_kn', null, array('id' => 'pcdbn_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phụ cấp khác</label>
                            {!!Form::text('pck_kn', null, array('id' => 'pck_kn','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id_kn" id="id_kn" />
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" id="capnhat" name="capnhat" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function add_kn(){
        $('#id_kn').val(0);
        $('#kiemnhiem-modal').modal('show');
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

    function edit_kn(id) {
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
        $('button[name="capnhat"]').click(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{$furl_kn}}'+'store_kiemnhiem',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo : $('#macanbo').val(),
                    mapb : $('#mapb_kn').val(),
                    id_kn: $('#id_kn').val(),
                    hesopc: $('#hesopc_kn').val(),
                    phanloai: $('#phanloai_kn').val(),
                    pctn: $('#pctn_kn').val(),
                    pckn: $('#pckn_kn').val(),
                    macvcq: $('#macvcq_kn').val(),
                    pcthni: $('#pcthni_kn').val(),
                    pcdbn: $('#pcdbn_kn').val(),
                    pck: $('#pck_kn').val()

                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success') {
                        toastr.success("Cập nhật thông tin thành công", "Thành công!");
                        $('#dskn').replaceWith(data.message);
                        $('#kiemnhiem-modal').modal('hide');
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