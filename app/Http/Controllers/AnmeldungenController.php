<?php

namespace App\Http\Controllers;

use App\Exports\ChildrenExport;
use App\Http\Requests\AnmeldungenRequest;
use App\Http\Requests\AnmeldungenUpdateRequest;
use App\Http\Resources\ChildrenResource;
use App\Mail\AnmeldungenMail;
use App\Models\ChildrenRegistration;
use App\Models\ChildrenRegistrationOpeningHour;
use App\Models\ChildrenSlot;
use App\Models\Division;
use App\Models\OpeningHour;
use App\Models\Spielgruppen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Models\Nationality;
use App\Models\MotherTongue;
class AnmeldungenController extends Controller
{
    public function index(Request $request)
    {

        // $childerenIds = [];
        // if (in_array(Auth::user()->role_id, [User::PLAYGROUP_LEADER, User::PLAYGROUP_ASSISTANT]) && empty($childerenIds)) {
        //     $openingHours = OpeningHour::whereHas('spielgruppen', function ($query) {
        //         $query->active()->where('company_id', auth()->user()->company_id);
        //     })->when(Auth::user()->role_id == User::PLAYGROUP_LEADER, function ($query) {
        //         return $query->where('lead_id', Auth::id());
        //     })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT, function ($query) {
        //         return $query->where('assistant_id', Auth::id());
        //     })->with('spielgruppen.uniqueChildrens')->get();

        //     foreach ($openingHours as $openingHour) {
        //         $child = $openingHour->spielgruppen->uniqueChildrens()
        //             ->where('day_id', $openingHour->day_id)
        //             ->where('hour_id', $openingHour->hour_id)
        //             ->pluck('children_registration_id');
        //         if ($values = $child->toArray()) {
        //             $childerenIds[] = $values;
        //         }
        //     }
        // }

        $results = ChildrenRegistration::with(['spielgruppen', 'nationality', 'motherTongue', 'spielgruppen', 'selectedSpielgruppen', 'closestSpielgruppen', 'status', 'slots'])
            ->where('company_id', auth()->user()->company_id)
            ->when(request('spielgruppen_id') && request('spielgruppen_id') != '0', function ($query) {
                return $query->whereHas('childrenRegistrationOpeningHours', function ($query) {
                    $query->where('spielgruppen_id', request('spielgruppen_id'));
                });
            })
            // ->when(in_array(Auth::user()->role_id, [User::PLAYGROUP_LEADER, User::PLAYGROUP_ASSISTANT]), function ($query) use ($childerenIds) {
            //     $query->whereIn('id', array_unique(call_user_func_array('array_merge', $childerenIds)));
            // })
            ->search($request->search)
            ->createdAt($request->start_date, $request->end_date)
            ->assignedPlaygroup($request->assigned_playgroup)
            ->preferredPlaygroup($request->preferred_playgroup)
            ->isCommented($request->commented)
            ->isMarked($request->marked)
            ->statusIs($request->status)
            ->divisionIs($request->division)
            ->orderByColumns($request->order_by, $request->direction)
            ->paginate($request->per_page ?? 25);

        return ChildrenResource::collection($results);
    }

    public function store(AnmeldungenRequest $request)
    {
        try {
            $request->spielgruppen_id = $request->spielgruppen_id == 'keine' ? null : $request->spielgruppen_id;

            DB::transaction(function () use ($request) {
                $mobile_phone_number = ($request->mobile_phone_number_prefix && $request->mobile_phone_number) ? ['mobile_phone_number_prefix' => "+$request->mobile_phone_number_prefix", 'mobile_phone_number' => $request->mobile_phone_number] : [];
                $anmeldungenRegistration = ChildrenRegistration::create([
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
                    'phone_number_prefix' => "+$request->phone_number_prefix",
                    'phone_number' => $request->phone_number,
                    'semester_id' => $request->semester_id,
                    'membership_id' => $request->membership_id,
                    'division_id' => $request->division_id,
                    'note' => $request->note,
                    'place_id' => $request->place_id,
                    'company_id' => auth()->user()->company_id,
                    'created_from' => 'admin_registration',
                    'session_id' => $request->session_id,
                    'spielgruppen_id' => $request->spielgruppen_id,
                    'optional_address' => $request->optional_address
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
                        'children_id' => $anmeldungenRegistration->id,
                    ];
                }
                ChildrenSlot::insert($slots);

                return \App\Jobs\ChildrenRegistration::dispatch($anmeldungenRegistration, $childOpeningHours, 'admin_registration');
            });
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }

        return response()->json('Anmeldungen created');
    }

