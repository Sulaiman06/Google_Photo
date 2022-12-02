<?php

namespace App\Http\Controllers;

use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PhotoController extends Controller
{
    public function index(Request $request) {
        $keyword = $request->keyword;
        $photos = Photos::where('name', 'LIKE', '%'.$keyword.'%')->paginate(5);
        return view('photo.index', compact('photos'));
    }

    public function create() {
        return view('photo.create');
    }

    public function store(Request $request) {
        $extensi = $request->file('picture')->getClientOriginalExtension();
        $pictureName = $request->name . '_' . Carbon::now() . '.' . $extensi;
        $request->file('picture')->storeAs('gambar', $pictureName);

        $request->validate([
            'name' => 'unique:photos',
            'picture' => 'mimes:jpg,jpeg,png'
        ]);

        $photo = DB::table('photos')->insert([
            'name' => $request->name,
            'picture' => $pictureName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if($photo) {
            Session::flash('status', 'success');
            Session::flash('message', 'Sukses menambah foto');
        }

        return redirect()->route('photo.index');
    }

    public function show(Photos $photo) {
        DB::table('photos')->where('id', $photo->id)->first();
        return view('photo.show', compact('photo'));
    }

    public function edit(Photos $photo) {
        DB::table('photos')->where('id', $photo->id)->first();
        return view('photo.update', compact('photo'));
    }

    public function update(Request $request, $id) {

        $photo = DB::table('photos')->where('id', $id)->update([
            'name' => $request->name,
            'updated_at' => Carbon::now()
        ]);

        $request->validate([
            'picture' => 'mimes:jpg,jpeg,png'
        ]);

        if($request->hasFile('picture')) {
            
            $extensi = $request->file('picture')->getClientOriginalExtension();
            $pictureName = $request->name . '_' . Carbon::now() . '.' . $extensi;
            $request->file('picture')->storeAs('gambar', $pictureName);
            
            $show = DB::table('photos')->where('id', $id)->first();
            File::delete(public_path('storage/gambar') . '/' . $show->picture);

            $photo = DB::table('photos')->where('id', $id)->update([
                'picture' => $pictureName
            ]);
        }

        if($photo) {
            Session::flash('status', 'success');
            Session::flash('message', 'Sukses mengedit foto');
        }

        return redirect()->route('photo.index');
    }

    public function trash() {
        $photo = Photos::onlyTrashed()->get();

        return view('photo.trash', compact('photo'));
    }

    public function deleted($id) {
        $photo = Photos::find($id)->delete();

        if($photo) {
            Session::flash('status', 'success');
            Session::flash('message', 'Sukses memindahkan foto ke recycle bin');
        }

        return redirect()->route('photo.index');
    }

    public function destroy($id) {

        $show = DB::table('photos')->where('id', $id)->first();
        File::delete(public_path('storage/gambar') . '/' . $show->picture);

        $photo = DB::table('photos')->where('id', $id)->delete();

        if($photo) {
            Session::flash('status', 'success');
            Session::flash('message', 'Sukses menghapus foto');
        }

        return redirect('photos/trash');
    }

    public function restore($id) {
        $photo = Photos::withTrashed()->where('id', $id)->restore();

        if($photo) {
            Session::flash('status', 'success');
            Session::flash('message', 'Sukses mengembalikan foto');
        }

        return redirect()->route('photo.index');
    }
}
