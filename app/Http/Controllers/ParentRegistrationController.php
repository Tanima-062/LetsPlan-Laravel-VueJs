<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnmeldungenRequest;
use App\Http\Requests\ParentRegistrationRequest;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\NationalityResource;
use App\Models\ChildrenRegistration;
use App\Models\ChildrenRegistrationOpeningHour;
use App\Models\ChildrenSlot;
use App\Models\Company;
use App\Models\Division;
use App\Models\MotherTongue;
use App\Models\Nationality;
use App\Models\OpeningHour;
use App\Models\Spielgruppen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;

class ParentRegistrationController extends Controller
{
    public function store(AnmeldungenRequest $request, $companyId)
    {

        $request->spielgruppen_id = $request->spielgruppen_id == 'keine' ? null : $request->spielgruppen_id;

        try {
            DB::transaction(function () use ($request, $companyId) {
                $division_id = Division::where('company_id', $companyId)->where('status_id', 2)->limit(1)->first()?->id;
                $mobile_phone_number = ($request->mobile_phone_number_prefix && $request->mobile_phone_number) ? ['mobile_phone_number_prefix' => "+$request->mobile_phone_number_prefix", 'mobile_phone_number' => $request->mobile_phone_number] : [];

                $parentRegistration = ChildrenRegistration::create([
                    'child_first_name' => $request->child_first_name,
                    'child_last_name' => $request->child_last_name,
                    'gender_id' => $request->gender_id,
                    'child_birthday' => Carbon::parse($request->input('child_birthday'))->toDateString(),
                    'nationality_id' => $request->nationality_id,
                    'mother_tongue_id' => $request->mother_tongue_id,
                    'other_mother_tongue' => $request->other_mother_tongue,
                    'mother_first_name' => $request->mother_first_name,
                    'mother_last_name' => $request->mother_last_name,
                    'father_first_name' => $request->father_first_name,
                    'father_last_name' => $request->father_last_name,
                    'street' => $request->street,
                    'street_number' => $request->street_number,
                    'postal_code' => $request->postal_code,
                    'place' => $request->place,
                    'email' => $request->email,
                    'prefix' => $request->phone_number_prefix,
                    'phone_number' => $request->phone_number,
                    'optional_address' => $request->optional_address,
                    'semester_id' => $request->semester_id,
                    'membership_id' => $request->membership_id,
                    'note' => $request->note,
                    'place_id' => $request->place_id,
                    'division_id' => $division_id,
                    'company_id' => $companyId,
                    'created_from' => 'parent_registration',
                    'spielgruppen_id' => $request->spielgruppen_id
                ] + $mobile_phone_number);

                $childOpeningHours = collect(array_map(function ($item) {
                    $key = key($item);
                    return collect([
                        'day_id' => $key,
                        'hour_id' => $item[$key],
                    ]);
                }, array_filter($request->openingHours)));

                foreach ($childOpeningHours->toArray() as $slot) {
                    $slots[] = [
                        'day_id' => $slot['day_id'],
                        'hour_id' => $slot['hour_id'],
                        'children_id' => $parentRegistration->id,
                    ];
                }

                ChildrenSlot::insert($slots);

                return \App\Jobs\ChildrenRegistration::dispatch($parentRegistration, $childOpeningHours);
            });
            return response()->json('Parent Registry Created');
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }
    }



    public function languages()
    {
        return LanguageResource::collection(MotherTongue::cursor());
    }

    public function spielgruppens(Request $request, $companyId)
    {
        $spielgruppens = Spielgruppen::select('id', 'name', 'maximum_occupancy', 'morning_time_range', 'afternoon_time_range')
            ->with('childrenRegistrations', function ($q) use ($request, $companyId) {
                if ($request->division == 'new_or_active') {
                    $divisionsIds = Division::where('company_id', $companyId)->whereIn('status_id', [1, 2])->pluck('id')->toArray();
                    return $q->whereIn('division_id', $divisionsIds);
                } else if ($request->division) {
                    return $q->where('division_id', $request->division);
                } else {
                    $divisionsId = Division::where('company_id', $companyId)->where('status_id', 2)->first()?->id;
                    return $q->where('division_id', $divisionsId);
                }
            })
            ->where('company_id', $companyId)
            ->statusIs($request->status)
            ->get();

        foreach ($spielgruppens as $key => $playgroup) {
            $playgroup->registrations_count = count($playgroup->childrenRegistrations);
            $selectableOpeningHoursCount = 0;
            foreach ($playgroup->openingHours as $key => $openingHour) {
                $registrationsPerOpeningHourCount =  $playgroup->childrenRegistrations->filter(fn ($registration) => $registration->pivot->hour_id == $openingHour->hour_id && $registration->pivot->day_id == $openingHour->day_id)->count();
                $openingHour->registrationCount = $registrationsPerOpeningHourCount;
                if ($registrationsPerOpeningHourCount < $playgroup->maximum_occupancy) {
                    $openingHour->selectable = true;
                    $selectableOpeningHoursCount++;
                }
            }
            $playgroup->selectable = $selectableOpeningHoursCount > 0 ? true : false;
            unset($playgroup['childrenRegistrations']);
        }

        return response()->json($spielgruppens);
    }

    public function nationalities()
    {
        return NationalityResource::collection(Nationality::cursor());
    }
}
