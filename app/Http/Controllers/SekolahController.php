<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SekolahController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $rules = [
        'stat'          => 'required|min_length[3]|max_length[50]',
        'harga'        => 'required|min_length[3]|max_length[50]'
    ];

    public function __construct()
    {
        //Start Controller Function
        $this->middleware('auth');
        $this->view = 'admin/sekolah/index';
        $this->page = '/admin/sekolah';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Sekolah';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('admin/sekolah/index', $this->data);
    }


    public function json()
    {
        $data = Sekolah::select('*')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $rows = Sekolah::find($id);
        $rows->update([
            'nama_sekolah' => $request->nama_sekolah,
            'npsn' => $request->npsn,
            'alamat' => $request->alamat
        ]);

        return redirect(route('admin.sekolah'));
    }

    public function store(Request $request)
    {

        DB::table('sekolah')->insert([
            'nama_sekolah' => $request->nama_sekolah,
            'npsn' => $request->npsn,
            'alamat' => $request->alamat
        ]);

        return redirect(route('admin.sekolah'));
    }

    public function destroy($id)
    {
        $rows = Sekolah::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.sekolah'));
    }

    public function find($id)
    {
        $data = Sekolah::select('*')->where('id_sekolah', $id)->get();

        return json_encode(array('data' => $data));
    }
}
