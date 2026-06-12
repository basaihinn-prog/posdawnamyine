<?php

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\User;
use App\Models\Table;
use App\Models\Option;
use App\Models\Business;
use App\Models\Currency;
use App\Models\UserCurrency;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

function expectedItemId(): string
{
    return 58370658;
}

function cache_remember(string $key, callable $callback, int $ttl = 1800): mixed
{
    return cache()->remember($key, env('CACHE_LIFETIME', $ttl), $callback);
}

function asset_with_fallback(?string $path, string $fallback): string
{
    $path = trim((string) $path);

    if ($path !== '' && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'))) {
        return $path;
    }

    if ($path !== '' && file_exists(public_path(ltrim($path, '/')))) {
        return asset($path);
    }

    return asset($fallback);
}

function invoice_setting()
{
    return get_option('invoice_setting_' . auth()->user()->business_id);
}

function get_option($key)
{
    return cache_remember($key, function () use ($key) {
        return Option::where('key', $key)->first()->value ?? [];
    });
}

function get_tables()
{
    return Table::with('orders')->withCount('orders')->get();
}

function formatted_date(?string $date = null, string $format = 'd M, Y'): ?string
{
    return !empty($date) ? Date::parse($date)->format($format) : null;
}

function formatted_time(?string $time = null, string $format = 'h:i A'): ?string
{
    return !empty($time) ? Date::parse($time)->format($format) : null;
}

if (!function_exists('format_pretty_date')) {
    function format_pretty_date($date)
    {
        return Carbon::parse($date)->format('l, d M');
    }
}

function sendNotification($id, $url, $message, $user = null)
{
    $notify = [
        'id' => $id,
        'url' => $url,
        'user' => $user,
        'message' => $message,
    ];

    $notify_user = User::where('role', 'superadmin')->first();
    Notification::send($notify_user, new SendNotification($notify));
}

function sendNotifyToUser($id, $url, $message, $user) {
    $notify = [
        'id' => $id,
        'url' => $url,
        'user' => $user,
        'message' => $message,
    ];

    $notify_user = User::where('business_id', $user)->get();
    Notification::send($notify_user, new SendNotification($notify));
}

function currency_format($amount, $type = "icon", $decimals = 2, $currency = null)
{
    $amount = number_format($amount, $decimals);
    $currency = $currency ?? default_currency();

    if ($type == "icon" || $type == "symbol") {
        if ($currency->position == "right") {
            return $amount . $currency->symbol;
        } else {
            return $currency->symbol . $amount;
        }
    } else {
        if ($currency->position == "right") {
            return $amount . ' ' . $currency->code;
        } else {
            return $currency->code . ' ' . $amount;
        }
    }
}

function default_currency($key = null, ?Currency $currency = null): object|int|string
{
    $currency = $currency ?? cache_remember('default_currency', function () {
        $currency = Currency::whereIsDefault(1)->first();

        if (!$currency) {
            $currency = (object)['name' => 'US Dollar', 'code' => 'USD', 'rate' => 1, 'symbol' => '$', 'position' => 'left', 'status' => true, 'is_default' => true,];
        }

        return $currency;
    });

    return $key ? $currency->$key : $currency;
}


function convert_money($amount, $currency, $multiply = true)
{
    if ($currency->code == default_currency('code')) {
        return $amount;
    } else {
        if ($multiply) {
            return $amount * $currency->rate;
        } else {
            return $amount / $currency->rate;
        }
    }
}

// Format phone number
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone)
    {
        return substr($phone, 0, 2) . '*****' . substr($phone, -2);
    }
}

if (!function_exists('formatUserName')) {
    function formatUserName($name)
    {
        $parts = explode(' ', $name);
        $initials = array_map(function ($part) {
            return strtoupper($part[0]);
        }, $parts);

        return implode('', $initials);
    }
}

