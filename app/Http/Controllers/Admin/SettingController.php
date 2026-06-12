<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SettingController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:settings-read')->only('index');
        $this->middleware('permission:settings-update')->only('update');
    }

    public function index()
    {
        $general = Option::where('key','general')->first();
        return view('admin.settings.general',compact('general'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'copy_right' => 'required|string|max:100',
            'admin_footer_text' => 'required|string|max:100',
            'admin_footer_link_text' => 'required|string|max:100',
            'admin_footer_link' => 'required|string|max:100',
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'common_header_logo' => 'nullable|image',
            'footer_logo' => 'nullable|image',
            'admin_logo' => 'nullable|image',
            'frontend_logo' => 'nullable|image',
        ]);

        $general = Option::findOrFail($id);
        $path = 'uploads/qr-codes/qrcode.svg';
        if (!file_exists($path)) {
            $qr = QrCode::size(300)->format('svg')->generate($general->value['admin_footer_link'] ?? '');
            Storage::put($path, $qr);
        }
        Cache::forget($general->key);
        $general->update([
            'value' => $request->except('_token','_method','logo','favicon','common_header_logo','footer_logo','admin_logo','login_page_logo', 'login_page_img', 'invoice_logo') + [
                    'favicon' => $request->favicon ? $this->upload($request, 'favicon', $general->favicon) : $general->value['favicon'],
                    'admin_logo' => $request->admin_logo ? $this->upload($request, 'admin_logo', $general->admin_logo) : $general->value['admin_logo'],
                    'login_page_logo' => $request->login_page_logo ? $this->upload($request, 'login_page_logo', $general->login_page_logo) : $general->value['login_page_logo'],
                    'login_page_img' => $request->login_page_img ? $this->upload($request, 'login_page_img', $general->login_page_img) : $general->value['login_page_img'],
                    'invoice_logo' => $request->invoice_logo ? $this->upload($request, 'invoice_logo', $general->invoice_logo) : $general->value['invoice_logo'],
                ]
        ]);

        return response()->json([
            'message'   => __('General Setting updated successfully'),
            'redirect'  => route('admin.settings.index')
        ]);
    }
}
