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
        BÁO CÁO TỔNG HỢP BIÊN CHẾ - TIỀN LƯƠNG
    </h3>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2a1_tt67'}}')">Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP (Mẫu 2a/1)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2a2_tt67'}}')">Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP (Mẫu 2a/2)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2b_tt67'}}')">Báo cáo tổng hợp quỹ trợ cấp tăng thêm của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a></li>

                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2c_tt67'}}')">Báo cáo nhu cầu chênh lệch (Mẫu 2c)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2d_tt67'}}')">Tổng hợp kinh phí tăng thêm để thực hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu 2d)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2e_tt67'}}')">Tổng hợp kinh phí tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2e)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2g_tt67'}}')">Tổng hợp phụ cấp ưu đãi tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2g)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau2h_tt67'}}')">Tổng hợp phụ cấp thu hút tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2h)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau4a_tt67'}}')">Báo cáo nguồn kinh phí để thực hiện cải cách tiền lương (Mẫu 4a)</a></li>
                                <li><a href="#" data-target="#chitiet-modal" data-toggle="modal" onclick="baocao('{{$furl.'mau4b_tt67'}}')">Tổng hợp nhu cầu, nguồn thực hiện nghị định 47/2017/NĐ-CP (Mẫu 4b)</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaibc', 'class'=>'form-horizontal form-validate']) !!}
            <div class="modal-dialog modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất báo cáo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="modal-body">
                            <div class="form-horizontal">
                                <!--
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
                                        '2017' => '2017',
                                        '2018' => '2018'
                                        ),null,
                                        array('id' => 'nam', 'class' => 'form-control'))
                                        !!}
                                    </div>
                                </div>
                                -->
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

                                                    @if(session('admin')->username != 'khthso' && isset($model_dvbc))
                                                        @foreach($model_dvbc as $dv)
                                                            <option value="{{$dv->madvbc}}">{{$dv->tendvbc}}</option>
                                                        @endforeach
                                                    @endif
                                                    @if(session('admin')->username == 'khthso' && isset($model_dvbcT))
                                                        @foreach($model_dvbcT as $dvT)
                                                            <option value="{{$dvT->madvbc}}">{{$dvT->tendvbc}}</option>
                                                        @endforeach
                                                    @endif
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-md-4 control-label"> Đơn vị tính</label>
                                    <div class="col-md-8">
                                        {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        function baocao(url){
            $('#thoaibc').attr('action',url);
        }
    </script>
@stop