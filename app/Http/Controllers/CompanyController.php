<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;

use App\Models\Company;
use App\Models\MotherTongue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;

class CompanyController extends Controller
{
    public function index()
    {

        $results = Company::query()
            ->where(function($q){
                $query = request('query');
                $q->when(request('query'), fn($q)=>$q->where('name', 'LIKE', "%$query%"));
            })
            ->when(request('order_by'), function($q){
                $q->when(
                    in_array(request('order_by'), ['created_at', 'uniq_id', 'name', 'first_name', 'telephone', 'email', 'status']),
                    fn($q)=>$q->orderBy(request('order_by'), request('direction', 'DESC'))
                );
            }, fn($q)=>$q->orderBy('id', request('direction', 'DESC')))
            ->paginate(request('per_page', 25));

        // dd($results);


        return CompanyResource::collection($results);
    }

    public function store(CompanyRequest $request)
    {
        $last_id =Company::max('id');
        $last_id = $last_id+'1';
        $p_uniq_id = 'UN'.strval(str_pad($last_id,5,0,STR_PAD_LEFT));
        try {
            $input = [
               'uniq_id' => $p_uniq_id,
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'city_id' => 1,
            ];
            if($request->country_code && $request->telephone) {
                $input = array_merge($input, [ 'country_code' => $request->country_code, 'telephone' => $request->telephone]);
            }
            $company = Company::create($input);

         //   Mail::to($request->email)->send(new BenutzerMail($benutzer, $password));

            return response()->json('Das Unternehmen wurde erfolgreich erstellt.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }


    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    public function getInfo($company)
    {

        $companies = Company::where('id',$company)->get();

        return response()->json($companies);
    }

    public function destroy(CompanyRequest $company)
    {
       Company::where('company', $company->id)->delete();
        $company->delete();
        return response()->json('Company deleted successfully');
    }

    public function activate(Company $company)
    {

        try {
            $company->update([
                'status' => 1
            ]);

             return response()->json('Das Unternehmen “'.$company->name.'” wurde erfolgreich aktiviert.');
        }  catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }
    }



    public function deactivate(Company $company)
    {
        try {
            $company->update([
                'status' => 0
            ]);

            return response()->json('Das Unternehmen “'.$company->name.'” wurde erfolgreich deaktiviert.');
        }  catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }


    }

    public function update(CompanyUpdateRequest $request, Company $company)
    {

        try {
            $input = [
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'country_code'=> $request->country_code,

            ];
             if($request->country_code && $request->telephone) {
                array_merge($input, [ 'country_code' => $request->country_code, 'telephone' => $request->telephone]);
            }

            $company->update($input);

             return response()->json('Das Unternehmen “'.$company->name.'” wurde erfolgreich aktualisiert.');

        }  catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong'.getMessage(), 500);
        }
    }


    public function getmax()
    {

        $last_id =Company::max('id');

        $last_id = $last_id+'1';

        $p_uniq_id = 'UN'.strval(str_pad($last_id,5,0,STR_PAD_LEFT));

        return response()->json($p_uniq_id);
    }

    public function getActiveCompanyLists()
    {

        $results = Company::select(['id', 'name'])->where('status', 1)->get();

        return response($results);
    }


}
