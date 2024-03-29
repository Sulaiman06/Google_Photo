<?php

namespace App\Http\Controllers;

use App\Models\Photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cloudinary\Cloudinary;
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
	$file = $request->file('picture');
        $pictureName = $request->name . '_' . Carbon::now();
        $cloudinary = new Cloudinary(
    	    [
              	'cloud' => [
            	    'cloud_name' => 'dtwzikt2h',
            	    'api_key'    => '996275865326779',
             	    'api_secret' => 'xD8CoE6NOVGFtuzLU8EYCOPoP3o',
            	],
	    ]
	);
	$cloudinary->uploadApi()->upload("$file", ['public_id' => "gambar/$pictureName"]);

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
            $pictureName = $request->name . '_' . Carbon::now();
            $file = $request->file('picture');

	    $cloudinary = new Cloudinary(
            	[
                    'cloud' => [
                    	'cloud_name' => 'dtwzikt2h',
                    	'api_key'    => '996275865326779',
                    	'api_secret' => 'xD8CoE6NOVGFtuzLU8EYCOPoP3o',
                    ],
            	]
            );
            $cloudinary->uploadApi()->upload("$file", ['public_id' => "gambar/$pictureName"]);

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
