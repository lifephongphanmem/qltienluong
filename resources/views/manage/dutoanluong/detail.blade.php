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
                        <i class="fa fa-list-alt"></i>CHI TIẾT DỰ TOÁN LƯƠNG CỦA ĐƠN VỊ NĂM {{$model_dutoan->namns}}
                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Số lượng</br>cán bộ</br>hiện có</th>
                                <th class="text-center">Số lượng</br>cán bộ</br>chưa tuyển</th>
                                <th class="text-center">Tổng số</br>dự toán</th>
                                <th class="text-center">Lương theo</br>ngạch bậc</th>
                                <th class="text-center">Tổng các khoản</br>phụ cấp</th>
                                <th class="text-center">Các khoản</br>đóng góp</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tenct}}</td>
                                        <td class="text-center">{{$value->canbo_congtac}}</td>
                                        <td class="text-center">{{$value->canbo_dutoan}}</td>
                                        <td class="text-right">{{number_format($value->tongcong)}}</td>
                                        <td class="text-right">{{number_format($value->luongnb_dt + $value->luongnb)}}</td>
                                        <td class="text-right">{{number_format($value->luonghs_dt)}}</td>
                                        <td class="text-right">{{number_format($value->luongbh_dt + $value->luongbh)}}</td>
                                        <td>
                                            @if($model_dutoan->trangthai == 'CHUAGUI')
                                                <button type="button" onclick="getDuToan('{{$value->masodv}}','{{$value->mact}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                                <button type="button" onclick="cfDel('{{$furl.'detail/del/'.$value->id}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-offset-5 col-md-8">
                            <a href="{{url($furl.'danh_sach')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>

                        </div>
                    </div>
                </div>
                </div>
            </div>
    </div>

    <!--Modal thông tin ngạch lương -->
    {!! Form::open(['url'=>$furl.'detail/update','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin phân loại công tác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet box blue" style="margin-bottom: 5px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    Thông tin chung
                                </div>
                            </div>

                            <div class="portlet-body" style="display: block;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Phân loại công tác</label>
                                        <select class="form-control" name="mact" id="mact" required="required" disabled>
                                            @foreach($model_nhomct as $kieuct)
                                                <optgroup label="{{$kieuct->tencongtac}}">
                                                    <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                    @foreach($mode_ct as $ct)
                                                        <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Dự toán cán bộ tuyển thêm
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Số lượng</label>
                                            {!!Form::text('canbo_dutoan', null, array('id' => 'canbo_dutoan','class' => 'form-control congthuc', 'data-mask'=>'fdecimal'))!!}
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label">Lương theo ngạch bậc</label>
                                            {!!Form::text('luongnb', null, array('id' => 'luongnb','class' => 'form-control text-right congthuc', 'data-mask'=>'fdecimal'))!!}
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label">Các khoản đóng góp</label>
                                            {!!Form::text('luongbh', null, array('id' => 'luongbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box blue" style="margin-bottom: 5px;">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Dự toán cán bộ đang công tác
                                    </div>
                                </div>

                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Số lượng</label>
                                            {!!Form::text('canbo_congtac', null, array('id' => 'canbo_congtac','class' => 'form-control', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label">Lương theo ngạch bậc</label>
                                            {!!Form::text('luongnb_dt', null, array('id' => 'luongnb_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label">Tổng các khoản phụ cấp</label>
                                            {!!Form::text('luonghs_dt', null, array('id' => 'luonghs_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label">Các khoản đóng góp</label>
                                            {!!Form::text('luongbh_dt', null, array('id' => 'luongbh_dt','class' => 'form-control text-right', 'data-mask'=>'fdecimal', 'readonly'))!!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                    <input type="hidden" id="id_ct" name="id_ct"/>
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
        function getDuToan(masodv, mact){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'detail/get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    masodv: masodv,
                    mact: mact
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mact').val(data.mact).trigger('change');
                    $('#canbo_congtac').val(data.canbo_congtac);
                    $('#canbo_dutoan').val(data.canbo_dutoan);

                    $('#luongnb').val(data.luongnb);
                    //$('#luonghs').val(data.luonghs);
                    $('#luongbh').val(data.luongbh);

                    $('#luongnb_dt').val(data.luongnb_dt);
                    $('#luonghs_dt').val(data.luonghs_dt);
                    $('#luongbh_dt').val(data.luongbh_dt);
                    $('#id_ct').val(data.id);
                },
                error: function (message) {
                    toastr.error(message, 'Lỗi!');
                }
            });


            $('#chitiet-modal').modal('show');
        }

        $(function(){
            $('#canbo_dutoan').change(function(){
                var soluong = $('#canbo_dutoan').val();
                var luongcb = '{{$inputs['luongcb']}}';
                var heso = '{{$inputs['heso']}}';
                var luongnb =  Math.round(soluong * luongcb * heso * 12);
                $('#luongnb').val(luongnb);
            });
        });
    </script>

    @include('includes.modal.delete')
@stop