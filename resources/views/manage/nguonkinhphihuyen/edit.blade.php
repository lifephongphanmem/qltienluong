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

    </script>
    @include('includes.script.scripts')
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue" id="form_wizard_1">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        THÊM MỚI THÔNG TIN NGUỒN
                    </div>
                </div>

                <div class="portlet-body form" id="form_wizard">
                    {!! Form::model($model, ['url'=>$furl.'update', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <div class="form-body">
                        <ul class="nav nav-pills nav-justified steps">
                            <li><a href="#tab1" data-toggle="tab" class="step">
                                    <p class="description"><i class="glyphicon glyphicon-list"></i> Thông tin cơ bản</p></a>
                            </li>
                            <li><a href="#tab6" data-toggle="tab" class="step">
                                    <p class="description"><i class="glyphicon glyphicon-th"></i>Đơn vị tự đảm bảo</p></a>
                            </li>
                        </ul>

                        <div id="bar" class="progress progress-striped" role="progressbar">
                            <div class="progress-bar progress-bar-success">
                            </div>
                        </div>

                        <div class="tab-content">
                            @include('manage.nguonkinhphihuyen.include.coban')
                            @include('manage.nguonkinhphihuyen.include.tudambao')
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

                                <button type="submit" class="btn btn-success">Lưu dữ liệu</button>

                                <a href="{{url('/nguon_kinh_phi/huyen/danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $("#sohieu").change(function(){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/nguon_kinh_phi/get_thongtu',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        sohieu: $("#sohieu").val()
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $("#namns").val(data.namdt);
                    }
                });
            });

            $('#create_nkp :submit').click(function(){
                var str = '';
                var ok = true;

                if(!$('#sohieu').val()){
                    str += '  - Số hiệu thông tư, quyết định \n';
                    $('#sohieu').parent().addClass('has-error');
                    ok = false;
                }

                //Kết quả
                if ( ok == false){
                    alert('Các trường: \n' + str + 'Không được để trống');
                    $("form").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("form").unbind('submit').submit();
                }
            });
        });
    </script>
@stop
