<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sale;
use App\Models\Seller;
use Exception;

class LoadController extends Controller
{
    public function login(Request $request)
    {
        if (!\Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        $user->api_token = $token;
        $user->api_token_date = \Carbon\Carbon::now();
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function load(Request $request) {

        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $fileName = md5(time()) . '.csv';
        Storage::disk('local')->put($fileName, $request->file->get('file'));
        return $this->readFile($fileName);


    }

    private function readFile($fileName)
    {
        $file = fopen(storage_path().'/app/'.$fileName, 'r');
        $i=0 ;
        while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
            //ignore csv head
            if ($i == 0) {
                $i++;
                continue;
            }

            $errorMessages = [];
            $checkExistSale = Sale::select('id')->where('seller_id',  $filedata[1]
            )->where('contact_product_type_offered_id', $filedata[10])->first();


            if(!isset($checkExistSale->id) || $checkExistSale->id < 1) {

                $checkExistSeller = Seller::select('id')->where('id', $filedata[1])->first();

                if(!isset($checkExistSeller->id) || $checkExistSeller->id < 1) {
                    $seller = new Seller();
                    $seller->id = $filedata[1];
                    $seller->firstname = $filedata[2];
                    $seller->lastname = $filedata[3];
                    $seller->date_joined = $filedata[4];
                    $seller->country = $filedata[5];
                    $seller->save();
                }

                $sale = new Sale();
                $sale->seller_id = $filedata[1];
                $sale->contact_customer_fullname = $filedata[8];
                $sale->contact_product_type_offered_id  = $filedata[10];
                $sale->contact_product_type_offered = $filedata[11];
                $sale->date = $filedata[7];
                $sale->type = $filedata[9];
                $sale->region = $filedata[6];

                $sale->entry_id = $filedata[0];
                if(isset($filedata[12]) && $filedata[12] > 0) {
                    $sale->sale_net_amount = $filedata[12] * 100;
                    $sale->sale_gross_amount = $filedata[13] * 100;
                    $sale->sale_tax_rate = $filedata[14] * 100;
                    $sale->sale_product_total_cost = $filedata[15] * 100;
                }

                $sale->save();
            } else {
                $errorMessages[] = 'Entry with ' . $filedata[1] . ' and ' . $filedata[10] . ' already exists';
            }
        }

        if(count($errorMessages) > 0) {
            return response()->json(['error' => $errorMessages], 200);
        } else {
            return response()->json(['success' => true], 200);
        }


    }

}
