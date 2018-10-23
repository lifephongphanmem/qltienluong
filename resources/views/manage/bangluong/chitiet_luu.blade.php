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
                    <div class="caption">
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

                    {!! Form::model($model, ['url'=>'/chuc_nang/bang_luong/updatect/'.$model->id, 'method' => 'POST', 'files'=>true, 'id' => 'create-hscb', 'class'=>'horizontal-form form-validate']) !!}
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: bold">Lương hệ số</label>
                                                        {!!Form::text('ttl', null, array('id' => 'ttl','class' => 'form-control', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

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
                                                        <label class="control-label" style="font-weight: bold">Nộp theo lương</label>
                                                        {!!Form::text('tbh', $model->ttbh, array('id' => 'tbh','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><b>Lương thực nhận </b></label>
                                                        {!!Form::text('luongtn', null, array('id' => 'luongtn','class' => 'form-control text-right', 'data-mask'=>'fdecimal','readonly'=>'true','style'=>'font-weight:bold'))!!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END PORTLET-->
                                </div>
                            </div>

                            <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">STT</th>
                                    <th class="text-center">Tên phụ cấp</th>
                                    <th class="text-center" style="width: 15%">Hệ số</th>
                                    <th class="text-center" style="width: 15%">Số tiền</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <?php $i=1;?>
                                <tbody>
                                @if(isset($model_pc))
                                    @foreach($model_pc as $key=>$value)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td>
                                            <td>{{$value->tenphanloai}}</td>
                                            <td>{{$value->tennguonkp}}</td>
                                            <td>{{$value->noidung}}</td>
                                            <td>
                                                @if($value->thaotac)
                                                    <button type="button" onclick="edit('{{$value->mabl}}','{{$value->phanloai}}')" class="btn btn-default btn-xs mbs">
                                                        <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                                    <a href="{{url($inputs['furl'].'bang_luong?mabl='.$value->mabl.'&mapb=')}}" class="btn btn-default btn-xs mbs">
                                                        <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>

                                                    <button type="button" onclick="cfDel('{{$inputs['furl'].'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                        <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                                @endif
                                                <button type="button" onclick="inbl('{{$value->mabl}}','{{$value->thang}}','{{$value->nam}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-print"></i>&nbsp; In bảng lương</button>
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
    <script>

        function tonghs() {
            var hs = 0;
            $('.heso').each(function () {
                if(getdl($(this).val()) < 500){
                    hs += getdl($(this).val());
                }
            });
            $('#tonghs').val(parseFloat(hs));
        }

        function tongtl(){
            var hs=$('#tonghs').val();
            var tpc=0;
            $('.heso').each(function () {
                if(getdl($(this).val()) > 5000){
                    tpc += getdl($(this).val());
                }
            });
            var luong = $('#luongcoban').val();
            return (hs*luong + tpc);
        }

        function baohiem(){
            //chạy khi hệ số thay đổi
            //truyền các loại phụ cấp đóng bảo hiểm thành pc,pc,pc => split() =>for để cộng
            var heso = getdl($('#heso').val())
                    + getdl($('#vuotkhung').val())
                    + getdl($('#pccv').val())
                    + getdl($('#pctnn').val());

            var luong = $('#luongcoban').val();
            var tienbh = heso * luong;

            var stbhxh= ($('#bhxh').val() * tienbh /100).toFixed(0);
            $('#stbhxh').val(stbhxh);

            var stbhyt=($('#bhyt').val() * tienbh /100).toFixed(0);
            $('#stbhyt').val(stbhyt);

            var stkpcd=($('#kpcd').val() * tienbh /100).toFixed(0);
            $('#stkpcd').val(stkpcd);

            var stbhtn=($('#bhtn').val() * tienbh /100).toFixed(0);
            $('#stbhtn').val(stbhtn);

            var stbhxh_dv=($('#bhxh_dv').val() * tienbh /100).toFixed(0);
            $('#stbhxh_dv').val(stbhxh_dv);
            var stbhyt_dv=($('#bhyt_dv').val() * tienbh /100).toFixed(0);
            $('#stbhyt_dv').val(stbhyt_dv);
            var stkpcd_dv=($('#kpcd_dv').val() * tienbh /100).toFixed(0);
            $('#stkpcd_dv').val(stkpcd_dv);
            var stbhtn_dv=($('#bhtn_dv').val() * tienbh /100).toFixed(0);
            $('#stbhtn_dv').val(stbhtn_dv);

            $('#ttbh_dv').val(parseFloat(stbhxh_dv) + parseFloat(stbhyt_dv) + parseFloat(stkpcd_dv) + parseFloat(stbhtn_dv));

            return parseFloat(stbhxh) + parseFloat(stbhyt) + parseFloat(stkpcd) + parseFloat(stbhtn);
        }

        function giamtru() {
            var giaml = getdl($('#giaml').val());
            var bhct = getdl($('#bhct').val());
            return bhct - giaml;

        }

        function luongtn() {
            var ttl = parseFloat(tongtl().toFixed(0));
            var bh = baohiem();
            var gt = giamtru();
            $('#ttl').val(ttl);
            $('#ttbh').val(bh);
            $('#tbh').val(bh);
            $('#luongtn').val(ttl + gt - bh);
        }
        $('.heso').change(function(){
            tonghs();
            luongtn();
        })

        $('.tienluong').change(function(){
            luongtn();
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