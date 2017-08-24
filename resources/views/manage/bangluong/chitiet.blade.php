@extends('main')

@section('custom-style')
    <link href="{{url('assets/global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script><script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>


@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        THÔNG TIN CHI TIẾT LƯƠNG CỦA CÁN BỘ {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect/'.$model->id, 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate', 'enctype'=>'multipart/form-data']) !!}
                        <input name="mabl" id="mabl" type="hidden" value="{{$model->mabl}}" />
                        <input name="macanbo" id="macanbo" type="hidden" value="{{$model->macanbo}}" />
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Mã ngạch </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Nhóm ngạch bậc </label>

                                        <div class="col-sm-6">
                                            {!!Form::text('plnb', null, array('id' => 'plnb','class' => 'form-control','readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Tên ngạch bậc </label>
                                        <div class="col-sm-6">
                                            {!!Form::text('tennb', null, array('id' => 'tennb','class' => 'form-control','readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Hệ số lương </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Hệ số vượt khung </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Tổng hệ số </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('tonghs', null, array('id' => 'tonghs','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Tổng tiền lương </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('ttl', null, array('id' => 'ttl','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Giảm trừ lương </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('giaml', null, array('id' => 'giaml','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Bảo hiểm chi trả </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('bhct', null, array('id' => 'bhct','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Số tiền BHXH </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhxh', null, array('id' => 'stbhxh','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Số tiền BHYT </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhyt', null, array('id' => 'stbhyt','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Số tiền KPCĐ </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stkpcd', null, array('id' => 'stkpcd','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Số tiền BHTN </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhtn', null, array('id' => 'stbhtn','class' => 'form-control tienluong', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Tổng tiền bảo hiểm </label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('ttbh', null, array('id' => 'ttbh','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">BHXH đơn vị nộp</label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhxh_dv', null, array('id' => 'stbhxh_dv','class' => 'form-control baohiem_dv', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">BHYT đơn vị nộp</label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhyt_dv', null, array('id' => 'stbhyt_dv','class' => 'form-control baohiem_dv', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">KPCĐ đơn vị nộp</label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stkpcd_dv', null, array('id' => 'stkpcd_dv','class' => 'form-control baohiem_dv', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">BHTN đơn vị nộp</label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('stbhtn_dv', null, array('id' => 'stbhtn_dv','class' => 'form-control baohiem_dv', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Tổng tiền BH đơn vị nộp</label>

                                        <div class="col-sm-6 controls">
                                            {!!Form::text('ttbh_dv', null, array('id' => 'ttbh_dv','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><b>Lương thực nhận </b></label>

                                        <div class="col-sm-10 controls">
                                            {!!Form::text('luongtn', null, array('id' => 'luongtn','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-left: 0px">
                                <div class="col-md-12">
                                    <button type="button" data-target="#modal-create" data-toggle="modal" class="btn btn-success btn-xs" onclick="clearForm()"><i class="fa fa-plus"></i>&nbsp;Thêm mới phụ cấp</button>
                                    &nbsp;
                                </div>
                                <div class="col-md-12" id="thongtinphucap">
                                    <table id="sample_3" class="table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">STT</th>
                                            <th class="text-center">Mã số</th>
                                            <th class="text-center">Tên phụ cấp</th>
                                            <th class="text-center">Hệ số</th>
                                            <th class="text-center">Nộp bảo hiểm</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $stt=1; ?>
                                        @if(isset($model_phucap))
                                            @foreach($model_phucap as $key=>$value)
                                                <tr>
                                                    <td class="text-center">{{$stt++}}</td>
                                                    <td>{{$value->mapc}}</td>
                                                    <td>{{$value->tenpc}}</td>
                                                    <td>{{$value->hesopc}}</td>
                                                    <td>{{$value->baohiem==1?'Có nộp bảo hiểm':'Không nộp bảo hiểm'}}</td>
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
                        <div style="text-align: center; border-top: 1px solid #eee;">
                            <button type="submit" class="btn btn-success">Tính lại lương<i class="fa fa-save mlx"></i></button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Lưu hồ sơ</button>
                            <a href="{{url('/nghiep_vu/ho_so/danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>

    <div id="modal-create" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">THÔNG TIN PHỤ CẤP</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
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

        function del_phucap(id){
            document.getElementById("id_del_pc").value=id;
        }

        function clearForm(){
            $('#mapc').val('');
            $('#hesopc').val('');
        }

        function edit_phucap(id){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}'+'get_phucap',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
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
            var mapc=$('#mapc').val();
            var hesopc=$('#hesopc').val();

            if(mapc==''){
                valid=false;
                message +='Tên phụ cấp không được bỏ trống \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{$furl}}'+'phucap',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        macanbo: $('#macanbo').val(),
                        mabl: $('#mabl').val(),
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
                url: '{{$furl}}'+'del_phucap',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: $('#id_del_pc').val(),
                    macanbo: $('#macanbo').val(),
                    mabl: $('#mabl').val()
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
    @include('includes.script.scripts')
@stop