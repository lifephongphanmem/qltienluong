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
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <b>DANH SÁCH CÁN BỘ ĐƯỢC HƯỞNG TRUY LĨNH LƯƠNG</b>
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-default btn-xs" data-target="#create-all-modal" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Thêm mới (cả đơn vị)</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="xoadachon()"><i class="fa fa-trash-o"></i>&nbsp;Xóa đã chọn</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-2 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thangct',getThangBC(),$inputs['thang'],array('id' => 'thangct', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('namct',getNam(true),$inputs['nam'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%"><!--input type="checkbox" class="checkall"/-->STT</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Hệ số<br>truy lĩnh</th>
                                <th class="text-center">Thời gian<br>truy lĩnh</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">
                                        @if($value->mabl == null)
                                            <input type="checkbox" onclick="setMaSo(this,'{{$value->maso}}')" name="cb_check"/>
                                        @endif
                                        {{$key+1}}
                                    </td>
                                    <td>{{isset($a_pl[$value->maphanloai])? $a_pl[$value->maphanloai]:'' }}</td>
                                    <td>
                                        @if($value->mabl == null)
                                            <a href="{{url($furl.'create?maso='.$value->maso)}}"><b>{{$value->tencanbo}}</b></a>
                                        @else
                                            <b>{{$value->tencanbo}}</b>
                                        @endif
                                        <p style="margin-top: 5px">Chức vụ: {{$value->tencvcq}}</p>
                                    </td>
                                    <td class="text-center">{{$value->heso}}</td>
                                    <td>{{getDayVn($value->ngaytu) .' - '. getDayVn($value->ngayden)}}</td>
                                    @if($value->mabl == null)
                                        <td class="text-center">Chưa chi trả</td>
                                    @else
                                        <td class="text-center">Đã chi trả<br>{{'('.$value->thang .'-'. $value->nam . ')'}}</td>
                                    @endif
                                    <td>
                                        @if($value->mabl == null)
                                            <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp;Sửa</a>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                                        @else
                                            <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thêm mới thông tin truy lĩnh -->
    {!! Form::open(['url'=>'/nghiep_vu/truy_linh/create','method'=>'get', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin truy lĩnh lương của cán bộ</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Phân loại</label>
                    {!!Form::select('maphanloai',getPhanLoaiTruyLinh(), null, array('id' => 'maphanloai','class' => 'form-control select2me'))!!}

                    <label class="form-control-label">Họ và tên cán bộ</label>
                    <select name="macanbo" id="macanbo" class="form-control select2me">
                        @foreach($a_canbo as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <!--Modal thêm mới thông tin truy lĩnh -->
    {!! Form::open(['url'=>'/nghiep_vu/truy_linh/create_all','method'=>'POST', 'id' => 'create']) !!}
    <div id="create-all-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin truy lĩnh lương của cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label">Phân loại truy lĩnh</label>
                            {!!Form::select('maphanloai',getPhanLoaiTruyLinh(), 'NGAYLV', array('id' => 'maphanloai','class' => 'form-control select2me'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Phân loại công tác</label>
                            <select class="form-control select2me" name="mact[]" id="mact" multiple required="required">
                                <option value="ALL">Tất cả phân loại công tác</option>
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
                        <div class="col-md-6">
                            <label class="form-control-label">Nguồn kinh phí</label>
                            {!!Form::select('manguonkp',getNguonKP(false), null, array('id' => 'manguonkp','class' => 'form-control select2me'))!!}
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Mức truy lĩnh </label>
                            {!!Form::text('luongcoban', session('admin')->luongcoban, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label">Từ ngày</label>
                            {!! Form::input('date','ngaytu',null,array('id' => 'ngaytu', 'class' => 'form-control ngaythang','required'))!!}
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Đến ngày</label>
                            {!! Form::input('date','ngayden',null,array('id' => 'ngayden', 'class' => 'form-control ngaythang','required'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Số tháng truy lĩnh </label>
                            {!!Form::text('thangtl', null, array('id' => 'thangtl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Số ngày truy lĩnh </label>
                            {!!Form::text('ngaytl', null, array('id' => 'ngaytl','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div id="delete-mul-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>'/nghiep_vu/truy_linh/del_mul','method'=>'POST', 'id' => 'frmDelete_mul']) !!}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
                    </div>
                    <input type="hidden" id="maso" name="maso" />
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

    <script>
        var a_maso = [];

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        function setMaSo (obj, maso) {
            if(obj.checked){
                a_maso.push(maso);
            }else{
                var index = a_maso.indexOf(maso);
                if (index !== -1) a_maso.splice(index, 1);
                //removeA(a_maso, maso);
            }
        }

        function xoadachon() {
            //var maso = getSelectedCheckboxes();
            if( a_maso.length == 0) {
                toastr.error('Chưa chọn cán bộ để xóa!','Thông báo lỗi');
            }else {
                //alert(ids);
                $('#delete-mul-modal').modal('show');
                $('#frmDelete_mul').find("[id='maso']").val(a_maso);
            }
        }

        function getSelectedCheckboxes() {
            var maso = '';
            $.each($("input[name='cb_check']:checked"), function(){
                maso += ($(this).attr('id')) + ',';
            });
            return maso;
        }

        function add(){
            $('#maso').val('ADD');
            $('#create-modal').modal('show');
        }

        function getLink(){
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{$furl}}'+'danh_sach?thang='+thang +'&nam='+nam;
        }

        function tinhtoan() {
            //cùng năm => so sánh tháng
            var ngaytu = $('#ngaytu').val();
            var ngayden = $('#ngayden').val();
            if(ngaytu =='' || ngayden ==''){
                $('#thangtl').val(0);
                $('#ngaytl').val(0);
            }else{
                var tungay = new Date(ngaytu);
                var denngay = new Date(ngayden);
                var ngaycong = 22;

                var nam_tu = tungay.getFullYear();
                var ngay_tu = tungay.getDate();
                var thang_tu = tungay.getMonth(); //bắt đầu từ 0

                var nam_den = denngay.getFullYear();
                var ngay_den = denngay.getDate();
                var thang_den = denngay.getMonth(); //bắt đầu từ 0

                var thangtl = 0;
                var ngaytl = 0;
                thangtl = thang_den + 12*(nam_den - nam_tu) - thang_tu + 1;//cộng 1 là do 7->8 = 2 tháng (như đếm số tự nhiên)
                //
                if(ngay_tu >1){//không pải từ đầu tháng => xét số ngày tl
                    thangtl = thangtl - 1;
                    //từ ngày xét đến cuối tháng
                    //lấy ngày cuối cùng của tháng từ
                    var ngay_tucuoi = new Date(nam_tu, thang_tu+1, 0).getDate();
                    if(ngay_tucuoi - ngay_tu >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl = ngay_tucuoi - ngay_tu;
                    }
                }

                var ngay_dencuoi = new Date(nam_den, thang_den+1, 0).getDate();
                if(ngay_den < ngay_dencuoi){
                    thangtl = thangtl - 1;
                    if( ngay_den >= ngaycong){
                        thangtl = thangtl + 1;
                    }else{
                        ngaytl += ngay_den;
                    }
                }
                if(ngaytl > ngaycong){
                    ngaytl = ngaytl - ngaycong;
                    thangtl = thangtl + 1;
                }
                $('#thangtl').val(thangtl);
                $('#ngaytl').val(ngaytl);
            }
        }

        $(function(){
            $('.ngaythang').change(function(){
                tinhtoan();
            });

            $('#thangct').change(function(){
                window.location.href = getLink();
            });

            $('#namct').change(function(){
                window.location.href = getLink();
            });
        })

    </script>

    @include('includes.modal.delete')
    @include('includes.script.scripts')
@stop