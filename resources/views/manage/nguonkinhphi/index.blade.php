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
    @include('includes.script.scripts')
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
                        <i class="fa fa-list-alt"></i>DANH SÁCH NGUỒN KINH PHÍ CỦA ĐƠN VỊ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Năm ngân</br>sách</th>
                                @if(session('admin')->maphanloai != 'KVXP')
                                    <th class="text-center">Lĩnh vực hoạt động</th>
                                @endif
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Nhu cầu</br>kinh phí</th>
                                <th class="text-center">Kinh phí</br>thực hiện</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr class="{{getTextStatus($value->trangthai)}}">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->namns}}</td>
                                        @if(session('admin')->maphanloai != 'KVXP')
                                            <td>{{$value->linhvuc}}</td>
                                        @endif
                                        <td>{{$value->noidung}}</td>
                                        <td class="text-right">{{number_format($value->nhucau)}}</td>
                                        <td class="text-right">{{number_format($value->nguonkp)}}</td>
                                        <td>{{$a_trangthai[$value->trangthai]}}</td>
                                        <td>
                                            @if($value->trangthai != 'DAGUI')
                                                <a href="{{url($furl.'ma_so='.$value->masodv)}}" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</a>
                                                <button type="button" class="btn btn-default btn-xs mbs" onclick="confirmChuyen('{{$value['masodv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                    Gửi dữ liệu</button>
                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                            <a href="{{url($furl.'ma_so='.$value->masodv.'/in')}}" target="_blank" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-print"></i>&nbsp; In số liệu</a>
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
    {!! Form::open(['url'=>$furl.'create','method'=>'POST', 'id' => 'create_dutoan', 'class'=>'horizontal-form']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin nguồn kinh phí</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" {{session('admin')->maphanloai != 'KVXP'?'':'style=display:none'}}>
                            <label class="control-label">Lĩnh vực hoạt động</label>
                            {!!Form::select('linhvuchoatdong',getLinhVucHoatDong(false), null, array('id' => 'linhvuchoatdong','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Căn cứ thông tư, quyết định</label>
                            {!!Form::select('sohieu',getThongTuQD(false), null, array('id' => 'sohieu','class' => 'form-control'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Nội dung</label>
                            {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="confirm_create()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'senddata','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi gửi.</b></label>
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>

                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <script>
        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        function add(){
            $('#create-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')
@stop