@extends('main')
@section('custom-script')
    @include('includes.script.scripts')
    <script>
        $('#tennb').val('{{$model->msngbac}}').trigger('change');
        $('#bac').val('{{$model->bac}}').trigger('change');
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        CHI TIẾT NHU CẦU KINH PHÍ
                    </div>
                </div>
                {!! Form::model($model, ['url'=>$furl.'update', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                <input type="hidden" id="masodv" name="masodv" value="{{$model->masodv}}" />

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
                                            <label class="control-label">Năm ngân sách</label>
                                            {!!Form::text('namns', null, array('id' => 'namns','class' => 'form-control', 'readonly'=>'true'))!!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Căn cứ thông tư, quyết định</label>
                                        {!!Form::select('sohieu',getThongTuQD(false), null, array('id' => 'sohieu','class' => 'form-control','disabled'=>'true'))!!}
                                    </div>

                                    <!-- Ẩn / hiện element in form (không mất trường trên form)-->
                                    <div class="col-md-3" {{session('admin')->maphanloai != 'KVXP'?'':'style=display:none'}}>
                                        <div class="form-group" >
                                            <label class="control-label">Lĩnh vực hoạt động </label>
                                            {!!Form::select('linhvuchoatdong',getLinhVucHoatDong() , null, array('id' => 'linhvuchoatdong','class' => 'form-control', 'disabled'=>'true'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="control-label">Nội dung</label>
                                        {!!Form::textarea('noidung', null, array('id' => 'noidung','class' => 'form-control','rows'=>'3'))!!}
                                    </div>
                                </div>
                            </div>
                        <!-- END PORTLET-->
                    </div>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    Thông tin nhu cầu kinh phí thức hiện cải cách tiền lương
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Tổng nhu cầu kinh phí năm {{$nam-2}}</label>
                                                {!!Form::text('tongnhucau2', null, array('id' => 'tongnhucau2','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Tổng nhu cầu kinh phí năm {{$nam-1}}</label>
                                                {!!Form::text('tongnhucau1', null, array('id' => 'tongnhucau1','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Lương, phụ cấp</label>
                                                {!!Form::text('luongphucap', null, array('id' => 'luongphucap','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Phí hoạt động ĐBHĐND</label>
                                                {!!Form::text('daibieuhdnd', null, array('id' => 'daibieuhdnd','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Trợ cấp bộ đã nghỉ hưu</label>
                                                {!!Form::text('nghihuu', null, array('id' => 'nghihuu','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Cán bộ không chuyên trách</label>
                                                {!!Form::text('canbokct', null, array('id' => 'canbokct','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Phụ cấp trách nhiệm cấp ủy</label>
                                                {!!Form::text('uyvien', null, array('id' => 'uyvien','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class=" control-label">Bồi dưỡng hoạt động cấp ủy</label>
                                                {!!Form::text('boiduong', null, array('id' => 'boiduong','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class=" control-label">Các khoản nộp theo lương</label>
                                                {!!Form::text('baohiem', null, array('id' => 'baohiem','class' => 'form-control nhucaukp text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tổng số</label>
                                                {!!Form::text('nhucaukp', null, array('id' => 'nhucaukp','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
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
                                    Thông tin nhu cầu thực hiện một số loại phụ cấp, trợ cấp
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Hỗ trợ chênh lệch cho người có thu nhập thấp</label>
                                                {!!Form::text('thunhapthap', null, array('id' => 'thunhapthap','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí tăng, giảm do điều chỉnh địa bàn (131/QĐ-TTg và 582/QĐ-TTg)</label>
                                                {!!Form::text('diaban', null, array('id' => 'diaban','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thực hiện chính sách tinh giản biên chế</label>
                                                {!!Form::text('tinhgiam', null, array('id' => 'tinhgiam','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thực hiện chính sách nghỉ hưu trước tuổi</label>
                                                {!!Form::text('nghihuusom', null, array('id' => 'nghihuusom','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thu hút (giảm do điều chỉnh huyện nghèo)</label>
                                                {!!Form::text('kpthuhut', null, array('id' => 'kpthuhut','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí ưu đãi (giảm do điều chỉnh huyện nghèo)</label>
                                                {!!Form::text('kpuudai', null, array('id' => 'kpuudai','class' => 'form-control nhucaupc text-right', 'data-mask'=>'fdecimal'))!!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Tổng số</label>
                                                {!!Form::text('nhucaupc', null, array('id' => 'nhucaupc','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
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
                                    Thông tin nguồn kinh phí
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>
                            <div class="portlet-body" style="display: block;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% ngân sách thực hiện năm {{$nam-1}}</label>
                                            {!!Form::text('thuchien1', null, array('id' => 'thuchien1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% ngân sách dự toán năm {{$nam}}</label>
                                            {!!Form::text('dutoan', null, array('id' => 'dutoan','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">50% ngân sách thực hiện năm {{$nam-1}}</label>
                                            {!!Form::text('dutoan1', null, array('id' => 'dutoan1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tiết kiệm chi 10% (năm {{$nam}})</label>
                                            {!!Form::text('tietkiem', null, array('id' => 'tietkiem','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tiết kiệm chi 10% (năm {{$nam-1}})</label>
                                            {!!Form::text('tietkiem1', null, array('id' => 'tietkiem1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tiết kiệm chi 10% (năm {{$nam-2}})</label>
                                            {!!Form::text('tietkiem2', null, array('id' => 'tietkiem2','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Học phí</label>
                                            {!!Form::text('hocphi', null, array('id' => 'hocphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Viện phí</label>
                                            {!!Form::text('vienphi', null, array('id' => 'vienphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn thu khác</label>
                                            {!!Form::text('nguonthu', null, array('id' => 'nguonthu','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Ngân sách bổ sung năm {{$nam}}</label>
                                            {!!Form::text('bosung', null, array('id' => 'bosung','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nguồn cải cách còn dư  {{$nam-1}}</label>
                                            {!!Form::text('caicach', null, array('id' => 'caicach','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Tổng số</label>
                                            {!!Form::text('nguonkp', null, array('id' => 'nguonkp','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!}
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
                    <a href="{{url($furl.'danh_sach')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>

        $('.kinhphi').change(function(){
            var tong = 0;
            $('.kinhphi').each(function () {
                tong += getdl($(this).val());
            });
            $('#nguonkp').val(tong);
        })

        $('.nhucaukp').change(function(){
            var tong = 0;
            $('.nhucaukp').each(function () {
                tong += getdl($(this).val());
            });
            $('#nhucaukp').val(tong);
        })

        $('.nhucaupc').change(function(){
            var tong = 0;
            $('.nhucaupc').each(function () {
                tong += getdl($(this).val());
            });
            $('#nhucaupc').val(tong);
        })


    </script>

@stop