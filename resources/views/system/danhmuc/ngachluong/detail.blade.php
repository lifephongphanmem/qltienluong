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
                        <b>DANH MỤC NGẠCH BẬC LƯƠNG</b>
                    </div>
                    <div class="actions">
                        <a href="{{url('/danh_muc/ngach_bac/index')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                        @if(can('dmngachluong','create'))
                            <button type="button" id="_btnaddPB" class="btn btn-default" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Mã ngạch bậc</th>
                                <th class="text-center">Tên ngạch bậc</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->msngbac}}</td>
                                        <td>{{$value->tenngachluong}}</td>
                                        <td>
                                            @if(can('dmngachluong','edit'))
                                                <button type="button" onclick="edit('{{$value->msngbac}}')" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif

                                            @if(can('dmngachluong','delete'))
                                                <button type="button" onclick="cfDel('/danh_muc/ngach_bac/del_detail/{{$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal thông tin ngạch lương -->
    {!! Form::open(['url'=>'danh_muc/ngach_bac/store_detail','method'=>'post', 'id' => 'create_ngachbac','enctype'=>'multipart/form-data']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin phân loại công tác</h4>
                </div>
                <div class="modal-body form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-control-label">Mã ngạch bậc<span class="require">*</span></label>
                            {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','required'=>'required','autofocus'=>'true'))!!}
                        </div>
                        <div class="col-md-8">
                            <label class="form-control-label">Tên ngạch bậc</label>
                            {!!Form::text('tenngachluong', null, array('id' => 'tenngachluong','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label">Hệ số lương</label>
                            {!!Form::text('heso', $model_nhom->heso, array('id' => 'heso','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="form-control-label">Hệ số lương lớn nhất</label>
                            {!!Form::text('hesolonnhat', $model_nhom->hesolonnhat, array('id' => 'hesolonnhat','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label class="form-control-label">Bậc lương cao nhất</label>
                            {!!Form::text('baclonnhat', $model_nhom->baclonnhat, array('id' => 'baclonnhat','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label">Bậc lương vượt khung</label>
                            {!!Form::text('bacvuotkhung', $model_nhom->bacvuotkhung, array('id' => 'bacvuotkhung','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>

                        <div class="col-md-6">
                            <label class="form-control-label">Phần trăm vượt khung</label>
                            {!!Form::text('vuotkhung', $model_nhom->vuotkhung, array('id' => 'vuotkhung','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label">Hệ số chênh lệnh</label>
                            {!!Form::text('hesochenhlech', $model_nhom->hesochenhlech, array('id' => 'hesochenhlech','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                        <div class="col-md-6">
                            <label class="form-control-label">Năm nâng lương</label>
                            {!!Form::text('namnb', $model_nhom->namnb, array('id' => 'namnb','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                    <input type="hidden" id="id_del" name="id_del"/>
                    <input type="hidden" id="manhom" name="manhom" value="{{$manhom}}"/>
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
            $('#tenct').val('');
            $('#mact').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
            $('#tenct').focus();
        }

        function edit(msngbac){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_detail',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    msngbac: msngbac
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#msngbac').val(data.msngbac);
                    $('#tenngachluong').val(data.tenngachluong);
                    $('#heso').val(data.heso);
                    $('#vuotkhung').val(data.vuotkhung);
                    $('#bacvuotkhung').val(data.bacvuotkhung);
                    $('#baclonnhat').val(data.baclonnhat);
                    $('#hesolonnhat').val(data.hesolonnhat);
                    $('#hesochenhlech').val(data.hesochenhlech);
                    $('#namnb').val(data.namnb);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#create-modal').modal('show');
        }

        $(function(){
            $('#create_ngachbac :submit').click(function(){
                var ok = true;
                if($('#msngbac').val()==''){
                    ok = false;
                }
                //Kết quả
                if ( ok == false){
                    alert(' Tên ngạch bậc không được bỏ trống ');
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