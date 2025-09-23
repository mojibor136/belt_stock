<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function account()
    {
        return view('setting.account');
    }

    public function accountStore(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $file = $request->file('image');
            $randomName = Str::random(4).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('image'), $randomName);
            $user->image = 'image/'.$randomName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Account updated successfully!');
    }

    public function general()
    {
        $setting = Setting::first();

        return view('setting.general', compact('setting'));
    }

    public function generalStore(Request $request)
    {
        $request->validate([
            'fav_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,ico,webp',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,ico,webp',
            'shop_name' => 'required|string',
            'shop_address' => 'required|string',
            'shop_phone' => 'required|string',
            'description' => 'required',
        ]);

        $shopNameArray = array_map('trim', explode(',', $request->shop_name));
        $shopAddressArray = array_map('trim', explode(',', $request->shop_address));
        $shopPhoneArray = array_map('trim', explode(',', $request->shop_phone));

        $settings = Setting::first();

        $favIconPath = null;
        $siteLogoPath = null;

        if ($request->hasFile('fav_icon')) {
            if ($settings && ! empty($settings->fav_icon) && file_exists(public_path($settings->fav_icon))) {
                unlink(public_path($settings->fav_icon));
            }

            $file = $request->file('fav_icon');
            $randomName = Str::random(4).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('image'), $randomName);
            $favIconPath = 'image/'.$randomName;
        }

        if ($request->hasFile('site_logo')) {
            if ($settings && ! empty($settings->site_logo) && file_exists(public_path($settings->site_logo))) {
                unlink(public_path($settings->site_logo));
            }

            $file = $request->file('site_logo');
            $randomName = Str::random(4).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('image'), $randomName);
            $siteLogoPath = 'image/'.$randomName;
        }

        $settingsData = [
            'shop_name' => $shopNameArray,
            'shop_address' => $shopAddressArray,
            'shop_phone' => $shopPhoneArray,
            'description' => $request->description,
        ];

        if ($favIconPath) {
            $settingsData['fav_icon'] = $favIconPath;
        }
        if ($siteLogoPath) {
            $settingsData['site_logo'] = $siteLogoPath;
        }

        Setting::updateOrCreate(
            ['id' => 1],
            $settingsData
        );

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function system()
    {
        $setting = Setting::first();

        return view('setting.system', compact('setting'));
    }

    public function systemStore(Request $request)
    {
        $settings = Setting::firstOrCreate(['id' => 1]);

        $settings->invoice = $request->has('invoice') ? 1 : 0;
        $settings->vendor_stock = $request->has('vendor_stock') ? 1 : 0;
        $settings->memo_status = $request->has('memo_status') ? 1 : 0;

        $settings->save();

        return redirect()->back()->with('success', 'System settings updated successfully!');
    }
}
