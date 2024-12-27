<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProductExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::select([
            'sector_id',
            'title',
            'description',
            'bestseller',
            'price',
            'discount_price',
            'total_price',
        ])->get()->map(function ($product){
            $product->description = strip_tags($product->description);
            return $product;
        });
    }   

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Category ID',
            'Title',
            'Description',
            'Bestseller',
            'Price',
            'Discount Price',
            'Total Price',
        ];
    }
}
