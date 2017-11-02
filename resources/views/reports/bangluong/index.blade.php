<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
        ?>
@extends('main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-header">
                    MẪU BÁO CÁO TẠI CÁC ĐƠN VỊ
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'don_vi/mauc02ahd'}}')">Mẫu 1 (Mẫu C02a - HD)</a></li>
                                <!--li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'don_vi/mauc02x'}}')">Mẫu 2 (Mẫu C02 - X)</a></li-->
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'don_vi/maubaohiem'}}')">Mẫu bảo hiểm phải nộp theo lương</a></li>
                                <li><a href="#" data-target="#thoaichitra-modal" data-toggle="modal" onclick="chitraluong('{{$furl.'don_vi/chitraluong'}}')">Tổng hợp tình hình chi trả lương</a></li>
                                <li><a href="{{url('/bao_cao/bang_luong/don_vi/dutoanluong')}}" target="_blank">Dự toán lương</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('admin')->quanlynhom)
                <div class="portlet box">
                    <div class="portlet-header">
                        MẪU BÁO CÁO TỔNG HỢP TỪ CÁC ĐƠN VỊ CẤP DƯỚI
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <ol>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02ahd_th'}}')">Mẫu 1 (Mẫu C02a - HD)</a></li>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02x_th'}}')">Mẫu 2 (Mẫu C02 - X)</a></li>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'maubaohiem_th'}}')">Mẫu bảo hiểm phải nộp theo lương</a></li>
                                    <li><a href="#" data-target="#thoaichitra-modal" data-toggle="modal" onclick="chitraluong('{{$furl.'chitraluong_th'}}')">Tổng hợp tình hình chi trả lương</a></li>
                                    <li><a href="{{url('/bao_cao/bang_luong/dutoanluong_th')}}" target="_blank">Dự toán lương</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('admin')->quanlykhuvuc)
                <div class="portlet box">
                    <div class="portlet-header">
                        MẪU BÁO CÁO TỔNG HỢP TRÊN TOÀN ĐỊA BÀN
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <ol>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02ahd_th'}}')">Mẫu 1 (Mẫu C02a - HD)</a></li>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02x_th'}}')">Mẫu 2 (Mẫu C02 - X)</a></li>
                                    <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'maubaohiem_th'}}')">Mẫu bảo hiểm phải nộp theo lương</a></li>
                                    <li><a href="#" data-target="#thoaichitra-modal" data-toggle="modal" onclick="chitraluong('{{$furl.'chitraluong_th'}}')">Tổng hợp tình hình chi trả lương</a></li>
                                    <li><a href="{{url('/bao_cao/bang_luong/dutoanluong_th')}}" target="_blank">Dự toán lương</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="thoaibangluong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaibangluong', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('thang',getThang(),null,array('id' => 'thang', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('nam',getNam(),date('Y'),array('id' => 'nam', 'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="thoaichitra-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaichitra', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất tổng hợp chi trả bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang',getThang(),'01',array('id' => 'tuthang', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam',getNam(),date('Y'),array('id' => 'tunam', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang',getThang(),'12',array('id' => 'denthang', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('dennam',getNam(),date('Y'),array('id' => 'dennam', 'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        function baocaobangluong(url){
            $('#thoaibangluong').attr('action',url);
        }

        function chitraluong(url){
            $('#thoaichitra').attr('action',url);
        }

    </script>
@stop