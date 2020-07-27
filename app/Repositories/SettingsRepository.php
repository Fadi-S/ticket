<?php


namespace App\Repositories;


use App\Http\Requests\SettingsRequest;
use App\Settings\Settings;

trait SettingsRepository
{

    public function getAll($paginate=100)
    {
        return Settings::paginate($paginate);
    }

    public function createSettings(SettingsRequest $request)
    {
        return Settings::create($request->all());
    }

    public function editSettings(SettingsRequest $request, Settings $setting)
    {
        return $setting->update($request->all());
    }

    public function deleteSettings(Settings $setting)
    {
        return $setting->delete();
    }
}
