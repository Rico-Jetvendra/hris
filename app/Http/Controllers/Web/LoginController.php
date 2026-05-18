<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Insurance;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller{
    public function index(){
        $employeeCount  = Employee::count();
        $vehicleCount   = Vehicle::count();
        $insuranceCount = Insurance::count();
        $companyCount   = Company::count();

        $count = [
            "employee"  => $employeeCount,
            "vehicle"   => $vehicleCount,
            "insurance" => $insuranceCount,
            "company"   => $companyCount,
        ];

        $today = Carbon::today();
        $nextWeek = Carbon::today()->addWeek();

        $vehicle = Vehicle::where(function ($query) use ($today, $nextWeek) {
            $query->where(function ($q) use ($today, $nextWeek) {
                $q->whereBetween('vehicle_tax_due', [$today, $nextWeek])
                ->orWhereDate('vehicle_tax_due', '<', $today);
            })

            ->orWhere(function ($q) use ($today, $nextWeek) {
                $q->whereBetween('vehicle_reg_due', [$today, $nextWeek])
                ->orWhereDate('vehicle_reg_due', '<', $today);
            });
        })->get();

        $employee = Employee::join('t_employee_company as ec', 'ec.employee_id', '=', 't_employee.employee_id')
                    ->whereBetween('ec.end_of_contract', [$today,$nextWeek])->get();

        $data = [
            "vehicle" => $vehicle,
            "employee" => $employee
        ];

        return view('index', compact('count', 'data'));
    }

    public function signin(){
        return view('login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $user = User::where('application_id', '=', 2)->where('email', '=', $validated['email'])->where('password', '=', MD5($validated['password']))->first();
        if(!$user){
            return redirect()->route('web.index')->with('error', 'Autentikasi gagal!');
        }

        $permissions = DB::table('security.t_user_roles as ur')
                            ->join('security.t_role_permissions as rp', 'rp.role_id', '=', 'ur.role_id')
                            ->join('security.t_permissions as p', 'p.id', '=', 'rp.permission_id')
                            ->where('ur.user_id', $user->id)
                            ->where('ur.status', '=', '1')
                            ->where('p.status', '=', '1')
                            ->where('rp.status', '=', '1')
                            ->pluck('p.name')
                            ->toArray();

        $user->update(['login_date' => Carbon::now()]);
        session(['user' => $user, 'permission' => $permissions]);

        // Prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('web.index')->with('success', 'Login Berhasil!');
    }

    public function logout(Request $request){
        $request->session()->forget(['user', 'permission', 'webpush_initialized']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.signin')->with('success', 'Berhasil logout!');
    }

    public function saveWebTokenSession(Request $request){
        $user = User::where('id', session('user')->id)->first();
        $user->update(['device_token' => $request->token]);

        $request->session()->put('webpush_initialized', true);

        return response()->json([
            'success' => true,
            'message' => 'Token saved in session',
        ]);
    }
}

?>
