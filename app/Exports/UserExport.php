<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select(
            'user_id',
            'name',
            'email',
            'gender',
            'address',
            'mobilenumber',
            'city',
            'state',
            'country',
            'pan_no',
            'ifsc',
            'account_no',
            'nominee_name',
            'bank',
            'relation_user',
            'zip',
            'sponsor_id',  
        )->get();
    }

    public function headings(): array
    {
        return [
            'User ID',       
            'Name',      
            'Email',       
            'Gender',      
            'Address',       
            'Mobile Number',  
            'City',           
            'State',        
            'Country',      
            'PAN Number',    
            'IFSC Code',      
            'Account Number', 
            'Nominee Name',   
            'Bank Name',      
            'Relation to User', 
            'ZIP Code',     
            'Sponsor ID',    
        ];
    }

    /**
     * Map the data to display user_id in the sponsor_id field
     * 
     * @param  \App\Models\User  $user
     * @return array
     */
    public function map($user): array
    {
        $sponsor = User::find($user->sponsor_id);  

        return [
            $user->user_id,         
            $user->name,              
            $user->email,             
            $user->gender,            
            $user->address,           
            $user->mobilenumber,      
            $user->city,              
            $user->state,             
            $user->country,           
            $user->pan_no,            
            $user->ifsc,              
            $user->account_no,        
            $user->nominee_name,      
            $user->bank,              
            $user->relation_user,     
            $user->zip,               
            $sponsor ? $sponsor->user_id : 'N/A',  
        ];
    }
}