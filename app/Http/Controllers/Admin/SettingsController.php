<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Repositories\SettingsRepository;
use App\Settings\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    use SettingsRepository;

    public function index()
    {
        $settings = $this->getAll();
        return view("settings.index", compact('settings'));
    }

    public function create()
    {
        return view("settings.create");
    }

    public function store(SettingsRequest $request)
    {
        if($this->createSettings($request))
            flash()->success("Setting created successfully");
        else
            flash()->error("Couldn't create setting");

        return redirect("settings/create");
    }

    public function edit(Settings $setting)
    {
        return view("settings.edit", compact('setting'));
    }

    public function update(SettingsRequest $request, Settings $setting)
    {
        if($this->editSettings($request, $setting))
            flash()->success("Setting edited successfully");
        else
            flash()->error("Couldn't edit setting");

        return redirect("settings/$setting->id/edit");
    }

    public function destroy(Settings $setting)
    {
        $this->deleteSettings($setting);

        flash()->success("Setting deleted successfully");

        return redirect("settings");
    }
}
