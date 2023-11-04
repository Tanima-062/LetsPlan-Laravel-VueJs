<?php

namespace App\Jobs;

use App\Models\ChildrenRegistrationOpeningHour;
use App\Models\Spielgruppen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChildrenRegistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\ChildrenRegistration
     */
    protected $childrenRegistration;
    protected $openingHours;
    /**
     * @var string
     */
    protected $createdFrom;
    /**
     * @var null
     */
    protected $spielgruppenId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Models\ChildrenRegistration $childrenRegistration, $openingHours, $createdFrom = 'parent_registration')
    {
        //
        $this->childrenRegistration = $childrenRegistration;
        $this->openingHours = $openingHours;
        $this->createdFrom = $createdFrom;
        $this->spielgruppenId = $childrenRegistration->spielgruppen_id;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        $selectedSpielgruppenId = $this->spielgruppenId;

        $spielgruppensPlaceIds = Spielgruppen::where('company_id', $this->childrenRegistration->company_id)->pluck('place_id', 'id')->toArray();

        //store distance of each spielgruppen
        $distanceKm = $this->storeDistanceOfEachSpielgruppen($spielgruppensPlaceIds, $this->childrenRegistration->place_id);

        logger('child_id = '.$this->childrenRegistration->id);
        //sort playgroups by distance
        asort($distanceKm);

        logger(print_r($distanceKm, true));
        $spielgruppensIds = array_keys($distanceKm);
        logger('spielgruppensIds');
        logger(print_r($spielgruppensIds, true));
        if (isset($spielgruppensIds[0])) {
            $this->childrenRegistration->update([
                'closest_spielgruppen' => $spielgruppensIds[0]
            ]);
        }
        //children has selected a specific playgroup, we start first with this one
        if ($selectedSpielgruppenId) {
            $temp[$selectedSpielgruppenId] = $distanceKm[$selectedSpielgruppenId];
            unset($distanceKm[$selectedSpielgruppenId]);
            $distanceKm = $temp + $distanceKm;
        }

        //spielgruppens with it's openingHours and sorted by distance

        $spielgruppensIds = array_keys($distanceKm);
        logger('sorted');
        logger(print_r($spielgruppensIds, true));
        $sorter = static function ($spielgruppen) use ($spielgruppensIds) {
            return array_search($spielgruppen->id, $spielgruppensIds);
        };
        $spielgruppens = Spielgruppen::with('openingHours', 'childrenRegistrations')->find($spielgruppensIds)->sortBy($sorter);
        $searchResults = [];
        $childOpeningHoursAsArray = $this->openingHours->toArray();

        foreach ($spielgruppens as $spielgruppen):

            logger('doWeHaveAvailability');
            logger($spielgruppen->id);
            $leadsAvailabilities = [];
            $spielgruppensAvailabilities = [];
            foreach ($spielgruppen->openingHours as $openingHour) {
                //Need Division filter here
                $doWeHaveAvailabilityForTheGivenSlot = ChildrenRegistrationOpeningHour::where('spielgruppen_id', $spielgruppen->id)
                    ->where('hour_id', $openingHour->hour_id)
                    ->where('day_id', $openingHour->day_id)
                    ->get()->count();
                logger($doWeHaveAvailabilityForTheGivenSlot);
                if ($doWeHaveAvailabilityForTheGivenSlot >= $spielgruppen->maximum_occupancy) {
                    continue;  //playgroup it's not available we can't continue;
                }
                $leadsAvailabilities[$openingHour->lead_id][] = [
                    'day_id' => $openingHour->day_id,
                    'hour_id' => $openingHour->hour_id,
                ];
                $spielgruppensAvailabilities[$spielgruppen->id][] = [
                    'day_id' => $openingHour->day_id,
                    'hour_id' => $openingHour->hour_id,
                ];
            }
            //check here for the same playgroup leader
            if (!empty($leadsAvailabilities)) {
                logger('lead Availabilities');
                logger(print_r($leadsAvailabilities, true));
                foreach ($leadsAvailabilities as $lead_id => $availabilities) {
                    //check is the playgroup leader available as requested by the children
                    if (count($availabilities) < $this->childrenRegistration->semester_id) {
                        //this leader isn't available as much as required by the children
                        continue;
                    }
                    $matchedSlots = $this->getSlotsMatchesBetweenPlaygroupLeaderAndChildren($availabilities, $childOpeningHoursAsArray);
                    logger('match');
                    logger(print_r($matchedSlots, true));
                    //check if founded slots are as many as requested slots
                    if (count($matchedSlots) >= $this->childrenRegistration->semester_id) {
                        //we found the leader which is available as requested by the children
                        $searchResults = [
                            'slots' => $matchedSlots,
                            'spielgruppen_id' => $spielgruppen->id,
                            'color' => (array_key_first($distanceKm) == $spielgruppen->id || $spielgruppen->id == $this->childrenRegistration->closest_spielgruppen) ? ChildrenRegistrationOpeningHour::GREEN : ChildrenRegistrationOpeningHour::ORANGE,
                        ];
                        break 2;
                    }
                }
            }
            logger('spielgruppens Availabilities');
            logger(print_r($spielgruppensAvailabilities, true));
            if(empty($spielgruppensAvailabilities)){
                continue; //
            }
            foreach ($spielgruppensAvailabilities as  $spielgruppen_id=>$availabilities):
                $matchedSlots = $this->getSlotsMatchesBetweenPlaygroupLeaderAndChildren($availabilities, $childOpeningHoursAsArray);
                logger('match on different leaders');
                logger(print_r($matchedSlots, true));
                if (count($matchedSlots) >= $this->childrenRegistration->semester_id) {
                    //we found the leader which is available as requested by the children
                    $searchResults = [
                        'slots' => $matchedSlots,
                        'spielgruppen_id' => $spielgruppen->id,
                        'color' => ChildrenRegistrationOpeningHour::YELLOW,
                    ];
                    break 2;
                }
            endforeach;

        endforeach;

        logger('searchResults');
        logger(print_r($searchResults, true));
        //we  found match
        if (!empty($searchResults)) {
            logger('===========================================================');
            return $this->storeChildrenRequestedSlots($this->childrenRegistration->semester_id, $searchResults);
        }
        //we didn't found any match,all playgroups are full, so we will assign the children to the closest playgroup
        logger('===========================================================');
        return $this->noMatchFound($childOpeningHoursAsArray, array_key_first($distanceKm));

    }

    /**
     * @param $spielgruppensPlaceIds
     * @param $childPlaceId
     * @return array
     */
    private function storeDistanceOfEachSpielgruppen($spielgruppensPlaceIds, $childPlaceId): array
    {
        $allDistancesInKm = [];
        foreach ($spielgruppensPlaceIds as $key => $spielgruppensPlaceId) {
            $allDistancesInKm[$key] = $this->getDistanceFromPlaygroupToChildrenLocation($childPlaceId, $spielgruppensPlaceId);
        }

        return $allDistancesInKm;
    }

    /**
     * @param $childPlaceId
     * @param $spielgruppenPlaceId
     * @return boolean | float | integer
     */
    private function getDistanceFromPlaygroupToChildrenLocation($childPlaceId, $spielgruppenPlaceId)
    {
        $distanceInKm = false;
        $api = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=place_id:' . $childPlaceId . '&destinations=place_id:' . $spielgruppenPlaceId . '&key=' . env('GOOGLE_MAP_API_KEY');
        // $distanceData = file_get_contents($api);

        $distanceData = file_get_contents_curl($api);
        info($distanceData);


        $arrDistanceData = json_decode($distanceData, true);
        if (is_array($arrDistanceData) && isset($arrDistanceData['status']) && $arrDistanceData['status'] == "OK") {
            $distanceInKm = $arrDistanceData['rows'][0]['elements'][0]['distance']['value'] ?? 0;
        }
        return $distanceInKm;
    }

    /**
     * @param $availabilities
     * @param array $childOpeningHoursAsArray
     * @return array
     */
    public function getSlotsMatchesBetweenPlaygroupLeaderAndChildren($availabilities, array $childOpeningHoursAsArray): array
    {
        $tmpArray = [];
        foreach ($availabilities as $availabilty) {

            $duplicate = false;
            foreach ($childOpeningHoursAsArray as $childOpeningHour) {
                if ($availabilty['day_id'] === $childOpeningHour['day_id'] && $availabilty['hour_id'] === $childOpeningHour['hour_id']) $duplicate = true;
            }

            if ($duplicate) $tmpArray[] = $availabilty;
        }
        return $tmpArray;
    }

    /**
     * @param int $requestedTimesPerWeek
     * @param array $searchResults
     * @return bool
     */
    public function storeChildrenRequestedSlots(int $requestedTimesPerWeek, array $searchResults): bool
    {

        for ($i = 0; $i < $requestedTimesPerWeek; $i++) {
            $childrenOpeningHours[] = [
                'children_registration_id' => $this->childrenRegistration->id,
                'day_id' => $searchResults['slots'][$i]['day_id'],
                'hour_id' => $searchResults['slots'][$i]['hour_id'],
                'created_from' => $this->createdFrom,
                'spielgruppen_id' => $searchResults['spielgruppen_id'],
                'color' => $searchResults['color'],
            ];
        }
        ChildrenRegistrationOpeningHour::insert($childrenOpeningHours);
        return true;
    }

    /**
     * @param array $childOpeningHoursAsArray
     * @param $spielgruppen_id
     * @return bool
     */
    public function noMatchFound(array $childOpeningHoursAsArray, $spielgruppen_id = null): bool
    {

        $this->storeChildrenRequestedSlots($this->childrenRegistration->semester_id, [
            'slots' => $childOpeningHoursAsArray,
            'spielgruppen_id' => $spielgruppen_id,
            // 'color' => ChildrenRegistrationOpeningHour::RED,
            'color' => ChildrenRegistrationOpeningHour::TURQUOISE,
        ]);
        return true;
    }
}
