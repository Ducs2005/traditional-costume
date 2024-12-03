<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;





class PaymentController extends Controller
{
    public function createVnpayPayment(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // VNPAY configurations from the environment
        $vnp_TmnCode = env('VNPAY_TMN_CODE'); // Terminal code
        $vnp_HashSecret = env('VNPAY_HASH_SECRET'); // Secret key
        $vnp_Url = env('VNPAY_URL'); // VNPAY payment URL
        $vnp_Returnurl = env('VNPAY_RETURN_URL'); // Return URL after payment

        // Collect input data from the request
        $vnp_TxnRef = $request->input('order_id'); // Order ID
        $vnp_OrderInfo = $request->input('order_desc');
        $vnp_OrderType = $request->input('order_type');
        $vnp_Amount = $request->input('amount') * 100; // Amount in smallest unit
        $vnp_Locale = $request->input('language', 'vn');
        $vnp_BankCode = $request->input('bank_code');
        $vnp_IpAddr = $request->ip(); // Client IP Address
        $vnp_ExpireDate = $request->input('txtexpire'); // Expire date

        // Billing details
        $fullName = $request->input('txt_billing_fullname', '');
        $nameParts = explode(' ', trim($fullName));
        $vnp_Bill_FirstName = array_shift($nameParts);
        $vnp_Bill_LastName = implode(' ', $nameParts);

        $vnp_Bill_Mobile = $request->input('txt_billing_mobile');
        $vnp_Bill_Email = $request->input('txt_billing_email');
        $vnp_Bill_Address = $request->input('txt_inv_addr1');
        $vnp_Bill_City = $request->input('txt_bill_city');
        $vnp_Bill_Country = $request->input('txt_bill_country');
        $vnp_Bill_State = $request->input('txt_bill_state');

        // Invoice details
        $vnp_Inv_Phone = $request->input('txt_inv_mobile');
        $vnp_Inv_Email = $request->input('txt_inv_email');
        $vnp_Inv_Customer = $request->input('txt_inv_customer');
        $vnp_Inv_Address = $request->input('txt_inv_addr1');
        $vnp_Inv_Company = $request->input('txt_inv_company');
        $vnp_Inv_Taxcode = $request->input('txt_inv_taxcode');
        $vnp_Inv_Type = $request->input('cbo_inv_type');

        // Build input data array
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$vnp_ExpireDate,
            "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
            "vnp_Bill_Email"=>$vnp_Bill_Email,
            "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
            "vnp_Bill_LastName"=>$vnp_Bill_LastName,
            "vnp_Bill_Address"=>$vnp_Bill_Address,
            "vnp_Bill_City"=>$vnp_Bill_City,
            "vnp_Bill_Country"=>$vnp_Bill_Country,
            "vnp_Inv_Phone"=>$vnp_Inv_Phone,
            "vnp_Inv_Email"=>$vnp_Inv_Email,
            "vnp_Inv_Customer"=>$vnp_Inv_Customer,
            "vnp_Inv_Address"=>$vnp_Inv_Address,
            "vnp_Inv_Company"=>$vnp_Inv_Company,
            "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
            "vnp_Inv_Type"=>$vnp_Inv_Type
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        
        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        if ($request->has('redirect')) {
            return redirect()->away($vnp_Url);
        }
    
        return response()->json(['code' => '00', 'message' => 'success', 'data' => $vnp_Url]);
    }





    private function generateVNPayUrl(array $paymentData)
    {
        // Here, you would use VNPay's SDK or logic to generate the URL.
        // For demonstration, we'll return a dummy URL.
        return "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . http_build_query($paymentData);
    }
}
