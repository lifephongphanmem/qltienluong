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
    @include('includes.script.scripts')
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
                    <div class="caption">DANH SÁCH BẢNG ĐĂNG KÝ LƯƠNG CỦA ĐƠN VỊ</div>
                    <div class="actions">
                        <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-2 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thangct',getThang(),$inputs['thang'],array('id' => 'thangct', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('namct',getNam(),$inputs['nam'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
                                <th class="text-center">Nội dung bảng lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <?php $i=1;?>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">{{$value->thang.'/'.$value->nam}}</td>
                                        <td>{{$value->noidung}}</td>
                                        <td>
                                            <button type="button" onclick="edit('{{$value->mabl}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                            <a href="{{url($furl.'maso='.$value->mabl)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>

                                            <button type="button" onclick="inbl('{{$value->mabl}}','{{$value->thang}}','{{$value->nam}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-print"></i>&nbsp; In</button>

                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal thông tin chi tiết -->

    {!! Form::open(['url'=>$furl.'store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin bảng lương cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label"> Nội dung</label>
                            {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Mức lương cơ bản</label>
                            {!!Form::text('luongcoban', $luongcb, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Ngày lập bảng lương</label>
                            <input type="date" name="ngaylap" id="ngaylap" class="form-control" value="{{date('Y-m-d')}}"/>

                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Người lập bảng lương</label>
                            {!!Form::text('nguoilap', session('admin')->nguoilapbieu, array('id' => 'nguoilap','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <input type="hidden" id="thang" name="thang" value="{{$inputs['thang']}}"/>
                    <input type="hidden" id="nam" name="nam" value="{{$inputs['nam']}}"/>
                    <input type="hidden" id="phantramhuong" name="phantramhuong" value="100"/>
                    <input type="hidden" id="id_ct" name="id_ct"/>
                    <input type="hidden" id="mabl" name="mabl"/>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="inbl-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">In bảng lương</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="inblmtt107('{{$furl.'mautt107'}}')" class="btn btn-default btn-xs mbs"
                                    data-target="#mautt107-modal" data-toggle="modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD hệ số phụ cấp hiển thị số tiền">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107/2017/TT-BTC)</button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="inblm1()" class="btn btn-default btn-xs mbs"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT185/2010/TT-BTC)</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="button" style="border-width: 0px" onclick="inblmtt107('{{$furl.'mautt107_m2'}}')" class="btn btn-default btn-xs mbs"
                                    data-target="#mautt107-modal" data-toggle="modal"
                                    title="Bảng lương của cán bộ theo mẫu C02-HD hệ số phụ cấp hiển thị số tiền">
                                <i class="fa fa-print"></i>&nbsp; Bảng lương mẫu C02-HD (TT107) - mẫu 2</button>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="nam_in" name="nam_in"/>
                <input type="hidden" id="thang_in" name="thang_in"/>
                <input type="hidden" id="mabl_in" name="mabl_in"/>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            </div>
        </div>
    </div>

    <!--Mẫu 1 -->
    {!! Form::open(['url'=>(isset($furl)?$furl : '').'mau01','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mau1']) !!}
    <div id="mau1-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Khối/Tổ công tác</label>
                            <select name="mapb_mau1" id="mapb_mau1" class="form-control select2me">
                                @foreach(getPhongBan(true) as $key=>$val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Chức vụ</label>
                            {!!Form::select('macvcq_mau1',getChucVuCQ(true), null, array('id' => 'macvcq_mau1','class' => 'form-control select2me'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact_mau1" id="mact_mau1">
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Cỡ chữ</label>
                            {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                        </div>
                    </div>
                </div>

                <input type="hidden" id="mabl_mau1" name="mabl_mau1"/>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBC1()">Đồng ý</button>
                <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC1_excel()">Xuất Excel</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!--Mẫu TT107 -->
    {!! Form::open(['url'=>'','method'=>'post' ,'target'=>'_blank', 'files'=>true, 'id' => 'printf_mautt107']) !!}
    <div id="mautt107-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="header-inbl" class="modal-title">In bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Khối/Tổ công tác</label>
                            <select name="mapb" id="mapb" class="form-control select2me">
                                @foreach(getPhongBan(true) as $key=>$val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Chức vụ</label>
                            {!!Form::select('macvcq',getChucVuCQ(true), null, array('id' => 'macvcq','class' => 'form-control select2me'))!!}
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact" id="mact">
                                <option value="">-- Tất cả các phân loại công tác --</option>
                                @foreach($model_nhomct as $kieuct)
                                    <optgroup label="{{$kieuct->tencongtac}}">
                                        <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="control-label">Cỡ chữ</label>
                            {!!Form::select('cochu',getCoChu(), 10, array('id' => 'cochu','class' => 'form-control select2me'))!!}
                        </div>
                    </div>
                </div>

                <input type="hidden" id="mabl_mautt107" name="mabl_mautt107"/>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" data-dismiss="modal" class="btn btn-success" onclick="ClickBCtt107()">Đồng ý</button>
                <button type="submit" data-dismiss="modal" class="btn btn-primary" onclick="ClickBC2_excel()">Xuất Excel</button>

            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function add(){
            $('#noidung').val('');
            $('#mabl').val('');
            $('#id_ct').val(0);
            $('#chitiet-modal').modal('show');
        }
        function getLink(){
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{$furl}}'+'danh_sach?thang='+thang +'&nam='+nam;
        }
        $(function(){
            $('#thangct').change(function(){
                window.location.href = getLink();
            });

            $('#namct').change(function(){
                window.location.href = getLink();
            });
        })


        function edit(mabl){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mabl: mabl
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#noidung').val(data.noidung);
                    $('#mabl').val(data.mabl);
                    $('#ngaylap').val(data.ngaylap);
                    $('#nguoilap').val(data.nguoilap);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#chitiet-modal').modal('show');

        }

        function inbl(mabl,thang,nam){
            var url = '{{$furl}}';
            document.getElementById("hd-inbl").innerHTML="In bảng lương tháng " + thang + ' năm ' + nam;
            $("#mabl_in").val(mabl);
            $("#thang_in").val(thang);
            $("#nam_in").val(nam);
            $('#inbl-modal').modal('show');
            //$('#inbl-modal').modal('hide');
        }

        function inblm1(){
            $('#mabl_mau1').val($('#mabl_in').val());
            $('#mau1-modal').modal('show');
        }
        function inblmtt107(url){
            $('#printf_mautt107').attr('action', url);
            $('#mabl_mautt107').val($('#mabl_in').val());
            //$('#mautt107-modal').modal('show');
        }

        $(function(){
            $('#printf_mau1 :submit').click(function(){
                $('#mau1-modal').modal('hide');
            });

            $('#printf_mautt107 :submit').click(function(){
                $('#mautt107-modal').modal('hide');
            });
        });

        function ClickBC1() {
            var url = '{{(isset($furl)?$furl : '').'mau01'}}'
            $('#printf_mau1').attr('action', url);
            $('#printf_mau1').submit();
        }

        function ClickBCtt107() {
            $('#printf_mautt107').submit();

        }
    </script>
    @include('includes.modal.delete')
@stop