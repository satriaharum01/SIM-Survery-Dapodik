<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SectionController extends Controller
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
        $this->view = 'admin/section/index';
        $this->page = '/admin/section';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Section Pertanyaan';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('admin/section/index', $this->data);
    }


    public function json()
    {
        $data = Section::select('*')
            ->get();
        foreach($data as $row) {
            $row->nama_survey = $row->cari_survey->judul;
            $row->for_filter = $row->cari_survey->judul . ' - ' . $row->nama_section;
        }
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $rows = Section::find($id);
        $rows->update([
            'nama_section' => $request->nama_section,
            'kode' => $request->kode,
            'urutan' => $request->urutan,
            'id_survey' => $request->survey
        ]);

        return redirect(route('admin.section'));
    }

    public function store(Request $request)
    {

        DB::table('section')->insert([
            'nama_section' => $request->nama_section,
            'kode' => $request->kode,
            'urutan' => $request->urutan,
            'id_survey' => $request->survey
        ]);

        return redirect(route('admin.section'));
    }

    public function destroy($id)
    {
        $rows = Section::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.section'));
    }

    public function find($id)
    {
        $data = Section::select('*')->where('id', $id)->get();

        return json_encode(array('data' => $data));
    }
}
