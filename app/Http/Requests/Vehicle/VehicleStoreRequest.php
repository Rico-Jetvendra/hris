<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'vehicle_number'                    => 'required|string|max:100',

            'vehicle_brand'                     => 'required|integer',
            'vehicle_frame'                     => 'required|string|max:255',
            'vehicle_machine'                   => 'required|string|max:255',
            'vehicle_color'                     => 'required|integer',
            'vehicle_company'                   => 'required|integer',

            'vehicle_tax_due'                   => 'nullable|date',
            'vehicle_reg_due'                   => 'nullable|date',

            'vehicle_bpkb'                      => 'required|in:true,false,1,0',

            'vehicle_insurance_payment'         => 'nullable|date',
            'vehicle_insurance_number'          => 'nullable|string|max:255',
            'vehicle_insurance_start'           => 'nullable|date',
            'vehicle_insurance_end'             => 'nullable|date',
            'vehicle_insurance'                 => 'nullable|integer',

            'remarks'                           => 'nullable|string',
        ];
    }
}
