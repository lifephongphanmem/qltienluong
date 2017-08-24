<!--form1 thông tin cơ bản -->
<div id="tab4" class="tab-pane" >
    <div class="form-horizontal">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label" for="plnb">Mã ngạch <span class="require">*</span></label>

                    <div class="col-sm-6 controls">
                        {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','autofocus'=>'autofocus','readonly'=>'true'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ngạch bậc </label>
                    <div class="col-sm-9">
                        <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                            @foreach($m_plnb as $plnb)
                                <optgroup label="{{$plnb->plnb}}">
                                    <?php
                                    $mode_ct=$m_pln->where('plnb',$plnb->plnb);
                                    ?>
                                    @foreach($mode_ct as $ct)
                                        <option value="{{$ct->msngbac}}">{{$ct->tennb}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Bậc lương </label>

                    <div class="col-sm-6 controls">
                        <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                            @foreach($m_bac as $nb)
                                <option value="{{$nb->bac}}">{{$nb->bac}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Hệ số lương </label>

                    <div class="col-sm-6 controls">
                        {!!Form::text('heso', 0, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Hệ số vượt khung </label>

                    <div class="col-sm-6 controls">
                        {!!Form::text('vuotkhung', 0, array('id' => 'vuotkhung','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Phần trăm hưởng </label>

                    <div class="col-sm-6 controls">
                        {!!Form::text('pthuong', '100', array('id' => 'pthuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Từ ngày <span class="require">*</span></label>

                    <div class="col-sm-6 controls">
                        {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control'))!!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-6 control-label">Đến ngày <span class="require">*</span></label>

                    <div class="col-sm-6 controls">
                        {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control'))!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-left: 0px">
            <div class="col-md-12" style="margin-bottom: 5px; text-align: right">
                <button type="button" data-target="#modal-create" data-toggle="modal" class="btn btn-default" onclick="clearForm()" style="border-color: #0088FF"><i class="fa fa-plus"></i>&nbsp;Thêm mới phụ cấp</button>
                &nbsp;
            </div>

            <div class="col-md-12" id="thongtinphucap">
                <table id="sample_3" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Từ ngày</th>
                            <th class="text-center">Đến ngày</th>
                            <th class="text-center">Tên phụ cấp</th>
                            <th class="text-center">Hệ số</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $stt=1; ?>
                    @if(isset($model_phucap))
                        @foreach($model_phucap as $key=>$value)
                            <tr>
                                <td class="text-center">{{$stt++}}</td>
                                <td>{{getDayVn($value->ngaytu)}}</td>
                                <td>{{getDayVn($value->ngayden)}}</td>
                                <td>{{$value->tenpc}}</td>
                                <td>{{$value->hesopc}}</td>
                                <td>
                                    <button type="button" onclick="edit_phucap({{$value->id}})" class="btn btn-info btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp;Chỉnh sửa</button>
                                    <button type="button" onclick="del_phucap('{{$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#modal-delete" data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
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
@include('includes.script.func_msnb')

<!--Modal thông tin chi tiết -->
<div id="modal-create" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">THÔNG TIN PHỤ CẤP</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-4 control-label"> Từ ngày<span class="require">*</span></label>
                    <div class="col-md-8">
                        <input type="date" name="ngaytu_pc" id="ngaytu_pc" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label"> Đến ngày</label>
                    <div class="col-md-8">
                        <input type="date" name="ngayden_pc" id="ngayden_pc" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label"> Phụ cấp</label>
                    <div class="col-md-8">
                        <select name="mapc" id="mapc" class="form-control" onchange="getphucap()">
                            <option value="">--Chọn phụ cấp--</option>
                            @if(isset($m_pc))
                                @foreach($m_pc as $pc)
                                    <option data-number="{{$pc['hesopc']}}" value="{{$pc['mapc']}}">{{$pc['tenpc']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label"> Hệ số phụ cấp</label>
                    <div class="col-md-8">
                        {!!Form::text('hesopc', null, array('id' => 'hesopc','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label"> Nộp bảo hiểm</label>
                    <div class="col-md-8">
                        <select name="baohiem" id="baohiem" class="form-control">
                            <option value="1">Có nộp bảo hiểm</option>
                            <option value="0">Không nộp bảo hiểm</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="button" class="btn btn-primary" onclick="confirm()">Đồng ý</button>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Đồng ý xóa thông tin phụ cấp?</h4>
            </div>
            <input type="hidden" id="id_del_pc" name="id_del_pc">
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                <button type="button" class="btn btn-primary" onclick="deleteRow()">Đồng ý</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
    function getphucap(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/ajax/phucap/',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                mapc: $('#mapc').val()
            },
            dataType: 'JSON',
            success: function (data) {
                if(data.status == 'success'){
                    $('#hesopc').val(data.hesopc);
                    $('#baohiem').val(data.baohiem);
                }
            }
        });
    }

    function getHS(){
        if($('#bac').val() != ''){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/ajax/heso/',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    msngbac: $('#tennb').val(),
                    bac: $('#bac').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status == 'success'){
                        var heso = data.message.split(';');
                        $('#heso').val(heso[0]);
                        $('#vuotkhung').val(heso[1]);
                    }else{
                        toastr.error('Không tìm thấy hệ số lương cho bậc lương này.','Lỗi!')
                        $('#heso').val(0);
                        $('#vuotkhung').val(0);
                    }
                }
            });
        } else {
            $('#heso').val(0);
            $('#vuotkhung').val(0);
        }
    }

    function del_phucap(id){
        document.getElementById("id_del_pc").value=id;
    }

    function clearForm(){
        $('#ngaytu_pc').val('');
        $('#ngayden_pc').val('');
        $('#mapc').val('');
        $('#hesopc').val('');
        //$('#chitiet-modal').modal('show');
    }

    function edit_phucap(id){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/nghiep_vu/ho_so/get_phucap',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                $('#ngaytu_pc').val(data.ngaytu);
                $('#ngayden_pc').val(data.ngayden);
                $('#mapc').val(data.mapc);
                $('#hesopc').val(data.hesopc);
                $('#baohiem').val(data.baohiem);
            },
            error: function(message){
                toastr.error(message,'Lỗi!');
            }
        });

        $('#modal-create').modal('show');
    }

    function confirm(){
        var valid=true;
        var message='';

        var ngaytu=$('#ngaytu_pc').val();
        var ngayden=$('#ngayden_pc').val();
        var mapc=$('#mapc').val();
        var hesopc=$('#hesopc').val();

        if(ngaytu=='' || ngayden==''){
            valid=false;
            message +='Thời gian không được bỏ trống \n';
        }
        if(mapc==''){
            valid=false;
            message +='Tên phụ cấp không được bỏ trống \n';
        }
        if(valid){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/nghiep_vu/ho_so/phucap',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        macanbo: $('#macanbo').val(),
                        ngaytu: ngaytu,
                        ngayden: ngayden,
                        mapc: mapc,
                        hesopc: hesopc,
                        baohiem:$('#baohiem').val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 'success') {
                            $('#thongtinphucap').replaceWith(data.message);
                            jQuery(document).ready(function() {
                                TableManaged.init();
                            });
                        }
                    },
                    error: function(message){
                        toastr.error(message);
                    }
                });
            $('#modal-create').modal('hide');
        }else{
            toastr.error(message);
        }
        return valid;
    }

    function deleteRow(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/nghiep_vu/ho_so/del_phucap',
            type: 'GET',
            data: {
                _token: CSRF_TOKEN,
                id: $('#id_del_pc').val(),
                macanbo: $('#macanbo').val()
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.status == 'success') {
                    $('#thongtinphucap').replaceWith(data.message);
                    jQuery(document).ready(function() {
                        TableManaged.init();
                    });
                }
            },
            error: function(message){
                toastr.error(message,'Lỗi!');
            }
        });
        $('#modal-delete').modal('hide');
    }

</script>

<!--end form4 -->