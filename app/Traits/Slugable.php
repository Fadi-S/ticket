<?php

namespace App\Traits;

trait Slugable
{

    protected static $separator = "-";

    protected static $slug = "slug";

    protected static $separatorRequired = true;

    public function getRouteKeyName()
    {
        return static::$slug;
    }

    public static function isUnique($username, $ignoreId) : bool
    {
        $reflection = new \ReflectionClass(get_class());
        $model = $reflection->newInstance();

        return !$model->withTrashed()->where([[static::$slug, '=', $username], ["id", "<>", $ignoreId]])->exists();
    }

    public static function replaceInvalidCharacters($slug) : string
    {
        $slug = trim($slug);
        $slug = mb_strtolower($slug, 'UTF-8');

        $slug = preg_replace("/[^a-z0-9_\s\-ءاآؤئبپتثجچحخدذرزژسشىصضطظعغفقكکگلمنوهیأيةإ]/u", '', $slug);

        // $slug = preg_replace("/[\s\-_]+/", ' ', $slug);

        $slug = mb_substr($slug, 0, 180, 'utf-8');

        if(static::$separatorRequired)
            $slug = preg_replace("/[\s]/", static::$separator, $slug);
        else
            $slug = preg_replace("/[\s]/", (!! rand(0, 1)) ? static::$separator : '', $slug);

        return $slug;
    }

    public static function makeSlug($string, $ignoreId=0, $iteration=0) : string
    {
        $reflection = new \ReflectionClass(get_class());
        $reflection->newInstance();

        $slug = self::replaceInvalidCharacters($string);

        $slug .= static::$separator . rand(1000*$iteration+1, 1000+(1000 * $iteration));

        if(static::isUnique($slug, $ignoreId))
            return $slug;

        return static::makeSlug($string, $ignoreId, $iteration + 1);
    }

}
