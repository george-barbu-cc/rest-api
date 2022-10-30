<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class SellerController extends Controller
{
    public function show($id)
    {
        $results = [];
        $sales = Sale::with('seller')->where('seller_id', $id)->get();
        foreach ($sales as $sale) {
            $results[] = [
                'seller_firstname' => $sale->seller->firstname ?? '',
                'seller_lastname' => $sale->seller->lastname ?? '',
                'date_joined' => $sale->seller->date_joined ?? '',
                'country' => $sale->seller->country ?? '',
            ];
        }

        return response()->json(['success' => true, 'data' => $results], 200);
    }

    public function sales($id) {
        $results = [];
        $sales = Sale::with('seller')->where('seller_id', $id)->get();
        foreach($sales as $sale) {
            $results[] = [
                'uuid' => $sale->entry_id ?? '',
                'seller_firstname' => $sale->seller->firstname ?? '',
                'seller_lastname' => $sale->seller->lastname ?? '',
                'date_joined' => $sale->seller->date_joined ?? '',
                'country' => $sale->seller->country ?? '',
                'contact_region' => $sale->seller->region ?? '',
                'contact_date' => $sale->date ?? '',
                'contact_customer_fullname' => $sale->contact_customer_fullname ?? '',
                'contact_type' => $sale->type ?? '',
                'contact_product_type_offered_id' => $sale->contact_product_type_offered_id ?? '',
                'contact_product_type_offered' => $sale->contact_product_type_offered ?? '',
                'sale_net_amount' => $sale->sale_net_amount / 100 ?? '',
                'sale_gross_amount' => $sale->sale_gross_amount / 100 ?? '',
                'sale_tax_rate' => $sale->sale_tax_rate / 100 ?? '',
                'sale_product_total_cost' => $sale->sale_product_total_cost / 100 ?? '',
            ];

        }

        return response()->json(['success' => true, 'data' => $results], 200);
    }

    public function contacts($id) {
        $results = [];
        $sales = Sale::where('seller_id', $id)->get();
        foreach ($sales as $sale) {
            $results[] = [
                'sale' => (($sale->sale_net_amount / 100) || ($sale->sale_gross_amount)) ? 'yes' : 'no',
                'contact_region' => $sale->region ?? '',
                'contact_date' => $sale->date ?? '',
                'contact_customer_fullname' => $sale->contact_customer_fullname ?? '',
                'contact_type' => $sale->type ?? '',
                'contact_product_type_offered_id' => $sale->contact_product_type_offered_id ?? '',
                'contact_product_type_offered' => $sale->contact_product_type_offered ?? '',
            ];
        }

        return response()->json(['success' => true, 'data' => $results], 200);
    }
}
