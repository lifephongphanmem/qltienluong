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
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">DANH SÁCH BÁO CÁO TẠI ĐƠN VỊ</div>
                    <div class="actions"></div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ol>
                                <li>
                                    <a href="#" data-target="#thoaidutoan-modal" data-toggle="modal"
                                       onclick="dutoanluong('{{$furl.'don_vi/dutoanluong'}}')">Dự toán lương</a>
                                </li>
                                <hr>

                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal" title="Dữ liệu chi trả theo tổng hợp lương tại đơn vị"
                                       onclick="chitraluong('{{$furl.'don_vi/chitraluong'}}')">Tổng hợp tình hình chi trả lương theo phân loại công tác</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal" title="Dữ liệu chi trả theo tổng hợp lương tại đơn vị"
                                       onclick="chitraluong('{{$furl.'don_vi/chitratheonkp'}}')">Tổng hợp tình hình chi trả lương theo nguồn kinh phí</a>

                                </li>
                                <li>
                                    <a href="#" data-target="#thoaichitra-modal" data-toggle="modal" title="Dữ liệu chi trả theo tổng hợp lương tại đơn vị"
                                       onclick="chitraluong('{{$furl.'don_vi/chitratheocb'}}')">Tổng hợp tình hình chi trả lương theo cán bộ</a>

                                </li>
                                <hr>

                                <li>
                                    <a href="#" onclick="nhucauluong('{{'/bao_cao/thong_tu_67/don_vi/mau2a1'}}')" data-toggle="modal"
                                       data-target="#thoainhucauluong-modal">Báo cáo nhu cầu kinh phí (Mẫu 2a/1)</a>
                                </li>
                                <li>
                                    <a href="#" onclick="nhucauluong('{{'/bao_cao/thong_tu_67/don_vi/mau2a2'}}')" data-toggle="modal"
                                       data-target="#thoainhucauluong-modal">Báo cáo nhu cầu kinh phí (Mẫu 2a/2)</a>
                                </li>
                                <hr>

                                <li>
                                    <a href="#" onclick="nhucauluong('{{'/bao_cao/thong_tu_67/don_vi/mau4a'}}')" data-toggle="modal"
                                       data-target="#thoainhucauluong-modal">Báo cáo nguồn kinh phí (Mẫu 4a)</a>
                                </li>
                                <li>
                                    <a href="#" onclick="nhucauluong('{{'/bao_cao/thong_tu_67/don_vi/mau4b'}}')" data-toggle="modal"
                                       data-target="#thoainhucauluong-modal">Tổng hợp nhu cầu, nguồn kinh phí (Mẫu 4b)</a>
                                </li>

                                {{--<li><a href="#" data-target="#thoaichitra-modal" data-toggle="modal" onclick="chitraluong('{{$furl.'don_vi/dangkyluong'}}')">Tổng hợp đăng ký lương</a></li>--}}
                                <hr>

                                <li>
                                    <a href="#" data-target="#thoainangluong-modal" data-toggle="modal"
                                       onclick="nangluong('{{$furl.'don_vi/nangluong'}}','NGACHBAC')">Danh sách nâng lương ngạch bậc (Theo danh sách nâng lương)</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoainangluong-modal" data-toggle="modal"
                                       onclick="nangluong('{{$furl.'don_vi/nangluong'}}','TNN')">Danh sách nâng thâm niên nghề (Theo danh sách nâng lương)</a>
                                </li>
                                <hr>

                                <li>
                                    <a href="#" data-target="#thoaidsnangluong-modal" data-toggle="modal"
                                       onclick="dsnangluong('{{$furl.'don_vi/dsnangluong'}}','NGACHBAC')">Danh sách nâng lương ngạch bậc (Theo danh sách cán bộ)</a>
                                </li>
                                <li>
                                    <a href="#" data-target="#thoaidsnangluong-modal" data-toggle="modal"
                                       onclick="dsnangluong('{{$furl.'don_vi/dsnangluong'}}','TNN')">Danh sách nâng thâm niên nghề (Theo danh sách cán bộ)</a>
                                </li>


                                <!-- Tạm thời bỏ để triển khai lạng sơn -->
                                <!--
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2a1')}}" target="_blank">Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP (Mẫu 2a/1)</a></li>
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2a2')}}" target="_blank">Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP (Mẫu 2a/2)</a></li>
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2b')}}" target="_blank">Báo cáo tổng hợp quỹ trợ cấp tăng thêm của cán bộ xã, phường, thị trấn đã nghỉ việc (Mẫu 2b)</a></li>
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2c')}}" target="_blank">Báo cáo nhu cầu chênh lệch (Mẫu 2c)</a></li>
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2d')}}" target="_blank">Tổng hợp kinh phí tăng thêm để thực hiện chế độ phụ cấp đối với cán bộ không chuyên trách (Mẫu 2d)</a></li>
                                @if(session('admin')->maphanloai == 'KVXP')
                                    <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2e')}}" target="_blank">Tổng hợp kinh phí tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2e)</a></li>
                                    <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2g')}}" target="_blank">Tổng hợp phụ cấp ưu đãi tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2g)</a></li>
                                    <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau2h')}}" target="_blank">Tổng hợp phụ cấp thu hút tăng, giảm do điều chỉnh địa bàn vùng kinh tế xã hội đặc biệt khó khăn (Mẫu 2h)</a></li>
                                @endif
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau4a')}}" target="_blank">Báo cáo nguồn kinh phí (Mẫu 4a)</a></li>
                                <li><a href="{{url('/bao_cao/thong_tu_67/don_vi/mau4b')}}" target="_blank">Tổng hợp nhu cầu, nguồn kinh phí (Mẫu 4b)</a></li>
                                -->

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
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tuthang',getThang(),'01',array('id' => 'tuthang', 'class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang',getThang(),'12',array('id' => 'denthang', 'class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam',getNam(),date('Y'),array('id' => 'tunam', 'class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Phân loại công tác<span class="require">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select2me" name="mact" id="mact">
                                <option value="">-- Tất cả các phân loại công tác --</option>
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
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="thoaidutoan-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaidutoan', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất dự toán lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('namns',getNam(),date('Y'),array('id' => 'namns', 'class' => 'form-control'))!!}
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

    <div id="thoainangluong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'frm_thoainangluong', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ ngày<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!!Form::input('date','ngaytu',date('Y').'-01-01', array('id' => 'ngaytu','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến ngày<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!!Form::input('date','ngayden', date('Y').'-12-31', array('id' => 'ngayden','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <input type="checkbox" name="indanangluong" />
                            <label class="control-label">Bao gồm các cán bộ đã nâng lương</label>
                        </div>
                    </div>

                    <input type="hidden" name="phanloai" id="phanloai">
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

    <div id="thoaidsnangluong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'frm_thoaidsnangluong', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Từ ngày<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!!Form::input('date','ngaytu',date('Y').'-01-01', array('id' => 'ngaytu','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đến ngày<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!!Form::input('date','ngayden', date('Y').'-12-31', array('id' => 'ngayden','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <input type="checkbox" name="indanangluong" />
                            <label class="control-label">Bao gồm các cán bộ đã nâng lương</label>
                        </div>
                    </div>

                    <input type="hidden" name="phanloai" id="phanloai">
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

    <div id="thoaichitra-khoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaichitra_khoi', 'class'=>'form-horizontal form-validate']) !!}
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
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang',getThang(),'12',array('id' => 'denthang', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam',getNam(),date('Y'),array('id' => 'tunam', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
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

    <div id="thoaidutoan-khoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaidutoan_khoi', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất dự toán lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('namns',getNam(),date('Y'),array('id' => 'namns', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
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

    <div id="thoaichitra-huyen-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaichitra_huyen', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất</h4>
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
                        <label class="col-md-4 control-label"> Đến tháng<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('denthang',getThang(),'12',array('id' => 'denthang', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('tunam',getNam(),date('Y'),array('id' => 'tunam', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
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

    <div id="thoaidutoan-huyen-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaidutoan_huyen', 'class'=>'form-horizontal form-validate']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất dự toán lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Năm<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('namns',getNam(),date('Y'),array('id' => 'namns', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Đơn vị tính</label>
                        <div class="col-md-8">
                            {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
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

    <div id="thoainhucauluong-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoainhucau', 'class'=>'form-horizontal']) !!}
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất nhu cầu kinh phí</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Căn cứ thông tư, quyết định</label>
                        {!!Form::select('sohieu',getThongTuQD(false), null, array('id' => 'sohieu','class' => 'form-control'))!!}
                    </div>
                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
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

        function dutoanluong(url){
            $('#thoaidutoan').attr('action',url);
        }

        function nangluong(url,phanloai){
            $('#frm_thoainangluong').attr('action',url);
            $('#frm_thoainangluong').find("[id='phanloai']").val(phanloai);
        }

        function dsnangluong(url,phanloai){
            $('#frm_thoaidsnangluong').attr('action',url);
            $('#frm_thoaidsnangluong').find("[id='phanloai']").val(phanloai);
        }

        function chitraluong_khoi(url){
            $('#thoaichitra_khoi').attr('action',url);
        }

        function dutoanluong_khoi(url){
            $('#thoaidutoan_khoi').attr('action',url);
        }

        function chitraluong_huyen(url){
            $('#thoaichitra_huyen').attr('action',url);
        }

        function dutoanluong_huyen(url){
            $('#thoaidutoan_huyen').attr('action',url);
        }

        function nhucauluong(url){
            $('#thoainhucau').attr('action',url);
        }
    </script>
@stop