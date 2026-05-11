<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            // Employee
            'employee.employee_name'       => 'required|string|max:100',
            'employee.employee_pob'        => 'required|string|max:100',
            'employee.employee_dob'        => 'required|date',
            'employee.employee_sex'        => 'required|integer',
            'employee.employee_blood'      => 'required|integer',
            'employee.employee_religion'   => 'required|integer',
            'employee.employee_marriage'   => 'required|integer',
            'employee.employee_ktp'        => 'required|string|max:16',

            'employee.employee_npwp'       => 'nullable|string|max:21',
            'employee.employee_education'  => 'nullable|integer',
            'employee.employee_father'     => 'nullable|string|max:100',
            'employee.employee_mother'     => 'nullable|string|max:100',
            'employee.employee_email'      => 'nullable|email|max:100',
            'employee.employee_address'    => 'nullable|string',
            'employee.employee_home_phone' => 'nullable|string|max:16',
            'employee.employee_phone'      => 'nullable|string|max:16',

            'employee.employee_remarks'    => 'nullable|string',

            // Employee Company
            'company.branch_id'           => 'required|integer',
            'company.company_id'          => 'required|integer',
            'company.department_id'       => 'required|integer',
            'company.position_id'         => 'required|integer',
            'company.entry_date'          => 'required|date',
            'company.end_of_contract'     => 'required|date',
        ];
    }
}
