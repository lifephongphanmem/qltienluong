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
                    <div class="caption">
                        CHI TIẾT BẢNG LƯƠNG THÁNG {{$m_bl->thang}} NĂM {{$m_bl->nam}}
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-1 col-md-2" style="text-align: right">Khối(tổ) công tác </label>
                            <div class="col-md-6">
                                {!! Form::select('mapb',getPhongBan(),$inputs['mapb'],array('id' => 'mapb', 'class' => 'form-control'))!!}
                            </div>

                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Mã công chức</th>
                                <th class="text-center">Họ tên</th>
                                <th class="text-center">Chức vụ</th>
                                <th class="text-center">Mã ngạch</th>
                                <th class="text-center">Thực lĩnh</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->macongchuc}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{isset($a_cv[$value->macvcq])? $a_cv[$value->macvcq]: ''}}</td>
                                    <td>{{$value->msngbac}}</td>
                                    <td class="text-right">{{number_format($value->luongtn)}}</td>
                                    <td>
                                        <a href="{{url($furl.'?maso='.$value->id)}}" class="btn btn-info btn-xs mbs">
                                            <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                        <button type="button" onclick="cfDel('{{$furl.'del_ct/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                            <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-offset-5 col-md-8">
                        <a href="{{url($furl.'chi_tra?thang='.$m_bl->thang.'&nam='. $m_bl->nam)}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getLink(){
            var mapb = $('#mapb').val();
            var mabl = '{{$m_bl->mabl}}';
            return '/chuc_nang/bang_luong/bang_luong?mabl='+ mabl +'&mapb=' + mapb;
        }

        $(function(){
            $('#mapb').change(function() {
                window.location.href = getLink();
            });

        })


    </script>

    @include('includes.modal.delete')
@stop