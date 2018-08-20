<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
        ?>
@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">TIÊU CHUẨN TÌM KIẾM CHI TRẢ LƯƠNG</div>
                    <div class="actions"></div>
                </div>
                    <div class="portlet-body" style="min-height: 380px">
                        {!! Form::open(['url'=>'/tra_cuu/chi_luong/ket_qua','method'=>'POST','id' => 'create-hscb','class'=>'horizontal-form']) !!}
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tháng</label>

                                        <div class="col-sm-8 controls">
                                            {!! Form::select(
                                            'thang',
                                            array(
                                            '01' => '01',
                                            '02' => '02',
                                            '03' => '03',
                                            '04' => '04',
                                            '05' => '05',
                                            '06' => '06',
                                            '07' => '07',
                                            '08' => '08',
                                            '09' => '09',
                                            '10' => '10',
                                            '11' => '11',
                                            '12' => '12',
                                            ),null,
                                            array('id' => 'thang', 'class' => 'form-control'))
                                            !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Năm</label>

                                        <div class="col-sm-8 controls">
                                            {!! Form::select(
                                            'nam',
                                            array(
                                            '2015' => '2015',
                                            '2016' => '2016',
                                            '2017' => '2017'
                                            ),null,
                                            array('id' => 'nam', 'class' => 'form-control'))
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Khu vực, địa bàn<span class="require">*</span></label>
                                        <div class="col-md-8">
                                            <select id="madv" name="madv" class="form-control select2me">
                                                <option value="">Tất cả các đơn vị</option>
                                                @if(isset($model_dvbc))
                                                    @foreach($model_dvbc as $dv)
                                                        <option value="{{$dv->madvbc}}">{{$dv->tendvbc}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Đơn vị<span class="require">*</span></label>
                                        <div class="col-md-8">
                                            <select id="madv" name="madv" class="form-control">
                                                <option value="">Tất cả các đơn vị</option>
                                                @if(isset($model_dv))
                                                    @foreach($model_dv as $dv)
                                                        <option value="{{$dv->madv}}">{{$dv->tendv}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Mã ngạch </label>

                                        <div class="col-sm-8">
                                            <select class="form-control select2me" name="tennb" id="tennb">
                                            @foreach($m_plnb as $plnb)
                                                <optgroup label="{{$plnb->plnb}}">
                                                    <?php
                                                    $mode_ct=$m_pln->where('plnb',$plnb->plnb);
                                                    ?>
                                                    @foreach($mode_ct as $ct)
                                                        <option value="{{$ct->msngbac}}">{{$ct->tennb}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Phụ cấp </label>

                                        <div class="col-sm-8">
                                            <select name="mapc" id="mapc" class="form-control">
                                                <option value="">--Chọn phụ cấp--</option>
                                                @if(isset($m_pc))
                                                    @foreach($m_pc as $pc)
                                                        <option data-number="{{$pc['hesopc']}}" value="{{$pc['mapc']}}">{{$pc['tenpc']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="border-top: 1px solid #e5e5e5;padding-top: 10px">
                            <button type="reset" class="btn btn-info"><i class="fa fa-refresh"></i>&nbsp;Tạo mới</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Tra cứu</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
    @include('includes.modal.delete')
@stop