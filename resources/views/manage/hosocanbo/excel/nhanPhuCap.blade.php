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
                        Thông tin nhận phụ cấp ưu đãi ngành cho cán bộ từ file Excel
                    </div>
                    
                </div>
                <div class="portlet-body form">

                    <!-- BEGIN FORM -->
                    {!! Form::open(['url'=>$url.'phucap_excel', 'method'=>'post' , 'files'=>true, 'id' => 'create_hscb','enctype'=>'multipart/form-data']) !!}
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
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Mã cán bộ<span class="require">*</span></label>
                                                            {!!Form::text('macanbo', 'B', array('id' => 'macanbo','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>

                                                  
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Phụ cấp Ưu đãi ngành</label>
                                                            {!!Form::text('pcudn', 'G', array('id' => 'pcudn','class' => 'form-control required'))!!}
                                                        </div>
                                                    </div>                                                  

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Nhận từ dòng<span class="require">*</span></label>
                                                            {!!Form::text('tudong', '7', array('id' => 'tudong','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Số lượng cán bộ</label>
                                                            {!!Form::text('sodong', '1000', array('id' => 'sodong','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-12">
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

            if (!$('#macanbo').val()) {
                str += '  - Mã tên cán bộ \n';
                $('#macanbo').parent().addClass('has-error');
                ok = false;
            }            

            if (!$('#fexcel').val()) {
                str += '  - File Excel \n';
                $('#fexcel').parent().addClass('has-error');
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