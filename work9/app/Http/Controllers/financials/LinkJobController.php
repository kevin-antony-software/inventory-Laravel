<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\Cheque;
use App\Models\financials\LinkJob;
use App\Models\financials\Payment;
use App\Models\inventory\Invoice;
use App\Models\technical\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LinkJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function link($id)
    {
        $payment = Payment::where('id', $id)->first();
        $arr['jobs'] = Job::select('id', 'dueAmount')->where('customer_name', $payment->customer_name)->get();
        $arr['payment'] = $payment;
        return view('admin.financials.jobLink.create')->with($arr);
    }

    public function create()
    {
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
        $paymentID = $request->paymentID;
        $payment = Payment::where('id', $paymentID)->first();

        for ($i = 1; $i <= 15; $i++) {
            $jobID = "jobID" . $i;
            $dueAmount = 'dueAmount' . $i;
            $paidAmount = "paidAmount" . $i;

            if ($request->$jobID != "") {
                $validatedData = $request->validate([
                    $dueAmount => 'required|numeric',
                    $paidAmount => 'required|numeric',
                ]);
            }
        }

        if ($payment->method == 'Cash' || $payment->method == 'BankTransfer') {
            for ($i = 1; $i <= 15; $i++) {
                $jobID = "jobID" . $i;
                $dueAmount = 'dueAmount' . $i;
                $paidAmount = "paidAmount" . $i;
                if ($request->$jobID != "") {
                    DB::table('jobs')->where('id', $request->$jobID)->increment('PaidAmount', $request->$paidAmount);
                    DB::table('jobs')->where('id', $request->$jobID)->decrement('dueAmount', $request->$paidAmount);
                    $job = Job::where('id', $request->$jobID)->first();

                    DB::table('invoice_payment')->insert([
                        'payment_id' => $payment->id,
                        'payment_date' => $payment->created_at,
                        'payment_method' => $payment->method,
                        'job_id' => $job->id,
                        'job_closed_date' => $job->jobClosedTime,
                        'customer_id' => $job->customer_id,
                        'customer_name' => $job->customer_name,
                        'commission_owner' => 0,
                        'amount' => $request->$paidAmount,
                        'balance' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    DB::table('payments')->where('id', $payment->id)->decrement('balanceToAllocate', $request->$paidAmount);
                    DB::table('payments')->where('id', $payment->id)->increment('allocatedToInvoice', $request->$paidAmount);
                    if (DB::table('jobs')->where('id', $job->id)->value('dueAmount') < 1) {
                        DB::table('jobs')
                            ->where('id', $request->$jobID)
                            ->update(['payment_status' => 'paid']);
                    }
                }
            }
        }
        if ($payment->method == 'Cheque') {
            $paymentLinks = array();
            $cheques = Cheque::where('payment_id', $paymentID)->orderBy('chequeDate', 'asc')->get();
            for ($i = 1; $i <= 15; $i++) {
                $jobID = "jobID" . $i;
                $dueAmount = 'dueAmount' . $i;
                $paidAmount = "paidAmount" . $i;
                if ($request->$jobID != "") {
                    $paymentLinks[$i - 1] = array(
                        'jobID' => $request->$jobID,
                        'amount' => $request->$paidAmount,
                        'balance' => $request->$paidAmount
                    );
                }
            }

            $keys = array_column($paymentLinks, 'jobID');
            array_multisort($keys, SORT_ASC, $paymentLinks);

            for ($j = 0; $j < count($cheques); $j++) {
                for ($k = 0; $k < count($paymentLinks); $k++) {
                    if ($cheques[$j]->balance == 0) {
                        break;
                    }
                    if ($paymentLinks[$k]['balance'] != 0) {
                        if ($cheques[$j]->balance > $paymentLinks[$k]['balance']) {

                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->increment('PaidAmount', $paymentLinks[$k]['balance']);
                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->decrement('dueAmount', $paymentLinks[$k]['balance']);
                            $job = Job::where('id', $paymentLinks[$k]['jobID'])->first();

                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'job_id' => $job->id,
                                'job_closed_date' => $job->jobClosedTime,
                                'customer_id' => $job->customer_id,
                                'customer_name' => $job->customer_name,
                                'commission_owner' => 0,
                                'amount' => $paymentLinks[$k]['balance'],
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $paymentLinks[$k]['balance']);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $paymentLinks[$k]['balance']);
                            if (DB::table('jobs')->where('id', $job->id)->value('dueAmount') < 1) {
                                DB::table('jobs')
                                    ->where('id', $job->id)
                                    ->update(['payment_status' => 'paid']);
                            }

                            $cheques[$j]->balance = $cheques[$j]->balance - $paymentLinks[$k]['balance'];
                            $cheques[$j]->save();
                            $paymentLinks[$k]['balance'] = 0;
                        } else if ($cheques[$j]->balance < $paymentLinks[$k]['balance']) {

                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->increment('PaidAmount', $cheques[$j]->balance);
                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->decrement('dueAmount', $cheques[$j]->balance);
                            $job = Job::where('id', $paymentLinks[$k]['jobID'])->first();
       
                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'job_id' => $job->id,
                                'job_closed_date' => $job->jobClosedTime,
                                'customer_id' => $job->customer_id,
                                'customer_name' => $job->customer_name,
                                'commission_owner' => 0,
                                'amount' => $cheques[$j]->balance,
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $cheques[$j]->balance);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $cheques[$j]->balance);
                            if (DB::table('jobs')->where('id', $job->id)->value('dueAmount') < 1) {
                                DB::table('jobs')
                                    ->where('id', $job->id)
                                    ->update(['payment_status' => 'paid']);
                            }

                            $paymentLinks[$k]['balance'] = $paymentLinks[$k]['balance'] - $cheques[$j]->balance;
                            $cheques[$j]->balance = 0;
                            $cheques[$j]->save();
                        } else if ($cheques[$j]->balance == $paymentLinks[$k]['balance']) {

                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->increment('PaidAmount', $cheques[$j]->balance);
                            DB::table('jobs')->where('id', $paymentLinks[$k]['jobID'])->decrement('dueAmount', $cheques[$j]->balance);
                            $job = Job::where('id', $paymentLinks[$k]['jobID'])->first();
       
                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'job_id' => $job->id,
                                'job_closed_date' => $job->jobClosedTime,
                                'customer_id' => $job->customer_id,
                                'customer_name' => $job->customer_name,
                                'commission_owner' => 0,
                                'amount' => $cheques[$j]->balance,
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $cheques[$j]->balance);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $cheques[$j]->balance);
                            if (DB::table('jobs')->where('id', $job->id)->value('dueAmount') < 1) {
                                DB::table('jobs')
                                    ->where('id', $job->id)
                                    ->update(['payment_status' => 'paid']);
                            }

                            $cheques[$j]->balance = 0;
                            $cheques[$j]->save();
                            $paymentLinks[$k]['balance'] = 0;
                        }
                    }
                }
            }
        }

        return redirect()->route('payment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\financials\LinkJob  $linkJob
     * @return \Illuminate\Http\Response
     */
    public function show(LinkJob $linkJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financials\LinkJob  $linkJob
     * @return \Illuminate\Http\Response
     */
    public function edit(LinkJob $linkJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financials\LinkJob  $linkJob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LinkJob $linkJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financials\LinkJob  $linkJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(LinkJob $linkJob)
    {
        //
    }
}
