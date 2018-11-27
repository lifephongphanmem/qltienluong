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
                        <i class="fa fa-list-alt"></i>DANH SÁCH HỒ SƠ LUÂN CHUYỂN, ĐIỀU ĐỘNG CÁN BỘ
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới                    </button>
                </div>
            </div>
            <div class="portlet-body form-horizontal">

                <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">STT</th>
                        <th class="text-center">Cán bộ</th>
                        <th class="text-center">Phân loại</th>
                        <th class="text-center">Ngày điều động,</br>luân chuyển</th>
                        <th class="text-center">Tên đơn vị</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td>{{$value->tenphanloai}}</td>
                                        <td class="text-center">{{getDayVn($value->ngaylc)}}</td>
                                        <td>{{($value->donvi == 'DONVI' ?'Đơn vị nhận: ':'Từ đơn vị: ').$value->tendv_dd}}</td>
                                        <td>{{$value->tentrangthai}}</td>
                                        <td>
                                            @if($value->donvi == 'DONVI')
                                                <!-- Kiểm tra trạng thái trc khi xóa -->
                                                @if($value->trangthai != 'DACHUYEN')
                                                    <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</a>
                                                    <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                                @else
                                                    <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                                @endif
                                            @else
                                                @if($value->trangthai != 'DACHUYEN')
                                                    <a href="{{url($furl.'accept?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-edit"></i>&nbsp; Nhận cán bộ</a>
                                                    <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-trash-o"></i>&nbsp; Trả lại</button>
                                                @else
                                                    <a href="{{url($furl.'accept?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                        <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                                @endif
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

    <!--Modal thêm mới thông tin -->
    {!! Form::open(['url'=>$furl.'create','method'=>'get', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin của cán bộ</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Họ và tên cán bộ</label>
                    <select name="macanbo" id="macanbo" class="form-control select2me">
                        @foreach($a_canbo as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                    <input type="hidden" id="id" name="id" />
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
            $('#id').val('ADD');
            $('#create-modal').modal('show');
        }
    </script>

    <script>
        function edit(id){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}'+'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#ngaylc').val(data.ngaylc);
                        $('#donvi').val(data.madv);
                        $('#macvcq').val(data.macvcq);
                        $('#soqd').val(data.soqd);
                        $('#ngayqd').val(data.ngayqd);
                        $('#nguoiky').val(data.nguoiky);
                        $('#id_ct').val(data.id);
                        $('#chitiet-modal').modal('show');
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
        }

        function confirm(){
            var valid=true;
            var message='';

            var macanbo = $('#cbmacb').val();

            var ngaylc=$('#ngaylc').val();
            var donvi=$('#donvi').val();
            var chucvu=$('#macvcq').val();
            var soqd=$('#soqd').val();
            var ngayqd=$('#ngayqd').val();
            var nguoiky=$('#nguoiky').val();

            var id=$('#id_ct').val();

            if(ngaylc==''){
                valid=false;
                message +='Ngày điều động không được bỏ trống \n';
            }

            if(donvi==''){
                valid=false;
                message +='Đơn vị mới không được bỏ trống \n';
            }

            if(chucvu==''){
                valid=false;
                message +='Chức vụ mới không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}'+'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            macanbo: macanbo,
                            ngaylc: ngaylc,
                            donvi: donvi,
                            chucvu: chucvu,
                            soqd: soqd,
                            ngayqd: ngayqd,
                            nguoiky: nguoiky
                            },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message,'Lỗi!');
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}'+'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            ngaylc: ngaylc,
                            donvi: donvi,
                            chucvu: chucvu,
                            soqd: soqd,
                            ngayqd: ngayqd,
                            nguoiky: nguoiky,
                            id: id
                            },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message,'Lỗi!');
                        }
                    });
                }
                $('#chitiet-modal').modal('hide');
            }else{
                alert(message);
            }
            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop