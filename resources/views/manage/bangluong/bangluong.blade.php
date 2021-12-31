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

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Họ tên</th>
                                <th class="text-center">Chức vụ</th>
                                <th class="text-center">Phân loại<br>công tác</th>
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
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{isset($a_cv[$value->macvcq])? $a_cv[$value->macvcq]: ''}}</td>
                                    <td>{{isset($a_ct[$value->mact])? $a_ct[$value->mact]: ''}}</td>
                                    <td>{{$value->msngbac}}</td>
                                    <td class="text-right">{{number_format($value->luongtn)}}</td>
                                    <td>
                                        <a href="{{url($furl.'can_bo?mabl='.$m_bl->mabl.'&maso='.$value->id)}}" class="btn btn-default btn-xs mbs">
                                            <i class="fa fa-edit"></i>&nbsp; Sửa</a>
                                        @if($m_bl->phanloai == 'BANGLUONG')
                                            <button type="button" class="btn btn-xs btn-default" data-target="#giatri-modal" data-toggle="modal"
                                                    onclick="giatri('{{$m_bl->mabl}}','{{$value->id}}','{{$value->tencanbo}}','{{$value->mact}}')">
                                                <i class="fa fa-list-alt"></i> Cập nhật
                                            </button>
                                        @endif
                                        <button type="button" onclick="cfDel('{{$m_bl->mabl}}','{{$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
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

    <!--Modal Delete-->
    <div id="delete-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!!Form::open(['id'=>'frmDelete', 'method'=>'POST', 'url'=>$furl.'del_ct']) !!}
            <input type="hidden" id="id" name="id" value="" />
            <input type="hidden" id="mabl" name="mabl" value="" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>

                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        {!!Form::close()!!}
    </div>

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="giatri-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">Thông tin chung</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_0" data-toggle="tab" aria-expanded="true">
                                        Phân loại công tác </a>
                                </li>
                                <li>
                                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                        Ngày công làm việc </a>
                                </li>
                                <!--li>
                                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                        Sự nghiệp </a>
                                </li>

                                <li>
                                    <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                        Nguồn kinh phí </a>
                                </li-->
                            </ul>
                            <div class="tab-content">
                                <!-- phân loại công tác -->
                                <div class="tab-pane active" id="tab_0">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption"></div>
                                            <div class="tools"></div>
                                        </div>
                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->
                                            {!! Form::open(['url'=>'/chuc_nang/bang_luong/updatect_plct', 'method' => 'POST', 'id' => 'frm_plct', 'class'=>'horizontal-form']) !!}
                                                <div class="form-body">
                                                    <div class="form-horizontal">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="control-label">Phân loại công tác mới</label>
                                                                <select class="form-control select2me" name="mact" id="mact">
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
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-offset-4 col-md-8">
                                                                <input type="checkbox" name="up_hoso" id="up_hoso" checked />
                                                                <label class="control-label" for="up_hoso">Cập nhật hồ sơ cán bộ</label>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="id" name="id"/>
                                                        <input type="hidden" id="mabl" name="mabl"/>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="row text-center">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn default">Hoàn thành</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>

                                <!-- Ngày công lam việc -->
                                <div class="tab-pane" id="tab_1">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption"></div>
                                            <div class="tools"></div>
                                        </div>
                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->
                                            {!! Form::open(['url'=>'/chuc_nang/bang_luong/updatect_ngaycong', 'method' => 'POST', 'id' => 'frm_ngaycong', 'class'=>'horizontal-form']) !!}
                                            <div class="form-body">
                                                <div class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label">Tổng số ngày làm việc</label>
                                                            {!!Form::text('tongngaylv', null, array('id' => 'tongngaylv','class' => 'form-control', 'data-mask'=>'fdecimal','style'=>'font-weight:bold'))!!}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label">Số ngày làm việc</label>
                                                            {!!Form::text('songaylv', null, array('id' => 'songaylv','class' => 'form-control', 'data-mask'=>'fdecimal','style'=>'font-weight:bold'))!!}
                                                        </div>
                                                    </div>

                                                    <input type="hidden" id="id" name="id"/>
                                                    <input type="hidden" id="mabl" name="mabl"/>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn default">Hoàn thành</button>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>

    <script>
        function getLink(){
            var mapb = $('#mapb').val();
            var mabl = '{{$m_bl->mabl}}';
            return '/chuc_nang/bang_luong/bang_luong?mabl='+ mabl +'&mapb=' + mapb;
        }

        function giatri(mabl, id, canbo, mact){
            document.getElementById("hd-inbl").innerHTML="Cập nhật thông tin cán bộ: " + canbo;
            // $('#frm_plct').find("[id^='id']").val(id);
            // $('#frm_plct').find("[id^='mabl']").val(mabl);
            // $('#frm_plct').find("[id^='mact']").val(mact).trigger('change');

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/bang_luong/get_ct',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                    mabl: mabl
                },
                dataType: 'JSON',
                success: function (data) {
                    var form = $('#frm_plct');
                    form.find("[id='id']").val(data.id);
                    form.find("[id='mabl']").val(data.mabl);
                    form.find("[id='mact']").val(data.mact).trigger('change');

                    var form_nc = $('#frm_ngaycong');
                    form_nc.find("[id='songaylv']").val(data.songaylv);
                    form_nc.find("[id='tongngaylv']").val(data.tongngaylv);
                    form_nc.find("[id='id']").val(data.id);
                    form_nc.find("[id='mabl']").val(data.mabl);
                    //alert(data.songaycong)
                },
                error: function (message) {
                    toastr.error(message, 'Lỗi!');
                }
            });

        }

        $(function(){
            $('#mapb').change(function() {
                window.location.href = getLink();
            });

        })

        function cfDel(mabl, id){
            $('#frmDelete').find("[id^='mabl']").val(mabl);
            $('#frmDelete').find("[id^='id']").val(id);
        }

        function subDel(){
            $('#frmDelete').submit();
        }
    </script>
@stop