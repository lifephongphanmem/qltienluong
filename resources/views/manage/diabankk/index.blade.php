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
                        <b>DANH SÁCH THÔN, TỔ DÂN PHỐ</b>
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới phân loại</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên địa bàn</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Ngày công nhận</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tendiaban}}</td>
                                        <td>{{$value->phanloai}}</td>
                                        <td>{{getDayVn($value->ngaytu)}}</td>
                                        <td>
                                            <a href="{{url($furl.'ma_so='.$value->madiaban)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-list"></i>&nbsp; Danh sách cán bộ</a>
                                            <button type="button" onclick="editCV('{{$value->madiaban}}')" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <button type="button" onclick="cfDel('{{$furl.'/del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin chức vụ -->
    {!! Form::open(['url'=>'/nghiep_vu/quan_ly/dia_ban_dbkk/store','method'=>'post', 'id' => 'create_diaban']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin địa bàn đặc biệt khó khăn</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Mã địa bàn</label>
                    {!!Form::text('madiaban', null, array('id' => 'madiaban','class' => 'form-control'))!!}

                    <label class="form-control-label">Tên địa bàn<span class="require">*</span></label>
                    {!!Form::text('tendiaban', null, array('id' => 'tendiaban','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}

                    <label class="form-control-label">Phân loại</label>
                    {!!Form::select('phanloai',array(''=>'--Chọn phận loại--','DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                        'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự') ,null, array('id' => 'phanloai','class' => 'form-control'))!!}

                    <label class="form-control-label">Ngày công nhận</label>
                    <input type="date" name="ngaytu" id="ngaytu" class="form-control" />

                    <label class="form-control-label">Ngày ra khỏi</label>
                    <input type="date" name="ngayden" id="ngayden" class="form-control" />

                    <label class="form-control-label">Ghi chú</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}

                    <input type="hidden" id="id" name="id"/>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function add(){
            $('#madiaban').val('');
            $('#tendiaban').val('');
            $('#phanloai').val('');
            $('#ngaytu').val('');
            $('#ngayden').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
        }

        function editCV(madiaban){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    madiaban: madiaban
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#madiaban').val(data.madiaban);
                    $('#tendiaban').val(data.tendiaban);
                    $('#phanloai').val(data.phanloai);
                    $('#ngaytu').val(data.ngaytu);
                    $('#ngayden').val(data.ngayden);
                    $('#ghichu').val(data.ghichu);
                    $('#id').val(data.id);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');
        }

        $(function(){
            $('#create_diaban :submit').click(function(){
                var ok = true, str_message='';

                if($('#tendiaban').val()==''){
                    ok = false;
                    str_message += 'Tên địa bàn không được bỏ trống \n'
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