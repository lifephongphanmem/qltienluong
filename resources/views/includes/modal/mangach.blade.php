<?php
/**
 * Created by PhpStorm.
 * User: HuongVu
 * Date: 08/03/2018
 * Time: 3:34 PM
 */
?>

<div class="modal fade in" id="modal-vitri" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Thông tin mã ngạch lương</h4>
            </div>
            <div class="modal-body" id="vitridat">
                <table class="table table-striped table-bordered table-hover" id="sample_3">
                    <thead>
                    <tr>
                        <th width="2%" style="text-align: center">STT</th>
                        <th style="text-align: center">Mã ngạch</th>
                        <th style="text-align: center">Tên ngạch lương</th>
                        <th style="text-align: center">Số năm</br>nâng lương</th>
                        <th style="text-align: center">Hệ số</br>bắt đầu</th>
                        <th style="text-align: center">Hệ số</br>lớn nhất</th>
                        <th style="text-align: center">Bậc lương</br>vượt khung</th>
                        <th style="text-align: center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;?>
                    @foreach($m_pln as $key=>$tt)
                        <tr>
                            <td style="text-align: center">{{$i++}}</td>
                            <td class="text-center" name="msngbac">{{$tt->msngbac}}</td>
                            <td name="tenngachluong">{{$tt->tenngachluong}}</td>
                            <td class="text-center" name="namnb">{{$tt->namnb}}</td>
                            <td class="text-center" name="heso">{{$tt->heso}}</td>
                            <td class="text-center" name="heso">{{$tt->hesolonnhat}}</td>
                            <td class="text-center" name="heso">{{$tt->bacvuotkhung}}</td>
                            <td>
                                <button type="button" onclick="set_vitri(this)" class="btn btn-default btn-xs mbs"><i class="fa fa-trash-o"></i>&nbsp;
                                    Chọn</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function set_vitri(e){
        var tr = $(e).closest('tr');
        $('#tennb').val($(tr).find('td[name=msngbac]').text()).trigger('change');
        //alert()
        $('#modal-vitri').modal('hide');
    }
</script>