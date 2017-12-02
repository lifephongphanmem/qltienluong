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
                        THÔNG TIN CHI TIẾT LƯƠNG CỦA CÁN BỘ {{$model->tencanbo}}
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

                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect/'.$model->id, 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
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
                                                        <label class="control-label">Mã ngạch </label>
                                                        {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','readonly'=>'true'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Tên ngạch bậc </label>
                                                        {!!Form::text('tennb', null, array('id' => 'tennb','class' => 'form-control','readonly'=>'true'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Tổng hệ số </label>
                                                        {!!Form::text('tonghs', null, array('id' => 'tonghs','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
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
                                                        <label class="control-label">Giảm trừ lương </label>
                                                        {!!Form::text('giaml', null, array('id' => 'giaml','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Bảo hiểm chi trả </label>
                                                        {!!Form::text('bhct', null, array('id' => 'bhct','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Nộp theo lương</label>
                                                        {!!Form::text('tbh', $model->ttbh, array('id' => 'tbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
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
                                                Thông tin các loại hệ số
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: block;">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số lương </label>
                                                        {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số v.khung </label>
                                                        {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số phụ cấp</label>
                                                        {!!Form::text('hesopc', null, array('id' => 'hesopc','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Hệ số truy lĩnh</label>
                                                        {!!Form::text('hesott', null, array('id' => 'hesott','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
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
                                                Thông tin các loại phụ cấp (nhập hệ số hoặc số tiền)
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="display: block;">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Chức vụ </label>
                                                        {!!Form::text('pccv', null, array('id' => 'pccv','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Lâu năm </label>
                                                        {!!Form::text('pcthni', null, array('id' => 'pcthni','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Kiêm nhiệm </label>
                                                        {!!Form::text('pckn', null, array('id' => 'pckn','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Trách nhiệm </label>
                                                        {!!Form::text('pctn', null, array('id' => 'pctn','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Công vụ </label>
                                                        {!!Form::text('pccovu', null, array('id' => 'pccovu','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Thâm niên VK </label>
                                                        {!!Form::text('pctnvk', null, array('id' => 'pctnvk','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Thâm niên nghề </label>
                                                        {!!Form::text('pctnn', null, array('id' => 'pctnn','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Khu vực </label>
                                                        {!!Form::text('pckv', null, array('id' => 'pckv','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Thu hút </label>
                                                        {!!Form::text('pcth', null, array('id' => 'pcth','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Ưu đãi </label>
                                                        {!!Form::text('pcudn', null, array('id' => 'pcudn','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Đặc biệt </label>
                                                        {!!Form::text('pcdbn', null, array('id' => 'pcdbn','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Lưu động </label>
                                                        {!!Form::text('pcld', null, array('id' => 'pcld','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Độc hại </label>
                                                        {!!Form::text('pcdh', null, array('id' => 'pcdh','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Đại biểu HĐND</label>
                                                        {!!Form::text('pcdbqh', null, array('id' => 'pcdbqh','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Cấp ủy viên</label>
                                                        {!!Form::text('pcvk', null, array('id' => 'pcvk','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Bồi dưỡng HĐCU </label>
                                                        {!!Form::text('pcbdhdcu', null, array('id' => 'pcbdhdcu','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Công tác Đảng </label>
                                                        {!!Form::text('pcdang', null, array('id' => 'pcdang','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Phân loại xã </label>
                                                            {!!Form::text('pclt', null, array('id' => 'pclt','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Đắt đỏ </label>
                                                        {!!Form::text('pcdd', null, array('id' => 'pcdd','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Phụ cấp khác </label>
                                                        {!!Form::text('pck', null, array('id' => 'pck','class' => 'form-control heso', 'data-mask'=>'fdecimal'))!!}
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

                            <a href="{{url('/chuc_nang/bang_luong/maso='.$model->mabl)}}" class="btn btn-default"><i class="fa fa-reply mlx"></i> Quay lại</a>
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>
    <script>

        function tonghs() {
            var hs = 0;
            $('.heso').each(function () {
                if(getdl($(this).val()) < 500){
                    hs += getdl($(this).val());
                }
            });
            $('#tonghs').val(parseFloat(hs));
        }

        function tongtl(){
            var hs=$('#tonghs').val();
            var tpc=0;
            $('.heso').each(function () {
                if(getdl($(this).val()) > 500){
                    tpc += getdl($(this).val());
                }
            });
            var luong = $('#luongcoban').val();
            return (hs*luong + tpc);
        }

        function baohiem(){
            //chạy khi hệ số thay đổi
            var heso = getdl($('#heso').val()) + getdl($('#vuotkhung').val()) + getdl($('#pccv').val());
            var luong = $('#luongcoban').val();
            var tienbh = heso * luong;

            var stbhxh= ($('#bhxh').val() * tienbh /100).toFixed(0);
            $('#stbhxh').val(stbhxh);

            var stbhyt=($('#bhyt').val() * tienbh /100).toFixed(0);
            $('#stbhyt').val(stbhyt);

            var stkpcd=($('#kpcd').val() * tienbh /100).toFixed(0);
            $('#stkpcd').val(stkpcd);

            var stbhtn=($('#bhtn').val() * tienbh /100).toFixed(0);
            $('#stbhtn').val(stbhtn);

            var stbhxh_dv=($('#bhxh_dv').val() * tienbh /100).toFixed(0);
            $('#stbhxh_dv').val(stbhxh_dv);
            var stbhyt_dv=($('#bhyt_dv').val() * tienbh /100).toFixed(0);
            $('#stbhyt_dv').val(stbhyt_dv);
            var stkpcd_dv=($('#kpcd_dv').val() * tienbh /100).toFixed(0);
            $('#stkpcd_dv').val(stkpcd_dv);
            var stbhtn_dv=($('#bhtn_dv').val() * tienbh /100).toFixed(0);
            $('#stbhtn_dv').val(stbhtn_dv);

            $('#ttbh_dv').val(parseFloat(stbhxh_dv) + parseFloat(stbhyt_dv) + parseFloat(stkpcd_dv) + parseFloat(stbhtn_dv));

            return parseFloat(stbhxh) + parseFloat(stbhyt) + parseFloat(stkpcd) + parseFloat(stbhtn);
        }



        function giamtru(){
            var giaml=getdl($('#giaml').val());
            var bhct=getdl($('#bhct').val());
            return bhct-giaml;
        }

        function luongtn() {
            var ttl = parseFloat(tongtl().toFixed(0));
            var bh = baohiem();
            var gt = giamtru();
            $('#ttl').val(ttl);
            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#luongtn').val(ttl + gt - bh);
        }
        $('.heso').change(function(){
            tonghs();
            luongtn();
        })

        $('.tienluong').change(function(){
            luongtn();
        })

        $('.baohiem_dv').change(function(){
            var stbhxh_dv=getdl($('#stbhxh_dv').val());
            var stbhyt_dv=getdl($('#stbhyt_dv').val());
            var stkpcd_dv=getdl($('#stkpcd_dv').val());
            var stbhtn_dv=getdl($('#stbhtn_dv').val());
            $('#ttbh_dv').val(stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv);
        })

        $('.baohiem').change(function(){
            var stbhxh_dv=getdl($('#stbhxh').val());
            var stbhyt_dv=getdl($('#stbhyt').val());
            var stkpcd_dv=getdl($('#stkpcd').val());
            var stbhtn_dv=getdl($('#stbhtn').val());
            var ttl = getdl($('#ttl').val());
            var bh = stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv;
            var gt = giamtru();
            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#luongtn').val(ttl + gt - bh);
        })

    </script>
    @include('includes.script.scripts')
@stop