<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;

class ChatbotSettingController extends Controller
{
    public function index()
    {
        $setting = ChatbotSetting::first();

        return view('admin.chatbot.settings', [
            'setting' => $setting ?? new ChatbotSetting(),
            'authTypes' => [
                'none' => 'Tanpa Auth',
                'basic' => 'Basic Auth',
                'bearer' => 'Bearer Token',
                'header' => 'Custom Header',
                'jwt' => 'JWT',
            ],
            'methods' => ['POST', 'GET'],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'webhook_url' => ['nullable', 'url'],
            'method' => ['required', 'in:GET,POST'],
            'timeout' => ['required', 'integer', 'min:5', 'max:60'],
            'allow_insecure_ssl' => ['nullable', 'boolean'],
            'auth_type' => ['required', 'in:none,basic,bearer,header,jwt'],
            'basic_user' => ['nullable', 'string', 'max:255'],
            'basic_pass' => ['nullable', 'string', 'max:255'],
            'bearer_token' => ['nullable', 'string'],
            'header_key' => ['nullable', 'string', 'max:255'],
            'header_value' => ['nullable', 'string'],
            'jwt_token' => ['nullable', 'string'],
        ], [
            'webhook_url.url' => 'Format URL webhook tidak valid.',
        ]);

        $data['allow_insecure_ssl'] = $request->boolean('allow_insecure_ssl');

        switch ($data['auth_type']) {
            case 'basic':
                $data['bearer_token'] = null;
                $data['header_key'] = null;
                $data['header_value'] = null;
                $data['jwt_token'] = null;
                break;
            case 'bearer':
                $data['basic_user'] = null;
                $data['basic_pass'] = null;
                $data['header_key'] = null;
                $data['header_value'] = null;
                $data['jwt_token'] = null;
                break;
            case 'header':
                $data['basic_user'] = null;
                $data['basic_pass'] = null;
                $data['bearer_token'] = null;
                $data['jwt_token'] = null;
                break;
            case 'jwt':
                $data['basic_user'] = null;
                $data['basic_pass'] = null;
                $data['bearer_token'] = null;
                $data['header_key'] = null;
                $data['header_value'] = null;
                break;
            default:
                $data['basic_user'] = null;
                $data['basic_pass'] = null;
                $data['bearer_token'] = null;
                $data['header_key'] = null;
                $data['header_value'] = null;
                $data['jwt_token'] = null;
                break;
        }

        $setting = ChatbotSetting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            ChatbotSetting::create($data);
        }

        return redirect()->route('admin.chatbot.settings')
            ->with('success', 'Konfigurasi Chatbot API berhasil diperbarui.');
    }
}
