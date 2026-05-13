<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\DueEmail;
use App\Models\Employee;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;

class NotificationController extends Controller{
    public function sendDueEmail(){
        $vehicleTax = Vehicle::whereBetween('vehicle_tax_due', [
                    Carbon::today(),
                    Carbon::today()->addWeeks(7)
        ])->get();

        $vehicleReg = Vehicle::whereBetween('vehicle_reg_due', [
                    Carbon::today(),
                    Carbon::today()->addWeeks(7)
        ])->get();

        $employee = Employee::join('t_employee_company as ec', 'ec.employee_id', '=', 't_employee.employee_id')
                    ->whereBetween('ec.end_of_contract', [
                    Carbon::today(),
                    Carbon::today()->addWeeks(7)
        ])->get();

        if ($vehicleTax->count() == 0 && $vehicleReg->count() == 0 && $employee->count() == 0) {
            return;
        }

        $user = User::join('security.t_user_roles as ur', 'tbl_users.id', '=', 'ur.user_id')->where('ur.role_id', '=', 7)->get();
        if($user->count() == 0){
            return;
        }

        foreach($user as $us){
            $data = [
                'username'      => $us->username,
                'vehicleTax'    => $vehicleTax,
                'vehicleReg'    => $vehicleReg,
                'employee'      => $employee
            ];

            Mail::to($us->email)->send(new DueEmail($data));
        }
    }
}
