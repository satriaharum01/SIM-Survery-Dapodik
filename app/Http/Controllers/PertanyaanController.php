<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class PertanyaanController extends Controller
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
        $this->middleware('is_admin');
        $this->view = 'admin/pertanyaan/index';
        $this->page = '/admin/pertanyaan';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Pertanyaan';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('admin/pertanyaan/index', $this->data);
    }


    public function json()
    {
        $data = Pertanyaan::select('*')
            ->get();
        foreach($data as $row) {
            $row->nama_section = $row->cari_section->cari_survey->judul . ' - ' . $row->cari_section->nama_section;
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $rows = Pertanyaan::find($id);
        $rows->update([
            'keterangan' => $request->keterangan,
            'id_section' => $request->section
        ]);

        return redirect(route('admin.pertanyaan'));
    }

    public function store(Request $request)
    {

        DB::table('pertanyaan')->insert([
            'keterangan' => $request->keterangan,
            'id_section' => $request->section
        ]);

        return redirect($this->page);

    }

    public function destroy($id)
    {
        $rows = Pertanyaan::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.pertanyaan'));
    }

    public function find($id)
    {
        $data = Pertanyaan::select('*')->where('id', $id)->get();
        foreach($data as $row) {
            $row->nama_section = $row->cari_section->cari_survey->judul . ' - ' . $row->cari_section->nama_section;
        }
        return json_encode(array('data' => $data));
    }
}
