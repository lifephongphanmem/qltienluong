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
    <h3 class="page-title">
        MẪU BẢNG LƯƠNG
    </h3>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02ahd'}}')">Mẫu 1 (Mẫu C02a - HD) - Đơn vị</a></li>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02x'}}')">Mẫu 2 (Mẫu C02 - X) - Đơn vị</a></li>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'maubaohiem'}}')">Mẫu bảo hiểm phải nộp theo lương - Đơn vị</a></li>
                            </ol>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <ol>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02ahd_th'}}')">Mẫu 1 (Mẫu C02a - HD) - Mẫu tổng hợp các đơn vị</a></li>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'mauc02x_th'}}')">Mẫu 2 (Mẫu C02 - X) - Mẫu tổng hợp các đơn vị</a></li>
                                <li><a href="#" data-target="#thoaibangluong-modal" data-toggle="modal" onclick="baocaobangluong('{{$furl.'maubaohiem_th'}}')">Mẫu bảo hiểm phải nộp theo lương - Mẫu tổng hợp các đơn vị</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
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

    </script>
@stop