<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
class AcnooDomainSettingController extends Controller
{
    public function index()
    {
        $domain = Option::where('key', 'domain-setting')->first();
        return view('admin.settings.domain',compact('domain'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ssl_required' => 'required|string|max:100|in:on,off',
            'automatic_approve' => 'required|string|max:100|in:on,off',
        ]);

        Option::updateOrCreate(
            ['key' => 'domain-setting'],
            ['value' => [
                'ssl_required' => $request->ssl_required,
                'automatic_approve' => $request->automatic_approve,
            ]]
        );

        Cache::forget('domain-setting');

        return response()->json([
            'message'   => __('Domain setting updated successfully.'),
            'redirect'  => route('admin.domain-settings.index')
        ]);

    }
}
