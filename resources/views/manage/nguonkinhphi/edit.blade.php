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
                <input type="hidden" id='huyen' name="huyen" value="{{$inputs['huyen']??0}}">
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
                                                // 'disabled' => 'true',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Số biên chế được giao</label>
                                            {!! Form::text('sobiencheduocgiao', null, [
                                                'id' => 'sobiencheduocgiao',
                                                'class' => 'form-control text-right',
                                                'data-mask' => 'fdecimal',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="control-label">Nội dung</label>
                                        {!! Form::textarea('noidung', null, ['id' => 'noidung', 'class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>                                
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                </div>
                @switch($m_thongtu->masobaocao)
                    @case('TT78_2022')
                        @include('manage.nguonkinhphi.TT78.index')
                    @break

                    @default
                        @include('manage.nguonkinhphi.includes.index')
                @endswitch
                <hr>
                <div class="form-actions" style="text-align: center;">
                    <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>
                    {{-- <a href="{{ url($furl . 'danh_sach') }}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay
                        lại</a> --}}
                        <a onclick="history.back()" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay
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
