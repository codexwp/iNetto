<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserCSVRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Package;
use App\Models\Seller;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\Pear2Service;
use App\Traits\CsvTransit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use CsvTransit;

    public function index(Request $request)
    {
        $sellers = Seller::getAll('asc');
        $packages = Package::getAll(1, 'asc');
        $params = $request->only(['q', 'id', 'package_id', 'seller_id', 'is_active_client', 'is_expired']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }

        $users = User::getByConditions($params, true, 100);
        return view('admin.pages.user.index', compact('users', 'sellers', 'packages'));
    }

    public function showCreateForm()
    {
        $sellers = Seller::getAll('asc');
        return view('admin.pages.user.create', compact('sellers'));
    }

    public function create(UserRequest $request)
    {
        try{
            $data = $request->validated();
            User::create($data);
            return redirect()->route('admin.user.index')->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($data)->with('error', $e->getMessage());
        }
    }

    public function details(User $user, Request $request)
    {
        return view('admin.pages.user.detail', compact('user'));
    }

    public function csvManage()
    {
        return view('admin.pages.user.csv_manage');
    }

    public function csvDownload()
    {
        $users = User::getAll('asc');
        $csv_header = self::csvHeaderForUsers();
        $csv_data = self::csvDataForUsers($users);
        $filename = now()->format('Y_m_d H_i') . '_users.csv';
        return self::export($csv_data, $csv_header, $filename);
    }

    public function csvUpload(UserCSVRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->records;
            $insert_records = [];
            $update_records = [];
            foreach ($data as $index => $row) {
                foreach ($row as $key => $item) {
                    if ($item === '') {
                        $data[$index][$key] = null;
                    }
                }
            }
            foreach ($data as $row) {
                if($row['action'] == '1') {
                    unset($row['action']);
                    $password = $row['password'];
                    $row['password'] = bcrypt($password);
                    $row['secret'] = encrypt_decrypt($password);
                    $insert_records[] = $row;
                }
                else if($row['action'] == '2') {
                    unset($row['action']);
                    if(!isset( $row['password']) || empty( $row['password'])){
                        unset($row['password']);
                    } else{
                        $password = $row['password'];
                        $row['password'] = bcrypt($password);
                        $row['secret'] = encrypt_decrypt($password);
                    }
                    $update_records[] = $row;
                }
            }

            if(count($insert_records) > 0){
                User::insert($insert_records);
            }

            foreach ($update_records as $record){
                $user = User::where('username', $record['username'])->first();
                if($user) {
                    $user->update($record);
                }
            }
            DB::commit();
            return back()->with('success', 'Successfully uploaded');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }

    //APIs
    public function fetchDetails(User $user)
    {
        $user_info = $user->with(['seller', 'package'])->where('users.id', $user->id)->first();
        $user_info->setAttribute('key', $user_info->secret);
        $payments = $user->payments()
            ->select('payments.id', 'payments.amount', 'payments.status', 'payments.created_at', 'payments.cost')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $seller_packages = $user->seller->getPackagesAndDetails(1);
        return $this->successResponse('Success', [
            'user_info' => $user_info,
            'payments'  => $payments,
            'seller_packages' => $seller_packages,
            'sellers' => Seller::getAll('asc')
        ]);
    }


    public function updateApi(User $user, UserRequest $request)
    {
        try{
            $user->update($request->validated());
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function othersApi(User $user, $action)
    {
        try{
            switch ($action) {
                case 'synchronize':
                    User::synchronize($user);
                    break;
                case 'delete':
                    $user->delete();
                    break;
                case 'delete-with-mikrotik':
                    User::destroyWithMikrotik($user);
                    break;
                default:
                    throw new \Exception('Invalid operation');
            }
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function serverPppoeStatus(User $user)
    {
        try{
            $p2s = new Pear2Service(1);
            if($p2s->client == null) {
                throw new \Exception($p2s->error);
            }
            $client = $p2s->findClient($user->username);
            if($client == null) {
                throw new \Exception("Client is not found in Mikrotik");
            }
            return $this->successResponse('Success', ['status' => $client['disabled'] == 'true' ? 0 : 1]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

}