function plan_data($business_id = null)
{
    $business_id = $business_id ?? auth()->user()->business_id;

    return cache_remember('plan-data-' . $business_id, function () use ($business_id) {
        $planSubscribe = PlanSubscribe::with('plan:id,subscriptionName')->where('business_id', $business_id)->latest()->first();

        if ($planSubscribe) {
            $business = Business::findOrFail($planSubscribe->business_id);
            $planSubscribe->will_expire = $business->will_expire;
        }
        return $planSubscribe;
    });
}


function business_currency($business_id = null)
{
    if (!$business_id) {
        if (app()->bound('currentBusiness')) {
            $business = app('currentBusiness');
            $business_id = $business?->id;
        } elseif (auth()->check()) {
            $business_id = auth()->user()->business_id;
        }
    }

    // Still nothing? fallback to default currency
    if (!$business_id) {
        return default_currency();
    }

    return cache_remember("business_currency_{$business_id}", function () use ($business_id) {
        $businessCurrency = UserCurrency::where('business_id', $business_id)->first() ?? Currency::where('is_default', 1)->first();;

        if ($businessCurrency) {
            return (object)[
                'name' => $businessCurrency->name,
                'rate' => $businessCurrency->rate,
                'code' => $businessCurrency->code,
                'symbol' => $businessCurrency->symbol,
                'position' => $businessCurrency->position,
            ];
        }

        return default_currency();
    });
}

function get_business()
{
    $business = Business::first();
    return $business;
}

function get_business_option($key)
{
    $businessId = null;

    if (app()->bound('currentBusiness') && app('currentBusiness')) {
        $businessId = app('currentBusiness')->id;
    } elseif (auth()->check() && auth()->user()->business_id) {
        $businessId = auth()->user()->business_id;
    }

    if (!$businessId) {
        return null;
    }

    $cacheKey = "business_setting_{$businessId}";

    return Cache::remember($cacheKey, now()->addDay(), function () use ($key, $businessId) {

        if ($key === 'business-settings') {
            return Option::where('key', 'business-settings')
                ->whereJsonContains('value->business_id', $businessId)
                ->first()
                ->value ?? null;
        }

        return null;
    });
}


// Get business onlne store settings data
function get_business_web($key)
{
    $business = app('currentBusiness');
    $business_id = $business?->id;

    $cacheKey = 'business-web-settings-' . $business_id;

    return Cache::remember($cacheKey, now()->addDay(), function () use ($key) {
        $business = app('currentBusiness');
        $business_id = $business?->id;
        if ($key === 'business-web-settings') {
            return Option::where('key', 'business-web-settings')
                ->whereJsonContains('value->business_id',  $business_id)
                ->first()
                ->value ?? null;
        }
        return null;
    });
}

if (!function_exists('get_vat_on_sale')) {
    function get_vat_on_sale()
    {
        if (!app()->bound('currentBusiness') || !app('currentBusiness')) {
            return null;
        }

        $business = app('currentBusiness');

        return Tax::where('business_id', $business->id)
                  ->where('vat_on_sale', 1)
                  ->first();
    }
}

if (! function_exists('get_scanned_table')) {
    function get_scanned_table() {
        $tableId = session('online_store_table_id');

        if (!$tableId) {
            return null;
        }

        return Table::find($tableId);
    }
}

if (!function_exists('get_discount')) {
    function get_discount()
    {
        if (!app()->bound('currentBusiness') || !app('currentBusiness')) {
            return null;
        }
        $business = app('currentBusiness');
        return Option::where('key', 'discount')
            ->whereJsonContains('value->business_id', $business->id)
            ->whereJsonContains('value->status', '1')
            ->first();
    }
}

if (!function_exists('get_order_type')) {
    function get_order_type()
    {
        if (!app()->bound('currentBusiness') || !app('currentBusiness')) {
            return null;
        }

        $business = app('currentBusiness');

        return Option::where('key', 'order-type')
            ->whereJsonContains('value->business_id', $business->id)
            ->first();
    }
}

