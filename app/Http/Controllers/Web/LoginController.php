<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Insurance;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller{
    public function index(){
        $employeeCount      = Employee::count();
        $vehicleCount       = Vehicle::count();
        $insuranceCount     = Insurance::count();
        $perusahaanCount    = Company::count();
        $sexCount           = $this->getEmployee()
                                    ->select(
                                        't_employee.employee_sex',
                                        DB::raw('COUNT(t_employee.employee_sex) as total')
                                    )
                                    ->where('ec.status', '1')
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('t_employee.employee_sex')
                                    ->orderBy('t_employee.employee_sex', 'ASC')
                                    ->pluck('total', 'employee_sex');
        $contractCount      = $this->getEmployee()
                                    ->select(
                                        'ec.contract_status',
                                        DB::raw('COUNT(ec.contract_status) as total')
                                    )
                                    ->where('ec.status', '1')
                                    ->groupBy('ec.contract_status')
                                    ->orderBy('ec.contract_status', 'ASC')
                                    ->pluck('total', 'contract_status');
        $religionCount      = $this->getEmployee()
                                    ->select(
                                        't_employee.employee_religion',
                                        DB::raw('COUNT(t_employee.employee_religion) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('t_employee.employee_religion')
                                    ->orderBy('t_employee.employee_religion', 'ASC')
                                    ->pluck('total', 'employee_religion');
        $marriageCount      = $this->getEmployee()
                                    ->select(
                                        't_employee.employee_marriage',
                                        DB::raw('COUNT(t_employee.employee_marriage) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('t_employee.employee_marriage')
                                    ->orderBy('t_employee.employee_marriage', 'ASC')
                                    ->pluck('total', 'employee_marriage');
        $departmentCount    = $this->getEmployee()
                                    ->join('t_department as d', 'd.department_id', '=', 'ec.department_id')
                                    ->select(
                                        'd.department_name',
                                        DB::raw('COUNT(ec.department_id) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('d.department_name')
                                    ->orderBy('total', 'DESC')
                                    ->get();
        $branchCount        = $this->getEmployee()
                                    ->join('t_branch as b', 'b.branch_id', '=', 'ec.branch_id')
                                    ->select(
                                        'b.branch_name',
                                        DB::raw('COUNT(ec.branch_id) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('b.branch_name')
                                    ->orderBy('total', 'DESC')
                                    ->get();
        $companyCount       = $this->getEmployee()
                                    ->join('t_company as c', 'c.company_id', '=', 'ec.company_id')
                                    ->select(
                                        'c.company_name',
                                        DB::raw('COUNT(ec.company_id) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('c.company_name')
                                    ->orderBy('total', 'DESC')
                                    ->get();
        $ageCount           = $this->getEmployee()
                                    ->select(
                                        DB::raw("
                                            CASE
                                                WHEN TIMESTAMPDIFF(YEAR, employee_dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                                                WHEN TIMESTAMPDIFF(YEAR, employee_dob, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                                                WHEN TIMESTAMPDIFF(YEAR, employee_dob, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
                                                WHEN TIMESTAMPDIFF(YEAR, employee_dob, CURDATE()) BETWEEN 46 AND 55 THEN '46-55'
                                                ELSE '56+'
                                            END as age
                                        "),
                                        DB::raw('COUNT(t_employee.employee_name) as total')
                                    )
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('age')
                                    ->orderByRaw("
                                        CASE age
                                            WHEN '18-25' THEN 1
                                            WHEN '26-35' THEN 2
                                            WHEN '36-45' THEN 3
                                            WHEN '46-55' THEN 4
                                            ELSE 5
                                        END
                                    ")
                                    ->get();
        $educationCount     = $this->getEmployee()
                                    ->select(
                                        't_employee.employee_education',
                                        DB::raw('COUNT(t_employee.employee_education) as total')
                                    )
                                    ->where('ec.status', '1')
                                    ->where('ec.contract_status', '1')
                                    ->groupBy('t_employee.employee_education')
                                    ->orderBy('total', 'DESC')
                                    ->pluck('total', 'employee_education');

        $count = [
            "employee"      => $employeeCount,
            "vehicle"       => $vehicleCount,
            "insurance"     => $insuranceCount,
            "perusahaan"    => $perusahaanCount,
            "sex"           => $sexCount,
            "contract"      => $contractCount,
            "religion"      => $religionCount,
            "marriage"      => $marriageCount,
            "department"    => $departmentCount,
            "branch"        => $branchCount,
            "companyGraph"  => $companyCount,
            "age"           => $ageCount,
            "education"     => $educationCount,
        ];

        $today      = Carbon::today();
        $nextWeek   = Carbon::today()->addWeek();

        $vehicle    = Vehicle::where(function ($query) use ($today, $nextWeek) {
            $query->where(function ($q) use ($today, $nextWeek) {
                $q->whereBetween('vehicle_tax_due', [$today, $nextWeek])
                ->orWhereDate('vehicle_tax_due', '<', $today);
            })

            ->orWhere(function ($q) use ($today, $nextWeek) {
                $q->whereBetween('vehicle_reg_due', [$today, $nextWeek])
                ->orWhereDate('vehicle_reg_due', '<', $today);
            });
        })->get();
        $employee   = $this->getEmployee()
                            ->whereMonth('ec.end_of_contract', now()->month)
                            ->whereYear('ec.end_of_contract', now()->year)
                            ->get();

        $hbd        = $this->getEmployee()
                        ->whereMonth('employee_dob', now()->month)
                        ->whereDay('employee_dob', '>=', now()->day)
                        ->where('ec.contract_status', '=', '1')
                        ->selectRaw('*, TIMESTAMPDIFF(YEAR, employee_dob, CURDATE()) as age')
                        ->orderByRaw('DAY(employee_dob) ASC')
                        ->get();

        $data = [
            "vehicle"   => $vehicle,
            "employee"  => $employee,
            "hbd"       => $hbd
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

        $user = User::where('application_id', '=', 2)->where('email', '=', $validated['email'])->first();
        if(!$user){
            ActivityLogger::failedLogin(['email' => $validated['email'], 'description' => 'User tidak ada!.']);
            return redirect()->route('web.index')->with('error', 'Autentikasi gagal!');
        }

        $passwordValid = false;
        if(Hash::check($validated['password'], $user->password)){
            $passwordValid = true;
        }else if($user->password === md5($validated['password'])){
            $passwordValid = true;
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        if (!$passwordValid) {
            ActivityLogger::failedLogin(['email' => $validated['email'], 'description' => 'Invalid password!.']);
            return redirect()->back()->with('error', 'Invalid password!');
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

        ActivityLogger::login();

        return redirect()->route('web.index')->with('success', 'Login Berhasil!');
    }

    public function logout(Request $request){
        ActivityLogger::logout();

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

    private function getEmployee(){
        return Employee::join('t_employee_company as ec', 'ec.employee_id', '=', 't_employee.employee_id');
    }
}

?>
