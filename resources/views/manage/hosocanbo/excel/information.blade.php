@extends('main')

@section('custom-style')
    <link href="{{url('assets/global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/admin/pages/scripts/form-wizard.js')}}"></script>

    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            FormWizard.init();
            TableManaged.init();
        });
    </script>
@stop

@section('content')

    <h3 class="page-title"> </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN DASHBOARD STATS -->
    <div class="row center">
        <div class="col-md-12 center">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        Thông tin nhận danh sách cán bộ<small> từ file Excel</small>
                    </div>
                    <div class="actions">
                        <a class="btn btn-default btn-xs" href="{{url('/data/download/MAU DS CAN BO.xls')}}"><i class="fa fa-download"></i>&nbsp;Tải file Excel mẫu</a>
                    </div>
                </div>
                <div class="portlet-body form">

                    <!-- BEGIN FORM -->
                    {!! Form::open(['url'=>$url.'create_excel', 'method'=>'post' , 'files'=>true, 'id' => 'create_hscb','enctype'=>'multipart/form-data']) !!}
                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <div class="form-body">
                            <!-- Thông tin chung-->
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

                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Mã số công chức/viên chức</label>
                                                            {!!Form::text('macongchuc', 'B', array('id' => 'macongchuc','class' => 'form-control','autofocus' => 'autofocus'))!!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Họ tên cán bộ<span class="require">*</span></label>
                                                            {!!Form::text('tencanbo', 'C', array('id' => 'tencanbo','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Chức vụ</label>
                                                            {!!Form::text('macvcq', 'D', array('id' => 'macvcq','class' => 'form-control'))!!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Mã ngạch bậc<span class="require">*</span></label>
                                                            {!!Form::text('msngbac', 'E', array('id' => 'msngbac','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Hệ số lương<span class="require">*</span></label>
                                                            {!!Form::text('heso', 'F', array('id' => 'heso','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Phần trăm vượt khung<span class="require">*</span></label>
                                                            {!!Form::text('vuotkhung', 'G', array('id' => 'vuotkhung','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->




                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Nhận từ dòng<span class="require">*</span></label>
                                                            {!!Form::text('tudong', '5', array('id' => 'tudong','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Số lượng cán bộ (dòng dữ liệu)<span class="require">*</span></label>
                                                            {!!Form::text('sodong', '100', array('id' => 'sodong','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Sheet lấy dữ liệu<span class="require">*</span></label>
                                                            {!!Form::text('sheet', '1', array('id' => 'sheet','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">File thông tin<span class="require">*</span></label>
                                                            <input id="fexcel" name="fexcel" type="file" class="form-control required" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END PORTLET-->
                                </div>
                            </div>

                            <!-- Các khoản phụ cấp-->
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
                                                    @foreach($a_phucap as $key=>$val)
                                                        @if($a_donvi[$key] == 3)
                                                            {!!Form::hidden($key, null, array('id' =>$key, 'class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                                                        @else
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{$val['infor']}}</label>
                                                                    {!!Form::text($key, $val['col'], array('id' =>$key, 'class' => 'form-control'))!!}
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
                        <div class="form-actions text-center">
                            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Nhận dữ liệu</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Tải lại</button>
                        </div>
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>

    <script>
    $(function() {
        $('#create_hscb :submit').click(function () {
            var str = '';
            var ok = true;

            if (!$('#tencanbo').val()) {
                str += '  - Họ tên cán bộ \n';
                $('#tencanbo').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#msngbac').val()) {
                str += '  - Mã ngạch lương \n';
                $('#msngbac').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#heso').val()) {
                str += '  - Hệ số lương \n';
                $('#heso').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#vuotkhung').val()) {
                str += '  - Phần trăm vượt khung \n';
                $('#vuotkhung').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#tudong').val()) {
                str += '  - Dòng bắt đầu nhận dữ liệu \n';
                $('#tudong').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#sodong').val()) {
                str += '  - Số dòng dữ liệu \n';
                $('#sodong').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#sheet').val()) {
                str += '  - Bảng tính lấy dữ liệu \n';
                $('#sheet').parent().addClass('has-error');
                ok = false;
            }

            if (!$('#fexcel').val()) {
                str += '  - File Excel \n';
                $('#fexcel').parent().addClass('has-error');
                ok = false;
            }

            if (ok == false) {
                alert('Các trường: \n' + str + 'Không được để trống');
                $("form").submit(function (e) {
                    e.preventDefault();
                });
            }
            else {
                $("form").unbind('submit').submit();
            }
        });
    });
    </script>
@stop