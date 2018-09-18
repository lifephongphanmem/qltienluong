@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    @include('includes.script.scripts')
    <script>
        jQuery(document).ready(function() {
            tinhtoan_load();
        });
        $(function() {
            //Multi select box
            //$("#ctpc").select2();
            $("#ctpc").change(function () {
                $("#congthuc").val($("#ctpc").val());
            });
        });
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin truy lĩnh lương của cán bộ
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN DASHBOARD STATS -->
    <div class="row center">
        <div class="col-md-12 center">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box blue">
                <!--div class="portlet-title">
                </div-->
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::model($model,['url'=>$furl.'store', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin chung
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Họ tên cán bộ<span class="require">*</span></label>
                                                    {!!Form::text('tencanbo', null, array('id' => 'tencanbo','class' => 'form-control','required','readonly'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">Đến ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control ngaythang','required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số tháng truy lĩnh </label>
                                                    {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Số ngày truy lĩnh </label>
                                                    {!!Form::text('ngaytl', null, array('id' => 'ngaytl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Hệ số truy lĩnh </label>
                                                    {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Nguồn KP hưởng lương</label>
                                                    {!!Form::select('manguonkp',getNguonKP(false), null, array('id' => 'manguonkp','class' => 'form-control select2me'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Mức lương truy lĩnh </label>
                                                    {!!Form::text('luongcoban', null, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Nội dung truy lĩnh </label>
                                                    {!!Form::text('noidung', null, array('id' => 'noidung','class' => 'form-control'))!!}
                                                </div>
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
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin các khoản phụ cấp
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">
                                        <div class="form-body">
                                            <div class="row">
                                                @foreach($model_pc as $pc)
                                                    @if(in_array($pc->mapc,$a_heso))
                                                        @continue
                                                    @endif

                                                    @if($pc->phanloai == 3)
                                                        {!!Form::hidden($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                    @elseif($pc->phanloai == 2)
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                <div class="input-group bootstrap-touchspin">
                                                                    {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                                    <span class="input-group-addon bootstrap-touchspin-postfix">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($pc->phanloai == 1)
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                <div class="input-group bootstrap-touchspin">
                                                                    {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                                    <span class="input-group-addon bootstrap-touchspin-postfix">VNĐ</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="text-align: center">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                    <a href="{{url($furl.'danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
                {!! Form::close() !!}
                <!-- END FORM-->

            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        $('.ngaythang').change(function(){
            tinhtoan();
        });

        function tinhtoan() {
            //cùng năm => so sánh tháng
            var ngaytu = $('#ngaytu').val();
            var ngayden = $('#ngayden').val();
            if(ngaytu =='' || ngayden ==''){
                $('#thangtl').val(0);
                $('#ngaytl').val(0);
            }else{
                var tungay = new Date(ngaytu);
                var denngay = new Date(ngayden);
                var ngaycong = 22;

                var nam_tu = tungay.getFullYear();
                var ngay_tu = tungay.getDate();
                var thang_tu = tungay.getMonth(); //bắt đầu từ 0

                var nam_den = denngay.getFullYear();
                var ngay_den = denngay.getDate();
                var thang_den = denngay.getMonth(); //bắt đầu từ 0

                var thangtl = 0;
                var ngaytl = 0;
                thangtl = thang_den + 12*(nam_den - nam_tu) - thang_tu + 1;//cộng 1 là do 7->8 = 2 tháng (như đếm số tự nhiên)
                //
                if(ngay_tu >1){//không pải từ đầu tháng => xét số ngày tl
                    thangtl = thangtl - 1;
                    //từ ngày xét đến cuối tháng
                    //lấy ngày cuối cùng của tháng từ
                    var ngay_tucuoi = new Date(nam_tu, thang_tu+1, 0).getDate();
                    if(ngay_tucuoi - ngay_tu >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl = ngay_tucuoi - ngay_tu;
                    }
                }

                var ngay_dencuoi = new Date(nam_den, thang_den+1, 0).getDate();
                if(ngay_den < ngay_dencuoi){
                    thangtl = thangtl - 1;
                    if( ngay_den >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl += ngay_den;
                    }
                }
                if(ngaytl > ngaycong){
                    ngaytl = ngaytl - ngaycong;
                    thangtl = thangtl + 1;
                }
                $('#thangtl').val(thangtl);
                $('#ngaytl').val(ngaytl);
            }
        }

        //chạy hàm khi đơn vị trc chưa tính theo ngày tháng
        function tinhtoan_load() {
            if($('#thangtl').val() ==0 || $('#ngaytl').val() ==0){
                //cùng năm => so sánh tháng
                var ngaytu = $('#ngaytu').val();
                var ngayden = $('#ngayden').val();
                if(ngaytu =='' || ngayden ==''){
                    $('#thangtl').val(0);
                    $('#ngaytl').val(0);
                }else{
                    var tungay = new Date(ngaytu);
                    var denngay = new Date(ngayden);
                    var ngaycong = 22;

                    var nam_tu = tungay.getFullYear();
                    var ngay_tu = tungay.getDate();
                    var thang_tu = tungay.getMonth(); //bắt đầu từ 0

                    var nam_den = denngay.getFullYear();
                    var ngay_den = denngay.getDate();
                    var thang_den = denngay.getMonth(); //bắt đầu từ 0

                    var thangtl = 0;
                    var ngaytl = 0;
                    thangtl = thang_den + 12*(nam_den - nam_tu) - thang_tu + 1;//cộng 1 là do 7->8 = 2 tháng (như đếm số tự nhiên)
                    //
                    if(ngay_tu >1){//không pải từ đầu tháng => xét số ngày tl
                        thangtl = thangtl - 1;
                        //từ ngày xét đến cuối tháng
                        //lấy ngày cuối cùng của tháng từ
                        var ngay_tucuoi = new Date(nam_tu, thang_tu+1, 0).getDate();
                        if(ngay_tucuoi - ngay_tu >= ngaycong){
                            thangtl = thangtl + 1;
                        }else{
                            ngaytl = ngay_tucuoi - ngay_tu;
                        }
                    }

                    var ngay_dencuoi = new Date(nam_den, thang_den+1, 0).getDate();
                    if(ngay_den < ngay_dencuoi){
                        thangtl = thangtl - 1;
                        if( ngay_den >= ngaycong){
                            thangtl = thangtl + 1;
                        }else{
                            ngaytl += ngay_den;
                        }
                    }

                    $('#thangtl').val(thangtl);
                    $('#ngaytl').val(ngaytl);
                }
            }
        }

        function validateForm(){
            var validator = $("#create_tttaikhoan").validate({
                rules: {
                    name :"required",
                    tendv :"required"

                },
                messages: {
                    name :"Chưa nhập dữ liệu",
                    tendv :"Chưa nhập dữ liệu"
                }
            });
        }
    </script>

@stop