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
                        DỮ LIỆU TỔNG HỢP CHI TRẢ LƯƠNG THÁNG
                    </div>
                </div>
                {!! Form::model($model, ['url'=>$furl.'store_detail', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                <input type="hidden" id="id" name="id" value="{{$model->id}}" />

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
                                                <label class="control-label">Nguồn kinh phí </label>
                                                {!!Form::select('manguonkp', getNguonKP(), null, array('id' => 'manguonkp','class' => 'form-control', 'disabled'=>'true'))!!}
                                            </div>
                                        </div>
                                        @if($model->mact == null)
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại công tác </label>
                                                    {!!Form::select('macongtac',getNhomCongTac(), null, array('id' => 'macongtac','class' => 'form-control', 'disabled'=>'true'))!!}
                                                </div>
                                            </div>
                                            <input type="hidden" name="mact" id="mact" value="{{$model->mact}}" />
                                        @else
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại công tác </label>
                                                    {!!Form::select('macongtac',getNhomCongTac(), null, array('id' => 'macongtac','class' => 'form-control', 'disabled'=>'true'))!!}
                                                </div>
                                            </div>
                                            <input type="hidden" name="mact" id="mact" value="{{$model->mact}}" />
                                        @endif


                                        <!-- Ẩn / hiện element in form (không mất trường trên form)-->
                                        <div class="col-md-3" {{session('admin')->maphanloai != 'KVXP'?'':'style=display:none'}}>
                                            <div class="form-group" >
                                                <label class="control-label">Lĩnh vực hoạt động </label>
                                                {!!Form::select('linhvuchoatdong',getLinhVucHoatDong() , null, array('id' => 'linhvuchoatdong','class' => 'form-control', 'disabled'=>'true'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Lương cơ bản</label>
                                                {!!Form::text('luongcoban', null, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Lương ngạch bậc </label>
                                                {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Phụ cấp lương </label>
                                                {!!Form::text('hesopc', null, array('id' => 'hesopc','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tổng các khoản phụ cấp</label>
                                                {!!Form::text('tongpc', null, array('id' => 'tongpc','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tổng các khoản nộp theo lương</label>
                                                {!!Form::text('tongbaohiem', $model->ttbh_dv, array('id' => 'tongbaohiem','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Các khoản giảm trừ</label>
                                                {!!Form::text('giaml', null, array('id' => 'giaml','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Chức vụ </label>
                                                {!!Form::text('pccv', null, array('id' => 'pccv','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Vượt khung</label>
                                                {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Kiêm nhiệm </label>
                                                {!!Form::text('pckn', null, array('id' => 'pckn','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Khu vực </label>
                                                {!!Form::text('pckv', null, array('id' => 'pckv','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Công vụ </label>
                                                {!!Form::text('pccovu', null, array('id' => 'pccovu','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Trách nhiệm </label>
                                                {!!Form::text('pctn', null, array('id' => 'pctn','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class=" control-label">Thâm niên nghề </label>
                                                {!!Form::text('pctnn', null, array('id' => 'pctnn','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Cấp ủy </label>
                                                {!!Form::text('pcvk', null, array('id' => 'pcvk','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Đại biểu HĐND </label>
                                                {!!Form::text('pcdbqh', null, array('id' => 'pcdbqh','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Thu hút </label>
                                                {!!Form::text('pcth', null, array('id' => 'pcth','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Ưu đãi </label>
                                                {!!Form::text('pcudn', null, array('id' => 'pcudn','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Đặc biệt </label>
                                                {!!Form::text('pcdbn', null, array('id' => 'pcdbn','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Lưu động </label>
                                                {!!Form::text('pcld', null, array('id' => 'pcld','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Độc hại </label>
                                                {!!Form::text('pcdh', null, array('id' => 'pcdh','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Thâm niên VK </label>
                                                {!!Form::text('pctnvk', null, array('id' => 'pctnvk','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Bồi dưỡng HĐCU </label>
                                                {!!Form::text('pcbdhdcu', null, array('id' => 'pcbdhdcu','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Thâm niên </label>
                                                {!!Form::text('pcthni', null, array('id' => 'pcthni','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Công tác Đảng </label>
                                                {!!Form::text('pcdang', null, array('id' => 'pcdang','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Ưu đãi ND 61</label>
                                                {!!Form::text('pcud61', null, array('id' => 'pcud61','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Phụ cấp khác </label>
                                                {!!Form::text('pck', null, array('id' => 'pck','class' => 'form-control text-right heso', 'data-mask'=>'fdecimal'))!!}
                                            </div>
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
                                    Thông tin các khoản nộp theo lương
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Số tiền BHXH</label>
                                            {!!Form::text('stbhxh_dv', null, array('id' => 'stbhxh_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Số tiền BHYT</label>
                                            {!!Form::text('stbhyt_dv', null, array('id' => 'stbhyt_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Số tiền BHTN</label>
                                            {!!Form::text('stbhtn_dv', null, array('id' => 'stbhtn_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Số tiền KPCĐ </label>
                                            {!!Form::text('stkpcd_dv', null, array('id' => 'stkpcd_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tổng số</label>
                                            {!!Form::text('ttbh_dv', null, array('id' => 'ttbh_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div>

                <div  class="form-actions" style="text-align: center; border-top: 1px solid #eee;">
                    <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                    <a href="{{url($furl.'detail/ma_so='.$model->mathdv)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>
        function baohiem(){
            var stbhxh=getdl($('#stbhxh_dv').val());
            var stbhyt=getdl($('#stbhyt_dv').val());
            var stkpcd=getdl($('#stkpcd_dv').val());
            var stbhtn=getdl($('#stbhtn_dv').val());

            $('#ttbh_dv').val(stbhxh+stbhyt+stkpcd+stbhtn);
            $('#tongbaohiem').val(stbhxh+stbhyt+stkpcd+stbhtn);
        }

        $(function(){
            $('#stbhxh_dv').change(function(){
                baohiem();
            });

            $('#stbhyt_dv').change(function(){
                baohiem();
            });

            $('#stkpcd_dv').change(function(){
                baohiem();
            });

            $('#stbhtn_dv').change(function(){
                baohiem();
            });

            $('.heso').change(function(){
                var tong = 0;
                $('.heso').each(function(){
                    tong += getdl($(this).val());
                })
                $('#tongpc').val(tong);
            });
        });
    </script>

@stop