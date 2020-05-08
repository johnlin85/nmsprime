<?php

namespace Modules\Dashboard\Http\Controllers;

use Log;
use View;
use Storage;
use App\GuiLog;
use Illuminate\Support\Facades\Auth;
use Modules\ProvBase\Entities\Modem;
use App\Http\Controllers\BaseController;
use Modules\HfcBase\Http\Controllers\HfcBaseController;

class DashboardController extends BaseController
{
    /**
     * @return Obj 	View: Dashboard (index.blade)
     */
    public function index()
    {
        $title = 'Dashboard';
        $logs = GuiLog::where([['username', '!=', 'cronjob'], ['model', '!=', 'User']])
            ->orderBy('updated_at', 'desc')->orderBy('user_id', 'asc')
            ->limit(50)->get();
        $tickets['table'] = Auth::user()->tickets()->where('state', '=', 'New')->get();
        $tickets['total'] = count($tickets['table']);

        $news = $this->news();

        // This is the most timeconsuming task
        $netelements = HfcBaseController::get_impaired_netelements();
        $services = HfcBaseController::get_impaired_services();

        return View::make('dashboard::index', $this->compact_prep_view(compact('title', 'logs', 'tickets', 'netelements', 'services', 'news')));
    }

    /**
     * Calculate modem statistics (online/offline), format and save to json
     * Used by Cronjob
     */
    public static function save_modem_statistics()
    {
        $avg_critical_us = config('hfccustomer.threshhold.avg.us.critical', 52);

        // Get only modems from valid contracts
        $query = Modem::join('contract as c', 'c.id', '=', 'modem.contract_id')
            ->where('c.contract_start', '<=', date('Y-m-d'))
            ->where(function ($query) {
                $query
                ->whereNull('c.contract_end')
                ->orWhere('c.contract_end', '=', '0000-00-00')
                ->orWhere('c.contract_end', '>', date('Y-m-d', strtotime('last day')));
            });

        $modems = [
            'all' => $query->where('modem.id', '>', '0')->count(),
            'online' => $query->where('modem.us_pwr', '>', '0')->count(),
            'critical' => $query->where('modem.us_pwr', '>', $avg_critical_us)->count(),
        ];

        Storage::disk('chart-data')->put('modems.json', json_encode($modems));
    }

    /**
     * Get modem statistics (online/offline) from json file - created by cron job
     *
     * @return array
     */
    public static function get_modem_statistics()
    {
        if (Storage::disk('chart-data')->has('modems.json') === false) {
            return false;
        }

        if (! \Module::collections()->has('HfcCustomer')) {
            return false;
        }

        $a = json_decode(Storage::disk('chart-data')->get('modems.json'));
        $modemStateAnalysis = new Modules\HfcCustomer\Entities\Utility\ModemStateAnalysis($a->online, $a->all);

        $a->text = 'Modems<br>'.$a->online.' / '.$a->all;

        $a->state = $modemStateAnalysis->get() ?? 'OK';

        switch ($a->state) {
            case 'OK':			$a->fa = 'fa fa-thumbs-up'; $a->style = 'success'; break;
            case 'WARNING':		$a->fa = 'fa fa-meh-o'; $a->style = 'warning'; break;
            case 'CRITICAL':	$a->fa = 'fa fa-frown-o'; $a->style = 'danger'; break;

            default:
                $a->fa = 'fa-question';
        }

        return $a;
    }

