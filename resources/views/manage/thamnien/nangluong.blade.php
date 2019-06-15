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
                    <div class="caption">DANH SÁCH CHI TIẾT CÁC CÁN BỘ ĐƯỢC NÂNG LƯƠNG </div>
                    <div class="actions">
                        <a class="btn btn-default" href="{{url($furl.'printf?maso='.$model_nangluong->manl)}}" target="_blank">
                            <i class="fa fa-print"></i> In danh sách
                        </a>

                        <button type="button" class="btn btn-default" data-target="#addCanBo-modal" data-toggle="modal">
                            <i class="fa fa-plus"></i> Thêm cán bộ
                        </button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Họ tên</th>
                                <th class="text-center">Chức vụ</th>
                                <th class="text-center">Thời gian</br>hưởng lương</th>
                                <th class="text-center">Thâm niên nghề</th>
                                <th class="text-center">Hệ số</br>truy lĩnh</th>
                                <th class="text-center">Thời gian</br>truy lĩnh</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td>{{$value->tencv}}</td>
                                        <td class="text-center">{{'Từ '.getDayVn($value->ngaytu).' đến '.getDayVn($value->ngayden)}}</td>
                                        <td class="text-center">{{$value->pctnn.'%'}}</td>
                                        <td class="text-center">{{dinhdangsothapphan( $value->heso,5)}}</td>
                                        <td class="text-center">{{isset($value->truylinhtungay)? 'Từ '.getDayVn($value->truylinhtungay):''}}</td>
                                        <td>

                                            <a type="button" href="{{url($furl.'chi_tiet?maso='.$value->manl.'&canbo='.$value->macanbo)}}" class="btn btn-info btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp;Chi tiết</a>
                                            @if($model_nangluong->trangthai != 'Đã nâng lương')
                                                <button type="button" onclick="cfDel('{{$furl.'deldt/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        @if($model_nangluong->trangthai != 'Đã nâng lương')
                            <a href="{{url($furl.'nang_luong/maso='.$model_nangluong->manl)}}" class="btn btn-default"><i class="fa fa-check-square-o"></i>&nbsp;Nâng thâm niên nghề</a>
                        @endif
                        <a href="{{url($furl.'danh_sach')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="addCanBo-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>$furl.'add_canbo', 'class'=>'horizontal-form']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thêm cán bộ nâng lương thâm niên nghề</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Chọn cán bộ nâng lương</label>
                        {!!Form::select('macanbo',$a_canbo, null, array('id' => 'macanbo','class' => 'form-control select2me'))!!}
                    </div>
                </div>

                <input type="hidden" id="manl" name="manl" value="{{$model_nangluong->manl}}"/>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="store_nkp()">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>


    <!--Modal thông tin chi tiết -->
    @include('includes.modal.delete')
@stop