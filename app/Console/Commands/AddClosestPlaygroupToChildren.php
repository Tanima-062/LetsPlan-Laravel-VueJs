<?php

namespace App\Console\Commands;

use App\Models\ChildrenRegistration;
use App\Models\Spielgruppen;
use Illuminate\Console\Command;

class AddClosestPlaygroupToChildren extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:closest-playgroup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add closest playgroup to children';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $childrens = ChildrenRegistration::whereNull('closest_spielgruppen')->get();
        $spielgruppensPlaceIds = Spielgruppen::pluck('place_id', 'id')->toArray();
        foreach ($childrens as $children){
            $distanceKm = $this->storeDistanceOfEachSpielgruppen($spielgruppensPlaceIds, $children->place_id);
            asort($distanceKm);
            $spielgruppensIds = array_keys($distanceKm);
            if (isset($spielgruppensIds[0])){
                $children->update([
                    'closest_spielgruppen'=>$spielgruppensIds[0]
                ]);
            }
        }
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
        $distanceData = file_get_contents($api);
        $arrDistanceData = json_decode($distanceData, true);
        if (is_array($arrDistanceData) && isset($arrDistanceData['status']) && $arrDistanceData['status'] == "OK") {
            $distanceInKm = $arrDistanceData['rows'][0]['elements'][0]['distance']['value'] ?? 0;
        }
        return $distanceInKm;
    }
}
