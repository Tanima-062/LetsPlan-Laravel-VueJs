<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpielGruppenRequest;
use App\Http\Requests\UpdateSpielGruppenRequest;
use App\Http\Resources\SpielgruppenResource;
use App\Models\OpeningHour;
use App\Models\Spielgruppen;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\Division;
use App\Models\Status;
use Illuminate\Support\Facades\Storage;

class SpielgruppenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        //Admin with role 1 can view all spielgruppens with the same company_id
        $spielgruppens = Spielgruppen::where('company_id', Auth::user()->company_id)
            // ->when(Auth::user()->role_id == User::PLAYGROUP_LEADER, function ($query) {
            //     $query->active()->whereIn('id',  OpeningHour::where('lead_id', Auth::id())->pluck('spielgruppen_id'));
            // })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT, function ($query) {
            //     $query->active()->whereIn('id', OpeningHour::where('assistant_id', Auth::id())->pluck('spielgruppen_id'));
            // })
            ->search($request->search)
            ->location($request->city)
            ->time($request->time)
            ->statusIs($request->status)
            ->lead($request->lead)
            ->assistant($request->assistant)
            ->orderByColumns($request->order_by, $request->direction)
            ->paginate($request->per_page ?? 25);

        return SpielgruppenResource::collection($spielgruppens);
    }

    public function getNextId()
    {
        return ['prefix_id' => nextIdByCompany('spielgruppens', Spielgruppen::PREFIX), 'id_by_company' => nextIncrementIdByCompany('spielgruppens')];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SpielGruppenRequest $request
     * @return JsonResponse
     */
    public function store(SpielGruppenRequest $request)
    {
        try {

            $morning_days = json_decode($request->morning_days);
            $afternoon_days = json_decode($request->afternoon_days);
            $morning_leads = json_decode($request->morning_leads);
            $morning_assistants = json_decode($request->morning_assistants);
            $afternoon_leads = json_decode($request->afternoon_leads);
            $afternoon_assistants = json_decode($request->afternoon_assistants);

            $spielgruppen = new Spielgruppen();

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = uniqid('FILE_') . dechex(time()) . "." . $request->file('image')->getClientOriginalExtension();
                $spielgruppen->file = $file->storeAs('/spielgruppens', $filename, ['disk' => 'public']);
                $spielgruppen->image = $request->file('image')->getClientOriginalName();
            }

            $spielgruppen->id_by_company = $request->id_by_company;
            $spielgruppen->name = $request->name;
            $spielgruppen->street_name = $request->street_name;
            $spielgruppen->place_id = $request->place_id;
            $spielgruppen->street_number = $request->street_number;
            $spielgruppen->postal_code = $request->postal_code;
            $spielgruppen->location = $request->location;
            $spielgruppen->minimum_occupancy = $request->minimum_occupancy;
            $spielgruppen->maximum_occupancy = $request->maximum_occupancy;
            $spielgruppen->status_id = 1;
            $spielgruppen->parent_admin_id = Auth::user()->id;
            $spielgruppen->company_id = Auth::user()->company_id;
            $spielgruppen->morning_time_range = $request->morning_time_range;
            $spielgruppen->afternoon_time_range = $request->afternoon_time_range;

            $spielgruppen->save();

            $opening_hours = [];

            if (count($morning_days) > 0) {
                foreach ($morning_days as $key => $morning_day) {
                    if (isset($morning_leads[$key]) && isset($morning_assistants[$key])) {
                        array_push($opening_hours, [
                            'spielgruppen_id' => $spielgruppen->id,
                            'day_id' => $morning_day,
                            'hour_id' => 1,
                            'lead_id' => $morning_leads[$key],
                            'assistant_id' => $morning_assistants[$key]
                        ]);
                    }
                }
            }
            if (count($afternoon_days) > 0) {
                foreach ($afternoon_days as $key => $afternoon_day) {
                    if (isset($afternoon_leads[$key]) && isset($afternoon_assistants[$key])) {
                        array_push($opening_hours, [
                            'spielgruppen_id' => $spielgruppen->id,
                            'day_id' => $afternoon_day,
                            'hour_id' => 2,
                            'lead_id' => $afternoon_leads[$key],
                            'assistant_id' => $afternoon_assistants[$key]
                        ]);
                    }
                }
            }

            if (count($opening_hours) > 0) {
                DB::table('opening_hours')->insert($opening_hours);
            }

            $city_names = City::pluck('name')->toArray();
            if (!in_array($spielgruppen->location, $city_names)) {
                $city = new City();
                $city->name = $spielgruppen->location;
                $city->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Die Spielgruppe wurde erfolgreich erstellt.']);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }

    public function edit($id)
    {
        $spielgruppen = Spielgruppen::find($id);
        $spielgruppen->append('file_url');
        $spielgruppen->prefix_id = 'SPG' . sprintf("%05d", $spielgruppen->id_by_company);
        return response()->json($spielgruppen);
    }

    /**
     * Display the specified resource.
     *
     * @param Spielgruppen $spielgruppen
     * @return SpielgruppenResource
     */
    public function show($id)
    {
        $spielgruppen = Spielgruppen::find($id);
        $spielgruppen->prefix_id = 'SPG' . sprintf("%05d", $spielgruppen->id_by_company);
        $spielgruppen->append('file_url');

        return response()->json($spielgruppen);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSpielGruppenRequest $request
     * @param Spielgruppen $spielgruppen
     * @return JsonResponse
     */
    public function update(UpdateSpielGruppenRequest $request, $id)
    {
        try {

            $morning_days = json_decode($request->morning_days);
            $afternoon_days = json_decode($request->afternoon_days);
            $morning_leads = json_decode($request->morning_leads);
            $morning_assistants = json_decode($request->morning_assistants);
            $afternoon_leads = json_decode($request->afternoon_leads);
            $afternoon_assistants = json_decode($request->afternoon_assistants);

            $spielgruppen = Spielgruppen::find($id);

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = uniqid('FILE_') . dechex(time()) . "." . $request->file('image')->getClientOriginalExtension();
                $spielgruppen->file = $file->storeAs('/spielgruppens', $filename, ['disk' => 'public']);
                $spielgruppen->image = $request->file('image')->getClientOriginalName();
            } else {
                $spielgruppen->image = $request->filename;
                $spielgruppen->file = $request->filepath;
            }

            $spielgruppen->name = $request->name;
            $spielgruppen->street_name = $request->street_name;
            $spielgruppen->place_id = $request->place_id;
            $spielgruppen->street_number = $request->street_number;
            $spielgruppen->postal_code = $request->postal_code;
            $spielgruppen->location = $request->location;
            $spielgruppen->minimum_occupancy = $request->minimum_occupancy;
            $spielgruppen->maximum_occupancy = $request->maximum_occupancy;
            $spielgruppen->status_id = 1;
            $spielgruppen->parent_admin_id = Auth::user()['id'];
            $spielgruppen->company_id = Auth::user()->company_id;
            $spielgruppen->morning_time_range = $request->morning_time_range;
            $spielgruppen->afternoon_time_range = $request->afternoon_time_range;

            $spielgruppen->save();

            $opening_hours = [];

            if (count($morning_days) > 0) {
                foreach ($morning_days as $key => $morning_day) {
                    if (isset($morning_leads[$key]) && isset($morning_assistants[$key])) {
                        array_push($opening_hours, [
                            'spielgruppen_id' => $spielgruppen->id,
                            'day_id' => $morning_day,
                            'hour_id' => 1,
                            'lead_id' => $morning_leads[$key],
                            'assistant_id' => $morning_assistants[$key]
                        ]);
                    }
                }
            }
            if (count($afternoon_days) > 0) {
                foreach ($afternoon_days as $key => $afternoon_day) {
                    if (isset($afternoon_leads[$key]) && isset($afternoon_assistants[$key])) {
                        array_push($opening_hours, [
                            'spielgruppen_id' => $spielgruppen->id,
                            'day_id' => $afternoon_day,
                            'hour_id' => 2,
                            'lead_id' => $afternoon_leads[$key],
                            'assistant_id' => $afternoon_assistants[$key]
                        ]);
                    }
                }
            }

            DB::table('opening_hours')->where('spielgruppen_id', $id)->delete();

            if (count($opening_hours) > 0) {
                DB::table('opening_hours')->insert($opening_hours);
            }

            $city_names = City::pluck('name')->toArray();

            if (!in_array($spielgruppen->location, $city_names)) {
                $city = new City();
                $city->name = $spielgruppen->location;
                $city->save();
            }
            $message = sprintf("Die Spielgruppe “%s” wurde erfolgreich aktualisiert.", $spielgruppen->name);
            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }
    /**
     * Update the specified resource from storage.
     *
     * @param Spielgruppen $spielgruppen
     * @return JsonResponse
     */
    public function status(Request $request, Spielgruppen $spielgruppen)
    {
        $request->validate(['status' => ['in:2,1']]);
        $spielgruppen->update(['status_id' => $request->status]);
        $status = ($spielgruppen->status_id == 1) ? 'aktiviert' : 'deaktiviert';
        $message = sprintf('Die Spielgruppe “%s” wurde erfolgreich %s.', $spielgruppen->name, $status);
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Spielgruppen $spielgruppen
     * @return JsonResponse
     */
    public function destroy(Spielgruppen $spielgruppen)
    {
        OpeningHour::where('spielgruppen_id', $spielgruppen->id)->delete();
        $spielgruppen->delete();
        return response()->json('Spielgruppen deleted successfully');
    }

    public function getLeads()
    {
        $leads = User::select('id', 'name')->leads()->byCompany()->get();
        return response()->json($leads);
    }

    public function getAssistants()
    {
        $assistants = User::select('id', 'name')->assistants()->byCompany()->get();
        return response()->json($assistants);
    }

    public function getCities()
    {
        $cities = City::all();
        return response()->json($cities);
    }

    public function getDay($day)
    {
        if ($day == 1) {
            return 'Montag';
        } elseif ($day == 2) {
            return 'Dienstag';
        } elseif ($day == 3) {
            return 'Mittwoch';
        } elseif ($day == 4) {
            return 'Donnerstag';
        } elseif ($day == 5) {
            return 'Freitag';
        }
    }

    public function getFilterableData(Request $request)
    {
        $column = $request->column;

        $results = Spielgruppen::where('company_id', Auth::user()->company_id)
            // ->when(Auth::user()->role_id == User::PLAYGROUP_LEADER, function ($query) {
            //     $query->active()->whereIn('id',  OpeningHour::where('lead_id', Auth::id())->pluck('spielgruppen_id'));
            // })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT, function ($query) {
            //     $query->active()->whereIn('id', OpeningHour::where('assistant_id', Auth::id())->pluck('spielgruppen_id'));
            // })
            ->when(request('column' == 'city'), function ($q) {
                $q->select('location');
            })
            ->when(request('column' == 'status_id'), function ($q) {
                $q->select('status_id');
            })
            ->search($request->search)
            ->location($request->city)
            ->time($request->time)
            ->statusIs($request->status)
            ->lead($request->lead)
            ->assistant($request->assistant)
            ->orderByColumns($request->order_by, $request->direction);

        if ($column == 'lead') {
            if ($request->lead) {
                $users = User::select('id as value', DB::raw("CONCAT(first_name,' ',last_name) as label"))->whereIn('id', explode(',', $request->lead))->get();
                return $users;
            }
            $spielgruppen_ids = $results->pluck('id')->unique()->values()->toArray();
            $leadIds = OpeningHour::whereIn('spielgruppen_id', $spielgruppen_ids)->pluck('lead_id')->unique()->values()->toArray();
            $users = User::select('id as value', DB::raw("CONCAT(first_name,' ',last_name) as label"))->whereIn('id', $leadIds)->get();
            return $users;
        }
        if ($column == 'assistant') {
            if ($request->assistant) {
                $users = User::select('id as value', DB::raw("CONCAT(first_name,' ',last_name) as label"))->whereIn('id', explode(',', $request->assistant))->get();
                return $users;
            }

            $spielgruppen_ids = $results->pluck('id')->unique()->values()->toArray();
            $assistantIds = OpeningHour::whereIn('spielgruppen_id', $spielgruppen_ids)->pluck('assistant_id')->unique()->values()->toArray();
            $users = User::select('id as value', DB::raw("CONCAT(first_name,' ',last_name) as label"))->whereIn('id', $assistantIds)->get();
            return $users;
        }
        if ($column == 'city') {
            $locations = $results->pluck('location')->unique()->values()->toArray();
            $order_by_locations = "FIELD('" . implode("', '", $locations) . "')";
            $cities = City::select('name as value', 'name as label')->whereIn("name", $locations)->when(count($locations) > 1, fn ($q) => $q->orderByRaw($order_by_locations))->get();
            return $cities;
        }

        if ($column == 'status') {
            $status_label = [1 => 'Aktiv', 2 => 'Inaktiv'];
            return $results->pluck('status_id')->unique()->values()->map(fn ($item) => ['label' => $status_label[$item], 'value' => $item]);
        }

        if ($column == 'time') {
            $temp = $results;
            $spielgruppens = $temp->get();

            $times = [];

            foreach ($spielgruppens as $spielgruppen) {
                foreach ($spielgruppen->openingHours as $opening_hour) {
                    if (isset($request->time)) {
                        $request_times = explode(',', $request->time);
                        if (!is_null($spielgruppen->morning_time_range) && $opening_hour->hour_id == 1) {
                            $value = $opening_hour->day_id . '_' . $spielgruppen->morning_time_range;
                            $name = $this->getDay($opening_hour->day_id) . ' ' . $spielgruppen->morning_time_range;
                            if (!in_array($value, array_column($times, 'value')) && in_array($value, $request_times)) {
                                array_push($times, ['value' => $value, 'label' => $name]);
                            }
                        }
                        if (!is_null($spielgruppen->afternoon_time_range) && $opening_hour->hour_id == 2) {
                            $value = $opening_hour->day_id . '_' . $spielgruppen->afternoon_time_range;
                            $name = $this->getDay($opening_hour->day_id) . ' ' . $spielgruppen->afternoon_time_range;
                            if (!in_array($value, array_column($times, 'value')) && in_array($value, $request_times)) {
                                array_push($times, ['value' => $value, 'label' => $name]);
                            }
                        }
                    } else {
                        if (!is_null($spielgruppen->morning_time_range) && $opening_hour->hour_id == 1) {
                            $value = $opening_hour->day_id . '_' . $spielgruppen->morning_time_range;
                            $name = $this->getDay($opening_hour->day_id) . ' ' . $spielgruppen->morning_time_range;
                            if (!in_array($value, array_column($times, 'value'))) {
                                array_push($times, ['value' => $value, 'label' => $name]);
                            }
                        }
                        if (!is_null($spielgruppen->afternoon_time_range) && $opening_hour->hour_id == 2) {
                            $value = $opening_hour->day_id . '_' . $spielgruppen->afternoon_time_range;
                            $name = $this->getDay($opening_hour->day_id) . ' ' . $spielgruppen->afternoon_time_range;
                            if (!in_array($value, array_column($times, 'value'))) {
                                array_push($times, ['value' => $value, 'label' => $name]);
                            }
                        }
                    }
                }
            }
            return response()->json($times);
        }

        return [];
    }


    /**
     * Display the specified resource.
     *
     * @param Spielgruppen $spielgruppen
     * @return SpielgruppenResource
     */
    public function getPlaygroupWithAvailableSlots($id)
    {
        $playgroup = Spielgruppen::where('id', $id)->with(['openingHours', 'childrenRegistrations'])->first();
        $division = Division::where('company_id', $playgroup->company_id)->where('status_id', 2)->first();
        $playgroup->prefix_id = 'SPG' . sprintf("%05d", $playgroup->id_by_company);
        $playgroup->append('file_url');

        $availableSLots = [];

        for ($day = 1; $day <= 5; $day++) {
            for ($hour = 1; $hour <= 2; $hour++) {
                //Check if playgroup has the available slot
                $openingHour = $playgroup->openingHours->where('day_id', $day)->where('hour_id', $hour)->first();
                if (!$openingHour)
                    continue;

                //Check if playgroup reached it's max cap on a particular slot
                $registered = $playgroup->childrenRegistrations->where('division_id', $division->id)->filter(function ($registration) use ($day, $hour) {

                    if($registration->pivot->day_id == $day && $registration->pivot->hour_id == $hour) {
                        return 1;
                    }
                    return 0;
                })->values();
                if (count($registered) < $playgroup->maximum_occupancy) {
                    $availableSLots[] = $openingHour;
                }
            }
        }
        $playgroup = $playgroup->toArray();
        $playgroup['opening_hours'] = $availableSLots;
        unset($playgroup['children_registrations']);
        return response()->json($playgroup);
    }
}
