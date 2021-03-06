<?php

namespace Modules\HfcBase\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Modules\HfcBase\Contracts\ImpairedContract;

class IcingaServiceStatus extends Model implements ImpairedContract
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql-icinga2';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'icinga_servicestatus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'servicestatus_id';

    /**
     * The amount of modems affected by this Service.
     *
     * @var int
     */
    public $affectedModems;

    /**
     * The "booting" method of the model. For every retrieved Service, try to
     * deserialize the perfdata property.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->additionalData = $model->deserializePerfdata($model->perfdata ?? '');
        });
    }

    /**
     * Relation to IcingaObject.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icingaObject()
    {
        return $this->belongsTo(IcingaObject::class, 'service_object_id', 'object_id')
            ->where('is_active', '=', '1');
    }

    /**
     * Relation to IcingaStateHistory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stateHistory()
    {
        return $this->hasMany(IcingaStateHistory::class, 'object_id', 'service_object_id');
    }

    /**
     * Relation to IcingaStateHistory for the last 2 months.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recentStateHistory()
    {
        return $this->stateHistory()
            ->orderBy('state_time')
            ->where('state_type', 1)
            ->where('state_time', '>', now()->subMonth(2));
    }

    /**
     * Scope to get all necessary informations for the trouble Dashboard.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTroubleDashboard($query)
    {
        return $query->orderBy('last_hard_state', 'desc')
            ->orderBy('last_time_ok', 'desc')
            ->with(['icingaObject.netelement'])
            ->whereHas('icingaObject');
    }

    /**
     * Scope to get all necessary informations for the trouble Dashboard.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCountsForTroubleDashboard($query)
    {
        return $query->whereHas('icingaObject')
            ->selectRaw('COUNT(CASE WHEN `last_hard_state` = 0 THEN 1 END) AS ok')
            ->selectRaw('COUNT(CASE WHEN `last_hard_state` = 1 THEN 1 END) AS warning')
            ->selectRaw('COUNT(CASE WHEN `last_hard_state` = 2 THEN 1 END) AS critical')
            ->selectRaw('COUNT(CASE WHEN `last_hard_state` = 3 THEN 1 END) AS unknown');
    }

    /**
     * Laravel magic method to quickly access the netelement.
     *
     * @return void
     */
    public function getNetelementAttribute()
    {
        return $this->icingaObject->netelement;
    }

    /**
     * Checks whether the deserialization of the perfdata property contained
     * additional Data.
     *
     * @return bool
     */
    public function hasAdditionalData()
    {
        return count($this->additionalData);
    }

    /**
     * Link for this service in IcingaWeb2
     *
     * @return string
     */
    public function toIcingaWeb()
    {
        return 'https://'.request()->server('HTTP_HOST').'/icingaweb2/monitoring/service/show?host='.
            $this->icingaObject->name1.'&service='.$this->icingaObject->name2;
    }

    /**
     * Link to Controlling page in NMS Prime. For Services there is currently
     * no such page.
     *
     * @return void
     */
    public function toControlling()
    {
    }

    /**
     * Link to Topo overview. Depending of the information available the
     * netelement or all netelements are displayed.
     *
     * @return string
     */
    public function toMap()
    {
        if ($this->netelement) {
            return route('TreeTopo.show', ['field' => 'id', 'search' => $this->netelement->id]);
        }

        if (is_numeric($id = explode('_', $this->icingaObject->name1)[0])) {
            return route('TreeTopo.show', ['field' => 'id', 'search' => $id]);
        }

        return route('TreeTopo.show', ['field' => 'id', 'search' => 2]);
    }

    /**
     * Link to Ticket creation form already prefilled.
     *
     * @return string
     */
    public function toTicket()
    {
        $state = preg_replace('/[<>]/m', '', $this->output);

        return route('Ticket.create', [
            'name' => "Service {$this->icingaObject->name2}: ",
            'description' => "{$state}\nSince {$this->last_hard_state_change}",
        ]);
    }

    /**
     * For the rows in additional data a ticket can be created.
     *
     * @param \Illuminate\Database\Eloquent\Collection $netelement
     * @return string
     */
    public function toSubTicket($netelement)
    {
        if (! isset($this->additionalData[0])) {
            return route('Ticket.create', [
                'name' => "{$this->check_command} on {$netelement->name}",
                'description' => "{$this->output}\n{$this->long_output}\nSince {$this->last_hard_state_change}",
            ]);
        }

        return route('Ticket.create', [
            'name' => "{$this->check_command} on {$netelement->name}: ",
            'description' => "{$this->additionalData[0]['text']}\n{$this->output}\n{$this->long_output}\nSince {$this->last_hard_state_change}",
        ]);
    }

    /**
     * Sums the modem count of each affected cluster if this service monitors
     * clusters otherwise it tries to get the amount of affected modems of
     * the related NetElement.
     *
     * @param \Illuminate\Database\Eloquent\Collection $netelements
     * @return int
     */
    public function affectedModemsCount($netelements)
    {
        if (Str::contains($this->icingaObject->name2, 'cluster')) {
            return $this->affectedModems = $this->additionalData->map(function ($element) use ($netelements) {
                if (isset($element['id']) && isset($netelements[$element['id']])) {
                    $element['modems'] = $netelements[$element['id']];
                }

                return $element;
            })->sum('modems');
        }

        if ($this->netelement) {
            return $this->affectedModems = $netelements[$this->netelement->id] ?? 0;
        }

        return 0;
    }

    /**
     * Return formatted impaired performance data for a given perfdata string
     *
     * @author Ole Ernst, Christian Schramm
     * @param string $perf
     * @return Illuminate\Support\Collection
     */
    private function deserializePerfdata(string $perf): \Illuminate\Support\Collection
    {
        $ret = [];
        preg_match_all("/('.+?'|[^ ]+)=([^ ]+)/", $perf, $matches, PREG_SET_ORDER);

        foreach ($matches as $idx => $match) {
            $data = explode(';', rtrim($match[2], ';'));

            $value = $data[0];
            $unifiedValue = intval(preg_replace('/[^0-9.]/', '', $value)); // remove unit of measurement, such as percent
            $warningThreshhold = $data[1] ?? null;
            $criticalThreshhold = $data[2] ?? null;

            if (is_numeric($unifiedValue) && (substr($value, -1) == '%' || (isset($data[3]) && isset($data[4])))) { // we are dealing with percentages
                $min = $data[3] ?? 0;
                $max = $data[4] ?? 100;
                $percentage = ($max - $min) ? (($unifiedValue - $min) / ($max - $min) * 100) : null;
                $percentageText = sprintf(' (%.1f%%)', $percentage);
            }

            if (is_numeric($unifiedValue) && $warningThreshhold && $criticalThreshhold) { // set the html color
                $htmlClass = $this->getPerfDataHtmlClass($unifiedValue, $warningThreshhold, $criticalThreshhold);

                if ($htmlClass === 'success') { // don't show non-impaired perf data
                    unset($ret[$idx]);
                    continue;
                }
            }

            $id = explode('_', substr($match[1], 1))[0];
            $text = is_numeric($id) ? "'".explode('_', substr($match[1], 1))[1] : $match[1];
            if (! is_numeric($id)) {
                $id = null;
            }

            $colorToState = [
                'danger' => 2,
                'warning' => 1,
                'success' => 0,
            ];

            $ret[$idx]['id'] = $id;
            $ret[$idx]['val'] = $value;
            $ret[$idx]['text'] = $text.($percentageText ?? null);
            $ret[$idx]['cls'] = $htmlClass ?? null;
            $ret[$idx]['state'] = isset($htmlClass) ? $colorToState[$htmlClass] : null;
            $ret[$idx]['per'] = $percentage ?? null;
        }

        return collect($ret);
    }

    /**
     * Return performance data colour class according to given limits
     *
     * @author Ole Ernst, Christian Schramm
     * @param int $value
     * @param int $warningThreshhold
     * @param int $criticalThreshhold
     * @return string
     */
    private function getPerfDataHtmlClass(int $value, int $warningThreshhold, int $criticalThreshhold): string
    {
        if ($criticalThreshhold > $warningThreshhold) { // i.e. for upstream power
            [$value, $warningThreshhold,$criticalThreshhold] =
                negate($value, $warningThreshhold, $criticalThreshhold);
        }

        if ($value > $warningThreshhold) {
            return 'success';
        }

        if ($value > $criticalThreshhold) {
            return 'warning';
        }

        return 'danger';
    }
}
