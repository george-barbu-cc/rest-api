<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function year($year)
    {
        $results = [];
        $resultsSales = [];
        $resultsCalculations = [];
        $sales = Sale::with('seller')->whereYear('date', $year)->get();



        $netAmount = $grossAmount = $taxAmount = $profit_percent = $profit_percent = $costAmount = 0;

        foreach ($sales as $sale) {
            $resultsSales[] = [
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
            $netAmount += $sale->sale_net_amount;
            $grossAmount += $sale->sale_gross_amount;
            $taxAmount += ($sale->sale_gross_amount / 100) - (($sale->sale_gross_amount / 100) / (1+($sale->sale_tax_rate / 100) ));
            $costAmount += $sale->sale_product_total_cost;
        }

        $netAmount = ($netAmount / 100);
        $grossAmount = ($grossAmount / 100);
        $costAmount = ($costAmount / 100);
        $profit = $grossAmount - $taxAmount - $costAmount;
        $profit_percent = $profit * 100 / $grossAmount;

        $resultsCalculations = [
            'netAmount' => $netAmount,
            'grossAmount' => $grossAmount,
            'taxAmount' => round($taxAmount, 2),
            'profit' => round($profit, 2),
            'profit_percent' => round($profit_percent, 2),
        ];

        $results = [
            'calculations' => $resultsCalculations,
            'sales' => $resultsSales
        ];


        return response()->json(['success' => true, 'data' => $results], 200);
    }
}
