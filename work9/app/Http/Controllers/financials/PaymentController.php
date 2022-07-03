<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\financials\Bank;
use App\Models\financials\Cheque;
use App\Models\financials\Payment;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use PDF;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function print($id)
    {
        $arr['payment'] = DB::table('payments')->where('id', $id)->first();
        $arr['city'] = DB::table('customers')->where(
            'id',
            DB::table('payments')->where('id', $id)->value('customer_id')
        )->value('city');
        $pdf = PDF::loadView('admin.financials.payment.print', $arr);
        return $pdf->download('payment.pdf');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $arr['payments'] = Payment::orderBy('id', 'desc')->paginate(25);
        return view('admin.financials.payment.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customers'] = Customer::select('id', 'customer_name')->orderBy('customer_name', 'asc')->get();
        $arr['banks'] = Bank::select('id', 'name')->get();
        return view('admin.financials.payment.create')->with($arr);
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
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'TotalAmount' => 'required|numeric',
            'customer_name' => 'required',
        ]);

        if (DB::table('customers')->where('customer_name', $request->customer_name)->doesntExist()) {
            return redirect()->route('payment.create')->with('error', 'no customer with that name')->withInput();
        }


        $customerID = Customer::where('customer_name', $request->customer_name)->value('id');
        if ($request->Method == 'Cheque') {
            for ($i = 1; $i < 20; $i++) {
                $chequeNo = 'chequeNo' . $i;
                $bankNo = 'bankNo' . $i;
                $branchNo = 'branchNo' . $i;
                $chequeAmount = 'chequeAmount' . $i;
                $chequeDate = 'chequeDate' . $i;
                if (
                    $request->$chequeNo != '' ||
                    $request->$bankNo != '' ||
                    $request->$branchNo != '' ||
                    $request->$chequeAmount != '' ||
                    $request->$chequeDate != ''
                ) {
                    $validatedData = $request->validate([
                        $chequeNo => 'required|numeric',
                        $bankNo => 'required|numeric',
                        $branchNo => 'required|numeric',
                        $chequeAmount => 'required|numeric',
                        $chequeDate => 'required',
                    ]);
                }
            }
        }
        $idIN = DB::select("SHOW TABLE STATUS LIKE 'payments'");
        $next_id = $idIN[0]->Auto_increment;

        if ($request->Method == 'Cheque') {
            for ($j = 1; $j < 20; $j++) {
                $chequeNo = 'chequeNo' . $j;
                $bankNo = 'bankNo' . $j;
                $branchNo = 'branchNo' . $j;
                $chequeAmount = 'chequeAmount' . $j;
                $chequeDate = 'chequeDate' . $j;

                if ($request->$chequeNo != '') {
                    $cheque = new Cheque();
                    $cheque->payment_id = $next_id;
                    $cheque->number = $request->$chequeNo;
                    $cheque->bank = $request->$bankNo;
                    $cheque->branch = $request->$branchNo;
                    $cheque->amount = $request->$chequeAmount;
                    $cheque->chequeDate = $request->$chequeDate;
                    $cheque->customer_id = $customerID;
                    $cheque->customer_name = $request->customer_name;
                    $cheque->status = 'pending';
                    $cheque->balance = $request->$chequeAmount;
                    $cheque->save();
                }
            }
        }
        $payment = new Payment();
        $user = Auth::user();
        $payment->user_id = $user->id;
        $payment->user_name = $user->name;
        $payment->status = 'with sales';
        $payment->method = $request->Method;
        $payment->totalAmount = $request->TotalAmount;
        $payment->allocatedToInvoice = 0;
        $payment->balanceToAllocate = $request->TotalAmount;
        $payment->customer_id = $customerID;
        $payment->customer_name = $request->customer_name;
        if ($request->Method == 'BankTransfer') {
            $payment->bank_id = $request->bank;
            $payment->bank_name = Bank::where('id', $request->bank)->value('name');
        }
        $payment->save();

        $textMessage = "Thank you! ";
       // $customerMobileNum = (mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile FROM customers WHERE customerName = '$customer'")))['mobile'];
       $BossMobileNum = Customer::where('customer_name', $request->customer_name)->value('mobile');
       
        $textMessage = "Thank you! " . $request->customer_name . " for the payment of Rs. " . $request->TotalAmount . ". Regards, K & K International Lanka Pvt Ltd";
        $textBossMobile = "94" . $BossMobileNum;

        if ($textBossMobile) {
            // echo "<br></br><br></br>";
            // echo $textBossMobile;

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


        return redirect()->route('payment.index')->with('message', 'new payment saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\financials\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }

        $arr['payment'] = $payment;
        $arr['payment_links'] = DB::table('invoice_payment')->where('payment_id', $payment->id)->get();
        $arr['cheques'] = DB::table('cheques')->where('payment_id', $payment->id)->get();

        return view('admin.financials.payment.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financials\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customers'] = Customer::select('id', 'customer_name')->orderBy('customer_name', 'asc')->get();
        $arr['banks'] = Bank::select('id', 'name')->get();
        $arr['payment'] = $payment;
        $arr['cheques'] = DB::table('cheques')->where('payment_id', $payment->id)->get();

        return view('admin.financials.payment.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financials\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }

        $validatedData = $request->validate([
            'TotalAmount' => 'required|numeric',
            'customer_name' => 'required',
        ]);
        $customerID = Customer::where('customer_name', $request->customer_name)->value('id');
        if ($payment->method == 'Cheque') {
            $numofCheques = Cheque::where('payment_id', $payment->id)->count('id');
            for ($i = 1; $i < $numofCheques; $i++) {
                $chequeNo = 'chequeNo' . $i;
                $bankNo = 'bankNo' . $i;
                $branchNo = 'branchNo' . $i;
                $chequeAmount = 'chequeAmount' . $i;
                $chequeDate = 'chequeDate' . $i;
                if (
                    $request->$chequeNo != '' ||
                    $request->$bankNo != '' ||
                    $request->$branchNo != '' ||
                    $request->$chequeAmount != '' ||
                    $request->$chequeDate != ''
                ) {
                    $validatedData = $request->validate([
                        $chequeNo => 'required|numeric',
                        $bankNo => 'required|numeric',
                        $branchNo => 'required|numeric',
                        $chequeAmount => 'required|numeric',
                        $chequeDate => 'required',
                    ]);
                }
            }
        }

        if ($request->Method == 'Cheque') {
            $deleted = DB::table('cheques')->where('payment_id', $payment->id)->delete();
            for ($j = 1; $j <= $numofCheques; $j++) {
                $chequeNo = 'chequeNo' . $j;
                $bankNo = 'bankNo' . $j;
                $branchNo = 'branchNo' . $j;
                $chequeAmount = 'chequeAmount' . $j;
                $chequeDate = 'chequeDate' . $j;

                if ($request->$chequeNo != '') {
                    $cheque = new Cheque();
                    $cheque->payment_id = $payment->id;
                    $cheque->number = $request->$chequeNo;
                    $cheque->bank = $request->$bankNo;
                    $cheque->branch = $request->$branchNo;
                    $cheque->amount = $request->$chequeAmount;
                    $cheque->chequeDate = $request->$chequeDate;
                    $cheque->customer_id = $customerID;
                    $cheque->customer_name = $request->customer_name;
                    $cheque->status = 'pending';
                    $cheque->balance = $request->$chequeAmount;
                    $cheque->save();
                }
            }
        }

        $payment->totalAmount = $request->TotalAmount;
        $payment->allocatedToInvoice = 0;
        $payment->balanceToAllocate = $request->TotalAmount;
        $payment->customer_id = $customerID;
        $payment->customer_name = $request->customer_name;
        if ($request->Method == 'BankTransfer') {
            $payment->bank_id = $request->bank;
            $payment->bank_name = Bank::where('id', $request->bank)->value('name');
        }
        $payment->save();
        return redirect()->route('payment.index')->with('message', 'new payment saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financials\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('home');
        }
        if ($payment->status == 'with sales') {
            if ($payment->method == 'Cheque') {
                $deleted = DB::table('cheques')->where('payment_id', $payment->id)->delete();
            }

            $payment->delete();
        }

        return redirect()->route('payment.index');
    }
}
