<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function changePassword($id)
    {
        $arr['user'] = DB::table('users')->where('id', $id)->first();
        return view('admin.user.reset-password')->with($arr);
    }

    public function changePasswordSave(Request $request)
    {

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // dd($request->userID);
        $password = Hash::make($request->password);


        $affected = DB::table('users')
            ->where('id', $request->userID)
            ->update(['password' => $password]);

        return redirect('user');
    }

    public function index()
    {
        if (Gate::denies('admin-only')) {
            return redirect()->route('dashboard');
        }
        $arr['users'] = User::All();
        return view('admin.user.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::allows('admin-only')) {
            return view('auth.register');
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $arr['selectedWarehouses'] = DB::table('user_warehouse')->where('user_id', $id)->pluck('warehouse_id')->toArray();
        // dd($arr['selectedWarehouses']);
        $arr['user'] = DB::table('users')->where('id', $id)->first();
        $arr['warehouses'] = DB::table('warehouses')->get();
        return view('admin.user.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $deleted = DB::table('user_warehouse')->where('user_id', $user->id)->delete();
        // $N = count($request->warehouse);
        // dd($N);
        if (isset($request->warehouse)){
            $N = count($request->warehouse);
            for ($i = 0; $i < $N; $i++) {
                $warehouse_name = DB::table('warehouses')->where('id', $request->warehouse[$i])->value('warehouse_name');
                DB::table('user_warehouse')->insert([
                    [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'warehouse_id' => $request->warehouse[$i],
                        'warehouse_name' => $warehouse_name
                    ],
                ]);
            }
        }


        $user->position = $request->position;
        $user->save();
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        if (Gate::denies('admin-only')) {
            return redirect()->route('user.index');
        }
        //$user->roles()->detach();
        // $user->warehouses()->detach();
        $user->delete();
        return redirect()->route('user.index');
    }
}
