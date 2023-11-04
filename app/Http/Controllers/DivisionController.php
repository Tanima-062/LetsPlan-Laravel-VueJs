<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Division;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\DivisionRequest;
use App\Http\Resources\DivisionResource;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Company;

class DivisionController extends Controller
{
    public function index()
    {

        $divisions = Division::query()
            ->where('company_id', Auth::user()->company_id)
            ->when(request('query'), fn ($q) => $q->where('name', "LIKE", "%" . request('query') . "%"))
            ->when(request('order_by') && in_array(request('order_by'), ['name', 'registration_start_date', 'playgroup_start_date', 'date_parent', 'status_id']), function ($q) {
                $q->orderBy(request('order_by'), request('direction', 'DESC'));
            }, fn ($q) => $q->orderBy('id', request('direction', 'DESC')))
            ->paginate(request('per_page', 25));;

        return DivisionResource::collection($divisions);
    }

    public function store(DivisionRequest $request)
    {
        try {

            $division = Division::create([
                'name' => $request->name,
                'registration_start_date' => $request->registration_start_date,
                'registration_end_date'   => $request->registration_end_date,
                'playgroup_start_date' => $request->playgroup_start_date,
                'playgroup_end_date' => $request->playgroup_end_date,
                'date_parent' => $request->date_parent,
                'open_door_date' => $request->open_door_date,
                'open_door_start_time' => $request->open_door_start_time,
                'open_door_end_time' => $request->open_door_end_time,
                'place' => $request->place,
                'company_id' => Auth::user()->company_id,
            ]);

            //   Mail::to($request->email)->send(new BenutzerMail($benutzer, $password));

            return response()->json('Die Einteilung wurde erfolgreich erstellt.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.' . $exception->getMessage(), 500);
        }
    }

    public function getInfo($division)
    {

        $divisions = Division::where('id', $division)->get();

        return response()->json($divisions);
    }


    public function destroy(Division $division)
    {
        Division::where('id', $division->id)->delete();
        $division->delete();
        return response()->json('Company deleted successfully');
    }

    public function activate(Division $division)
    {

        try {
            $division->update([
                'status_id' => 1
            ]);

            return response()->json('Die Einteilung “' . $division->name . '” wurde erfolgreich aktiviert.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }
    }

    public function deactivate(Division $division)
    {
        try {
            $division->update([
                'status_id' => 0
            ]);

            return response()->json('Die Einteilung “' . $division->name . '” wurde erfolgreich deaktiviert.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }
    }

    public function update(UpdateDivisionRequest $request, Division $division)
    {

        try {
            $division->update([
                'name' => $request->name,
                'registration_start_date' => $request->registration_start_date,
                'registration_end_date'   => $request->registration_end_date,
                'playgroup_start_date' => $request->playgroup_start_date,
                'playgroup_end_date' => $request->playgroup_end_date,
                'date_parent' => $request->date_parent,
                'open_door_date' => $request->open_door_date,
                'open_door_start_time' => $request->open_door_start_time,
                'open_door_end_time' => $request->open_door_end_time,
                'place' => $request->place,
            ]);

            return response()->json('Die Einteilung “' . $division->name . '” wurde erfolgreich aktualisiert.');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Something went wrong', 500);
        }
    }

    public function getmax()
    {

        $last_id = Division::getNextPrefixId(Auth::user()->company_id);
        $last_id = $last_id;
        $p_uniq_id = 'ET' . strval(str_pad($last_id, 5, 0, STR_PAD_LEFT));

        return response()->json($p_uniq_id);
    }


    public function getDivision(Division $division)
    {
        return new DivisionResource($division);
    }


    public function updateDivisionStatus(Request $request, Division $division)
    {
        $request->validate([
            'status_id' =>  ['required']
        ]);
        // $request->validate([
        //     'status_id' =>  ['required']
        // ]);


        $division->update([
            'status_id' =>  $request->status_id
        ]);

        return response($division);
        // return new DivisionResource($division);
    }

    public function getAllDivisions()
    {
        return response()->json(Division::where('company_id', Auth::user()->company_id)
            ->whereIn('status_id', [1,2,3])
            ->select('id', 'name')
            ->get());
    }

    public function getRegistrationPeriodInfo($companyId)
    {
        $company = Company::where('id', $companyId)->firstOrFail();
        $division = Division::where('company_id', $companyId)->where('status_id', 2)->first();
        $upcoming_division = Division::upComingDivision()->where('company_id', $companyId)->first();

        $months =  [
            '01' => 'Januar',
            '02' => 'Februar',
            '03' => 'März',
            '04' => 'April',
            '05' => 'Mai',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'August',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Dezember'
        ];


        $open_door_date_time = Carbon::parse("$division?->open_door_date $division?->open_door_start_time")?->locale('de_DE');
        $open_door_start_time =  explode(":", $division?->open_door_start_time);
        $open_door_end_time =  explode(":", $division?->open_door_end_time);

        $open_door_start_time = $open_door_start_time ? $open_door_start_time[0] : null;
        $open_door_end_time = $open_door_end_time ? $open_door_end_time[0] : null;

        $results = [
            'playgroup_phase_start_date_year' => $division?->playgroup_start_date->format('Y'),
            'playgroup_phase_end_date_year' => $division?->playgroup_end_date->format('Y'),

            'playgroup_phase_start_date_month' => $months[$division?->playgroup_start_date->format('m')] ?? null,
            'playgroup_phase_end_date_month' => $months[$division?->playgroup_end_date->format('m')] ?? null,

            'registration_phase_from' => $division?->registration_start_date->format('d.m.Y'),
            'registration_phase_to' => $division?->registration_end_date->format('d.m.Y'),

            'parents_inform_date' => $division?->date_parent->format('d.m.Y'),
            'children_minimum_age' => $division?->playgroup_start_date->subMonths(30)->format('d.m.Y'),

            'upcoming_registration_start_date' => $upcoming_division?->registration_start_date->format('d.m.Y'),
            'upcoming_registration_end_date' => $upcoming_division?->registration_end_date->format('d.m.Y'),

            'open_door_date' => $open_door_date_time->translatedFormat("l, j F Y"),
            'open_door_start_time' => $open_door_start_time,
            'open_door_end_time' => $open_door_end_time,
            'place' => $division?->place,
        ];

        return response()->json($results);
    }
}
