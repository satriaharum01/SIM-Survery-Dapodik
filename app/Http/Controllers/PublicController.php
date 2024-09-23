<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Jawaban;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class PublicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('is_admin');
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        $this->data['page'] = 'Front';
        return view('landing.index', $this->data);
    }

    public function json()
    {
        $data = Sekolah::select('*')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function input_kenyataan()
    {
        $nilai = array(
            '3',
            '5',
            '5',
            '2',
            '3',
            '4',
            '3',
            '1',
            '1',
            '1',
            '4',
            '5',
            '5',
            '5',
            '4',
            '1',
            '4',
            '5',
            '4',
            '4'
        );

        $data2 = Pertanyaan::select('*')->get();
        $opsi = 'kenyataan';
        $user = '44';
        $i = 0;
        foreach($data2 as $rows) {
            $data1 = Jawaban::select('*')->where('id_pertanyaan', $rows->id)->where('opsi', $opsi)->where('id_user', $user)->first();
            $hasil;
            if(empty($data1)) {
                DB::table('jawaban')->insert([
                    'nilai' => $nilai[$i],
                    'opsi'  => $opsi,
                    'id_pertanyaan' => $rows->id,
                    'id_user' => $user
                ]);
            } else {
                $rows = Jawaban::find($data1->id);
                $rows->update([
                    'nilai' => $nilai[$i],
                    'opsi'  => $opsi,
                    'id_user' => $user
                ]);
            }
            $i++;
        }

        return print_r($nilai);
    }

    public function input_harapan()
    {
        $data = array(
            '4',
            '4',
            '4',
            '3',
            '4',
            '4',
            '3',
            '4',
            '3',
            '3',
            '4',
            '4',
            '4',
            '4',
            '4',
            '4',
            '4',
            '4',
            '2',
            '3'
        );

        return print_r($data);
    }
}
