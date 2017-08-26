<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class baocaothongtu67Controller extends Controller
{
    function index() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.index')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('pageTitle','Báo cáo tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $ar_III = array();
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp tỉnh');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp huyện');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp xã');

            $ar_IV = array();
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp tỉnh');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp huyện');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp xã');

            return view('reports.thongtu67.mau2a1_tt67')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $ar_III = array();
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp tỉnh');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp huyện');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp xã');

            $ar_IV = array();
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp tỉnh');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp huyện');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp xã');

            return view('reports.thongtu67.mau2a2_tt67')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');


            return view('reports.thongtu67.mau2b')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }
}
