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
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02x'}}')">Mẫu 2 (Mẫu C02 - X)</a></li>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'maubaohiem'}}')">Mẫu bảo hiểm phải nộp theo lương</a></li>
                                <li><a href="#" data-target="#thoaichitra-modal" data-toggle="modal" onclick="chitraluong('{{$furl.'chitraluong'}}')">Tổng hợp tình hình chi trả lương</a></li>
                                <li><a href="{{url('/bao_cao/bang_luong/dutoanluong')}}" target="_blank">Dự toán lương</a></li>
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
                            {!! Form::select(
                            'thang',
                            array(
                            '01' => '01',
                            '02' => '02',
                            '03' => '03',
                            '04' => '04',
                            '05' => '05',
                            '06' => '06',
                            '07' => '07',
                            '08' => '08',
                            '09' => '09',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            ),null,
                            array('id' => 'thang', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'nam',
                            array(
                            '2015' => '2015',
                            '2016' => '2016',
                            '2017' => '2017'
                            ),null,
                            array('id' => 'nam', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                    @if(session('admin')->level=='H')
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Đơn vị<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select id="madv" name="madv" class="form-control">
                                    <option value="">Tất cả các đơn vị</option>
                                    @if(isset($model_dv))
                                        @foreach($model_dv as $dv)
                                            <option value="{{$dv->madv}}">{{$dv->tendv}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif

                    @if(session('admin')->level=='T')
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Khu vực, địa bàn<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select id="madv" name="madv" class="form-control">
                                    <option value="">Tất cả các đơn vị</option>
                                    @if(isset($model_dvbc))
                                        @foreach($model_dvbc as $dv)
                                            <option value="{{$dv->madvbc}}">{{$dv->tendvbc}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif

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
                            {!! Form::select(
                            'tuthang',
                            array(
                            '01' => '01',
                            '02' => '02',
                            '03' => '03',
                            '04' => '04',
                            '05' => '05',
                            '06' => '06',
                            '07' => '07',
                            '08' => '08',
                            '09' => '09',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            ),'01',
                            array('id' => 'tuthang', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'tunam',
                            array(
                            '2015' => '2015',
                            '2016' => '2016',
                            '2017' => '2017'
                            ),'2017',
                            array('id' => 'tunam', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'denthang',
                            array(
                            '01' => '01',
                            '02' => '02',
                            '03' => '03',
                            '04' => '04',
                            '05' => '05',
                            '06' => '06',
                            '07' => '07',
                            '08' => '08',
                            '09' => '09',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            ),'12',
                            array('id' => 'denthang', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select(
                            'dennam',
                            array(
                            '2015' => '2015',
                            '2016' => '2016',
                            '2017' => '2017'
                            ),'2017',
                            array('id' => 'dennam', 'class' => 'form-control'))
                            !!}
                        </div>
                    </div>

                    @if(session('admin')->level=='H')
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Đơn vị<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select id="madv" name="madv" class="form-control">
                                    <option value="">Tất cả các đơn vị</option>
                                    @if(isset($model_dv))
                                        @foreach($model_dv as $dv)
                                            <option value="{{$dv->madv}}">{{$dv->tendv}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif

                    @if(session('admin')->level=='T')
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Khu vực, địa bàn<span class="require">*</span></label>
                            <div class="col-md-8">
                                <select id="madv" name="madv" class="form-control">
                                    <option value="">Tất cả các đơn vị</option>
                                    @if(isset($model_dvbc))
                                        @foreach($model_dvbc as $dv)
                                            <option value="{{$dv->madvbc}}">{{$dv->tendvbc}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif

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