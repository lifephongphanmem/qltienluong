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
                        <b>DANH SÁCH CÁN BỘ ĐÃ THÔI CÔNG TÁC</b>
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Ngày nghỉ</th>
                                <th class="text-center">Lý do nghỉ</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td>{{$value->phanloai}}</td>
                                        <td>{{getDayVn($value->ngaynghi)}}</td>
                                        <td>{{$value->lydo}}</td>
                                        <td>
                                            <button type="button" onclick="editCV('{{$value->maso}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin thêm mới -->
    {!! Form::open(['url'=>$furl.'store','method'=>'post', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin cán bộ đã thôi công tác</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Họ và tên cán bộ</label>
                    {!!Form::select('macanbo',$a_canbo, null, array('id' => 'macanbo','class' => 'form-control select2me'))!!}

                    <label class="form-control-label">Phân loại</label>
                    {!!Form::select('maphanloai',getPhanLoaiThoiCongTac(), null, array('id' => 'maphanloai','class' => 'form-control'))!!}

                    <label class="form-control-label">Ngày nghỉ</label>
                    <input type="date" name="ngaynghi" id="ngaynghi" class="form-control" />

                    <label class="control-label"> Số quyết định</label>
                    {!!Form::text('soqd', null, array('id' => 'soqd','class' => 'form-control'))!!}

                    <label class="control-label"> Ngày quyết định</label>
                    <input type="date" name="ngayqd" id="ngayqd" class="form-control" />

                    <label class="control-label"> Người ký</label>
                    {!!Form::text('nguoiky', null, array('id' => 'nguoiky','class' => 'form-control'))!!}

                    <label class="form-control-label">Lý do nghỉ</label>
                    {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}

                    <input type="hidden" id="maso" name="maso"/>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function add(){
            $('#maso').val('ADD');
            $('#create-modal').modal('show');
        }

        function editCV(maso){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    maso: maso
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#macanbo').val(data.macanbo);
                    $('#maphanloai').val(data.maphanloai);
                    $('#ngaynghi').val(data.ngaynghi);
                    $('#soqd').val(data.soqd);
                    $('#ngayqd').val(data.ngayqd);
                    $('#lydo').val(data.lydo);
                    $('#nguoiky').val(data.nguoiky);
                    $('#coquanqd').val(data.coquanqd);
                    $('#maso').val(data.maso);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');
        }

        $(function(){
            $('#create :submit').click(function(){
                var ok = true, str_message='';

                if($('#ngaytu').val()=='' || $('#ngayden').val()==''){
                    ok = false;
                    str_message += 'Thời gian tạm ngừng theo dõi không được bỏ trống \n'
                }

                if ( ok == false){
                    alert(str_message);
                    $("form").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("form").unbind('submit').submit();
                }
            });
        });
    </script>

    @include('includes.modal.delete')
@stop