<?php


namespace App\Models\User;


use App\Helpers\NormalizePhoneNumber;

trait UserAttributes
{

    public function scopeSearch($query, $search, $strict=false)
    {
        if(!$search)
            return $query;

        if(str_starts_with($search, '~')) {
            return $query->where("id", ltrim($search, '~'));
        }

        if(str_starts_with($search, '@')) {
            return $query->where("username", 'LIKE', '%' . ltrim($search, '@') . '%');
        }

        $origSearch = $search;
        if(!$strict) {
            $search = "$search%";
        }

        return $query->where("name", 'like', $search)
            ->orWhere('username', 'like', $search)
            ->orWhere('arabic_name', 'like', $search)
            ->orWhere("phone", "like", NormalizePhoneNumber::create($origSearch, false)
                ->handle() . '%')
            //->orWhere("national_id", "like", $search)
            ->orWhere("email", "like", $search);
    }

    public function scopeStrictSearch($query, $search)
    {
        $query->search($search, true);
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
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function getLocaleNameAttribute()
    {
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
            return url("images/defaultPicture.png");

        return url(\Storage::url("public/photos/$picture"));
    }

}