if (!function_exists('get_reservation_setting')) {
    function get_reservation_setting()
    {
        if (!app()->bound('currentBusiness') || !app('currentBusiness')) {
            return null;
        }

        $business = app('currentBusiness');

        return Option::where('key', 'reservation-setting')
            ->whereJsonContains('value->business_id', $business->id)
            ->first();
    }
}

if (!function_exists('get_online_store_url')) {
    function get_online_store_url()
    {
        $business = auth()->user()->business;
        $url = $business->publicQrUrl();

        return rtrim(preg_replace('#/menu/?$#', '', $url), '/');
    }
}


// user role permission
if (!function_exists('visible_permission')) {
    function visible_permission($permission)
    {
        $user = auth()->user();

        // Ensure the user is authenticated and has a business_id
        if (!$user || !$user->business_id) {
            return false;
        }

        // Handle visibility field directly as an array or decode it if it's a string
        $permissions = is_array($user->visibility)
            ? $user->visibility
            : json_decode($user->visibility, true);

        return $permissions[$permission] ?? false;
    }
}


function get_root_domain()
{
    $appUrl = config('app.url');
    return parse_url($appUrl, PHP_URL_HOST);
}

function checkDomainStatus($domain)
{
    $result = [
        'domain' => $domain,
        'exists' => false,
        'http'   => false,
        'https'  => false,
    ];

    // 1. Check if domain resolves (DNS record exists)
    if (dns_get_record($domain, DNS_A) || dns_get_record($domain, DNS_AAAA)) {
        $result['exists'] = true;

        // 2. Check HTTP (port 80)
        try {
            $response = Http::timeout(5)->get("http://{$domain}");
            if ($response->successful()) {
                $result['http'] = true;
            }
        } catch (\Exception $e) {
            $result['http'] = false;
        }

        // 3. Check HTTPS (port 443)
        try {
            $response = Http::timeout(5)->get("https://{$domain}");
            if ($response->successful()) {
                $result['https'] = true;
            }
        } catch (\Exception $e) {
            $result['https'] = false;
        }
    }

    return $result;
}

function moduleCheck($module)
{

    $module = Module::find($module);

    if ($module && $module->isEnabled()) {
        return true;
    }

    return false;
}


