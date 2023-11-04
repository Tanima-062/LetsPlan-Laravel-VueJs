<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;

use App\Models\Company;
use App\Models\MotherTongue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\AdminuserRequest;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Http\Requests\UpdateAdminuserRequest;
use App\Notifications\PlaygroupAdminInvitationNotification;

class AdminuserController extends Controller
{
    public function index(Request $request)
    {

        $query = User::where('role_id',1)
                // ->search($request->search)
                ->when(request('query'), fn($q)=> $q->SearchAdminUser(request('query')))
                ->companyIs($request->company_id)
                ->statusIs($request->status_id)
                ->orderByColumns($request->order_by, $request->direction)
                ->paginate($request->per_page ?? 25);


        return UserResource::collection($query);
    }

    public function store(AdminuserRequest $request)
    {
        //old code
        //    $last_id =User::max('id');
        //    $last_id = $last_id+'1';
        //    $p_uniq_id = 'SPGA'.strval(str_pad($last_id,5,0,STR_PAD_LEFT));


        try {
            $password  = uniqid();
            $user = User::create([

                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'prefix_id'   => $request->prefix_id,
                'email' => $request->email,
                'role_id' => 1,
                'status_id' => 3,
                'parent_admin_id'=> 1,
                'company_id'=>$request->company_id,
                'email_notifications'=>0,
                'password'      => bcrypt($password)
            ]);

        $user->notify(new PlaygroupAdminInvitationNotification($password));

            return response()->json('Das Unternehmen wurde erfolgreich erstellt.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.'.$exception->getMessage(), 500);
        }
    }

    public function getInfo($user)
    {

        $users = User::where('id',$user)->get();

        return response()->json($users);
    }


    public function destroy(AdminuserRequest $user)
    {
        User::where('user', $user->id)->delete();
        $user->delete();
        return response()->json('Company deleted successfully');
    }


    public function update(AdminUserUpdateRequest $request, User $user)
    {

        try {
            $usertrans = User::where('id',$user->id);
            $usertrans->update([
                'company_id' => $request->company_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email
            ]);

            $user = $user->fresh();

             return response()->json(sprintf('Der Spielgruppen Admin "%s %s" wurde erfolgreich aktualisiert.', $user->first_name, $user->last_name));
            //  return response()->json('Das Unternehmen “'.$user->first_name.'” wurde erfolgreich aktualisiert.');

        }  catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }
    }

    public function getmax()
    {
        // old code
        // $last_id =User::max('id');
        // $last_id = $last_id+'1';
        // $p_uniq_id = 'SPGA'.strval(str_pad($last_id,5,0,STR_PAD_LEFT));

        // return response()->json($p_uniq_id);

        $last_registration = User::query()
            ->where('role_id', 1)
            ->orderBy('id', 'desc')
            ->count()
        ;

        $id =  $last_registration > 0 ? $last_registration + 1 : 1;

        return sprintf("%s%05d", 'SGPA', $id);
    }

    public function getFilterData(Request $request)
    {

        $query = User::query()->where('role_id', 1)
            // ->search($request->search)
            ->when(request('query'), fn($q)=> $q->SearchAdminUser(request('query')))
            ->companyIs($request->company_id)
            ->statusIs($request->status_id)
            ->orderByColumns($request->order_by, $request->direction);

        if ($request->column == 'company_id') {
            $temp = $query;
            $companyIds = $temp->pluck('company_id')->toArray();

            return Company::select('id', 'name')
                ->whereIn('id', $companyIds)
                ->orderBy('name', 'asc')
                ->get();
        } else if ($request->column == 'status_id') {
            $status_label = [1 => 'Aktiv', 2 => 'Inaktiv', '3' => 'Pendent'];
            return $query->pluck('status_id')->unique()->values()->map(fn ($item) => ['name' => $status_label[$item], 'id' => $item]);

        }
    }

    public function updateStatus(Request $request, User $user)
    {
        // $request->validate(['status' => ['in:2,1']]);
        // $user->update(['status_id' => $request->status]);
        // $status = ($user->status_id == 1) ? 'aktiviert' : 'deaktiviert';
        // $name = $user->first_name . ' ' . $user->last_name;
        // $message = sprintf('Die Spielgruppe Admin “%s” wurde erfolgreich %s.',$name,$status);
        // return response()->json(['status' => 'success', 'message' => $message]);



        $user->update([
            'status_id' => $request->status_id
        ]);

        if($request->status_id == 3){
            $password  = uniqid();
            $user->update([
                'password'      => bcrypt($password)
            ]);

            $user->notify(new PlaygroupAdminInvitationNotification($password));
        }

        return response()->json('Adminuser updated successfully');
    }



}
