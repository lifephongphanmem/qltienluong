<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 7/14/2016
 * Time: 10:17 AM
 */
        ?>

    @if(session('admin')->sadmin=='sa')
        <td>
            <button type="button" onclick="edit('{{$value->id}}')" class="btn btn-info btn-xs mbs">
                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
        </td>
    @else
        <td style="text-align: center">Chức năng của quản</br>lý hệ thống</td>
    @endif
