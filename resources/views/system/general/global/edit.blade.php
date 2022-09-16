@extends('main')

@section('custom-style')

@stop


@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}">
    </script>
@stop

@section('content')


    <h3 class="page-title">
        Thông tin hệ thống<small> chỉnh sửa</small>
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
                    {!! Form::model($model, [
                        'url' => '/he_thong/quan_tri/he_thong/update',
                        'method' => 'POST',
                        'class' => 'horizontal-form',
                        'id' => 'update_tthethong',
                        'files' => true,
                    ]) !!}

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Tuổi nghỉ hưu của nam - năm</label>
                                    {!! Form::text('tuoinam', null, ['id' => 'tuoinam', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Tuổi nghỉ hưu của nam - tháng</label>
                                    {!! Form::text('thangnam', null, ['id' => 'thangnam', 'class' => 'form-control']) !!}
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Thời gian hết tập sự (tháng)</label>
                                    {!! Form::text('tg_hetts', null, ['id' => 'tg_hetts', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Tuổi nghỉ hưu của nữ - năm</label>
                                    {!! Form::text('tuoinu', null, ['id' => 'tuoinu', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Tuổi nghỉ hưu của nữ - tháng</label>
                                    {!! Form::text('thangnu', null, ['id' => 'thangnu', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Thời gian xét nâng lương (tháng)</label>
                                    {!! Form::text('tg_xetnl', null, ['id' => 'tg_xetnl', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Lương cơ bản</label>
                                    {!! Form::text('luongcb', null, ['id' => 'luongcb', 'class' => 'form-control', 'data-mask' => 'fdecimal']) !!}
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phân loại công tác tuyển thêm</label>
                                    {!! Form::select('mact_tuyenthem', $a_mact, null, ['class' => 'form-control']) !!}
                                </div>
                            </div>                           
                        </div>
                        
                        @if (in_array(session('admin')->level, ['SSA', 'SA']))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Nội dung thông báo</label>
                                        {!! Form::textarea('thongbao', null, ['id' => 'thongbao', 'class' => 'form-control', 'rows' => '2']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">File hướng dẫn sử dụng: </label>
                                        @if (isset($model->ipf1))
                                            <a href="{{ url('/data/huongdan/' . $model->ipf1) }}"
                                                target="_blank">{{ $model->ipf1 }}</a>
                                        @endif
                                        <input name="ipf1" id="ipf1" type="file">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- END FORM-->
                </div>
            </div>
            <div class="row" style="text-align: center">
                <div class="col-md-12">
                    <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Cập
                        nhật</button>
                    <a href="{{ url('/he_thong/quan_tri/he_thong') }}" class="btn btn-default">
                        <i class="fa fa-backward"></i> Quay lại </a>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- END VALIDATION STATES-->
        </div>
    </div>
    <script type="text/javascript">
        function validateForm() {

            var validator = $("#update_tthethong").validate({
                rules: {
                    ten: "required"
                },
                messages: {
                    ten: "Chưa nhập dữ liệu"
                }
            });
        }
    </script>
@stop
