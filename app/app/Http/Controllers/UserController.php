<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreLecturerRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::query()->with('department')->paginate(7);
        // $data['users'] = User::withTrashed()->with('department')->paginate(7);

        // withTrashed() menyertakan data beserta baris yang telah dihapus
        // onlytrashed() hanya menampilkan data yang telah dihapus
        // restore() mengembalikan data (set deleted_at to null)
        // forceDelete() delete dari basis data
        // restoreAll() mengembalikan semua data
        // forceDeleteAll() menghapus keseluruhan data dari basis data
        $penerima = [
            'mail1@mail.com',
            'mail2@mail.com',
            'mail3@mail.com',
            'mail4@mail.com',
            'mail5@mail.com',
            'mail6@mail.com',
            'mail7@mail.com',
            'mail8@mail.com',
            'mail9@mail.com',
            'mail10@mail.com',
        ];
        // foreach($penerima as $pen) {
        //     Mail::to($pen)->send(new TestMail());
        // }

        return view('user', $data);
    }

    public function user_form_lecturer ()
    {
        $departments = Department::all();
        return view('user_form_lecturer', compact('departments'));
    }

     public function user_store_lecturer (StoreLecturerRequest $req)
    {
        // $validated = $req->validate([
        //     'username' => 'min:8|required',
        //     'email' => 'email|required',
        //     'password' => 'min:8|required'
        // ]);

        // $validated['firstname'] = $req->firstname;
        // $validated['lastname'] = $req->lastname;
        // $validated['department_id'] = $req->department_id;
        // $validated['role'] = 1;

        // $validated2 = $req->validate([
        //     'nidn' => 'required|digits:10'
        // ]);

        // $validated2['address'] = $req->address;

        // $user= User::create($validated);

        // $validated2['user_id'] = $user->id;
        // $user = User::create([
        //     'username' => $req->username,
        //     'firstname' => $req->firstname,
        //     'lastname' => $req->lastname,
        //     'email' => $req->email,
        //     'password' => Hash::make($req->password),
        //     'department_id' => $req->department_id,
        //     'role' => 1
        // ]);

        // Lecturer::create($validated2);
        // Lecturer::create([
        //     'nidn' => $req->nidn,
        //     'address' => $req->address,
        //     'user_id' => $user->id
        // ]);

        $validatedUser = $req->safe()->except(['nidn','address']);
        $validatedUser['role'] = 1;
        $user = User::create($validatedUser);

        $validatedLecturer = $req->safe()->only(['nidn','address']);
        $validatedLecturer['user_id'] = $user->id;
        Lecturer::create($validatedLecturer);

        return redirect()->route('user.index');
    }
}
