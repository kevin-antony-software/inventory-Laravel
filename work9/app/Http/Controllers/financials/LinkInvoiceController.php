<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\Cheque;
use App\Models\financials\LinkInvoice;
use App\Models\financials\Payment;
use App\Models\inventory\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LinkInvoiceController extends Controller
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

        $arr['invoices'] = Invoice::select('id', 'dueAmount')->where('customer_name', $payment->customer_name)->get();
        $arr['payment'] = $payment;
        return view('admin.financials.invoiceLink.create')->with($arr);
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
            $invoiceID = "invoiceID" . $i;
            $dueAmount = 'dueAmount' . $i;
            $paidAmount = "paidAmount" . $i;

            if ($request->$invoiceID != "") {
                $validatedData = $request->validate([
                    $dueAmount => 'required|numeric',
                    $paidAmount => 'required|numeric',
                ]);
            }
        }

        if ($payment->method == 'Cash' || $payment->method == 'BankTransfer') {
            for ($i = 1; $i <= 15; $i++) {
                $invoiceID = "invoiceID" . $i;
                $dueAmount = 'dueAmount' . $i;
                $paidAmount = "paidAmount" . $i;
                if ($request->$invoiceID != "") {
                    DB::table('invoices')->where('id', $request->$invoiceID)->increment('payed', $request->$paidAmount);
                    DB::table('invoices')->where('id', $request->$invoiceID)->decrement('dueAmount', $request->$paidAmount);
                    $invoice = Invoice::where('id', $request->$invoiceID)->first();
                    $CollectionDays = $payment->created_at->diffInDays($invoice->created_at);
                    $calCommissionValue = $this->calCommission($CollectionDays, $request->$paidAmount);
                    $calCommissionPercentage = $this->calCommissionPercent($CollectionDays);
                    DB::table('invoice_payment')->insert([
                        'payment_id' => $payment->id,
                        'payment_date' => $payment->created_at,
                        'payment_method' => $payment->method,
                        'invoice_id' => $invoice->id,
                        'invoice_date' => $invoice->created_at,
                        'customer_id' => $invoice->customer_id,
                        'customer_name' => $invoice->customer_name,
                        'commission_owner' => $invoice->commission_user_ID,
                        'amount' => $request->$paidAmount,
                        'days' => $CollectionDays,
                        'commission_percentage' => $calCommissionPercentage,
                        'commission' => $calCommissionValue,
                        'balance' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $request->$paidAmount);
                    DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $request->$paidAmount);
                    if (DB::table('invoices')->where('id', $request->$invoiceID)->value('dueAmount') < 1) {
                        DB::table('invoices')
                            ->where('id', $request->$invoiceID)
                            ->update(['status' => 'paid']);
                    }
                    $month = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->month;
                    $year = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->year;
                    $owner = $invoice->commission_user_ID;

                    $affected = DB::table('commission')
                        ->where([
                            ['month', $month],
                            ['year', $year],
                            ['owner_id', $owner]
                        ])
                        ->increment('totalCommission', $calCommissionValue);
                }
            }
        }
        if ($payment->method == 'Cheque') {
            $paymentLinks = array();
            $cheques = Cheque::where('payment_id', $paymentID)->orderBy('chequeDate', 'asc')->get();
            for ($i = 1; $i <= 15; $i++) {
                $invoiceID = "invoiceID" . $i;
                $dueAmount = 'dueAmount' . $i;
                $paidAmount = "paidAmount" . $i;
                if ($request->$invoiceID != "") {
                    $paymentLinks[$i - 1] = array(
                        'invoiceID' => $request->$invoiceID,
                        'amount' => $request->$paidAmount,
                        'balance' => $request->$paidAmount
                    );
                }
            }

            $keys = array_column($paymentLinks, 'invoiceID');
            array_multisort($keys, SORT_ASC, $paymentLinks);

            for ($j = 0; $j < count($cheques); $j++) {
                for ($k = 0; $k < count($paymentLinks); $k++) {
                    if ($cheques[$j]->balance == 0) {
                        break;
                    }
                    if ($paymentLinks[$k]['balance'] != 0) {
                        if ($cheques[$j]->balance > $paymentLinks[$k]['balance']) {

                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->increment('payed', $paymentLinks[$k]['balance']);
                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->decrement('dueAmount', $paymentLinks[$k]['balance']);
                            $invoice = Invoice::where('id', $paymentLinks[$k]['invoiceID'])->first();
                            $formatted_dt1 = Carbon::parse($cheques[$j]->chequeDate);
                            $CollectionDays = $formatted_dt1->diffInDays($invoice->created_at);
                            $calCommissionValue = $this->calCommission($CollectionDays, $paymentLinks[$k]['balance']);
                            $month = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->month;
                            $year = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->year;
                            $owner = $invoice->commission_user_ID;
                            $affected = DB::table('commission')
                                ->where([
                                    ['month', $month],
                                    ['year', $year],
                                    ['owner_id', $owner]
                                ])
                                ->increment('totalCommission', $calCommissionValue);
                            $calCommissionPercentage = $this->calCommissionPercent($CollectionDays);
                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'invoice_id' => $invoice->id,
                                'invoice_date' => $invoice->created_at,
                                'customer_id' => $invoice->customer_id,
                                'customer_name' => $invoice->customer_name,
                                'commission_owner' => $invoice->commission_user_ID,
                                'amount' => $paymentLinks[$k]['balance'],
                                'days' => $CollectionDays,
                                'commission_percentage' => $calCommissionPercentage,
                                'commission' => $calCommissionValue,
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $paymentLinks[$k]['balance']);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $paymentLinks[$k]['balance']);
                            if (DB::table('invoices')->where('id', $invoice->id)->value('dueAmount') < 1) {
                                DB::table('invoices')
                                    ->where('id', $invoice->id)
                                    ->update(['status' => 'paid']);
                            }

                            $cheques[$j]->balance = $cheques[$j]->balance - $paymentLinks[$k]['balance'];
                            $cheques[$j]->save();
                            $paymentLinks[$k]['balance'] = 0;
                        } else if ($cheques[$j]->balance < $paymentLinks[$k]['balance']) {

                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->increment('payed', $cheques[$j]->balance);
                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->decrement('dueAmount', $cheques[$j]->balance);
                            $invoice = Invoice::where('id', $paymentLinks[$k]['invoiceID'])->first();
                            $formatted_dt1 = Carbon::parse($cheques[$j]->chequeDate);
                            $CollectionDays = $formatted_dt1->diffInDays($invoice->created_at);
                            $calCommissionValue = $this->calCommission($CollectionDays, $cheques[$j]->balance);
                            $month = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->created_at)->month;
                            $year = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->created_at)->year;
                            $owner = $invoice->commission_user_ID;
        
                            $affected = DB::table('commission')
                                ->where([
                                    ['month', $month],
                                    ['year', $year],
                                    ['owner_id', $owner]
                                ])
                                ->increment('totalCommission', $calCommissionValue);
                            $calCommissionPercentage = $this->calCommissionPercent($CollectionDays);
                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'invoice_id' => $invoice->id,
                                'invoice_date' => $invoice->created_at,
                                'customer_id' => $invoice->customer_id,
                                'customer_name' => $invoice->customer_name,
                                'commission_owner' => $invoice->commission_user_ID,
                                'amount' => $cheques[$j]->balance,
                                'days' => $CollectionDays,
                                'commission_percentage' => $calCommissionPercentage,
                                'commission' => $calCommissionValue,
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $cheques[$j]->balance);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $cheques[$j]->balance);
                            if (DB::table('invoices')->where('id', $invoice->id)->value('dueAmount') < 1) {
                                DB::table('invoices')
                                    ->where('id', $invoice->id)
                                    ->update(['status' => 'paid']);
                            }

                            $paymentLinks[$k]['balance'] = $paymentLinks[$k]['balance'] - $cheques[$j]->balance;
                            $cheques[$j]->balance = 0;
                            $cheques[$j]->save();
                        } else if ($cheques[$j]->balance == $paymentLinks[$k]['balance']) {

                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->increment('payed', $cheques[$j]->balance);
                            DB::table('invoices')->where('id', $paymentLinks[$k]['invoiceID'])->decrement('dueAmount', $cheques[$j]->balance);
                            $invoice = Invoice::where('id', $paymentLinks[$k]['invoiceID'])->first();
                            $formatted_dt1 = Carbon::parse($cheques[$j]->chequeDate);
                            $CollectionDays = $formatted_dt1->diffInDays($invoice->created_at);
                            $calCommissionValue = $this->calCommission($CollectionDays, $cheques[$j]->balance);
                            $month = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->created_at)->month;
                            $year = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->created_at)->year;
                            $owner = $invoice->commission_user_ID;
        
                            $affected = DB::table('commission')
                                ->where([
                                    ['month', $month],
                                    ['year', $year],
                                    ['owner_id', $owner]
                                ])
                                ->increment('totalCommission', $calCommissionValue);
                            $calCommissionPercentage = $this->calCommissionPercent($CollectionDays);
                            DB::table('invoice_payment')->insert([
                                'payment_id' => $payment->id,
                                'payment_date' => $payment->created_at,
                                'payment_method' => $payment->method,
                                'cheque_id' => $cheques[$j]->id,
                                'invoice_id' => $invoice->id,
                                'invoice_date' => $invoice->created_at,
                                'customer_id' => $invoice->customer_id,
                                'customer_name' => $invoice->customer_name,
                                'commission_owner' => $invoice->commission_user_ID,
                                'amount' => $cheques[$j]->balance,
                                'days' => $CollectionDays,
                                'commission_percentage' => $calCommissionPercentage,
                                'commission' => $calCommissionValue,
                                'balance' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            DB::table('payments')->where('id', $request->paymentID)->decrement('balanceToAllocate', $cheques[$j]->balance);
                            DB::table('payments')->where('id', $request->paymentID)->increment('allocatedToInvoice', $cheques[$j]->balance);
                            if (DB::table('invoices')->where('id', $invoice->id)->value('dueAmount') < 1) {
                                DB::table('invoices')
                                    ->where('id', $invoice->id)
                                    ->update(['status' => 'paid']);
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
     * @param  \App\Models\financials\LinkInvoice  $linkInvoice
     * @return \Illuminate\Http\Response
     */
    private function calCommissionPercent($collectionDays)
    {
        if ($collectionDays < 90) {
            $comValue = 3;
        } elseif ($collectionDays < 124) {
            $comValue = 2;
        } else {
            $comValue = 0;
        }
        return $comValue;
    }
    private function calCommission($CollectionDays, $paidAmount)
    {
        if ($CollectionDays < 90) {
            $comValue = $paidAmount * 0.03;
        } elseif ($CollectionDays < 124) {
            $comValue = $paidAmount * 0.02;
        } else {
            $comValue = 0;
        }
        return $comValue;
    }

    public function show(LinkInvoice $linkInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financials\LinkInvoice  $linkInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(LinkInvoice $linkInvoice)
    {
        dd($linkInvoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financials\LinkInvoice  $linkInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LinkInvoice $linkInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financials\LinkInvoice  $linkInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(LinkInvoice $linkInvoice)
    {
        //
    }
}
