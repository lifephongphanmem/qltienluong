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
                    <div class="caption text-uppercase">
                        THÔNG TIN CHI TIẾT LƯƠNG CỦA CÁN BỘ: {{$model->tencanbo}}
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    <input type="hidden" id="bhxh" name="bhxh" value="{{$model->bhxh}}" />
                    <input type="hidden" id="bhyt" name="bhyt" value="{{$model->bhyt}}" />
                    <input type="hidden" id="bhtn" name="bhtn" value="{{$model->bhtn}}" />
                    <input type="hidden" id="kpcd" name="kpcd" value="{{$model->kpcd}}" />
                    <input type="hidden" id="bhxh_dv" name="bhxh_dv" value="{{$model->bhxh_dv}}" />
                    <input type="hidden" id="bhyt_dv" name="bhyt_dv" value="{{$model->bhyt_dv}}" />
                    <input type="hidden" id="bhtn_dv" name="bhtn_dv" value="{{$model->bhtn_dv}}" />
                    <input type="hidden" id="kpcd_dv" name="kpcd_dv" value="{{$model->kpcd_dv}}" />
                    <input type="hidden" id="luongcoban" name="luongcoban" value="{{$model->luongcoban}}" />

                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect', 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
                    <input type="hidden" id="macanbo" name="macanbo" value="{{$model->macanbo}}" />
                    <input type="hidden" id="mabl" name="mabl" value="{{$model->mabl}}" />
                    <input type="hidden" id="id" name="id" value="{{$model->id}}" />
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
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Mã ngạch </label>
                                                    {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control','readonly'=>'true'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Tên ngạch bậc </label>
                                                    {!!Form::text('tennb', null, array('id' => 'tennb','class' => 'form-control','readonly'=>'true'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" style="font-weight: bold">Tổng hệ số </label>
                                                    {!!Form::text('tonghs', null, array('id' => 'tonghs','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" style="font-weight: bold">Lương hệ số</label>
                                                    {!!Form::text('ttl', null, array('id' => 'ttl','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" style="font-weight: bold">Nộp theo lương</label>
                                                    {!!Form::text('tbh', $model->ttbh, array('id' => 'tbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Giảm trừ lương </label>
                                                    {!!Form::text('giaml', null, array('id' => 'giaml','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Bảo hiểm chi trả </label>
                                                    {!!Form::text('bhct', null, array('id' => 'bhct','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Thuế thu nhập </label>
                                                    {!!Form::text('thuetn', null, array('id' => 'thuetn','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Khen thưởng </label>
                                                    {!!Form::text('tienthuong', null, array('id' => 'tienthuong','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Đóng góp (trích nộp) </label>
                                                    {!!Form::text('trichnop', null, array('id' => 'trichnop','class' => 'form-control tienluong text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label"><b>Lương thực nhận </b></label>
                                                    {!!Form::text('luongtn', null, array('id' => 'luongtn','class' => 'form-control text-right', 'data-mask'=>'fdecimal', 'style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET-->
                            </div>
                        </div>

                        <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tên phụ cấp</th>
                                <th class="text-center" style="width: 15%">Hệ số</th>
                                <th class="text-center" style="width: 15%">Số tiền</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                            </thead>
                            <?php $i=1;?>
                            <tbody>
                            @if(isset($model_pc))
                                @foreach($model_pc as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td>{{$value->tenpc}}</td>
                                        <td class="text-center">{{dinhdangsothapphan($value->heso, 5)}}</td>
                                        <td class="text-right">{{dinhdangso($value->sotien)}}</td>
                                        <td>
                                            <button type="button" onclick="edit('{{$value->mapc}}','{{$model->id}}')" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            Thông tin khoản phải nộp theo lương (nhập số tiền)
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: block;">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số tiền BHXH </label>
                                                    {!!Form::text('stbhxh', null, array('id' => 'stbhxh','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số tiền BHYT </label>
                                                    {!!Form::text('stbhyt', null, array('id' => 'stbhyt','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số tiền KPCĐ </label>
                                                    {!!Form::text('stkpcd', null, array('id' => 'stkpcd','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Số tiền BHTN </label>
                                                    {!!Form::text('stbhtn', null, array('id' => 'stbhtn','class' => 'form-control baohiem text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" style="font-weight: bold">Tổng tiền cá nhân nộp bảo hiểm </label>
                                                    {!!Form::text('ttbh', null, array('id' => 'ttbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">BHXH đơn vị nộp</label>
                                                    {!!Form::text('stbhxh_dv', null, array('id' => 'stbhxh_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">BHYT đơn vị nộp</label>
                                                    {!!Form::text('stbhyt_dv', null, array('id' => 'stbhyt_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">KPCĐ đơn vị nộp</label>
                                                    {!!Form::text('stkpcd_dv', null, array('id' => 'stkpcd_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">BHTN đơn vị nộp</label>
                                                    {!!Form::text('stbhtn_dv', null, array('id' => 'stbhtn_dv','class' => 'form-control baohiem_dv text-right', 'data-mask'=>'fdecimal'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" style="font-weight: bold">Tổng tiền đơn vị nộp bảo hiểm</label>
                                                    {!!Form::text('ttbh_dv', null, array('id' => 'ttbh_dv','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Ghi chú</label>
                                                {!! Form::textarea('ghichu',null,array('id' => 'ghichu', 'class' => 'form-control','rows'=>'3'))!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
                    <hr>
                    <div style="text-align: center;">

                        <button type="submit" class="btn btn-default">Hoàn thành <i class="fa fa-save mlx"></i></button>

                        <a href="{{url('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb)}}" class="btn btn-default"><i class="fa fa-reply mlx"></i> Quay lại</a>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>

    {!! Form::open(['url'=>$furl.'update_chitiet','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
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
                            <label class="control-label"> Tên phụ cấp</label>
                            {!! Form::text('tenpc',null,array('id' => 'tenpc', 'class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Hệ số</label>
                            {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Mức lương cơ bản</label>
                            {!!Form::text('luongcb', null, array('id' => 'luongcb','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Số tiền</label>
                            {!!Form::text('sotien', null, array('id' => 'sotien','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>
                    <input type="hidden" id="mapc" name="mapc"/>
                    <input type="hidden" id="id_hs" name="id_hs" />
                    <input type="hidden" id="mabl_hs" name="mabl_hs" />
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function edit(mapc,id){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_chitiet',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mapc: mapc,
                    mabl: $('#mabl').val(),
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#tenpc').val(data.tenpc);
                    $('#heso').val(data.heso);
                    $('#sotien').val(data.sotien);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#mapc').val(mapc);
            $('#id_hs').val(id);
            $('#luongcb').val($('#luongcoban').val());
            $('#mabl_hs').val($('#mabl').val());
            $('#chitiet-modal').modal('show');
        }

        function giamtru() {
            var giaml = getdl($('#giaml').val());
            var bhct = getdl($('#bhct').val());
            var thuetn = getdl($('#thuetn').val());
            var tienthuong = getdl($('#tienthuong').val());
            var trichnop = getdl($('#trichnop').val());
            return bhct + tienthuong - giaml - thuetn - trichnop;

        }

        function luongtn() {
            var ttl = getdl($('#ttl').val());
            var bh = getdl($('#ttbh').val());
            var gt = giamtru();
            $('#luongtn').val(ttl + gt - bh);
        }


        $('.tienluong').change(function(){
            luongtn();
        })

        $('#heso').change(function(){
            var luongcb = getdl($('#heso').val());
            var heso = getdl($('#luongcb').val());
            $('#sotien').val(heso * luongcb);
        })

        $('#luongcb').change(function(){
            var luongcb = getdl($('#heso').val());
            var heso = getdl($('#luongcb').val());
            $('#sotien').val(heso * luongcb);
        })

        $('.baohiem_dv').change(function(){
            var stbhxh_dv=getdl($('#stbhxh_dv').val());
            var stbhyt_dv=getdl($('#stbhyt_dv').val());
            var stkpcd_dv=getdl($('#stkpcd_dv').val());
            var stbhtn_dv=getdl($('#stbhtn_dv').val());
            $('#ttbh_dv').val(stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv);
        })

        $('.baohiem').change(function(){
            var stbhxh_dv=getdl($('#stbhxh').val());
            var stbhyt_dv=getdl($('#stbhyt').val());
            var stkpcd_dv=getdl($('#stkpcd').val());
            var stbhtn_dv=getdl($('#stbhtn').val());
            var ttl = getdl($('#ttl').val());
            var bh = stbhxh_dv + stbhyt_dv + stkpcd_dv + stbhtn_dv;
            var gt = giamtru();
            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#luongtn').val(ttl + gt - bh);
        })

    </script>
    @include('includes.script.scripts')
@stop