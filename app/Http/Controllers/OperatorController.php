<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Pertanyaan;
use App\Models\Section;
use App\Models\Sekolah;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class OperatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $opsi = [
        1 => "STS",
        2 => "TS",
        3 => "CS",
        4 => "S",
        5 => "SS"
    ];

    private $jawaban = [
        1 => "Sangat Tidak Setuju",
        2 => "Tidak Setuju",
        3 => "Cukup Setuju",
        4 => "Setuju",
        5 => "Sangat Setuju"
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_operator');
        $this->data['count_halte'] = 0;
        $this->data['count_user'] = 0;
        $this->data['count_bus'] = 0;
        $this->data['count_koridor'] = 0;
        $this->data['count_graf'] = 0;
        $this->data['title'] = 'Dashboard Operator';
        //$this->middleware('is_admin');
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('operator/dashboard/index', $this->data);
    }

    public function update(Request $request, $id)
    {
        $rows = Sekolah::find($id);
        $rows->update([
            'nama_sekolah' => $request->nama_sekolah,
            'npsn' => $request->npsn,
            'alamat' => $request->alamat
        ]);

        $rows = User::find(Auth::user()->id);
        $rows->update([
            'name' => $request->nama,
            'email' => $request->email
        ]);

        return redirect(route('operator.sekolah'));
    }

    public function sekolah()
    {
        $this->view = 'operator/sekolah/index';
        $this->page = '/operator/sekolah';
        $this->data['page'] = $this->page;
        $this->data['load'] = Sekolah::find(Auth::user()->id_sekolah);
        $this->data['profil'] = User::find(Auth::user()->id);
        $this->data[ 'title' ] = 'Profil Operator';
        return view('operator/sekolah/index', $this->data);
    }

    public function jawaban()
    {
        $this->view = 'operator/jawaban/index';
        $this->page = '/operator/jawaban';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Nilai Quisoner';
        return view('admin/jawaban/index', $this->data);
    }

    public function pertanyaan()
    {
        $this->view = 'operator/pertanyaan/index';
        $this->page = '/operator/pertanyaan';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Pertanyaan';
        return view('operator/pertanyaan/index', $this->data);
    }

    public function survey()
    {
        $this->data[ 'title' ] = 'Data Survey';
        $this->data[ 'link' ] = '/operator/survey';
        $this->page = '/operator/survey';
        $this->data['page'] = $this->page;
        return view('operator/survey/index', $this->data);
    }

    public function mulai_survey($id, $opt = 'kenyataan')
    {
        $this->data['load'] = Survey::find($id);
        $this->data['opsii'] = $opt;
        $this->data['user'] = User::find(Auth::user()->id);
        if($opt == 'kenyataan') {
            $this->data['sub_judul'] = 'Analisis Kualitas Layanan Sistem Informasi Dapotik terhadap Kenyataan Pengguna';
        } else {
            $this->data['sub_judul'] = 'Analisis Kualitas Layanan Sistem Informasi Dapotik menurut Harapan Pengguna';
        }
        $for_result = array();
        $section = Section::select('*')->where('id_survey', $id)->get();
        foreach($section as $row) {
            unset($row->created_at);
            unset($row->updated_at);
            $pertanyaan = Pertanyaan::select('*')->where('id_section', $row->id)->get();
            foreach($pertanyaan as $dom) {
                $jawaban = Jawaban::select('*')->where('id_pertanyaan', $dom->id)->where('opsi', $opt)->where('id_user', Auth::user()->id)->first();
                if(empty($jawaban->nilai)) {
                    $dom->jawaban = 0;
                } else {
                    $dom->jawaban = $jawaban->nilai;
                }
                unset($dom->created_at);
                unset($dom->updated_at);
                $for_result[] = $dom;
            }

        }
        $this->data['result'] = $for_result;
        $this->data['pilihan'] = $this->opsi;
        $this->data['p_opt'] = $opt;
        //return ($this->data['opt']);
        return view('operator/survey/result', $this->data);
    }

    public function live_update(Request $request, $id)
    {
        $data = Jawaban::select('*')->where('id_pertanyaan', $id)->where('opsi', $request->Opsi)->where('id_user', Auth::user()->id)->first();
        $hasil;
        if(empty($data)) {
            DB::table('jawaban')->insert([
                'nilai' => $request->Nilai,
                'opsi'  => $request->Opsi,
                'id_pertanyaan' => $id,
                'id_user' => Auth::user()->id
            ]);
        } else {
            $rows = Jawaban::find($data->id);
            $rows->update([
                'nilai' => $request->Nilai,
                'opsi'  => $request->Opsi,
                'id_user' => Auth::user()->id
            ]);
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Dipilih'
            ]
        );
    }

    public function json($pilihan)
    {
        $user = User::find(Auth::user()->id);
        $pertanyaan = Pertanyaan::select('*')->orderBy('id', 'ASC')->get();

        foreach($pertanyaan as $row) {
            $data = Jawaban::select('*')->where('opsi', $pilihan)->where('id_pertanyaan', $row->id)->where('id_user', $user->id)->first();
            if(!empty($data)) {
                $row->jawaban = $this->jawaban[$data->nilai];
                $row->nilai = $data->nilai;
            } else {
                $row->jawaban = 'Belum Menjawab';
                $row->nilai = 0;
            }
        }

        return Datatables::of($pertanyaan)
            ->addIndexColumn()
            ->make(true);
    }
}
