<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// composer require box/spout
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();

        // Pass the data to the view using compact
        return view('payments', compact('payments'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->path());

        $headerRow = null;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $rowData = $row->toArray();

                if ($rowIndex === 1) {
                    $headerRow = $rowData;
                    continue;
                }

                $rowData = array_combine($headerRow, $rowData);

                $username = config('services.yo_pay.username');
                $password = config('services.yo_pay.password');
                $mode = "production";

                $yoAPI = new \YoAPI($username, $password, $mode);
                $transaction_reference = date("YmdHis") . rand(1, 100);
                $yoAPI->set_external_reference($transaction_reference);
                $response = $yoAPI->ac_deposit_funds($rowData['contact'], $rowData['net_pay'], 'Payment');

                dd($response);

                if ($response['Status'] == 'OK') {
                    $status = 1;
                    $res = $response['Status'];
                } else {
                    $status = 0;
                    $res = $response['Status'];
                }

                $this->save_payment($rowData, $status, $res);
            }
        }

        $reader->close();

        return redirect()->back()->with('success', 'Payments processed successfully.');
    }

    function save_payment($rowData, $status, $res) {
        $rate = config('services.yo_pay.rate');
        $pay = $rowData['net_pay'];
        $net_pay = $rate * $pay;

        Payment::create([
            'dist_no' => $rowData['dist_no'],
            'dist_name' => $rowData['dist_name'],
            'shop_id' => $rowData['shop_id'],
            'net_pay' => $net_pay,
            'contacts' => $rowData['contact'],
            'status' => $status,
            'error' => $res,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
