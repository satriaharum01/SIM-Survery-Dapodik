<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Section;
use App\Models\Sekolah;
use App\Models\Pertanyaan;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');

        $this->data['count_halte'] = 0;
        $this->data['count_user'] = 0;
        $this->data['count_bus'] = 0;
        $this->data['count_koridor'] = 0;
        $this->data['count_graf'] = 0;
        $this->data['title'] = 'Dashboard Admin';
        //$this->middleware('is_admin');
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        $load = $this->tabel_gap();
        $label = array();
        $nilai = array();
        foreach($load as $row) {
            $label[] = $row['section'];
            $nilai[] = number_format($row['gap'], 2);
        }
        $this->data['label'] = json_encode($label);
        $this->data['nilai'] = json_encode($nilai);

        $this->data['c_survey'] = $this->c_survey();
        $this->data['c_sekolah'] = $this->c_sekolah();
        $this->data['c_kenyataan'] = $this->c_kenyataan();
        $this->data['c_harapan'] = $this->c_harapan();

        //return $this->data['nilai'];
        return view('admin/dashboard/index', $this->data);
    }

    public function jawaban()
    {
        $this->view = 'admin/jawaban/index';
        $this->page = '/admin/jawaban';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Nilai Quisoner';
        return view('admin/jawaban/index', $this->data);
    }

    public function pertanyaan()
    {
        $this->view = 'admin/pertanyaan/index';
        $this->page = '/admin/pertanyaan';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Pertanyaan';
        return view('admin/pertanyaan/index', $this->data);
    }

    public function section()
    {
        $this->view = 'admin/section/index';
        $this->page = '/admin/section';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Section Pertanyaan';
        return view('admin/section/index', $this->data);
    }

    public function survey()
    {
        $this->data[ 'title' ] = 'Data Survey';
        $this->data[ 'link' ] = '/admin/survey';
        $this->page = '/admin/survey';
        $this->data['page'] = $this->page;
        return view('admin/survey/index', $this->data);
    }

    public function sekolah()
    {
        $this->data[ 'title' ] = 'Data Sekolah';
        $this->data[ 'link' ] = '/admin/sekolah';
        $this->page = '/admin/sekolah';
        $this->data['page'] = $this->page;
        return view('admin/sekolah/index', $this->data);
    }

    public function pengguna()
    {
        $this->data[ 'title' ] = 'Data Pengguna';
        $this->data[ 'link' ] = '/admin/pengguna';
        $this->page = '/admin/pengguna';
        $this->data['page'] = $this->page;
        return view('admin/pengguna/index', $this->data);
    }

    public function c_sekolah()
    {
        $data = Sekolah::select('*')->get()->count();

        return $data;
    }

    public function c_survey()
    {
        $data = Survey::select('*')->get()->count();

        return $data;
    }

    public function c_kenyataan()
    {
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();
        $total = 0;
        //KENYATAAN
        foreach($datadummy as $row) {
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();

            $total = $total + array_sum(array_column($kenyataan, 'nilai'));
        }

        return $total;
    }

    public function c_harapan()
    {
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();
        $total = 0;
        //KENYATAAN
        foreach($datadummy as $row) {
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();

            $total = $total + array_sum(array_column($kenyataan, 'nilai'));
        }

        return $total;
    }

    public function tabel_gap($get = 0)
    {
        $total = array();
        $result = array();
        $in = array();
        $data = User::select('*')->where('level', '!=', 'Admin')->get();
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();
        //KENYATAAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['x'] = 0;
                $total[$i]['n'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['gap'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->kode;
                $i++;
            }
        }
        foreach($data as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $rows['nilai'];
                $total[$i]['x'] = $total[$i]['x'] + $result[$row->name][$i]['x'];
                $total[$i]['n'] = $total[$i]['n'] + 1;
                $i++;
            }
        }

        $ccc = count($result);

        foreach($datadummy as $row) {
            $i = 0;
            $j = 1;
            $kode = '';
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                if($kode != $section->kode) {
                    $kode = $section->kode;
                    $j = 1;
                }
                $in[$i]['section'] = $section->kode . $j;
                $in[$i]['nilai_r'] = $total[$i]['x'] / $total[$i]['n'];
                $i++;
                $j++;
            }
        }

        $total = array();
        $result = array();
        //HARAPAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['x'] = 0;
                $total[$i]['n'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['gap'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->kode;
                $i++;
            }
        }
        foreach($data as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();

            $out['x'] = array_sum(array_column($kenyataan, 'nilai'));
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['y'] = $rows['nilai'];
                $total[$i]['y'] = $total[$i]['y'] + $result[$row->name][$i]['y'];
                $total[$i]['n'] = $total[$i]['n'] + 1;
                $i++;
            }
        }

        $ccc = count($result);

        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan', 'ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $in[$i]['nilai_q'] = $total[$i]['y'] / $total[$i]['n'];
                $in[$i]['gap'] = $in[$i]['nilai_r'] - $in[$i]['nilai_q'];
                $i++;
            }
        }
        return $in;

    }
}
