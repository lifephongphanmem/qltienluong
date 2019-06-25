<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 04/07/2016
 * Time: 3:50 CH
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
                    <div class="caption">
                        <i class="fa fa-list-alt"></i>DANH SÁCH HỒ SƠ CÁN BỘ NÂNG LƯƠNG - {{$tendv}}
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-lg btn-default" data-target="#danhsach-modal" data-toggle="modal">
                            <i class="fa fa-print"></i> In danh sách
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label class="control-label " style="text-align: right">Đơn vị:</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control select2me" name="madv" id="madv" >
                            <option value="ALL">--Chọn tất cả--</option>
                            @foreach($a_donvi as $dv)
                                <option value="{{$dv['madv']}}"  @if($dv['madv'] == $donvi) selected @endif>{{$dv['tendv']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label class="control-label " style="text-align: right">Phân loại:</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::select('nangluong',$a_nangluong,$nangluong, array('id' => 'nangluong', 'class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="sample_3" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Họ và tên</th>
                            <th class="text-center">Mã ngạch</th>
                            <th class="text-center">Hệ số lương</th>
                            <th class="text-center">Ngày nâng lương</th>
                            <th class="text-center">Đơn vị</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;?>
                        @if(isset($m_hs))
                            @foreach($m_hs as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{$value->msngbac}}</td>
                                    <td>{{$value->heso}}</td>
                                    @if($nangluong == 'TNN')
                                        <td>{{getDayVn($value->tnndenngay)}}</td>
                                    @else
                                        <td>{{getDayVn($value->ngayden)}}</td>
                                    @endif
                                    <td>{{$value->tendv}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="danhsach-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'/nghiep_vu/ho_so/danh_sach_nang_luong','method'=>'post' , 'files'=>true, 'id' => 'innangluong','target'=>'_blank']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin danh sách cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Đơn vị:</label>
                                <select class="form-control select2me" name="madv" id="madv" >
                                    <option value="ALL">--Chọn tất cả--</option>
                                    @foreach($a_donvi as $dv)
                                        <option value="{{$dv['madv']}}"  @if($dv['madv'] == $donvi) selected @endif>{{$dv['tendv']}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Phân loại:</label>
                                    {!! Form::select('nangluong',$a_nangluong,$nangluong, array('id' => 'nangluong', 'class' => 'form-control'))!!}
                            </div>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script>

        function getLink(){
            var madv = $('#madv').val();
            var nangluong = $('#nangluong').val();
            return '/nghiep_vu/ho_so/nang_luong_th?madv=' + madv+'&nangluong='+nangluong;
        }

        $(function(){
            $('#madv').change(function() {
                window.location.href = getLink();
            });
            $('#nangluong').change(function() {
                window.location.href = getLink();
            });
        })
    </script>@stop
