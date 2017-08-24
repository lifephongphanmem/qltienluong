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
                        DANH MỤC KHU VỰC, ĐỊA BÀN QUẢN LÝ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="addPB()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Cấp độ quản lý </label>
                            <div class="col-md-5">
                                {!! Form::select('mucbaomat',$a_baomat,$level,array('id' => 'mucbaomat', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên khu vực, địa bàn</th>
                                <th class="text-center">Đơn vị quản lý</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tendvbc}}</td>
                                        <td>{{$value->tendv}}</td>
                                        <td>
                                            <a href="{{url('/danh_muc/khu_vuc/ma_so='.$value->madvbc.'/list_unit')}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-list-alt"></i>&nbsp; Danh sách đơn vị</a>
                                            <button type="button" onclick="edit('{{$value->madvbc}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <button type="button" onclick="unit_manage('{{$value->madvbc}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-sitemap"></i>&nbsp; Đơn vị quản lý</button>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->madvbc}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal thêm mới -->
    <div id="phongban-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin khu vực, địa bàn quản lý</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Tên khu vực, địa bàn quản lý<span class="require">*</span></label>
                    {!!Form::text('tendvbc', null, array('id' => 'tendvbc','class' => 'form-control'))!!}

                    <label class="form-control-label">Cấp độ quản lý</label>
                    {!! Form::select('level',$a_baomat,$level,array('id' => 'level', 'class' => 'form-control'))!!}

                    <label class="form-control-label">Ghi chú</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}

                    <input type="hidden" id="madvbc" name="madvbc"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal đơn vị chủ quản -->
    <div id="chuquan-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin đơn vị quản lý địa bàn</h4>
                </div>
                <div class="modal-body" id="donvichuquan">
                    <label class="form-control-label">Chọn đơn vị quản lý</label>
                    <select class="form-control select2me" name="madvcq_cq" id="madvcq_cq"></select>
                </div>
                <input type="hidden" id="madvbc_cq" name="madvbc_cq"/>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfdvcq()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(function(){
            $('#mucbaomat').change(function() {
                window.location.href = '/danh_muc/khu_vuc/ma_so='+$('#mucbaomat').val();
            });
        })

        function addPB(){
            $('#tendvbc').val('');
            $('#ghichu').val('');
            $('#madvbc').val('');
            $('#phongban-modal').modal('show');
        }

        function edit(madvbc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    madvbc: madvbc
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#tendvbc').val(data.tendvbc);
                    $('#level').val(data.level);
                    $('#ghichu').val(data.ghichu);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#madvbc').val(madvbc);
            $('#phongban-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var message='';
            var madvbc=$('#madvbc').val();
            var tendvbc=$('#tendvbc').val();
            var ghichu=$('#ghichu').val();

            if(tendvbc ==''){
                valid=false;
                message +='Tên khu vực, địa bàn không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(madvbc ==''){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            tendvbc: tendvbc,
                            level: $('#level').val(),
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            alert(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            tendvbc: tendvbc,
                            level: $('#level').val(),
                            ghichu: ghichu,
                            madvbc: madvbc
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
                toastr.error(message,'Lỗi!!');
            }

            return valid;
        }

        function unit_manage(madvbc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_list_unit',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    madvbc: madvbc
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status=='success'){
                        $('#donvichuquan').replaceWith(data.message);
                    }else{
                        toastr.error('Khu vực, địa bàn này chưa có đơn vị nào.','Lỗi!');
                        $('#donvichuquan').replaceWith(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#madvbc_cq').val(madvbc);
            $('#chuquan-modal').modal('show');
        }

        function cfdvcq(){
            var madvbc=$('#madvbc_cq').val();
            var madvcq=$('#madvcq_cq').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //Cập nhật
                $.ajax({
                    url: '{{$furl}}' + 'set_management',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        madvcq: madvcq,
                        madvbc: madvbc
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
            $('#chuquan-modal').modal('hide');
        }
    </script>

    @include('includes.modal.delete')
@stop