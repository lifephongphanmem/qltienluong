<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05/04/2019
 * Time: 4:00 PM
 */
        ?>
<div style="text-align: center; padding-bottom: 15px">
    @if(!isset($model->mabl) ||(isset($model->mabl) && $model->mabl == null))
        <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
    @endif
    <a href="{{url('nghiep_vu/truy_linh/danh_sach?thang=ALL&nam='.date('Y'))}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
</div>