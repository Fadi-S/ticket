<?php


namespace App\Models\User;


use App\Helpers\ArabicNumbersToEnglish;
use App\Helpers\DataFromNationalID;
use App\Helpers\NormalizePhoneNumber;
use App\Models\Church;
use App\Models\Location;
use App\Models\Login;

trait UserAttributes
{

    public function scopeSearchDatabase($query, $search, $strict=false)
    {
        if(!$search)
            return $query;

        if(str_starts_with($search, '#')) {
            return $query->where("id", '=', ltrim($search, '#'));
        }

        if(str_starts_with($search, '@')) {
            return $query->where("username", 'LIKE', ltrim($search, '@') . '%');
        }

        $origSearch = $search;
        if(!$strict) {
            $search = "$search%";
        }

        return $query->where("name", 'like', $search)
            ->orWhere('arabic_name', 'like', $search)
            ->orWhere("phone", "like", NormalizePhoneNumber::create($origSearch, false)->handle() . ((!$strict) ? '%' : ''))
            ->orWhere("national_id", "like", $search)
            ->orWhere("email", "like", $search);
    }

    public function shouldSearchScout()
    {
        return $this->can('tickets.view');
    }

    public function scopeVerified($query)
    {
        $query->where('verified_at', '<>', null);
    }

    public function isVerified()
    {
        return $this->verified_at != null;
    }

    public function scopeStrictSearch($query, $search)
    {
        $query->searchDatabase($search, true);
    }

    public function scopeHasFriends($query)
    {
        $query->whereIn('id', auth()->user()->friends()->get()->pluck('id'))
            ->orWhere('id', auth()->id());
    }

    public function scopeAddUsernameToName($query)
    {
        return $query->select("*", \DB::raw("CONCAT(name,' (@',username,')') as text"));
    }

    public function toAPI()
    {
        return [
            'name' => $this->name,
            'arabic_name' => $this->arabic_name,
            'national_id' => $this->national_id,
            'username' => $this->username,
            'gender' => $this->gender,
            'email' => $this->email,
            'isAdmin' => $this->can('tickets.view'),
            'phone' => $this->phone,
            'locale' => $this->locale,
            'location_id' => $this->location->name,
        ];
    }

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->locale_name)[0];
    }

    public function getGenderAttribute($gender)
    {
        if(!$this->national_id)
            return $gender;

        $nid = $this->NID();
        return !! $nid ? $nid->gender() : $gender;
    }

    public function getNationalIdAttribute($national_id)
    {
        return ArabicNumbersToEnglish::create($national_id)->handle();
    }

    public function getNameAttribute($name)
    {
        if(config('settings.arabic_name_only'))
            return $this->arabic_name;

        return $name;
    }

    public function hasFirstNameOnly() : bool
    {
        $isFirstName = fn($name) => count(explode(' ', $name)) < 3;

        return $isFirstName($this->arabic_name) || (!config('settings.arabic_name_only') && $isFirstName($this->name));
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = NormalizePhoneNumber::create($phone)->handle();
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower(preg_replace("/\s+/", ' ', trim($name))));
    }

    public function setArabicNameAttribute($name)
    {
        $this->attributes['arabic_name'] = preg_replace("/\s+/", ' ', trim($name));
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = empty($email) ? null : strtolower($email);
    }

    public function setNationalIdAttribute($national_id)
    {
        $this->attributes['national_id'] = empty($national_id) ? null : $national_id;
    }

    public function getLocaleNameAttribute()
    {
        if(config('settings.arabic_name_only'))
            return $this->arabic_name;

        if(! $this->arabic_name)
            return $this->name;

        return app()->getLocale() === 'ar' ? $this->arabic_name : $this->name;
    }

    public function getSmartNameAttribute()
    {
        return $this->arabic_name ?? $this->name;
    }

    public function getPictureAttribute($picture)
    {
        if(!$picture || !\Storage::exists("public/photos/$picture"))
            return url("images/defaultPicture-min.png");

        return url(\Storage::url("public/photos/$picture"));
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    public function creator()
    {
        return $this->belongsTo(self::class, 'creator_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function users()
    {
        return $this->hasMany(self::class, 'creator_id', 'id');
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}
