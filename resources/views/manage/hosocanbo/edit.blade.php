<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 7/7/2016
 * Time: 2:42 PM
 */
?>
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

        <!--Gán các giá trị và các ô select box -->
        $('#tenct').val('{{$model->tenct}}').trigger('change');
        $('#lvhd').val('{{$model->lvhd}}').trigger('change');
        $('#lvtd').val('{{$model->lvtd}}').trigger('change');
        $('#sunghiep').val('{{$model->sunghiep}}').trigger('change');
        $('#macvcq').val('{{$model->macvcq}}').trigger('change');
        $('#mapb').val('{{$model->mapb}}').trigger('change');
        $('#nhommau').val('{{$model->nhommau}}').trigger('change');
        $('#macvd').val('{{$model->macvd}}').trigger('change');
        //$('#bac').val('{{$model->bac}}').trigger('change');
        //$('#tennb').val('{{$model->msngbac}}').trigger('change');
    </script>


@stop


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue" id="form_wizard_1">
                <div class="portlet-title">
                    <div class="caption">SỬA THÔNG TIN HỒ SƠ CÁN BỘ</div>
                    <div class="tools hidden-xs">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>

                <div class="portlet-body form" id="form_wizard">
                    {!! Form::model($model, ['url'=>'/nghiep_vu/ho_so/update/'.$model->id, 'method' => 'PATCH', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate', 'enctype'=>'multipart/form-data']) !!}

                    <div class="form-body">
                        <ul class="nav nav-pills nav-justified steps">

                            <li><a href="#tab1" data-toggle="tab" class="step">
                                    <p class="description"><i class="glyphicon glyphicon-user"></i> Thông tin cơ bản</p></a>
                            </li>
                            <li><a href="#tab4" data-toggle="tab" class="step">
                                    <p class="description"> <i class="glyphicon glyphicon-check"></i> Thông tin lương và phụ cấp</p></a>
                            </li>

                            <li><a href="#tab5" data-toggle="tab" class="step">
                                    <p class="description"><i class="glyphicon glyphicon-paperclip"></i> Thông tin khác</p></a>
                            </li>
                        </ul>

                        <div id="bar" class="progress progress-striped" role="progressbar">
                            <div class="progress-bar progress-bar-success">
                            </div>
                        </div>

                        <div class="tab-content">
                            @include('manage.hosocanbo.include.coban')
                            @include('manage.hosocanbo.include.luong')
                            @include('manage.hosocanbo.include.khac')
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="button" name="previous" value="Previous" class="btn btn-info button-previous">
                                    <i class="fa fa-arrow-circle-o-left mrx"></i>Quay lại
                                </button>
                                <button id="btnnext" type="button" name="next" value="Next" class="btn btn-info button-next mlm">
                                    Tiếp theo<i class="fa fa-arrow-circle-o-right mlx"></i></button>
                                <!-- Kiem tra co quyen moi dc sửa, ko thì chỉ là xem -->
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Lưu hồ sơ</button>
                                <a href="{{url('/nghiep_vu/ho_so/danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop
