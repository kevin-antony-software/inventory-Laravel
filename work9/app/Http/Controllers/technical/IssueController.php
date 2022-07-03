<?php

namespace App\Http\Controllers\technical;

use App\Http\Controllers\Controller;
use App\Models\technical\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
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
    public function index()
    {
        $arr['issues'] = Issue::orderBy('id', 'desc')->get();
        return view('admin.technical.issues.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('home');
        }
        return view('admin.technical.issues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('home');
        }
        $validatedData = $request->validate([
            'issue' => 'required|unique:issues,issue',
        ]);
        $issue = new Issue();
        $issue->issue = $request->issue;
        $issue->save();
        return redirect()->route('issues.index')->with('message', 'new issue created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\technical\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\technical\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\technical\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Issue $issue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\technical\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        //
    }
}
