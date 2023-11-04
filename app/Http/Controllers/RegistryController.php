<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistryRequest;
use App\Http\Resources\RegistryResource;
use App\Models\Company;
use App\Models\Registry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegistryController extends Controller
{
    public function store(RegistryRequest $request)
    {
        try {
            $toStoreOrUpdate =[
                'parent_admid_id' => Auth::id(),
                'company_id' => Auth::user()->company_id,
                'registration_phase_start_date' => Carbon::parse($request->registration_phase_start_date)->toDateString(),
                'registration_phase_end_date' => Carbon::parse($request->registration_phase_end_date)->toDateString(),
                'playground_period_start_date' => Carbon::parse($request->playground_period_start_date)->toDateString(),
                'playground_period_end_date' => Carbon::parse($request->playground_period_end_date)->toDateString(),
                'parents_inform_date' => Carbon::parse($request->parents_inform_date)->toDateString(),
                'open_day_date' => Carbon::parse($request->open_day_date)->toDateString(),
                'open_day_time_from' => $request->open_day_time_from,
                'open_day_time_to' => $request->open_day_time_to,
                'content' => $request->open_day_description
            ];
            $request->input('id') ? Registry::findOrFail($request->input('id'))->update($toStoreOrUpdate): Registry::create($toStoreOrUpdate);
            return response()->json('Registry Created');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }

    public function show()
    {
        $registry = Registry::where('company_id', Auth::user()->company_id)->latest()->first();
        if ($registry){
            return new RegistryResource($registry);
        }
       return  [];
    }
    public function getRegistrationPeriodInfo($companyId)
    {
        $company = Company::whereHas('division', fn($q) => $q->where('status_id', 2))->where('id', $companyId)->firstOrFail();
        $results = [
            'year_of_the_end_date'=> null,
            'year_of_the_end_date_register'=> null,
            'year_of_the_start_date'=> null,
            'end_date_of_the_registration_phase'=> null,
            'end_date_of_the_registration'=> null,
            'end_date_of_the_registration_month'=> null,
            'registration_phase_start_date'=> null,
            'registration_phase_start_date_month'=> null,
            'parents_inform_date'=> null,
            'children_minimum_age'=> null,
            'registration_phase_to'=> null,
            'registration_phase_from'=> null,
        ];

        $registry = Registry::where('company_id', $companyId)->latest()->first();
        if ($registry){
            $months =  [
                '01' =>'Januar',
                '02' =>'Februar',
                '03' => 'März',
                '04' =>'April',
                '05'=>'Mai',
                '06'=>'Juni',
                '07'=>'Juli',
                '08'=>'August',
                '09'=>'September',
                '10'=>'Oktober',
                '11'=>'November',
                '12'=>'Dezember'
            ];
            $results = [
                'year_of_the_end_date'=> $registry->playground_period_end_date->format('Y'),
                'year_of_the_end_date_register'=> $registry->playground_period_end_date->format('Y'),
                'year_of_the_start_date'=> $registry->playground_period_start_date->format('Y'),
                'end_date_of_the_registration_phase'=> $registry->registration_phase_end_date->format('d.m.Y'),
                'registration_phase_to'=> $registry->registration_phase_end_date->format('d.m.Y'),
                'registration_phase_from'=> $registry->registration_phase_start_date->format('d.m.Y'),
                'end_date_of_the_registration'=> $registry->playground_period_end_date->format('d.m.Y'),
                'end_date_of_the_registration_month'=> $months[$registry->playground_period_end_date->format('m')],
                'registration_phase_start_date'=> $registry->playground_period_start_date->format('d.m.Y'),
                'registration_phase_start_date_month'=> $months[$registry->playground_period_start_date->format('m')],
                'parents_inform_date'=> $registry->parents_inform_date->format('d.m.Y'),
                'children_minimum_age'=> $registry->playground_period_start_date->subMonths(30)->format('d.m.Y'),
            ];
        }
        return response()->json($results);
    }

}
