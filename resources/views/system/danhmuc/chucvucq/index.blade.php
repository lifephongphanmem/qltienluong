<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
        ?>
@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
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
                        <b>DANH MỤC CHỨC VỤ CHÍNH QUYỀN</b>
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="addCV()"><i class="fa fa-plus"></i>&nbsp;Thêm mới chức vụ</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Phân loại đơn vị</label>
                            <div class="col-md-5">
                                {!! Form::select('mapl',$model_pl,$mapl,array('id' => 'mapl', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên chức vụ</th>
                                <th class="text-center">Tên viết tắt<br>(trên bảng lương)</th>
                                <th class="text-center">Mô tả chức vụ</th>
                                <th class="text-center" style="width: 10%">Sắp xếp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td name="tencv">{{$value->tencv}}</td>
                                        <td name="tenvt">{{$value->tenvt}}</td>
                                        <td name="ghichu">{{$value->ghichu}}</td>
                                        <td class="text-center" name="sapxep">{{$value->sapxep}}</td>
                                        <td>
                                            @if(session('admin')->level == 'SA' || session('admin')->level == 'SSA' || session('admin')->madv == $value->madv)
                                                <button type="button" onclick="editCV('{{$value->macvcq}}')" class="btn btn-info btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>

                                                <button type="button" onclick="cfDel('/danh_muc/chuc_vu_cq/del/{{$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
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

    <!--Modal thông tin chức vụ -->
    <div id="chucvu-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin chức vụ chính quyền</h4>
                </div>
                <div class="modal-body">

                    <label class="control-label">Phân loại đơn vị</label>
                    {!! Form::select('maphanloai',$model_pl,$mapl,array('id' => 'maphanloai', 'class' => 'form-control', 'readonly'))!!}

                    <label class="form-control-label">Tên chức vụ<span class="require">*</span></label>
                    {!!Form::text('tencv', null, array('id' => 'tencv','class' => 'form-control','required'=>'required'))!!}

                    <label class="form-control-label">Tên chức vụ viết tắt</label>
                    {!!Form::text('tenvt', null, array('id' => 'tenvt','class' => 'form-control'))!!}

                    <label class="form-control-label">Mô tả chức vụ</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}

                    <label class="form-control-label">Sắp xếp</label>
                    {!!Form::text('sapxep', null, array('id' => 'sapxep','class' => 'form-control'))!!}

                    <input type="hidden" id="macvcq" name="macvcq"/>
                    <input type="hidden" id="id_cv" name="id_cv"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfCV()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#mapl').change(function() {
                window.location.href = '/danh_muc/chuc_vu_cq/ma_so='+$('#mapl').val();
            });
        })

        function addCV(){
            //var date=new Date();
            $('#tencv').val('');
            $('#tenvt').val('');
            $('#ghichu').val('');
            $('#sapxep').attr('value','99');
            $('#macvcq').val('');
            $('#id_cv').val(0);
            $('#chucvu-modal').modal('show');
        }

        function editCV(macvcq){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macvcq: macvcq
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#tencv').val(data.tencv);
                    $('#tenvt').val(data.tenvt);
                    $('#ghichu').val(data.ghichu);
                    $('#sapxep').val(data.sapxep);
                    $('#maphanloai').val(data.maphanloai);
                    $('#macvcq').val(macvcq);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#chucvu-modal').modal('show');
        }

        function cfCV(){
            var valid=true;
            var message='';
            var macvcq=$('#macvcq').val();
            var tencv=$('#tencv').val();
            var ghichu=$('#ghichu').val();
            var sapxep=$('#sapxep').val();
            var id=$('#id_cv').val();

            if(tencv==''){
                valid=false;
                message +='Tên chức vụ không được bỏ trống \n';
            }
            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(macvcq==''){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            maphanloai: $('#mapl').val(),
                            tencv: tencv,
                            tenvt: $('#tenvt').val(),
                            ghichu: ghichu,
                            sapxep: sapxep
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            macvcq: macvcq,
                            tenvt: $('#tenvt').val(),
                            tencv: tencv,
                            ghichu: ghichu,
                            sapxep: sapxep
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message,'Lỗi!!');
                        }
                    });
                }
                $('#phongban-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!.');
            }
        }
    </script>

    @include('includes.modal.delete')
@stop