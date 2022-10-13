@extends('main')
@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop
@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    @include('includes.script.scripts')
    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}">
    </script>
    <script type="text/javascript" src="{{ url('assets/admin/pages/scripts/form-wizard.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
            FormWizard.init();
        });
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
                {!! Form::model($model, [
                    'url' => $furl . 'update',
                    'method' => 'POST',
                    'files' => true,
                    'id' => 'create-hscb',
                    'class' => 'horizontal-form form-validate',
                ]) !!}
                <input type="hidden" id="masodv" name="masodv" value="{{ $model->masodv }}" />

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
                                            {!! Form::text('namns', null, ['id' => 'namns', 'class' => 'form-control', 'readonly' => 'true']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Căn cứ thông tư, quyết định</label>
                                        {!! Form::select('sohieu', getThongTuQD(false), null, [
                                            'id' => 'sohieu',
                                            'class' => 'form-control',
                                            'disabled' => 'true',
                                        ]) !!}
                                    </div>

                                    <!-- Ẩn / hiện element in form (không mất trường trên form)-->
                                    <div class="col-md-3"
                                        {{ session('admin')->maphanloai != 'KVXP' ? '' : 'style=display:none' }}>
                                        <div class="form-group">
                                            <label class="control-label">Lĩnh vực hoạt động </label>
                                            {!! Form::select('linhvuchoatdong', getLinhVucHoatDong(), null, [
                                                'id' => 'linhvuchoatdong',
                                                'class' => 'form-control',
                                                'disabled' => 'true',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="control-label">Nội dung</label>
                                        {!! Form::textarea('noidung', null, ['id' => 'noidung', 'class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-12">
                                        <table id="sample_3" class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 3%">S</br>T</br>T</th>
                                                    <th class="text-center">Phân loại công tác</th>
                                                    <th class="text-center">Lương</br>phụ cấp</th>
                                                    <th class="text-center">Phí hoạt</br>động</br>ĐBHĐND</th>
                                                    <th class="text-center">Trợ cấp</br>bộ đã</br>nghỉ hưu</th>
                                                    <th class="text-center">Cán bộ</br>không</br>chuyên</br>trách</th>
                                                    <th class="text-center">Phụ cấp</br>trách</br>nhiệm</br>cấp ủy</th>
                                                    <th class="text-center">Bồi dưỡng</br>hoạt động</br>cấp ủy</th>
                                                    <th class="text-center">Các khoản</br>nộp theo</br>lương</th>
                                                    {{-- <th class="text-center">Thao tác</th> --}}
                                                </tr>
                                            </thead>
                    
                                            <tbody>
                                                @foreach ($model_ct as $key => $value)
                                                    <tr class="{{ getTextStatus($value->trangthai) }}">
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $a_ct[$value->mact] }}</td>
                                                        <td class="text-right">{{ dinhdangso($value->luongphucap) }}
                                                        </td>
                                                        <td class="text-right">{{ dinhdangso($value->daibieuhdnd) }}
                                                        </td>
                                                        <td class="text-right">{{ dinhdangso($value->nghihuu) }}</td>
                                                        <td class="text-right">{{ dinhdangso($value->canbokct) }}</td>
                                                        <td class="text-right">{{ dinhdangso($value->uyvien) }}</td>
                                                        <td class="text-right">{{ dinhdangso($value->boiduong) }}</td>
                                                        <td class="text-right">{{ dinhdangso($value->baohiem) }}</td>
                                                        {{-- <td> --}}
                                                        {{-- <button type="button" onclick="indutoan('{{$value->mact}}','{{$value->masodv}}')" class="btn btn-default btn-xs mbs" data-target="#indt-modal" data-toggle="modal"> --}}
                                                        {{-- <i class="fa fa-edit"></i>&nbsp; Sửa</button> --}}
                                                        {{-- </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box blue" id="form_wizard_1">
                            <div class="portlet-title">
                                <div class="caption">
                                    Thông tin nhu cầu kinh phí thực hiện cải cách tiền lương
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                </div>
                            </div>

                            <div class="portlet-body form" id="form_wizard">
                                <div class="form-body" style="padding-left: 10px; padding-right: 10px">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li><a href="#tab1" data-toggle="tab" class="step">
                                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4a</p>
                                            </a>
                                        </li>
                                        <li><a href="#tab3" data-toggle="tab" class="step">
                                            <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4b</p>
                                        </a>
                                    </li>
                                        <li><a href="#tab2" data-toggle="tab" class="step">
                                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2b</p>
                                            </a>
                                        </li>
                                        <li><a href="#tab6" data-toggle="tab" class="step">
                                                <p class="description"><i class="glyphicon glyphicon-check"></i>
                                                    Mẫu 2đ</p>
                                            </a>
                                        </li>
                                        <li><a href="#tab4" data-toggle="tab" class="step">
                                                <p class="description"> <i class="glyphicon glyphicon-check"></i> Mẫu 2e</p>
                                            </a>
                                        </li>
                                    </ul>

                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>

                                    <div class="tab-content">
                                        @include('manage.nguonkinhphi.includes.maukhac')
                                        @include('manage.nguonkinhphi.includes.mau2b')
                                        @include('manage.nguonkinhphi.includes.mau2đ')
                                        @include('manage.nguonkinhphi.includes.mau2e')
                                        @include('manage.nguonkinhphi.includes.mau4b')
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- <div class="row">
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
                                                <label class="control-label">Hỗ trợ chênh lệch cho người có thu nhập
                                                    thấp</label>
                                                {!! Form::text('thunhapthap', null, [
                                                    'id' => 'thunhapthap',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí tăng, giảm do điều chỉnh địa bàn
                                                    (131/QĐ-TTg và 582/QĐ-TTg)</label>
                                                {!! Form::text('diaban', null, [
                                                    'id' => 'diaban',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thực hiện chính sách tinh giản biên
                                                    chế</label>
                                                {!! Form::text('tinhgiam', null, [
                                                    'id' => 'tinhgiam',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thực hiện chính sách nghỉ hưu trước
                                                    tuổi</label>
                                                {!! Form::text('nghihuusom', null, [
                                                    'id' => 'nghihuusom',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí thu hút (giảm do điều chỉnh huyện
                                                    nghèo)</label>
                                                {!! Form::text('kpthuhut', null, [
                                                    'id' => 'kpthuhut',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Kinh phí ưu đãi (giảm do điều chỉnh huyện
                                                    nghèo)</label>
                                                {!! Form::text('kpuudai', null, [
                                                    'id' => 'kpuudai',
                                                    'class' => 'form-control nhucaupc text-right',
                                                    'data-mask' => 'fdecimal',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Tổng số</label>
                                                {!! Form::text('nhucaupc', null, [
                                                    'id' => 'nhucaupc',
                                                    'class' => 'form-control text-right',
                                                    'data-mask' => 'fdecimal',
                                                    'readonly' => 'true',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div> --}}

                {{-- <div class="row"> --}}
                {{-- <div class="col-md-12"> --}}
                {{-- <!-- BEGIN PORTLET--> --}}
                {{-- <div class="portlet box blue"> --}}
                {{-- <div class="portlet-title"> --}}
                {{-- <div class="caption"> --}}
                {{-- Thông tin nguồn kinh phí --}}
                {{-- </div> --}}
                {{-- <div class="tools"> --}}
                {{-- <a href="javascript:;" class="collapse" data-original-title="" title=""></a> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="portlet-body" style="display: block;"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">50% ngân sách thực hiện năm {{$nam-1}}</label> --}}
                {{-- {!!Form::text('thuchien1', null, array('id' => 'thuchien1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">50% ngân sách dự toán năm {{$nam}}</label> --}}
                {{-- {!!Form::text('dutoan', null, array('id' => 'dutoan','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">50% ngân sách thực hiện năm {{$nam-1}}</label> --}}
                {{-- {!!Form::text('dutoan1', null, array('id' => 'dutoan1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Tiết kiệm chi 10% (năm {{$nam}})</label> --}}
                {{-- {!!Form::text('tietkiem', null, array('id' => 'tietkiem','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Tiết kiệm chi 10% (năm {{$nam-1}})</label> --}}
                {{-- {!!Form::text('tietkiem1', null, array('id' => 'tietkiem1','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Tiết kiệm chi 10% (năm {{$nam-2}})</label> --}}
                {{-- {!!Form::text('tietkiem2', null, array('id' => 'tietkiem2','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="row"> --}}
                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Học phí</label> --}}
                {{-- {!!Form::text('hocphi', null, array('id' => 'hocphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Viện phí</label> --}}
                {{-- {!!Form::text('vienphi', null, array('id' => 'vienphi','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Nguồn thu khác</label> --}}
                {{-- {!!Form::text('nguonthu', null, array('id' => 'nguonthu','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="row"> --}}
                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Ngân sách bổ sung năm {{$nam}}</label> --}}
                {{-- {!!Form::text('bosung', null, array('id' => 'bosung','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Nguồn cải cách còn dư  {{$nam-1}}</label> --}}
                {{-- {!!Form::text('caicach', null, array('id' => 'caicach','class' => 'form-control text-right kinhphi', 'data-mask'=>'fdecimal'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="col-md-4"> --}}
                {{-- <div class="form-group"> --}}
                {{-- <label class="control-label">Tổng số</label> --}}
                {{-- {!!Form::text('nguonkp', null, array('id' => 'nguonkp','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true'))!!} --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <!-- END PORTLET--> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                <div class="form-actions" style="text-align: center; border-top: 1px solid #eee;">
                    <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                    <a href="{{ url($furl . 'danh_sach') }}" class="btn btn-default"><i
                            class="fa fa-reply"></i>&nbsp;Quay
                        lại</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>
        $('.kinhphi').change(function() {
            var tong = 0;
            $('.kinhphi').each(function() {
                tong += getdl($(this).val());
            });
            $('#nguonkp').val(tong);
        })

        $('.nhucaukp').change(function() {
            var tong = 0;
            $('.nhucaukp').each(function() {
                tong += getdl($(this).val());
            });
            $('#nhucaukp').val(tong);
        })

        $('.nhucaupc').change(function() {
            var tong = 0;
            $('.nhucaupc').each(function() {
                tong += getdl($(this).val());
            });
            $('#nhucaupc').val(tong);
        })
    </script>

@stop
