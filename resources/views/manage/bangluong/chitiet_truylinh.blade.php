@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        THÔNG TIN CHI TIẾT TRUY LĨNH LƯƠNG CỦA CÁN BỘ {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <input type="hidden" id="bhxh" name="bhxh" value="{{$model->bhxh}}" />
                    <input type="hidden" id="bhyt" name="bhyt" value="{{$model->bhyt}}" />
                    <input type="hidden" id="bhtn" name="bhtn" value="{{$model->bhtn}}" />
                    <input type="hidden" id="kpcd" name="kpcd" value="{{$model->kpcd}}" />
                    <input type="hidden" id="bhxh_dv" name="bhxh_dv" value="{{$model->bhxh_dv}}" />
                    <input type="hidden" id="bhyt_dv" name="bhyt_dv" value="{{$model->bhyt_dv}}" />
                    <input type="hidden" id="bhtn_dv" name="bhtn_dv" value="{{$model->bhtn_dv}}" />
                    <input type="hidden" id="kpcd_dv" name="kpcd_dv" value="{{$model->kpcd_dv}}" />
                    <input type="hidden" id="luongcoban" name="luongcoban" value="{{$model->luongcoban}}" />

                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect_truylinh', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <input type="hidden" id="id" name="id" value="{{$model->id}}" />
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}" />
                    <input type="hidden" id="mabl" name="mabl" value="{{$model->mabl}}" />
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

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Lương cơ bản</label>
                                                        {!!Form::text('luongcoban', null, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Hệ số truy lĩnh </label>
                                                        {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control heso', 'data-mask'=>'fdecimal', 'style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Tổng hệ số </label>
                                                        {!!Form::text('tonghs', null, array('id' => 'tonghs','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Truy lĩnh từ ngày</label>
                                                        <input type="date" name="ngaytu" id="ngaytu" class="form-control ngaythang" value="{{$model->ngaytu}}"/>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Truy lĩnh đến ngày </label>
                                                        <input type="date" name="ngayden" id="ngayden" class="form-control ngaythang" value="{{$model->ngayden}}"/>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Số tháng truy lĩnh</label>
                                                        {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control heso', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Lương hệ số</label>
                                                        {!!Form::text('ttl', null, array('id' => 'ttl','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>


                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Nộp theo lương</label>
                                                        {!!Form::text('tbh', $model->ttbh, array('id' => 'tbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"><b>Lương thực nhận </b></label>
                                                        {!!Form::text('luongtn', null, array('id' => 'luongtn','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
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
                                                Thông tin các loại phụ cấp
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: block;">
                                            <div class="row">
                                                @foreach($model_pc as $pc)
                                                    @if($pc->phanloai == 3)
                                                        {!!Form::hidden($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    @else
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">{{$pc->form}}</label>
                                                                {!!Form::text($pc->mapc, null, array('id' =>$pc->mapc, 'class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

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
                                                Thông tin khoản phải nộp theo lương (nhập số tiền)
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: block;">

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Số tiền BHXH </label>
                                        {!!Form::text('stbhxh', null, array('id' => 'stbhxh','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Số tiền BHYT </label>
                                        {!!Form::text('stbhyt', null, array('id' => 'stbhyt','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Số tiền KPCĐ </label>
                                        {!!Form::text('stkpcd', null, array('id' => 'stkpcd','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Số tiền BHTN </label>
                                        {!!Form::text('stbhtn', null, array('id' => 'stbhtn','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight: bold">Tổng tiền cá nhân nộp bảo hiểm </label>
                                        {!!Form::text('ttbh', null, array('id' => 'ttbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">BHXH đơn vị nộp</label>
                                        {!!Form::text('stbhxh_dv', null, array('id' => 'stbhxh_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">BHYT đơn vị nộp</label>
                                        {!!Form::text('stbhyt_dv', null, array('id' => 'stbhyt_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">KPCĐ đơn vị nộp</label>
                                        {!!Form::text('stkpcd_dv', null, array('id' => 'stkpcd_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">BHTN đơn vị nộp</label>
                                        {!!Form::text('stbhtn_dv', null, array('id' => 'stbhtn_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight: bold">Tổng tiền đơn vị nộp bảo hiểm</label>
                                        {!!Form::text('ttbh_dv', null, array('id' => 'ttbh_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                    </div>
                                </div>
                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>
                    <hr>
                        <div style="text-align: center;">

                            <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                            <a href="{{url('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb)}}" class="btn btn-default"><i class="fa fa-reply mlx"></i> Quay lại</a>
                            <!--a href="{{url('/chuc_nang/bang_luong/maso='.$model->mabl)}}" class="btn btn-default"><i class="fa fa-reply mlx"></i> Quay lại</a-->
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>
    <script>
        $('.heso').change(function() {
            getLuong();
        })

        function getLuong(){
            //getBaoHiem();
            var luong = getdl($('#luongcoban').val());
            var heso = parseFloat($('#hesott').val());
            var thang = parseFloat($('#thangtl').val());
            var ttl = luong * heso * thang;

            var stbhxh = ($('#bhxh').val() * ttl / 100).toFixed(0);
            $('#stbhxh').val(stbhxh);

            var stbhyt = ($('#bhyt').val() * ttl / 100).toFixed(0);
            $('#stbhyt').val(stbhyt);

            var stkpcd = ($('#kpcd').val() * ttl / 100).toFixed(0);
            $('#stkpcd').val(stkpcd);

            var stbhtn = ($('#bhtn').val() * ttl / 100).toFixed(0);
            $('#stbhtn').val(stbhtn);

            var stbhxh_dv = ($('#bhxh_dv').val() * ttl / 100).toFixed(0);
            $('#stbhxh_dv').val(stbhxh_dv);

            var stbhyt_dv = ($('#bhyt_dv').val() * ttl / 100).toFixed(0);
            $('#stbhyt_dv').val(stbhyt_dv);

            var stkpcd_dv = ($('#kpcd_dv').val() * ttl / 100).toFixed(0);
            $('#stkpcd_dv').val(stkpcd_dv);

            var stbhtn_dv = ($('#bhtn_dv').val() * ttl / 100).toFixed(0);
            $('#stbhtn_dv').val(stbhtn_dv);

            var bh = parseFloat(stbhxh) + parseFloat(stbhyt) + parseFloat(stkpcd) + parseFloat(stbhtn);
            var bh_dv = parseFloat(stbhxh_dv) + parseFloat(stbhyt_dv) + parseFloat(stkpcd_dv) + parseFloat(stbhtn_dv);

            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#ttbh_dv').val(bh_dv);

            $('#ttl').val(ttl);
            $('#luongtn').val(ttl - bh);
        }

        $('.ngaythang').change(function() {
            var ngaytu = new Date($('#ngaytu').val());
            var ngayden = new Date($('#ngayden').val());
            var namtu = ngaytu.getFullYear();
            var namden = ngayden.getFullYear();
            var thangtu = ngaytu.getMonth();
            var thangden = ngayden.getMonth();

            var thangtl = thangden - thangtu + (namden - namtu) * 12;
            if(thangtl<0){thangtl = 0;}
            $('#thangtl').val(thangtl);

            getLuong();
            //getBaoHiem();
        })

        $('.baohiem_dv').change(function() {
            var stbhxh_dv = getdl($('#stbhxh_dv').val());
            var stbhyt_dv = getdl($('#stbhyt_dv').val());
            var stkpcd_dv = getdl($('#stkpcd_dv').val());
            var stbhtn_dv = getdl($('#stbhtn_dv').val());
            $('#ttbh_dv').val(stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv);
        })

        $('.baohiem').change(function() {
            var stbhxh_dv = getdl($('#stbhxh').val());
            var stbhyt_dv = getdl($('#stbhyt').val());
            var stkpcd_dv = getdl($('#stkpcd').val());
            var stbhtn_dv = getdl($('#stbhtn').val());
            var ttl = getdl($('#ttl').val());
            var bh = stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv;

            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#luongtn').val(ttl - bh);
        })

    </script>
    @include('includes.script.scripts')
@stop