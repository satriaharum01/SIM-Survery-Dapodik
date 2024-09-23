<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Pertanyaan;
use App\Models\Section;
use App\Models\Jawaban;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class JawabanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        //Start Controller Function
        $this->middleware('auth');
        $this->view = 'admin/jawaban/index';
        $this->page = '/admin/jawaban';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Nilai Quisoner';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        $this->view = 'admin/jawaban/index';
        $this->page = '/admin/jawaban';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Nilai Quisoner';
        $atas = $this->tabel_gap(1);
        $bawah = $this->tabel_gap(1);
        $column = array_column($atas, 'gap');
        $column1 = array_column($bawah, 'gap');
        array_multisort($column, SORT_DESC, $atas);
        array_multisort($column1, SORT_ASC, $bawah);
        $this->data['atas'] = $atas[0]['section'];
        $this->data['bawah'] = $bawah[0]['section'];
        return view('admin/jawaban/index', $this->data);
    }


    public function json()
    {
        $data = User::select('*')->where('level', '!=', 'Admin')->get();

        foreach($data as $row) {
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->get()->toArray();
            $harapan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->get()->toArray();
            $row->n_kenyataan = array_sum(array_column($kenyataan, 'nilai'));
            $row->n_harapan = array_sum(array_column($harapan, 'nilai'));
            $row->gap = $row->n_kenyataan - $row->n_harapan;
            $row->responden = $row->name . ' - ' . $row->cari_sekolah->nama_sekolah;
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function detail_jawaban()
    {
        $out = array(
            'Y' => 0,
            'x' => 0
        );
        $total = array();
        $result = array();
        $in = array();
        $data = User::select('*')->where('level', '!=', 'Admin')->get();
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();
        //KENYATAAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['x'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['xx'] = 0;
                $total[$i]['xy'] = 0;
                $total[$i]['yy'] = 0;
                //ARRAY
                $result[$row->name][$i]['x'] = 0;
                $result[$row->name][$i]['y'] = 0;
                $result[$row->name][$i]['xx'] = 0;
                $result[$row->name][$i]['xy'] = 0;
                $result[$row->name][$i]['yy'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->nama_section;
                $result[$row->name][$i]['pertanyaan'] = $pert->keterangan;
                $i++;
            }
            $out['Y'] = $out['Y'] + array_sum(array_column($kenyataan, 'nilai'));
        }
        foreach($data as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

            $out['x'] = array_sum(array_column($kenyataan, 'nilai'));
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $result[$row->name][$i]['x'] + $rows['nilai'];
                $total[$i]['x'] = $total[$i]['x'] + $result[$row->name][$i]['x'];
                $result[$row->name][$i]['y'] = $out['x'];
                $total[$i]['y'] = $total[$i]['y'] + $result[$row->name][$i]['y'];
                $result[$row->name][$i]['xx'] = $result[$row->name][$i]['xx'] + pow($rows['nilai'], 2);
                $total[$i]['xx'] = $total[$i]['xx'] + $result[$row->name][$i]['xx'];
                $result[$row->name][$i]['yy'] = $out['x'] * $out['x'];
                $total[$i]['yy'] = $total[$i]['yy'] + $result[$row->name][$i]['yy'];
                $result[$row->name][$i]['xy'] = $result[$row->name][$i]['xy'] + ($out['x'] * $rows['nilai']);
                $total[$i]['xy'] = $total[$i]['xy'] + $result[$row->name][$i]['xy'];
                $i++;
            }
        }
        $i = 0;
        foreach($total as $row) {
            $total[$i]['x2'] = pow($row['x'], 2);
            $total[$i]['y2'] = pow($row['y'], 2);
            $total[$i]['xx2'] = pow($row['xx'], 2);
            $total[$i]['xy2'] = pow($row['xy'], 2);
            $total[$i]['yy2'] = pow($row['yy'], 2);
            $i++;
        }

        $ccc = count($result);
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $in[$i]['section'] = $section->nama_section;
                $in[$i]['pertanyaan'] = $pert->keterangan;
                $atas = ($ccc * $total[$i]['xy']) - ($total[$i]['x'] * $total[$i]['y']);
                $bawah = sqrt(($ccc * $total[$i]['xx']) - $total[$i]['x2']) *  sqrt(($ccc * $total[$i]['yy']) - $total[$i]['y2']);
                //print_r($bawah);
                $in[$i]['nilai_r'] = $atas / $bawah;

                $in[$i]['r_table'] = 0.1698;
                $i++;
            }
        }
        $out = array(
            'Y' => 0,
            'x' => 0
        );
        $total = array();
        $result = array();
        //HARAPAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['x'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['xx'] = 0;
                $total[$i]['xy'] = 0;
                $total[$i]['yy'] = 0;
                //ARRAY
                $result[$row->name][$i]['x'] = 0;
                $result[$row->name][$i]['y'] = 0;
                $result[$row->name][$i]['xx'] = 0;
                $result[$row->name][$i]['xy'] = 0;
                $result[$row->name][$i]['yy'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->nama_section;
                $result[$row->name][$i]['pertanyaan'] = $pert->keterangan;
                $i++;
            }
            $out['Y'] = $out['Y'] + array_sum(array_column($kenyataan, 'nilai'));
        }
        foreach($data as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

            $out['x'] = array_sum(array_column($kenyataan, 'nilai'));
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $result[$row->name][$i]['x'] + $rows['nilai'];
                $total[$i]['x'] = $total[$i]['x'] + $result[$row->name][$i]['x'];
                $result[$row->name][$i]['y'] = $out['x'];
                $total[$i]['y'] = $total[$i]['y'] + $result[$row->name][$i]['y'];
                $result[$row->name][$i]['xx'] = $result[$row->name][$i]['xx'] + pow($rows['nilai'], 2);
                $total[$i]['xx'] = $total[$i]['xx'] + $result[$row->name][$i]['xx'];
                $result[$row->name][$i]['yy'] = $out['x'] * $out['x'];
                $total[$i]['yy'] = $total[$i]['yy'] + $result[$row->name][$i]['yy'];
                $result[$row->name][$i]['xy'] = $result[$row->name][$i]['xy'] + ($out['x'] * $rows['nilai']);
                $total[$i]['xy'] = $total[$i]['xy'] + $result[$row->name][$i]['xy'];
                $i++;
            }
        }
        $i = 0;
        foreach($total as $row) {
            $total[$i]['x2'] = pow($row['x'], 2);
            $total[$i]['y2'] = pow($row['y'], 2);
            $total[$i]['xx2'] = pow($row['xx'], 2);
            $total[$i]['xy2'] = pow($row['xy'], 2);
            $total[$i]['yy2'] = pow($row['yy'], 2);
            $i++;
        }
        $out = array_diff($out, $out);
        $ccc = count($result);
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $in[$i]['section'] = $section->nama_section;
                $in[$i]['pertanyaan'] = $pert->keterangan;
                $atas = ($ccc * $total[$i]['xy']) - ($total[$i]['x'] * $total[$i]['y']);
                $bawah = sqrt(($ccc * $total[$i]['xx']) - $total[$i]['x2']) *  sqrt(($ccc * $total[$i]['yy']) - $total[$i]['y2']);
                $in[$i]['nilai_q'] = $atas / $bawah;
                if($in[$i]['nilai_r'] >= $in[$i]['r_table'] && $in[$i]['nilai_q'] >= $in[$i]['r_table']) {
                    $in[$i]['hasil'] = 'Valid';
                } else {
                    $in[$i]['hasil'] = 'Tidak Valid';
                }
                $i++;
            }
        }
        //print_r($out);

        return Datatables::of($in)
            ->addIndexColumn()
            ->make(true);

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
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
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
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
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $in[$i]['nilai_q'] = $total[$i]['y'] / $total[$i]['n'];
                $in[$i]['gap'] = $in[$i]['nilai_r'] - $in[$i]['nilai_q'];
                $i++;
            }
        }
        if($get == 0) {
            return Datatables::of($in)
                ->addIndexColumn()
                ->make(true);
        } else {
            return $in;
        }
    }


    public function reliabel()
    {
        $get_var = array();
        $total = array();
        $result = array();
        $in = array();
        $out = array();
        $data = User::select('*')->where('level', '!=', 'Admin')->get();
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();

        $temp_1 = array();
        //KENYATAAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan', 'id_pertanyaan')->where('id_user', $row->id)->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['n'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['gap'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->kode;
                $i++;
            }
        }
        $k = 0;
        foreach($data as $row) {
            $i = 0;
            $temp_1[$k]['nilai'] = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan', 'id_pertanyaan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $rows['nilai'];
                $temp_1[$k]['nilai'] = $temp_1[$k]['nilai'] + $rows['nilai'];
                $total[$i]['x'][] = $result[$row->name][$i]['x'];
                $total[$i]['n'] = $total[$i]['n'] + 1;
                $i++;
            }
            $k++;
        }

        $ccc = count($total);
        foreach($datadummy as $row) {
            $i = 0;
            $j = 1;
            $kode = '';
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan', 'id_pertanyaan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                if($kode != $section->kode) {
                    $kode = $section->kode;
                    $j = 1;
                }
                $in[$i]['section'] = $section->kode . $j;
                $in[$i]['var_k'] = $this->getVariance($total[$i]['x']);
                $i++;
                $j++;
            }
        }
        $new_temp = array();
        foreach($temp_1 as $row) {
            if($row['nilai'] != 0) {
                $new_temp[] = $row['nilai'];
            }
        }
        $get_var['sumvar'] = 0;
        $get_var['totvar'] = 0;
        foreach($in as $row) {
            $get_var['sumvar'] =  $get_var['sumvar'] + $row['var_k'];

        }
        $get_var['totvar'] = $this->getVariance($new_temp);
        $out['kenyataan']['std'] = 0.60;
        $out['kenyataan']['kuesioner'] = 'Kenyataan';
        $out['kenyataan']['crc'] = ($ccc / ($ccc - 1)) * (1 - ($get_var['sumvar'] / $get_var['totvar']));
        if($out['kenyataan']['crc'] > $out['kenyataan']['std']) {
            $out['kenyataan']['status'] = 'Reliabel';
        } else {
            $out['kenyataan']['status'] = 'Tidak Reliabel';
        }

        $total = array();
        $result = array();
        $temp_1 = array();

        //HARAPAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan', 'id_pertanyaan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();

            foreach($kenyataan as $rows) {
                //TOTAL
                $total[$i]['n'] = 0;
                $total[$i]['y'] = 0;
                $total[$i]['gap'] = 0;
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                $result[$row->name][$i]['section'] = $section->kode;
                $i++;
            }
        }
        $k = 0;
        foreach($data as $row) {
            $i = 0;
            $temp_1[$k]['nilai'] = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan', 'id_pertanyaan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $rows['nilai'];
                $temp_1[$k]['nilai'] = $temp_1[$k]['nilai'] + $rows['nilai'];
                $total[$i]['x'][] = $result[$row->name][$i]['x'];
                $total[$i]['n'] = $total[$i]['n'] + 1;
                $i++;
            }
            $k++;
        }

        $ccc = count($total);
        foreach($datadummy as $row) {
            $i = 0;
            $j = 1;
            $kode = '';
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan', 'id_pertanyaan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get()->toArray();
            foreach($kenyataan as $rows) {
                $pert = Pertanyaan::find($rows['id_pertanyaan']);
                $section = Section::find($pert->id_section);
                if($kode != $section->kode) {
                    $kode = $section->kode;
                    $j = 1;
                }
                $in[$i]['section'] = $section->kode . $j;
                $in[$i]['var_k'] = $this->getVariance($total[$i]['x']);
                $i++;
                $j++;
            }
        }
        $new_temp = array();
        foreach($temp_1 as $row) {
            if($row['nilai'] != 0) {
                $new_temp[] = $row['nilai'];
            }
        }
        $get_var['sumvar'] = 0;
        $get_var['totvar'] = 0;
        foreach($in as $row) {
            $get_var['sumvar'] =  $get_var['sumvar'] + $row['var_k'];

        }
        $get_var['totvar'] = $this->getVariance($new_temp);
        $out['harapan']['std'] = 0.60;
        $out['harapan']['kuesioner'] = 'Harapan';
        $out['harapan']['crc'] = ($ccc / ($ccc - 1)) * (1 - ($get_var['sumvar'] / $get_var['totvar']));
        if($out['harapan']['crc'] > $out['harapan']['std']) {
            $out['harapan']['status'] = 'Reliabel';
        } else {
            $out['harapan']['status'] = 'Tidak Reliabel';
        }

        return Datatables::of($out)
             ->addIndexColumn()
             ->make(true);

    }

    public function update(Request $request, $id)
    {
        $rows = Jawaban::find($id);
        $rows->update([
            'nilai' => $request->nilai,
            'id_pertanyaan' => $request->section,
            'id_user' => $request->user
        ]);

        return redirect(route('admin.jawaban'));
    }

    public function store(Request $request)
    {

        DB::table('jawaban')->insert([
            'nilai' => $request->nilai,
            'id_pertanyaan' => $request->section,
            'id_user' => $request->user
        ]);

        return redirect($this->page);

    }

    public function destroy($id)
    {
        $rows = Jawaban::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.jawaban'));
    }

    public function find($id)
    {
        $data = Jawaban::select('*')->where('id', $id)->get();
        foreach($data as $row) {
            $row->nama_section = $this->is_null($row->cari_section->cari_survey->judul) . ' - ' . $row->cari_section->nama_section;
        }
        return json_encode(array('data' => $data));
    }

    private function getVariance(array $arrayOfNumbers)
    {
        $variance = 0.0;
        $totalElementsInArray = count($arrayOfNumbers);
        // Calc Mean.
        $averageValue = array_sum($arrayOfNumbers) / $totalElementsInArray;

        foreach ($arrayOfNumbers as $item) {
            $variance += pow(abs($item - $averageValue), 2);
        }

        return $variance / ($totalElementsInArray - 1);
    }

    /**
    * Simple deviation.
    *
    * @param float $variance
    * @return float
    */
    private function getStdDeviation($variance)
    {
        return (float) sqrt($variance);
    }

    public function is_null($variable)
    {
        if($variable != '') {
            return $variable;
        } else {
            return 'Null';
        }
    }

    public function test_gap()
    {
        $total = array();
        $result = array();
        $in = array();
        $data = User::select('*')->where('level', '!=', 'Admin')->get();
        $datadummy = User::select('*')->where('level', '!=', 'Admin')->get();
        $pertanyaan = Pertanyaan::select('id')->get()->toArray();
        //KENYATAAN
        foreach($datadummy as $row) {
            $i = 0;
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->whereIn('id_pertanyaan',$pertanyaan)->where('opsi', 'kenyataan')->where('id_user', $row->id)->orderby('id_pertanyaan','ASC')->get();
            foreach($kenyataan as $rows) {
                $result[$row->name][$i]['x'] = $rows->nilai;
                $total[$i]['x'] = $total[$i]['x'] + $rows->nilai;
                $total[$i]['n'] = $total[$i]['n'] + 1;
                $i++;
            }
        }
/*
        $ccc = count($result);
        foreach($datadummy as $row) {
            $i = 0;
            $j = 1;
            $kode = '';
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan')->where('id_user', $row->id)->get()->toArray();
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
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan', 'id_pertanyaan')->where('id_user', $row->id)->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'harapan', 'id_pertanyaan')->where('id_user', $row->id)->get()->toArray();

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
            $kenyataan = Jawaban::select('*')->where('opsi', 'kenyataan', 'id_pertanyaan')->where('id_user', $row->id)->get()->toArray();
            foreach($kenyataan as $rows) {
                $in[$i]['nilai_q'] = $total[$i]['y'] / $total[$i]['n'];
                $in[$i]['gap'] = $in[$i]['nilai_r'] - $in[$i]['nilai_q'];
                $i++;
            }
        }
        */
        return $total;
    }
}
