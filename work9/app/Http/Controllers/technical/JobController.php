<?php

namespace App\Http\Controllers\technical;

use App\Http\Controllers\Controller;
use App\Models\technical\Job;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App;
use App\Models\Customer;
use App\Models\technical\Jobdetail;
use App\Models\technical\Component;
use App\Models\technical\Issue;
use App\Models\technical\MachineModel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Gate;
// use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;
use ImageOptimizer;
use Image;
use View;


class JobController extends Controller
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

    public function jobSummary()
    {
        $arr['jobs'] = DB::table('jobs')->orderBy('id', 'desc')->get();
        return view('admin.technical.jobs.jobsAll')->with($arr);
    }

    public function index()
    {

        $arr['jobs'] = Job::orderBy('id', 'desc')->paginate(25);
        return view('admin.technical.jobs.index')->with($arr);
    }
    public function printDetail($id)
    {
        $job = Job::where('id', $id)->first();
        $arr['jobDetails'] = DB::table('jobdetails')->where('job_id', $id)->get();
        $arr['job'] = $job;
        $arr['customer'] = Customer::where('id', $job->customer_id)->first();

        $pdf = PDF::loadView('admin.technical.jobs.printDetail', $arr);
        return $pdf->download('Repair_invoice.pdf');

    }

    public function print($id)
    {
        $job = Job::where('id', $id)->first();
        $arr['job'] = $job;
        $arr['customer'] = Customer::where('id', $job->customer_id)->first();

        $pdf = PDF::loadView('admin.technical.jobs.print', $arr);
        return $pdf->download('Repair_invoice.pdf');

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('tech-executive-only')) {
            return redirect()->route('dashboard');
        }
        $arr['models'] = MachineModel::orderBy('name', 'desc')->get();
        $arr['customers'] = Customer::orderBy('customer_name', 'desc')->get();
        return view('admin.technical.jobs.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job)
    {
        if (Gate::denies('tech-executive-only')) {
            return redirect()->route('dashboard');
        }

        $validatedData = $request->validate([
            'customer_name' => 'required',
            'serialNumber' => 'required',
            'model' => 'required',
            'promptIn' => 'required',
            'machine' => 'required',
            'warranty' => 'required',
        ]);
        if (DB::table('customers')->where('customer_name', $request->customer_name)->doesntExist()) {
            return redirect()->route('jobs.create')->with('error', 'no customer with that name')->withInput();
        }

        $job->customer_name = $request->customer_name;
        $job->customer_id = Customer::where('customer_name', $request->customer_name)->value('id');
        $job->serialNum = $request->serialNumber;
        $job->soldDate = $request->soldDate;
        $job->model = $request->model;
        $job->duration = 0;
        $job->promptIn = $request->promptIn;
        $job->machineType = $request->machine;
        if ($request->warranty == "withWarranty") {
            $job->warranty = "withWarranty";
        } else {
            $job->warranty = "withoutWarranty";
        }

        $job->jobStatus = "Job-Created";
        $jobRepairTimes = DB::table('jobs')->where('serialNum', $request->serialNumber)->count();
        $job->repairTimes = $jobRepairTimes;
        $job->save();

        if ($jobRepairTimes > 0) {
            $textMessage = "this machine with serial " . $request->serialNumber . " repair for " . $jobRepairTimes . 'times';
            $textBossMobile = "94725681335";
            if ($textBossMobile) {
                $user = "94777696922";
                $password = "5177";
                $text = urlencode($textMessage);
                $to = $textBossMobile;
                $baseurl = "http://www.textit.biz/sendmsg";
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                $ret = file($url);
                $res = explode(":", $ret[0]);
                if (trim($res[0]) == "OK") {
                    echo "Message Sent - ID : " . $res[1];
                } else {
                    echo "Sent Failed - Error : " . $res[1];
                }
            }
        }
        if ($jobRepairTimes > 1) {
            $textMessage = "this machine with serial " . $request->serialNumber . " repair for " . $jobRepairTimes . 'times';;
            $textBossMobile = "94777770091";
            if ($textBossMobile) {
                $user = "94777696922";
                $password = "5177";
                $text = urlencode($textMessage);
                $to = $textBossMobile;
                $baseurl = "http://www.textit.biz/sendmsg";
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
                $ret = file($url);
                $res = explode(":", $ret[0]);
                if (trim($res[0]) == "OK") {
                    echo "Message Sent - ID : " . $res[1];
                } else {
                    echo "Sent Failed - Error : " . $res[1];
                }
            }
        }

        return redirect()->route('jobs.index')->with('message', 'new job created');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $arr['job'] = $job;
        $arr['image'] = DB::table('image_u')->where('job_id', $job->id)->first();
        $arr['componentsAdded'] = DB::table('jobdetails')->where('job_id', $job->id)->get();
        return view('admin.technical.jobs.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */

    public function suspend(Job $job)
    {
        $job->jobStatus = 'suspended';
        $startTime = Carbon::parse($job->jobStartTime);
        $finishTime = Carbon::parse(Carbon::now());
        $totalDuration = $finishTime->diffInMinutes($startTime);
        $job->duration = $job->duration + $totalDuration;
        $job->save();
        return redirect()->route('jobs.index');
    }

    public function estimate(Job $job)
    {
        $job->jobStatus = 'estimate';
        $startTime = Carbon::parse($job->jobStartTime);
        $finishTime = Carbon::parse(Carbon::now());
        $totalDuration = $finishTime->diffInMinutes($startTime);
        $job->duration = $job->duration + $totalDuration;
        if ($job->jobStatus == 'jobClosed' || $job->jobStatus == 'imageUploaded' || $job->jobStatus == 'Delivered') {
            $job->dueAmount = $job->finalTotal;
        }
        $job->save();
        $arr['job'] = $job;
        $arr['components'] = Component::orderBy('component_name', 'desc')->get();
        return view('admin.technical.jobs.estimateJob')->with($arr);
    }

    public function changeWarranty(Job $job)
    {
        if ($job->PaidAmount > 0) {
            return redirect()->route('jobs.index');
        } else {
            if ($job->warranty == 'withWarranty') {
                $job->warranty = 'withoutWarranty';
                $job->dueAmount = $job->finalTotal;
                $job->save();
            } else if ($job->warranty == 'withoutWarranty') {
                $job->warranty = 'withWarranty';
                $job->dueAmount = 0;
                $job->save();
            }
        }

        return redirect()->route('jobs.index');
    }

    public function edit(Job $job)
    {
        if ($job->jobStatus == 'Job-Created') {
            $job->jobStatus = 'jobStarted';
            $user = auth()->user();
            $job->jobStartUser_id = $user->id;
            $job->jobStartUser_name = $user->name;
            $job->jobStartTime = Carbon::now();
            $job->save();
            return redirect()->route('jobs.index');
        } else if ($job->jobStatus == 'suspended') {
            $job->jobStatus = 'jobStarted';
            $job->jobStartTime = Carbon::now();
            $user = auth()->user();
            $job->jobStartUser_id = $user->id;
            $job->jobStartUser_name = $user->name;
            $job->save();
            $arr['jobs'] = Job::orderBy('id', 'desc')->get();
            return redirect()->route('jobs.index');
        } else if ($job->jobStatus == 'estimated') {
            $arr['job'] = $job;
            $arr['jobDetails'] = Jobdetail::where('job_id', $job->id)->get();
            $arr['components'] = Component::orderBy('component_name', 'desc')->get();
            return view('admin.technical.jobs.estimated')->with($arr);
        } else if ($job->jobStatus == 'jobStarted') {
            $arr['job'] = $job;
            $arr['components'] = Component::orderBy('component_name', 'desc')->get();
            $arr['issues'] = Issue::all();
            return view('admin.technical.jobs.closeJob')->with($arr);
        } else if ($job->jobStatus == 'jobClosed') {
            $arr['job'] = $job;
            return view('admin.technical.jobs.uploadImages')->with($arr);
        } else if ($job->jobStatus == 'imageUploaded') {
            $arr['job'] = $job;
            return view('admin.technical.jobs.deliver')->with($arr);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        if ($job->jobStatus == 'jobStarted' || $job->jobStatus == 'estimate' || $job->jobStatus == 'estimated') {
            $componentCharges = 0;

            for ($i = 1; $i < 30; $i++) {
                $quantity1 = "quantity_" . $i;
                $product_id = "itemNo_" . $i;
                $itemName = "itemName_" . $i;
                $itemPrice = "itemPrice_" . $i;

                if ($request->$quantity1 != "") {

                    if ($job->jobStatus == 'estimated') {
                        $jobDetail = Jobdetail::where('job_id', $job->id)->first();
                    } else {
                        $jobDetail = new Jobdetail();
                    }

                    $jobDetail->job_id = $job->id;
                    $jobDetail->component_id = $request->$product_id;
                    $jobDetail->component_name = $request->$itemName;
                    $jobDetail->component_price = $request->$itemPrice;
                    $jobDetail->qty = $request->$quantity1;
                    $jobDetail->subTotal = $request->$itemPrice * $request->$quantity1;
                    $jobDetail->save();
                    $componentCharges += $jobDetail->subTotal;
                    //modify the inventory
                    if ($job->jobStatus == 'jobStarted' || $job->jobStatus == 'estimated') {
                        DB::table('stocks')
                            ->where('component_id', $request->$product_id)
                            ->decrement('qty', $request->$quantity1);
                    }
                }
            }

            $job->componentCharges = $componentCharges;
            $job->repairCharges = $request->repairCharges;
            $job->totalCharges = $request->totalCharges;
            $job->discount = $request->discount;
            $job->finalTotal = $request->finalTotal;
            if ($job->warranty == 'withoutWarranty') {
                $job->dueAmount = $request->finalTotal;
            } else {
                $job->dueAmount = 0;
            }
            $job->PaidAmount = 0;
            
            if($request->commonIssue == 'common'){

                $job->issue = $request->issueOld;
            } else {
                
                $job->issue = $request->issue;
            }
            
            $job->payment_status = 'not paid';
            $user = auth()->user();
            $job->jobClosedUser_id = $user->id;
            $job->jobClosedUser_name = $user->name;
            $job->jobClosedTime = Carbon::now();
            $startTime = Carbon::parse($job->jobStartTime);
            $finishTime = Carbon::parse(Carbon::now());

            $totalDuration = $finishTime->diffInMinutes($startTime);
            $job->duration = $job->duration + $totalDuration;

            if ($job->jobStatus == 'estimate') {
                $job->jobStatus = 'estimated';
            } else {
                $job->jobStatus = 'jobClosed';
            }
            $job->save();
            return redirect()->route('jobs.index')->with('message', 'job was updated');
        } else if ($job->jobStatus == 'jobClosed') {

            $validatedData = $request->validate([
                'image1' => 'image|nullable',
                'image2' => 'image|nullable',
                'image3' => 'image|nullable',
                'image4' => 'image|nullable',
                'image5' => 'image|nullable',
            ]);
            // $directory = 'images/job' . $job->id;
            // Storage::makeDirectory($directory);
            $directory = 'images';

            if ($request->hasFile('image1')) {

                $imageName1 = 'job' . $job->id . '_1_.' . $request->file('image1')->extension();
                $request->image1->move(public_path('images'), $imageName1);

            } else {
                $imageName1 = 'noimage.jpg';
            }
            if ($request->hasFile('image2')) {
                $imageName2 = 'job' . $job->id . '_2_.' . $request->file('image2')->extension();
                $request->image2->move(public_path('images'), $imageName2);

                
            } else {
                $imageName2 = 'noimage.jpg';
            }
            if ($request->hasFile('image3')) {
                $imageName3 = 'job' . $job->id . '_3_.' . $request->file('image3')->extension();
                $request->image3->move(public_path('images'), $imageName3);
                
            } else {
                $imageName3 = 'noimage.jpg';
            }
            if ($request->hasFile('image4')) {
                $imageName4 = 'job' . $job->id . '_4_.' . $request->file('image4')->extension();
                $request->image4->move(public_path('images'), $imageName4);
                
            } else {
                $imageName4 = 'noimage.jpg';
            }
            if ($request->hasFile('image5')) {
                $imageName5 = 'job' . $job->id . '_5_.' . $request->file('image5')->extension();
                $request->image5->move(public_path('images'), $imageName5);
                
            } else {
                $imageName5 = 'noimage.jpg';
            }

            DB::table('image_u')->insert(
                [
                    'job_id' => $job->id,
                    // 'imagepath4' => $fileNameToStore1,
                    // 'imagepath2' => $fileNameToStore2,
                    // 'imagepath3' => $fileNameToStore3,
                    // 'imagepath4' => $fileNameToStore4,
                    // 'imagepath5' => $fileNameToStore5,

                    'imagepath1' => $imageName1,
                    'imagepath2' => $imageName2,
                    'imagepath3' => $imageName3,
                    'imagepath4' => $imageName4,
                    'imagepath5' => $imageName5,

                ]
            );
            $job->jobStatus = 'imageUploaded';
            $job->save();
            return redirect()->route('jobs.index')->with('message', 'job was updated');
        } else if ($job->jobStatus == 'imageUploaded') {
            $job->deliveredDate = Carbon::now();
            $job->comment = $request->comment;
            $job->promptOut = $request->promptOut;
            $job->jobStatus = 'Delivered';
            $job->save();
            return redirect()->route('jobs.index')->with('message', 'job was updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('home');
        }
        if ($job->jobStatus != 'jobClosed' && $job->jobStatus != 'imageUploaded' && $job->jobStatus != 'Delivered') {
            $job->delete();
        }
        return redirect()->route('jobs.index');
    }
}
