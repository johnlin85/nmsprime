<?php

namespace Modules\HfcBase\Http\Controllers;

use Modules\ProvBase\Entities\Modem;
use Modules\HfcReq\Entities\NetElement;
use Modules\HfcBase\Entities\IcingaObject;
use Modules\HfcBase\Entities\IcingaHostStatus;
use Modules\HfcBase\Entities\IcingaServiceStatus;

class TroubleDashboardController
{
    /**
     * Collect all necessary information for the trouble dashboard.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function impairedData()
    {
        if (! IcingaObject::db_exists()) {
            return collect(['impairedData' => [], 'netelements' => [], 'services' => [], 'hosts' => []]);
        }

        $hosts = IcingaHostStatus::forTroubleDashboard()->get();
        $services = IcingaServiceStatus::forTroubleDashboard()->get();
        $netelements = NetElement::withActiveModems('id', '>', '0')->get()->keyBy('id');
        $affectedModemsCount = self::calculateModemCounts($netelements);

        $impairedData = $hosts->concat($services)
            ->sortByDesc(function ($element) use ($affectedModemsCount) {
                return [
                    $element->last_hard_state,
                    $element->affectedModemsCount($affectedModemsCount),
                ];
            })
            ->map(function ($impaired) use ($affectedModemsCount) {
                if (isset($impaired->additionalData) && ! is_array($impaired->additionalData)) {
                    $impaired->additionalData = $impaired->additionalData
                        ->sortByDesc(function ($element) use ($affectedModemsCount) {
                            return [
                                // $element['state'],
                                ((isset($affectedModemsCount[$element['id']])) ? $affectedModemsCount[$element['id']] : 0),
                            ];
                        });
                }

                return $impaired;
            });

        $ackState = $impairedData->mapWithKeys(function ($impaired) {
            return [$impaired->icingaObject->object_id => $impaired->problem_has_been_acknowledged];
        })->toJson();

        return collect(compact('ackState', 'hosts', 'impairedData', 'netelements', 'services', 'ackstate', 'affectedModemsCount'));
    }

    /**
     * Get the cummulated number of Modems for each NetElement
     *
     * @param \Illuminate\Database\Eloquent\Collection $netelements
     * @return array
     */
    public static function calculateModemCounts(\Illuminate\Database\Eloquent\Collection $netelements)
    {
        $netelementIds = Modem::select('netelement_id')->distinct()->pluck('netelement_id')->filter();
        $modemsPerNetelement = [];

        foreach ($netelementIds as $id) {
            $currentId = $id;
            $parentId = $netelements[$currentId]->parent_id;
            $branchModemCount = 0;

            while ($parentId > 1) {
                $branchModemCount = $branchModemCount +
                    (! isset($modemsPerNetelement[$currentId]) ? $netelements[$currentId]->modems_count : 0);

                $modemsPerNetelement[$currentId] = ($modemsPerNetelement[$currentId] ?? 0) + $branchModemCount;

                $currentId = $parentId;
                $parentId = $netelements[$currentId]->parent_id;
            }
        }

        return $modemsPerNetelement;
    }

    /**
     * Call to Icinga2 API and acknowledge or remove acknowledgement for a Problem.
     *
     * @param string $type
     * @param int $id
     * @param bool $mute
     * @return \Illuminate\Http\Response
     */
    public function muteProblem($type, $id, $mute)
    {
        $filter = $this->composeFilter($type, $id = IcingaObject::find($id));
        $icingaApi = new \Modules\HfcBase\Helpers\IcingaApi($type, $filter);
        $results = $mute ? $icingaApi->acknowledgeProblem() : $icingaApi->removeAcknowledgement();

        if (! $results) {
            \Log::alert('An API request was sent to ICINGA2, but it is probably not runnig.');

            return response(json_encode(['error' => 'ICINGA2 is not running.']), 400);
        }

        $results['id'] = $id->object_id;

        return response(json_encode($results), $results['error'] ?? 200);
    }

    /**
     * Create the filter for Icinga2 API
     *
     * @param string $type
     * @param IcingaObject $icingaObject
     * @return string|\Illuminate\Http\Response
     */
    protected function composeFilter($type, $icingaObject)
    {
        if ($type === 'Service') {
            return "host.name == \"{$icingaObject->name1}\" && service.name == \"{$icingaObject->name2}\"";
        }

        if ($type === 'Host') {
            return "host.name == \"{$icingaObject->netelement->id_name}\"";
        }

        return response(['results' => [
            'error' => 400,
            'status' => 'Bad Request. Your Type Parameter is not matching our database.',
        ],
        ], 400);
    }
}
