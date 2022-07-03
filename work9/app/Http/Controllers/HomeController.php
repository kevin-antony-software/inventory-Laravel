<?php

namespace App\Http\Controllers;

use App\Models\financials\Cash;
use App\Models\financials\Cheque;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        $now = Carbon::now();

        $arr['invoices'] = DB::table('invoices')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');
        $arr['unpaid'] = DB::table('invoices')
            ->sum('dueAmount');
        $arr['payments'] = DB::table('payments')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('totalAmount');
        $arr['products'] = DB::table('products')
            ->count('product_name');

        $bp = DB::table('invoices')
            ->select(DB::raw('SUM(total) as total, MONTH(created_at) as month, SUM(dueAmount) as dueAmount'))
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))
            ->get();

        $bppp = DB::table('payments')
            ->select(DB::raw('SUM(totalAmount) as amount, MONTH(created_at) as month'))
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))
            ->get();

        $dateA = array();
        $valueA = array();
        $valueB = array();
        $paymentArray = array();
        $payMonthArray = array();

        foreach ($bppp as $monthItem) {
            array_push($paymentArray, $monthItem->amount);
            array_push($payMonthArray, $monthItem->month);
        }

        foreach ($bp as $item) {
            array_push($dateA, $item->month);
            array_push($valueA, $item->total);
            array_push($valueB, $item->dueAmount);
        }

        $arr['x'] = $dateA;
        $arr['y'] = $valueA;
        $arr['due'] = $valueB;
        $arr['xp'] = $payMonthArray;
        $arr['yp'] = $paymentArray;
        $arr['cheques'] = Cheque::where('status', 'pending')->sum('amount');
        $arr['ReturnCheques'] = Cheque::where('status', 'returned')->sum('amount');
        $arr['cashBalance'] = Cash::orderBy('id', 'desc')->first();
        $totalInventoryValue = 0;
        $inventoryItems = DB::table('inventories')->select('qty', 'price')->get();
        foreach ($inventoryItems as $inventoryItem) {
            $totalInventoryValue += $inventoryItem->qty * $inventoryItem->price;
        }
        $arr['inventory'] = $totalInventoryValue * 0.6;
        $arr['bankAvailableTotal'] = DB::table('banks')->sum('balance');
        $arr['InvoiceTotalDue'] = DB::table('invoices')->sum('dueAmount');
        $arr['JobTotalDue'] = DB::table('jobs')->sum('dueAmount');

        $arr['TotalAssets'] = $arr['cheques'] + $arr['ReturnCheques'] + $arr['cashBalance']->balance + $arr['bankAvailableTotal'] +
            $arr['inventory'] + $arr['InvoiceTotalDue'] + $arr['JobTotalDue'];

        $arr['TotalPayments'] = DB::table('payments')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('totalAmount');

        $cashIn = DB::table('cashes')
            ->where('expense_id', NULL)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
        $cashOut = DB::table('cashes')
            ->where('payment_id', NULL)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
        $bankIN = DB::table('bank_details')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('credit');
        $bankOut = DB::table('bank_details')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('debit');

        $arr['netCashFlow'] = $cashIn - $cashOut + $bankIN - $bankOut;

        $arr['expenses'] = DB::table('expenses')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('category', '!=', 'COGS')
            ->sum('amount');

        $arr['COGS'] = DB::table('expenses')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('category', 'COGS')
            ->sum('amount');

            $Techusers = DB::table('jobs')->select('jobClosedUser_name')->distinct()->get();
            $UserPerformance = array();
            $UserCount = 0;

            foreach ($Techusers as $techUser) {
                $AvgDuration = DB::table('jobs')->where('jobClosedUser_name', $techUser->jobClosedUser_name)->avg('duration');
                $UserPerformance += [ $techUser->jobClosedUser_name => $AvgDuration ];
            }
            
            $arr['Performance'] = $UserPerformance;
        return view('admin.home')->with($arr);
    }
}
