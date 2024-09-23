<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SurveyController extends Controller
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
        $this->survey = new Survey();
        $this->data[ 'title' ] = 'Data Survey';
        $this->data[ 'link' ] = '/admin/survey';
        $this->page = '/admin/survey';
    }

    /*
     * Dashboad Function
    */
    public function index()
    {
        return view('admin/survey/index', $this->data);
    }


    public function json()
    {
        $data = Survey::select('*')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $rows = Survey::find($id);
        $rows->update([
            'judul' => $request->judul
        ]);

        return redirect(route('admin.bus'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'judul' => 'required|min:5',
         ]);

        DB::table('survey')->insert([
           'judul' => $request->judul
        ]);

        return redirect($this->page);

    }

    public function destroy($id)
    {
        $rows = Survey::findOrFail($id);
        $rows->delete();

        return redirect(route('admin.bus'));
    }

    public function find($id)
    {
        $data = Survey::select('*')->where('id', $id)->get();

        return json_encode(array('data' => $data));
    }
}
