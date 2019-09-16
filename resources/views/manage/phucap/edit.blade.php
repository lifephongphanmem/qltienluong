@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop


@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>

    @include('includes.script.scripts')
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            //TableManaged.init();
        });
    </script>
@stop

@section('content')
    <h3 class="page-title">
        Thông tin quá trình lương, phụ cấp của cán bộ - {{$model->tencanbo}}
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN DASHBOARD STATS -->
    <div class="row center">
        <div class="col-md-12 center">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box blue">
                <!--div class="portlet-title">
                </div-->
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::model($model,['url'=>$inputs['furl'].'update', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" id="maso" name="maso" value="{{$model->maso}}"/>
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}"/>
                    <input type="hidden" id="macvcq" name="macvcq" value="{{$model->macvcq}}"/>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}"/>
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phân loại chức vụ</label>
                                                    {!!Form::select('maphanloai',getPhanLoaiKiemNhiem(true),null,['id'=>'maphanloai','class'=>'form-control', 'disabled'])!!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Chức vụ</label>
                                                    {!!Form::select('macv',getChucVuCQ(false),$model->macvcq,['id'=>'macv','class'=>'form-control', 'disabled'])!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Từ ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control required'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Đến ngày<span class="require">*</span></label>
                                                    {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control required'))!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Hệ số lương; phụ cấp</label>
                                                {!!Form::select('mapc',$a_pc,null,['id'=>'mapc','class'=>'form-control select2me'])!!}
                                            </div>


                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label class="control-label">Mức được hưởng</label>
                                                    {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control required','data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>


                    </div>
                    <div style="text-align: center; padding-bottom: 15px">
                        <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                        <a href="{{url($inputs['furl'].'danh_sach?canbo='.$model->macanbo)}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                    {!! Form::close() !!}
                    <!-- END FORM-->
                <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.ngaythang').change(function(){
            tinhtoan();
        });

        function validateForm(){
            var validator = $("#create_tttaikhoan").validate({
                rules: {
                    name :"required",
                    tendv :"required"

                },
                messages: {
                    name :"Chưa nhập dữ liệu",
                    tendv :"Chưa nhập dữ liệu"
                }
            });
        }
    </script>

@stop