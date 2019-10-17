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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ</div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <label class="control-label col-md-3" style="text-align: right">Năm ngân sách</label>
                                <div class="col-md-7">
                                    {!! Form::select('namns',getNam(),$inputs['namns'],array('id' => 'namns', 'class' => 'form-control'))!!}
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;In danh sách</button>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                <div class="col-md-7">
                                    {!! Form::select('trangthai',$a_trangthai,$inputs['trangthai'],array('id' => 'trangthai', 'class' => 'form-control'))!!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-md-3" style="text-align: right">Phân loại </label>
                                <div class="col-md-8">
                                    {!! Form::select(
                                    'phanloai',$a_phanloai,$inputs['phanloai'],
                                    array('id' => 'phanloai', 'class' => 'form-control'))
                                    !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Tên đơn vị</th>
                            <th class="text-center">Tên đơn vị tổng hợp dữ liệu</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$value->tendv}}</td>
                                    <td>{{$value->tendvcq}}</td>
                                    <td>
                                        @if ($value->masodv != NULL)
                                            <!--a href="{{url($furl_th.'printf?maso='.$value['masodv'])}}" class="btn btn-default btn-sm" TARGET="_blank"-->
                                            <a href="{{url($furl_th.'tonghopct?namns='.$inputs['namns'].'&madv='.$value['madv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                            <!--button type="button" onclick="inbl('{{$value['masodv']}}')" class="btn btn-default btn-xs mbs"-->
                                            <button type="button" onclick="inbl('{{$value['madv']}}','{{$inputs['namns']}}','{{$value['masodv']}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết</button>

                                            @if($value->tralai)
                                                <button type="button" class="btn btn-default btn-sm" onclick="confirmChuyen('{{$value['masodv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa icon-share-alt"></i>&nbsp;
                                                    Trả lại dữ liệu</button>
                                            @endif
                                        @else
                                            <button class="btn btn-danger btn-xs mbs">
                                                <i class="fa fa-warning"></i>&nbsp; Đơn vị chưa tổng hợp dữ liệu</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl_th.'tralai','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý trả lại số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Lý do trả lại dữ liệu</label>
                        {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>
                    <input type="hidden" name="masodv" id="masodv">
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn blue">Đồng ý</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <div id="inbl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In số liệu chi tiết</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_bl" href="" onclick="insolieu('/chuc_nang/du_toan_luong/huyen/chitietbl')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu chi tiết từng tháng</a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blCR" href="" onclick="insolieuCR('/chuc_nang/du_toan_luong/huyen/chitietblCR')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số liệu tổng hợp các tháng (CR)</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <a id="in_blnl" href="" onclick="innangluongth('/chuc_nang/du_toan_luong/huyen/nangluongth')" style="border-width: 0px;margin-left: 5px" target="_blank">
                                <i class="fa fa-print"></i>&nbsp; In số danh sách cán bộ nâng lương</a>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="mabl_in" name="mabl_in"/>
            <input type="hidden" id="madv_in" name="madv_in"/>
            <input type="hidden" id="namns_in" name="namns_in"/>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-lg modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed" id="tab_cre">
                                <div class="tab-content">
                                    <!-- Thông tin chung -->
                                    <div class="tab-pane active" id="tab_0_cre">
                                        {!! Form::open(['url'=>'chuc_nang/xem_du_lieu/du_toan/danhsach','target'=>'_blank' ,'method'=>'post' , 'class'=>'form-horizontal form-validate']) !!}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-md-3" style="text-align: right">Năm</label>
                                                <div class="col-md-9">
                                                    {!! Form::select('namnds',getNam(),$inputs['namns'],array('id' => 'namnds', 'class' => 'form-control'))!!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label col-md-3" style="text-align: right">Trạng thái </label>
                                                <div class="col-md-9">
                                                    {!! Form::select('trangthaids',$a_trangthai,$inputs['trangthai'],array('id' => 'trangthaids', 'class' => 'form-control'))!!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label col-md-3" style="text-align: right"></label>
                                                <input type="checkbox" name="excel" id = "excel"/>
                                                Xuất dữ liệu ra file excel
                                            </div>
                                            <input type="hidden" id="id_ct" name="id_ct"/>
                                            <input type="hidden" id="mabl" name="mabl"/>
                                        </div>
                                    </div>
                                </div>
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
    </div>
    <script>
        function add(){
            $('#chitiet-modal').modal('show');
        }
        function inbl(madv,namns,mabl){
            $("#mabl_in").val(mabl);
            $("#madv_in").val(madv);
            $("#namns_in").val(namns);
            $('#inbl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }
        function insolieu($url){
            $("#in_bl").attr("href", $url +'?maso='+ $('#mabl_in').val());
        }
        function insolieuCR($url){
            $("#in_blCR").attr("href", $url +'?maso='+ $('#mabl_in').val()+'&madv='+ $('#madv_in').val()+'&namns='+ $('#namns_in').val());
        }
        function innangluongth($url){
            $("#in_blnl").attr("href", $url +'?maso='+ $('#mabl_in').val()+'&madv='+ $('#madv_in').val()+'&namns='+ $('#namns_in').val());
        }
        function confirmChuyen(masodv) {
            document.getElementById("masodv").value = masodv;
        }

        $(function(){
            $('#frm_chuyen :submit').click(function(){
                var chk = true;
                if($('#lydo').val()==''){
                    chk = false;
                }

                //Kết quả
                if ( chk == false){
                    toastr.error('Lý do trả lại không được bỏ trống.','Lỗi!');
                    $("#frm_chuyen").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("#frm_chuyen").unbind('submit').submit();
                }
            });
        });


    </script>

    <script>

        function getLink(){
            var namns = $('#namns').val();
            var trangthai = $('#trangthai').val();
            var phanloai = $('#phanloai').val();
            return '{{$furl_xem}}' + '?namns='+ namns + '&trangthai=' + trangthai+ '&phanloai=' + phanloai;
        }

        $(function(){
            $('#namns').change(function() {
                window.location.href = getLink();
            });

            $('#trangthai').change(function() {
                window.location.href = getLink();
            });
            $('#phanloai').change(function() {
                window.location.href = getLink();
            });
        })
    </script>

@stop