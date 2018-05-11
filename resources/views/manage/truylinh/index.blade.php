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
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <b>DANH SÁCH CÁN BỘ ĐƯỢC HƯỞNG TRUY LĨNH LƯƠNG</b>
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
                                <th class="text-center">Từ ngày</th>
                                <th class="text-center">Đến ngày</th>
                                <th class="text-center">Mã ngạch</br>lương</th>
                                <th class="text-center">Hệ số</br>(phần trăm)</br>truy lĩnh</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td>{{getDayVn($value->truylinhtungay)}}</td>
                                        <td>{{getDayVn($value->truylinhdenngay)}}</td>
                                        <td>{{$value->msngbac}}</td>
                                        <td>{{$value->hesott}}</td>
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

    <!--Modal thông tin chức vụ -->
    {!! Form::open(['url'=>'/nghiep_vu/truy_linh/store','method'=>'post', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin tạm ngừng theo dõi cán bộ</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Họ và tên cán bộ</label>
                    <select name="macanbo" id="macanbo" class="form-control">
                        <option value="">--Chọn cán bộ --</option>
                        @foreach($a_canbo as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>

                    <label class="form-control-label">Ngày bắt đầu truy lĩnh</label>
                    <input type="date" name="ngaytu" id="ngaytu" class="form-control" />

                    <label class="form-control-label">Hệ số (phần trăm) truy lĩnh</label>
                    {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}


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
            $('#maso').val('');
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
                    $('#ngaytu').val(data.ngaytu);
                    $('#heso').val(data.heso);
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
                var ok = true, str='';

                if($('#macanbo').val()==''){
                    ok = false;
                    str += ' - Họ tên cán bộ \n'
                }

                if($('#ngaytu').val()==''){
                    ok = false;
                    str += ' - Thời gian truy lĩnh \n'
                }

                if ( ok == false){
                    alert('Các trường: \n' + str + 'Không được để trống');
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
    @include('includes.script.scripts')
@stop