    public function update(AnmeldungenUpdateRequest $request, ChildrenRegistration $child_registration)
    {
        try {
            $request->spielgruppen_id = $request->spielgruppen_id == 'keine' ? null : $request->spielgruppen_id;

            DB::transaction(function () use ($request, $child_registration) {
                $mobile_phone_number = ($request->mobile_phone_number_prefix && $request->mobile_phone_number) ? ['mobile_phone_number_prefix' => "+$request->mobile_phone_number_prefix", 'mobile_phone_number' => $request->mobile_phone_number] : [];
                $anmeldungenRegistration = $child_registration->update([
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
                    'phone_number_prefix' => $request->phone_number_prefix,
                    'phone_number' => $request->phone_number,
                    'semester_id' => $request->semester_id,
                    'membership_id' => $request->membership_id,
                    'division_id' => $request->division_id,
                    'note' => $request->note,
                    'internal_comments' => $request->internal_comments,
                    'place_id' => $request->place_id,
                    'company_id' => auth()->user()->company_id,
                    'created_from' => 'admin_registration',
                    'session_id' => $request->session_id,
                    'spielgruppen_id' => $request->spielgruppen_id,
                    'optional_address' => $request->optional_address
                ] + $mobile_phone_number);

                $childOpeningHours = collect(array_map(function ($item) {
                    $key = key($item);
                    return collect([
                        'day_id' => $key,
                        'hour_id' => $item[$key],
                    ]);
                }, array_filter($request->openingHours)));


                // if($this->openingHoursChanged($childOpeningHours)) {
                // }
                foreach ($childOpeningHours->toArray() as $slot) {
                    $slots[] = [
                        'day_id' => $slot['day_id'],
                        'hour_id' => $slot['hour_id'],
                        'children_id' => $child_registration->id,
                    ];
                }
                $child_registration->childrenRegistrationOpeningHours()->delete();
                $child_registration->slots()->delete();
                ChildrenSlot::insert($slots);

                return \App\Jobs\ChildrenRegistration::dispatch($child_registration, $childOpeningHours, 'admin_registration');
            });
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json('Es ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 500);
        }

        $message = "Die Anmeldung für “" . $child_registration->child_first_name . " " . $child_registration->child_last_name . "” wurde erfolgreich aktualisiert.";
        return response()->json($message);
    }

    public function show(ChildrenRegistration $child_registration)
    {
        $child_registration->load(['nationality', 'motherTongue', 'selectedSpielgruppen', 'closestSpielgruppen', 'childrenRegistrationOpeningHours', 'division', 'spielgruppen', 'slots']);
        return response()->json($child_registration);
    }
    public function getLastRegisterdChildrenId(Request $request)
    {
        return response()->json(ChildrenRegistration::getNextPrefixId($request->company_id ?? Auth::user()->company_id));
    }

    public function export(Request $request)
    {
        $data = ChildrenRegistration::where('company_id', auth()->user()->company_id)
            ->where('status_id', 1)
            ->with(['motherTongue', 'nationality', 'selectedSpielgruppen', 'closestSpielgruppen', 'slots', 'childrenRegistrationOpeningHours.spielgruppen'])


            ->when(request('playgroup') && !is_null(request('playgroup')), function ($q) {
                $playgroups = explode(',', request('playgroup'));
                $q->whereIn('spielgruppen_id', $playgroups);
            })
            // ->when(request('classification') && !is_null(request('classification')), function ($q) {
            //     $classifications = explode(',', request('classification'));
            //     $q->whereIn('division_id', $classifications);
            // })
            // ->when(request('classification') && !is_null(request('classification')), function($q){
            //     $classifications = explode(',', request('classification'));
            //     $q->whereIn('division_id', $classifications);
            // }, function($q){
            //     $q->whereHas('division', function($q){
            //         $q->where('status_id', 1);
            //     });
            // })
            ->when(request('classification') && !is_null(request('classification')), function($q){
                $classifications = explode(',', request('classification'));
                $q->whereIn('division_id', $classifications);
            }, function($q){
                $q->whereHas('division', function($q) {
                    $activeDivision = Division::query()
                    ->where('company_id', \auth()->user()->company_id)
                    ->where('status_id', 2)
                    ->first()
                    ;

                    if($activeDivision) {
                        $q->where('id', $activeDivision->id);
                    }else {
                        $q->where('status_id', 1);
                    }
                });
            })
            ->when(request('playgroup_leader') && !is_null(request('playgroup_leader')), function ($q) {
                $q->whereHas('spielgruppen', function ($q) {
                    $q->whereHas('openingHours', function ($q) {
                        $playgroupLeaders = explode(',', request('playgroup_leader'));
                        $q->whereIn('lead_id', $playgroupLeaders);
                    });
                });
            })
            ->when(request('assistant') && !is_null(request('assistant')), function ($q) {
                $q->whereHas('spielgruppen', function ($q) {
                    $q->whereHas('openingHours', function ($q) {
                        $playgroupAssistants = explode(',', request('assistant'));
                        $q->whereIn('assistant_id', $playgroupAssistants);
                    });
                });
            })
            ->when(request('child') && !is_null(request('child')), function ($q) {
                $childrens = explode(',', request('child'));
                $q->whereIn('id', $childrens);
            })
            ->when(request('marking') && !is_null(request('marking')), function ($q) {
                $markings = explode(',', request('marking'));
                $q->whereIn('children_registration_id', $markings);
            })
            ->when(request('query'), function ($q) {
                $q->where(function ($q) {
                    $query = request('query');
                    $q
                        ->where('child_first_name', 'LIKE', "$query%")
                        ->orWhere('child_last_name', 'LIKE', "$query%")
                        ->orWhereRaw("CONCAT(`child_first_name`, ' ', `child_last_name`) LIKE ?", [$query . '%'])
                        // ->orWhere('child_birthday', 'LIKE', "$query%")
                        ;

                    if(preg_match("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $query, $matches)){
                        $date = sprintf("%s-%s-%s", $matches[3], $matches[2], $matches[1]);
                        $q->orWhere('child_birthday', 'LIKE', "$date%");
                    }
                });
            })
            // ->cursor()
            ->get();

        return Excel::download(new ChildrenExport($data), 'childrens.xlsx');
    }

    /**
     * Update Registration Status
     * @param Illuminate\Http\Request $request
     * @param App\Models\ChildrenRegistration $child_registration
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function updateStatus(Request $request, ChildrenRegistration $child_registration)
    {
        $request->validate(['status' => ['in:2,1']]);
        $child_registration->update(['status_id' => $request->status]);

        if($child_registration->status_id == ChildrenRegistration::STATUS_INACTIVE) {
            $child_registration->childrenRegistrationOpeningHours()->delete();
        }

        $message = $child_registration->status_id == ChildrenRegistration::STATUS_ACTIVE
            ? "Die Anmeldung für “{$child_registration->child_first_name} {$child_registration->child_last_name}” wurde erfolgreich aktiviert und die Planungseinträge für das entsprechende Kind vorgenommen."
            : "Die Anmeldung für “{$child_registration->child_first_name} {$child_registration->child_last_name}” wurde erfolgreich deaktiviert und die Planungseinträge für das entsprechende Kind gelöscht.";

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * Mark Unmark Registration
     * @param Illuminate\Http\Request $request
     * @param App\Models\ChildrenRegistration $child_registration
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function updateMarked(Request $request, ChildrenRegistration $child_registration)
    {
        $request->validate(['marked' => ['in:0,1']]);
        $child_registration->update(['marked' => $request->marked]);
        $message = $child_registration->marked
            ? "Die Anmeldung für “{$child_registration->child_first_name} {$child_registration->child_last_name}” wurde erfolgreich markiert."
            : "Die Anmeldung für “{$child_registration->child_first_name} {$child_registration->child_last_name}” wurde erfolgreich demarkiert.";
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * Get Filterable Data of an column based on the result
     *
     */
    public function getFilterableData(Request $request)
    {
        $column = $request->column;

        // $childerenIds = [];
        // if (in_array(Auth::user()->role_id, [User::PLAYGROUP_LEADER, User::PLAYGROUP_ASSISTANT]) && empty($childerenIds)) {
        //     $openingHours = OpeningHour::whereHas('spielgruppen', function ($query) {
        //         $query->active()->where('company_id', auth()->user()->company_id);
        //     })->when(Auth::user()->role_id == User::PLAYGROUP_LEADER, function ($query) {
        //         return $query->where('lead_id', Auth::id());
        //     })->when(Auth::user()->role_id == User::PLAYGROUP_ASSISTANT, function ($query) {
        //         return $query->where('assistant_id', Auth::id());
        //     })->with('spielgruppen.uniqueChildrens')->get();

        //     foreach ($openingHours as $openingHour) {
        //         $child = $openingHour->spielgruppen->uniqueChildrens()
        //             ->where('day_id', $openingHour->day_id)
        //             ->where('hour_id', $openingHour->hour_id)
        //             ->pluck('children_registration_id');
        //         if ($values = $child->toArray()) {
        //             $childerenIds[] = $values;
        //         }
        //     }
        // }
        $results = ChildrenRegistration::where('company_id', auth()->user()->company_id)
            ->when(request('spielgruppen_id') && request('spielgruppen_id') != '0', function ($query) {
                return $query->whereHas('childrenRegistrationOpeningHours', function ($query) {
                    $query->where('spielgruppen_id', request('spielgruppen_id'));
                });
            })
            // ->when(in_array(Auth::user()->role_id, [User::PLAYGROUP_LEADER, User::PLAYGROUP_ASSISTANT]), function ($query) use ($childerenIds) {
            //     $query->whereIn('id', array_unique(call_user_func_array('array_merge', $childerenIds)));
            // })
            ->search($request->search)
            ->createdAt($request->start_date, $request->end_date)
            ->assignedPlaygroup($request->assigned_playgroup)
            ->preferredPlaygroup($request->preferred_playgroup)
            ->isCommented($request->commented)
            ->isMarked($request->marked)
            ->statusIs($request->status)
            ->orderByColumns($request->order_by, $request->direction);

        if ($column == 'preferred_playgroup') {
            $playgroup_ids = $results->pluck('spielgruppen_id')->unique()->values()->toArray();
            $order_by_playgroup_ids = "FIELD('" . implode("', '", $playgroup_ids) . "')";
            $playgroups = Spielgruppen::select('id as value', 'name as label')->whereIn("id", $playgroup_ids)->when(count($playgroup_ids) > 1, fn ($q) => $q->orderByRaw($order_by_playgroup_ids))->get();
            return $playgroups;
        }

        if ($column == 'division') {
            $division_ids = $results->pluck('division_id')->unique()->values()->toArray();
            $order_by_division_ids = "FIELD('" . implode("', '", $division_ids) . "')";
            $divisions = Division::select('id as value', 'name as label')->whereIn("id", $division_ids)->when(count($division_ids) > 1, fn ($q) => $q->orderByRaw($order_by_division_ids))->get();
            return $divisions;
        }

        if ($column == 'commented') {
            $notes = $results->pluck('note')->toArray();
            $filters = [];
            foreach ($notes as $key => $note) {
                $existing_filter =  0;
                if (isset($filters[0]) && $filters[0]['value']) {
                    $existing_filter = 'commented';
                }
                if (isset($filters[0]) && !$filters[0]['value']) {
                    $existing_filter = 'not commented';
                }

                if (is_null($note) && $existing_filter !== 'not commented') {
                    array_push($filters, ['label' => 'Anmeldungen ohne Bemerkungen', 'value' => 0]);
                }

                if (!is_null($note) && $existing_filter !== 'commented') {
                    array_push($filters, ['label' => 'Anmeldungen mit Bemerkungen', 'value' => 1]);
                }
                if (count($filters) > 1) {
                    break;
                }
            }
            return $filters;
        }

        if ($column == 'marked') {
            $marked_label = [1 => 'Markierte', 0 => 'Unmarkierte'];
            return $results->pluck('marked')->unique()->values()->map(fn ($item) => ['label' => $marked_label[$item], 'value' => $item]);
        }

        if ($column == 'status') {
            $status_label = [1 => 'Aktiv', 2 => 'Inaktiv', 3 => 'Pendent'];
            return $results->pluck('status_id')->unique()->values()->map(fn ($item) => ['label' => $status_label[$item], 'value' => $item]);
        }

        return [];
    }

    public function sendMail(ChildrenRegistration $child_registration)
    {
        Mail::to($child_registration->email)->send(new AnmeldungenMail());
        $child_registration->mail_sent_at = Carbon::now();
        $child_registration->save();

        $name = $child_registration->child_first_name . ' ' . $child_registration->child_last_name;
        $message = sprintf("Die Elterninfo-E-Mail wurde erfolgreich an die Eltern von %s gesendet.", $name);


        return response()->json([
                'status'        =>  'success',
                'message'       =>  $message,
                'email_sent_at' =>  $child_registration->mail_sent_at
        ]);
    }

    public function getFilterableNationalities($query){
       $results = Nationality::select('id', 'name')->where('name','like','%'.$query.'%')->get();
       return $results;
    }

    public function getFilterableMotherTongues($query){
        $results = MotherTongue::select('id', 'name')->where('name','like','%'.$query.'%')->get();
        return $results;
     }
}
