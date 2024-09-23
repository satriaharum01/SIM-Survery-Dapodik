<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Hash;

class PenggunaController extends Controller
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
        $this->middleware('is_admin');
        $this->view = 'admin/pengguna/index';
        $this->page = '/admin/pengguna';
        $this->data['page'] = $this->page;
        $this->data[ 'title' ] = 'Data Pengguna';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('admin/pengguna/index', $this->data);
    }


    public function json()
    {
        $data = User::select('*')
            ->where('level', 'Operator')
            ->get();

        foreach($data as $row) {
            $row->sekolah = $row->cari_sekolah->nama_sekolah;
            $row->no_hp = '0' . $row->no_hp;
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $rows = User::find($id);

        if($request->password == true) {
            $rows->update([
                'name' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'level' => 'Operator',
                'id_sekolah' => $request->id_sekolah,
                'updated_at' => now()
            ]);
        } else {
            $rows->update([
                'name' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'level' => 'Operator',
                'id_sekolah' => $request->id_sekolah,
                'updated_at' => now()
            ]);
        }
        return redirect(route('admin.pengguna'));
    }

    public function store(Request $request)
    {
        if($request->password == true) {
            DB::table('users')->insert([
                'name' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'level' => 'Operator',
                'id_sekolah' => $request->id_sekolah,
                'updated_at' => now()
            ]);
        } else {
            DB::table('users')->insert([
                'name' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => '',
                'level' => 'Operator',
                'id_sekolah' => $request->id_sekolah,
                'updated_at' => now()
            ]);
        }
        return redirect(route('admin.pengguna'));
    }

    public function destroy($id)
    {
        $rows = User::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.pengguna'));
    }

    public function find($id)
    {
        $data = User::select('*')->where('id', $id)->get();

        return json_encode(array('data' => $data));
    }
}