    /*
     * For News Blade:
     *
     * This function should guide a new user through critical stages
     * like installation. To do this, we should test how far installation
     * process is and addvice the next steps the user should do..
     *
     * This function could also be used to inform the user of new updates (etc)
     */
    public function news()
    {
        // check for insecure install
        if ($insecure = $this->isInsecureInstall()) {
            return $insecure;
        }

        // Install add sequence check
        if (\Module::collections()->has('ProvBase') && (\Modules\ProvBase\Entities\Modem::count() == 0)) {
            return $this->newsInstallAndSequenceCheck();
        }

        // Check for official news from support.nmsprime.com
        if ($news = $this->newsLoadOfficialSite()) {
            return $news;
        }

        // crowdin - check if language is still supported, otherwise show crowdin link
        if (! in_array(\Auth::user()->language, config('app.supported_locales'))) {
            return ['youtube' => 'https://www.youtube.com/embed/9mydbfHDDP4',
                'text' => ' <li>NMS PRIME is not yet translated to your language. Help translating NMS PRIME with
                    <a href="https://crowdin.com/project/nmsprime/'.\Auth::user()->language.'" target="_blank">Crowdin</a></li>', ];
        }

        // links need to be in embedded style, like:
        // return ['youtube' => 'https://www.youtube.com/embed/9mydbfHDDP4',
        //      'text' => "You should do: <a href=https://lifeisgood.com>BlaBlaBla</a>"];
    }

    /*
     * For News Blade:
     *
     * Check if installation is secure
     */
    private function isInsecureInstall()
    {
        // change default psw's
        if (\Hash::check('toor', \Auth::user()->password)) {
            return ['youtube' => 'https://www.youtube.com/embed/TVjJ7T8NZKw',
                'text' => '<li>Next: Change default Password! '.\HTML::linkRoute('User.profile', 'Global Config', \Auth::user()->id), ];
        }

        // means: secure – nothing todo
    }

    /*
     * News panel: load news from support server to json file
     * Documentation panel: load documentation.json from support server
     *
     * Official News Parser
     */
    public function newsLoadToFile()
    {
        if (env('IGNORE_NEWS')) {
            return false;
        }

        $support = 'https://support.nmsprime.com';

        $numNetGw = 0;
        $numModems = 0;
        if (\Module::collections()->has('ProvBase')) {
            $numNetGw = \Modules\ProvBase\Entities\NetGw::count();
            $numModems = \Modules\ProvBase\Entities\Modem::count();
        }

        $numNetelements = 0;
        if (\Module::collections()->has('HfcReq')) {
            $numNetelements = \Modules\HfcReq\Entities\NetElement::count();
        }

        $numTvbillings = 0;
        if (\Module::collections()->has('BillingBase')) {
            $numTvbillings = \Modules\BillingBase\Entities\Item::join('product', 'item.product_id', 'product.id')
            ->where('type', 'TV')
            ->where(function ($query) {
                $query->where('valid_to', null)
                    ->orWhere('valid_to', '>=', date('Y-m-d'));
            })
            ->count();
        }

        $files = [
            'news.json' => "$support/news.php?ns=&sla=".urlencode(\App\Sla::pluck('name')->first()).'&mc='.$numModems.'&nm='.$numNetGw.'&nn='.$numNetelements.'&nt='.$numTvbillings,
            'documentation.json' => "$support/documentation.json",
        ];

        foreach ($files as $name => $url) {
            try {
                Storage::put("data/dashboard/$name", file_get_contents($url));
            } catch (\Exception $e) {
                Log::error("Error retrieving $name (using installed version): ".$e->getMessage());
                Storage::delete("data/dashboard/$name");
            }
        }
    }

    /*
     * For News Blade:
     *
     * Official News Parser
     */
    private function newsLoadOfficialSite()
    {
        $file = 'data/dashboard/news.json';

        if (! Storage::exists($file)) {
            return;
        }

        $json = json_decode(Storage::get($file));

        if (! isset($json->youtube) || ! isset($json->text)) {
            return;
        }

        return ['youtube' => $json->youtube,
            'text' => $json->text, ];
    }

    /*
     * For News Blade:
     *
     * check install sequence order
     */
    private function newsInstallAndSequenceCheck()
    {
        $text = '<li>'.trans('helper.next');
        // set ISP name
        if (! \GlobalConfig::first()->name) {
            return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s',
                'text' => $text.\HTML::linkRoute('Config.index', trans('helper.set_isp_name')), ];
        }

        // add NetGw
        if (\Modules\ProvBase\Entities\NetGw::count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s?start=159&',
                'text' => $text.\HTML::linkRoute('NetGw.create', trans('helper.create_netgw')), ];
        }

