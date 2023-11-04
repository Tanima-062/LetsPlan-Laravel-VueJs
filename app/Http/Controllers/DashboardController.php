<?php

namespace App\Http\Controllers;

use App\Models\ChildrenRegistration;
use App\Models\ChildrenRegistrationOpeningHour;
use App\Models\Spielgruppen;
use App\Models\OpeningHour;
use App\Models\Division;
use App\Models\MotherTongue;
use App\Models\Nationality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $results = ChildrenRegistration::where('company_id', Auth::user()->company_id)->division($request->division);
        $total_child_registrations = count($results->get());
        $spielgruppenIds = Spielgruppen::where('company_id', Auth::user()->company_id)->where('status_id', 1)->pluck('id')->unique()->values()->toArray();
        $total_spielgruppen_half_days = OpeningHour::whereIn('spielgruppen_id', $spielgruppenIds)->get();
        $total_spielgruppen_half_days = count($total_spielgruppen_half_days);
        $total_spielgruppens = count($spielgruppenIds);

        $registrations_chart_data = $this->getRegistrationsCountPieCartData($request);
        $nationalities_chart_data = $this->getRegistrationsNationalitiesCountPieCartData($request);
        $mother_toungs_chart_data = $this->getRegistrationsMotherTungsCountPieCartData($request);

        $data = [
            'total_child_registrations' => $total_child_registrations,
            'total_spielgruppen_half_days' => $total_spielgruppen_half_days,
            'total_spielgruppens' => $total_spielgruppens,

            'registrations_chart_data' => $registrations_chart_data,
            'nationalities_chart_data' => $nationalities_chart_data,
            'mother_toungs_chart_data' => $mother_toungs_chart_data
        ];
        return $data;
    }

    public function getFilterableData(Request $request)
    {
        $results = ChildrenRegistration::where('company_id', Auth::user()->company_id)->division($request->division);
        if ($request->column == 'division') {
            $divisionIds = $results->pluck('division_id')->unique()->values()->toArray();
            $divisions = Division::select('id as value', 'name as label')->whereIn('id', $divisionIds)->get();
            return $divisions;
        }
    }

    /**
     * Registrations Count Pie Chart Data And Labels
     *
     * @return Array
     */
    private function getRegistrationsCountPieCartData(Request $request)
    {
        $registrations_condition_label = [
            'Optimal eingeteilt', // Optimally divided
            'Nicht im naheliegendsten Standort eingeteilt', // Not zoned in the closest location
            'Nicht bei derselben Spielgruppenleitung eingeteilt', // Not assigned to the same playgroup management
            'Nicht im naheliegendsten Standort und nichtbei derselben Spielgruppenleitung eingeteilt', // Not in the closest location and not assigned to the same playgroup leader
            'Manuell eingeteilt', // Manually assigned
            'Konnte nicht eingeteilt werden', // Could not be assigned
        ];
        $registrations_count_per_condition = collect([]);

        foreach ($registrations_condition_label as $condition) {
            $registrations = ChildrenRegistration::query()
                ->where('company_id', Auth::user()->company_id)
                ->division($request->division);
            if ($condition == 'Optimal eingeteilt') {
                $count = $registrations->optimallyAssigned()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#9CDAE5', "textColor" => 'white'];
            }

            if ($condition == 'Nicht im naheliegendsten Standort eingeteilt') {
                $count = $registrations->notInClosestLocation()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#F5B88D', "textColor" => 'white'];
            }

            if ($condition == 'Nicht bei derselben Spielgruppenleitung eingeteilt') {
                $count = $registrations->notAssignedToSameLead()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#F8E7A8', "textColor" => 'white'];
            }

            if ($condition == 'Nicht im naheliegendsten Standort und nichtbei derselben Spielgruppenleitung eingeteilt') {
                $count = $registrations->notInBothBothLocationAndLead()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#9FDCF8', "textColor" => 'white'];
            }

            if ($condition == 'Manuell eingeteilt') {
                $count = $registrations->manuallyAssigned()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#FAFCFF', "textColor" => '#87B1CB'];
            }

            if ($condition == 'Konnte nicht eingeteilt werden') {
                $count = $registrations->notAssigned()->count();
                $registrations_count_per_condition[$condition] = ['count' => $count, 'color' => '#FD8181', "textColor" => 'white'];
            }
        }

        //Update labels and index based on count;
        $registrations_count_per_condition = $registrations_count_per_condition->filter(fn ($item) => $item['count'] > 0);
        // dd($registrations_count_per_condition);

        return [
            'registrations_condition_label' => $registrations_count_per_condition->keys()->toArray(),
            'registrations_count_per_condition' => $registrations_count_per_condition->pluck('count')->toArray(),
            'registrations_condition_label_color' => $registrations_count_per_condition->pluck('color')->toArray(),
            'registrations_condition_text_color' => $registrations_count_per_condition->pluck('textColor')->toArray()
        ];
    }

    /**
     * get Registrations Nationalities data for chart
     *
     */

    public function getRegistrationsNationalitiesCountPieCartData(Request $request)
    {
        $registrations = ChildrenRegistration::query()->selectRaw('count(*) as registration_count, nationality_id')
            ->where('company_id', Auth::user()->company_id)
            ->division($request->division)
            ->groupBy('nationality_id')
            ->orderBy('nationality_id')
            ->get();


        $nations = Nationality::select('name')->whereIn('id', $registrations->pluck('nationality_id')->toArray())->pluck('name')->toArray();
        $registrations_counts = $registrations->pluck('registration_count')->toArray();

        return ['nations' => $nations, 'registration_counts' => $registrations_counts];
    }

    /**
     * get Registrations Nationalities data for chart
     *
     */

    public function getRegistrationsMotherTungsCountPieCartData(Request $request)
    {
        $registrations = ChildrenRegistration::query()->selectRaw('count(*) as registration_count, mother_tongue_id')
            ->where('company_id', Auth::user()->company_id)
            ->division($request->division)
            ->groupBy('mother_tongue_id')
            ->orderBy('mother_tongue_id')
            ->get();


        $motherToungs = MotherTongue::select('name')->whereIn('id', $registrations->pluck('mother_tongue_id')->toArray())->pluck('name')->toArray();
        $registrations_counts = $registrations->pluck('registration_count')->toArray();

        return ['mother_tongus' => $motherToungs, 'registration_counts' => $registrations_counts];
    }
}
