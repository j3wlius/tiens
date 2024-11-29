<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// composer require box/spout
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Models\Payment;
use App\Models\Category;
use SimpleXMLElement;

use Exception;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * 
     */

    public $username;
    public $password;
    private $internal_reference;
    private $external_reference;
    private $NonBlocking;
    private $provider_reference_text;
    private $transaction_limit_account_identifier;
    private $public_key_authentication_nonce;
    private $public_key_authentication_signature_base64;

    private $YOURL = "https://paymentsapi1.yo.co.ug/ybs/task.php"; 


    public function __construct() {
        $this->username = config('services.yo_pay.username');
        $this->password = config('services.yo_pay.password');
        $this->internal_reference = NULL; // or some default value
        $this->external_reference = NULL; // or some default value
        $this->NonBlocking = "FALSE"; // or some default value
        $this->provider_reference_text = NULL; // or some default value
        $this->transaction_limit_account_identifier = NULL; // or some default value
        $this->public_key_authentication_nonce = NULL; //rand(1, 100); // or some default value
        $this->public_key_authentication_signature_base64 = NULL; // or some default value

    }

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

        $category = $request->input('category');
        $month_of_pay = $request->input('month_of_pay');
        $file = $request->file('file');

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->path());

        $headerRow = null;

        // $month_of_pay = '2024-10-01';
        // $category = 1001;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $rowData = $row->toArray();

                if ($rowIndex === 1) {
                    $headerRow = $rowData;
                    continue;
                }

                $rowData = array_combine($headerRow, $rowData); //dd($rowData);
                // $username = config('services.yo_pay.username');
                // $password = config('services.yo_pay.password');
                // $mode = "production";

                // $yoAPI = new \YoAPI($username, $password, $mode);
                // $transaction_reference = date("YmdHis") . rand(1, 100);
                // $yoAPI->set_external_reference($transaction_reference);
                // $response = $yoAPI->ac_deposit_funds($rowData['contact'], $rowData['net_pay'], 'Payment');
                
                $rate = config('services.yo_pay.rate');
                $pay = $rowData['net_pay'];
                $net_pay = $rate * $pay;
                // dd($net_pay );

                $response = $this->ac_withdraw_funds($rowData['contact'], $net_pay, 'Payment');
                // dd($disburse);

                //dd($response);

                if ($response['Status'] == 'OK') {
                    $status = 1;
                    $res = $response['Status'];
                } else {
                    $status = 0;
                    $res = $response['Status'];
                }

                $status_message = $response['StatusMessage'];

                //dd($response);

              

                $this->save_payment($rowData, $status, $status_message, $category, $month_of_pay);
            }
        }

        $reader->close();

        return redirect()->back()->with('success', $status_message);
    }

    


   


    /*function import() {
        // ac_internal_transfer("UGX",100,'256751462182',"wanderatimothy2@gmail.com", "distribution payment");
        // $disburse = $this->ac_internal_transfer("UGX",100,'256773635696',"wanderatimothy2@gmail.com", "distribution payment");
        $disburse = $this->ac_withdraw_funds("256773635696",1000,"distribution payment");
        dd($disburse);
    }*/

    //This is the code for disbursement
    public function ac_withdraw_funds($msisdn, $amount, $narrative)
    {
        $xml = '';
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<AutoCreate>';
        $xml .= '<Request>';
        $xml .= '<APIUsername>'.$this->username.'</APIUsername>';
        $xml .= '<APIPassword>'.$this->password.'</APIPassword>';
        $xml .= '<Method>acwithdrawfunds</Method>';
        $xml .= '<NonBlocking>'.$this->NonBlocking.'</NonBlocking>';
        $xml .= '<Account>'.$msisdn.'</Account>';
        $xml .= '<Amount>'.$amount.'</Amount>';
        $xml .= '<Narrative>'.$narrative.'</Narrative>';
        if( $this->external_reference != NULL ){ $xml .= '<ExternalReference>'.$this->external_reference.'</ExternalReference>'; }
        if( $this->internal_reference != NULL ) { $xml .= '<InternalReference>'.$this->internal_reference.'</InternalReference>'; }
        if( $this->provider_reference_text != NULL ){ $xml .= '<ProviderReferenceText>'.$this->provider_reference_text.'</ProviderReferenceText>'; }
        if( $this->transaction_limit_account_identifier != NULL ){ $xml .= '<TransactionLimitAccountIdentifier>'.$this->transaction_limit_account_identifier.'</TransactionLimitAccountIdentifier>';}
        if( $this->public_key_authentication_nonce != NULL ){ $xml .= '<PublicKeyAuthenticationNonce>'.$this->public_key_authentication_nonce.'</PublicKeyAuthenticationNonce>';}
        if( $this->public_key_authentication_signature_base64 != NULL ){ $xml .= '<PublicKeyAuthenticationSignatureBase64>'.$this->public_key_authentication_signature_base64.'</PublicKeyAuthenticationSignatureBase64>';}
        $xml .= '</Request>';
        $xml .= '</AutoCreate>';

        $xml_response = $this->get_xml_response($xml);

        $simpleXMLObject =  new SimpleXMLElement($xml_response);
        $response = $simpleXMLObject->Response;

        $result = array();
        $result['Status'] = (string) $response->Status;
        $result['StatusCode'] = (string) $response->StatusCode;
        $result['StatusMessage'] = (string) $response->StatusMessage;
        $result['TransactionStatus'] = (string) $response->TransactionStatus;
        if (!empty($response->ErrorMessageCode)) {
            $result['ErrorMessageCode'] = (string) $response->ErrorMessageCode;
        }
        if (!empty($response->ErrorMessage)) {
            $result['ErrorMessage'] = (string) $response->ErrorMessage;
        }
        if (!empty($response->TransactionReference)) {
            $result['TransactionReference'] = (string) $response->TransactionReference;
        }
        if (!empty($response->MNOTransactionReferenceId)) {
            $result['MNOTransactionReferenceId'] = (string) $response->MNOTransactionReferenceId;
        }
        if (!empty($response->IssuedReceiptNumber)) {
            $result['IssuedReceiptNumber'] = (string) $response->IssuedReceiptNumber;
        }

        return $result;
        
    }

        public function ac_internal_transfer($currency_code, $amount, $beneficiary_account, $beneficiary_email, $narrative)
        {
           

            $xml = '';
            $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<AutoCreate>';
            $xml .= '<Request>';
            $xml .= '<APIUsername>'.$this->username.'</APIUsername>';
            $xml .= '<APIPassword>'.$this->password.'</APIPassword>';
            $xml .= '<Method>acinternaltransfer</Method>';
            $xml .= '<CurrencyCode>'.$currency_code.'</CurrencyCode>';
            $xml .= '<Amount>'.$amount.'</Amount>';
            $xml .= '<BeneficiaryAccount>'.$beneficiary_account.'</BeneficiaryAccount>';
            $xml .= '<BeneficiaryEmail>'.$beneficiary_email.'</BeneficiaryEmail>';
            $xml .= '<Narrative>'.$narrative.'</Narrative>';
            if($this->internal_reference != NULL) { 
                $xml .= '<InternalReference>'.$this->internal_reference.'</InternalReference>'; 
            }
            if($this->external_reference != NULL) { 
                $xml .= '<ExternalReference>'.$this->external_reference.'</ExternalReference>'; 
            }
            $xml .= '</Request>';
            $xml .= '</AutoCreate>';
    
            $xml_response = $this->get_xml_response($xml);
    
            $simpleXMLObject =  new SimpleXMLElement($xml_response);
            $response = $simpleXMLObject->Response;
    
            $result = array();
            $result['Status'] = (string) $response->Status;
            $result['StatusCode'] = (string) $response->StatusCode;
            $result['StatusMessage'] = (string) $response->StatusMessage;
            $result['TransactionStatus'] = (string) $response->TransactionStatus;
            if (!empty($response->ErrorMessageCode)) {
                $result['ErrorMessageCode'] = (string) $response->ErrorMessageCode;
            }
            if (!empty($response->ErrorMessage)) {
                $result['ErrorMessage'] = (string) $response->ErrorMessage;
            }
            if (!empty($response->TransactionReference)) {
                $result['TransactionReference'] = (string) $response->TransactionReference;
            }
            if (!empty($response->MNOTransactionReferenceId)) {
                $result['MNOTransactionReferenceId'] = (string) $response->MNOTransactionReferenceId;
            }
            if (!empty($response->IssuedReceiptNumber)) {
                $result['IssuedReceiptNumber'] = (string) $response->IssuedReceiptNumber;
            }
    
            return $result;
        }
    
    protected function get_xml_response($xml)
    {
        
        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $this->YOURL);
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($soap_do, CURLOPT_TIMEOUT, 120);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($soap_do, CURLOPT_VERBOSE, false);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array('Content-Type: text/xml','Content-transfer-encoding: text','Content-Length: '.strlen($xml)));

        $xml_response = curl_exec($soap_do);
        curl_close($soap_do);

        return $xml_response;
    }
    

    function save_payment($rowData, $status, $status_message, $category, $month_of_pay) {
        $rate = config('services.yo_pay.rate');
        $pay = $rowData['net_pay'];
        $net_pay = $rate * $pay;

        Payment::create([
            'dist_no' => $rowData['dist_no'],
            'dist_name' => $rowData['dist_name'],
            'shop_id' => $rowData['shop_id'],
            'net_pay' => $net_pay,
            'contacts' => $rowData['contact'],
            'category' => $category,
            'month_of_pay' => $month_of_pay,
            'status' => $status,
            'description' => $status_message,
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

    public function category()
    {
       
        // Pass the data to the view using compact
        return view('category.index');
    }

    public function addcategory()
    {
        $request = request(); //dd($request); 
        
        // $request->validate([
        //     'category' => 'required|in:1,2,3',
        // ], [
        //     'category.required' => 'Please select a category.',
        // ]);

        $categoryData = [
            'name' => $request['name'],            
        ];
     

        if ($categoryData != null) {                            
            Category::create(array_merge($categoryData));
            return redirect('/payments/category')->with('success','Category Created successfully');
        }  
    }
}