        // add CM and CPEPriv IP-Pool
        foreach (['CM', 'CPEPriv'] as $type) {
            if (\Modules\ProvBase\Entities\IpPool::where('type', $type)->count() == 0) {
                return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s?start=240&',
                    'text' => $text.\HTML::linkRoute('IpPool.create', trans('helper.create_'.strtolower($type).'_pool'),
                            ['netgw_id' => \Modules\ProvBase\Entities\NetGw::first()->id, 'type' => $type]), ];
            }
        }

        // QoS
        if (\Modules\ProvBase\Entities\Qos::count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s?start=380&',
                'text' => $text.\HTML::linkRoute('Qos.create', trans('helper.create_qos')), ];
        }

        // Product
        if (\Module::collections()->has('BillingBase') &&
            \Modules\BillingBase\Entities\Product::where('type', '=', 'Internet')->count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s?start=425&',
                'text' => $text.\HTML::linkRoute('Product.create', trans('helper.create_product')), ];
        }

        // Configfile
        if (\Modules\ProvBase\Entities\Configfile::where('device', '=', 'cm')->where('public', '=', 'yes')->count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/aYjuWXhaV3s?start=500&',
                'text' => $text.\HTML::linkRoute('Configfile.create', trans('helper.create_configfile')), ];
        }

        // add sepa account
        if (\Module::collections()->has('BillingBase') && \Modules\BillingBase\Entities\SepaAccount::count() == 0) {
            return ['text' => $text.\HTML::linkRoute('SepaAccount.create', trans('helper.create_sepa_account'))];
        }

        // add costcenter
        if (\Module::collections()->has('BillingBase') && \Modules\BillingBase\Entities\CostCenter::count() == 0) {
            return ['text' => $text.\HTML::linkRoute('CostCenter.create', trans('helper.create_cost_center'))];
        }

        // add Contract
        if (\Modules\ProvBase\Entities\Contract::count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/t-PFsy42cI0?start=0&',
                'text' => $text.\HTML::linkRoute('Contract.create', trans('helper.create_contract')), ];
        }

        // check if nominatim email address is set, otherwise osm geocoding won't be possible
        if (env('OSM_NOMINATIM_EMAIL') == '') {
            return ['text' => $text.trans('helper.create_nominatim')];
        }

        // check if E-mails and names are set in Global Config Page/.env for Ticket module
        if ($text = $this->checkTicketSettings()) {
            return $text;
        }

        // check for local nameserver
        preg_match('/^Server:\s*(\d{1,3}).\d{1,3}.\d{1,3}.\d{1,3}$/m', shell_exec('nslookup nmsprime.com'), $matches);
        if (isset($matches[1]) && $matches[1] != '127') {
            return ['text' => $text.trans('helper.create_nameserver')];
        }

        // add Modem
        if (\Modules\ProvBase\Entities\Modem::count() == 0) {
            return ['youtube' => 'https://www.youtube.com/embed/t-PFsy42cI0?start=40&',
                'text' => $text.\HTML::linkRoute('Contract.edit', trans('helper.create_modem'), \Modules\ProvBase\Entities\Contract::first()), ];
        }

        return false;
    }

    /**
     * Check if the User can send/receive E-mails via Ticketsystem.
     *
     * @author Roy Schneider
     */
    private function checkTicketSettings()
    {
        // set variables in .env
        if (env('MAIL_HOST') == null || env('MAIL_USERNAME') == null || env('MAIL_PASSWORD') == null) {
            return ['text' => '<li> '.trans('helper.mail_env').' </li>'];
        }

        // set noreply name and address in Global Config Page
        $globalConfig = \GlobalConfig::first();

        if (Module::collections()->has('Ticketsystem') && (empty($globalConfig->noReplyName) || empty($globalConfig->noReplyMail))) {
            return ['text' => '<li>'.trans('helper.ticket_settings').'</li>'];
        }
    }
}
