<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * Map the incoming rows from the Excel file to the Product model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'sector_id' => $row['category_id'],  
            'title' => $row['title'],            
            'description' => strip_tags($row['description']), 
            'bestseller' => $row['bestseller'], 
            'price' => $row['price'],            
            'discount_price' => $row['discount_price'], 
            'total_price' => $row['total_price'],      
        ]);
    }
}