function languages()
{
    return [
        'en' => ['name' => 'English', 'flag' => 'us'],
        'ar' => ['name' => 'Arabic', 'flag' => 'sa'],
        'bn' => ['name' => 'Bengali', 'flag' => 'bd'],
        'zh' => ['name' => 'Chinese', 'flag' => 'cn'],
        'fr' => ['name' => 'French', 'flag' => 'fr'],
        'de' => ['name' => 'German', 'flag' => 'de'],
        'hi' => ['name' => 'Hindi', 'flag' => 'in'],
        'es' => ['name' => 'Spanish', 'flag' => 'es'],
        'ja' => ['name' => 'Japanese', 'flag' => 'jp'],
        'rum' => ['name' => 'Romanian', 'flag' => 'ro'],
        'vi' => ['name' => 'Vietnamese', 'flag' => 'vn'],
        'it' => ['name' => 'Italian', 'flag' => 'it'],
        'th' => ['name' => 'Thai', 'flag' => 'th'],
        'bs' => ['name' => 'Bosnian', 'flag' => 'ba'],
        'nl' => ['name' => 'Dutch', 'flag' => 'nl'],
        'pt' => ['name' => 'Portuguese', 'flag' => 'pt'],
        'pl' => ['name' => 'Polish', 'flag' => 'pl'],
        'he' => ['name' => 'Hebrew', 'flag' => 'il'],
        'hu' => ['name' => 'Hungarian', 'flag' => 'hu'],
        'fi' => ['name' => 'Finnish', 'flag' => 'fi'],
        'el' => ['name' => 'Greek', 'flag' => 'gr'],
        'ko' => ['name' => 'Korean', 'flag' => 'kr'],
        'ms' => ['name' => 'Malay', 'flag' => 'my'],
        'id' => ['name' => 'Indonesian', 'flag' => 'id'],
        'fa' => ['name' => 'Persian', 'flag' => 'ir'],
        'tr' => ['name' => 'Turkish', 'flag' => 'tr'],
        'sr' => ['name' => 'Serbian', 'flag' => 'rs'],
        'km' => ['name' => 'Khmer', 'flag' => 'khm'],
        'uk' => ['name' => 'Ukrainian', 'flag' => 'ua'],
        'lo' => ['name' => 'Lao', 'flag' => 'la'],
        'ru' => ['name' => 'Russian', 'flag' => 'ru'],
        'cs' => ['name' => 'Czech', 'flag' => 'cz'],
        'kn' => ['name' => 'Kannada', 'flag' => 'ka'],
        'mr' => ['name' => 'Marathi', 'flag' => 'mh'],
        'sv' => ['name' => 'Swedish', 'flag' => 'se'],
        'da' => ['name' => 'Danish', 'flag' => 'dk'],
        'ur' => ['name' => 'Urdu', 'flag' => 'pk'],
        'sq' => ['name' => 'Albanian', 'flag' => 'al'],
        'sk' => ['name' => 'Slovak', 'flag' => 'sk'],
        'bur' => ['name' => 'Burmese', 'flag' => 'mm'],
        'ti' => ['name' => 'Tigrinya', 'flag' => 'er'],
        'kz' => ['name' => 'Kazakh', 'flag' => 'kz'],
        'az' => ['name' => 'Azerbaijani', 'flag' => 'az'],
        'zh-cn' => ['name' => 'Chinese (CN)', 'flag' => 'zh-cn'],
        'zh-tw' => ['name' => 'Chinese (TW)', 'flag' => 'zh-tw'],
        'pt-br' => ['name' => 'Portuguese (BR)', 'flag' => 'pt-br'],
        'tz' => ['name' => 'Swahili', 'flag' => 'tz'],
        'ps' => ['name' => 'Pashto', 'flag' => 'af'],
        'prs' => ['name' => 'Dari', 'flag' => 'afdari'],
        'ca' => ['name' => 'Catalan', 'flag' => 'ad'],
        'bt' => ['name' => 'Dzongkha', 'flag' => 'dz'],
        'drcfr' => ['name' => 'Congo (DRC)', 'flag' => 'drc'],
        'cgfr' => ['name' => 'Congo (Republic)', 'flag' => 'cg'],
        'escr' => ['name' => 'Costa Rica (Spanish)', 'flag' => 'cr'],
        'enbw' => ['name' => 'Botswana (English)', 'flag' => 'bw'],
        'bws' => ['name' => 'Botswana (Setswana)', 'flag' => 'bws'],
        'deat' => ['name' => 'Austria(German)', 'flag' => 'at'],
        'enbs' => ['name' => 'Bahamas(English)', 'flag' => 'bs'],
        'arbh' => ['name' => 'Bahrain(Arabic)', 'flag' => 'bh'],
        'pt-ao' => ['name' => 'Angola(Portuguese)', 'flag' => 'ao'],
        'es-ar' => ['name' => 'Argentina(Spanish)', 'flag' => 'ar'],
        'hy' => ['name' => 'Armenian', 'flag' => 'am'],
        'au-en' => ['name' => 'Australia', 'flag' => 'au'],
        'bb-en' => ['name' => 'Barbados(English)', 'flag' => 'bb'],
        'be' => ['name' => 'Belarusian', 'flag' => 'by'],
        'nl-be' => ['name' => 'Belgium(Dutch)', 'flag' => 'be'],
        'bz-en' => ['name' => 'Belize(English)', 'flag' => 'bz'],
        'bj-fr' => ['name' => 'Benin(French)', 'flag' => 'bj'],
        'bo-es' => ['name' => 'Bolivia(Spanish)', 'flag' => 'bo'],
        'bn-ms' => ['name' => 'Brunei(Malay)', 'flag' => 'bn'],
        'bg' => ['name' => 'Bulgarian', 'flag' => 'bg'],
        'bf-fr' => ['name' => 'Burkina Faso(French)', 'flag' => 'bf'],
        'cm-fr' => ['name' => 'Cameroon(French)', 'flag' => 'cm'],
        'ca-en' => ['name' => 'Canada(English)', 'flag' => 'ca'],
        'cl-es' => ['name' => 'Chile(Spanish)', 'flag' => 'cl'],
        'co-es' => ['name' => 'Colombia(Spanish)', 'flag' => 'co'],
        'km-ar' => ['name' => 'Comoros(Arabic)', 'flag' => 'km'],
        'hr' => ['name' => 'Croatian', 'flag' => 'hr'],
        'cu-es' => ['name' => 'Cuba(Spanish)', 'flag' => 'cu'],
        'cy-el' => ['name' => 'Cyprus(Greek)', 'flag' => 'cy'],
        'dj-fr' => ['name' => 'Djibouti(French)', 'flag' => 'dj'],
        'dm-en' => ['name' => 'Dominica(English)', 'flag' => 'dm'],
        'tet' => ['name' => 'Tetum', 'flag' => 'tl'],
        'ec-es' => ['name' => 'Ecuador(Spanish)', 'flag' => 'ec'],
        'eg-ar' => ['name' => 'Egypt(Arabic)', 'flag' => 'eg'],
        'sv-es' => ['name' => 'El Salvador(Spanish)', 'flag' => 'sv'],
        'gq-es' => ['name' => 'Equatorial Guinea(Spanish)', 'flag' => 'gq'],
        'et' => ['name' => 'Estonian', 'flag' => 'ee'],
        'ss' => ['name' => 'Swati', 'flag' => 'sz'],
        'am' => ['name' => 'Amharic', 'flag' => 'et'],
        'fj' => ['name' => 'Fijian', 'flag' => 'fj'],
        'ga-fr' => ['name' => 'Gabon(French)', 'flag' => 'ga'],
        'gm-en' => ['name' => 'Gambia(English)', 'flag' => 'gm'],
        'ka' => ['name' => 'Georgian', 'flag' => 'ge'],
        'gh-en' => ['name' => 'Ghana(English)', 'flag' => 'gh'],
        'gd-en' => ['name' => 'Grenada(English)', 'flag' => 'gd'],
        'gt-en' => ['name' => 'Guatemala(English)', 'flag' => 'gt'],
        'gn-fr' => ['name' => 'Guinea(French)', 'flag' => 'gn'],
        'gy-en' => ['name' => 'Guyana(English)', 'flag' => 'gy'],
        'ht-fr' => ['name' => 'Haiti(French)', 'flag' => 'ht'],
        'hn-es' => ['name' => 'Honduras(Spanish)', 'flag' => 'hn'],
    ];
}

function getArrow(int|float $last, int|float $current): string
{
    if ($current > $last) {
        return 'up';
    }

    if ($current < $last) {
        return 'down';
    }

    return 'same';
}

function demoReset()
{
    if (!env('DEMO_MODE')) {
        return true;
    }

    DB::table('sales')->where('business_id', 1)->delete();
    DB::table('purchases')->where('business_id', 1)->delete();
    DB::table('parties')->where('business_id', 1)->delete();
    DB::table('expense_categories')->where('business_id', 1)->delete();
    DB::table('income_categories')->where('business_id', 1)->delete();
    DB::table('expenses')->where('business_id', 1)->delete();
    DB::table('incomes')->where('business_id', 1)->delete();

    Artisan::call('db:seed', ['--class' => 'DemoSeeder']);
}
