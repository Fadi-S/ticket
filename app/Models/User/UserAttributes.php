<?php


namespace App\Models\User;


trait UserAttributes
{

    public function scopeSearch($query, $search, $strict=false)
    {
        if(str_starts_with($search, '~')) {
            return $query->where("id", ltrim($search, '~'));
        }

        if(str_starts_with($search, '@')) {
            return $query->where("username", 'LIKE', '%' . ltrim($search, '@') . '%');
        }

        if(!$strict) {
            $search = "%$search%";
        }

        return $query->where("name", 'like', $search)
            ->orWhere('username', 'like', $search)
            ->orWhere('arabic_name', 'like', $search)
            ->orWhere("phone", "like", $search)
            ->orWhere("national_id", "like", $search)
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
        if(!$phone || $phone == '') {
            $this->attributes['phone'] = null;
            return;
        }

        preg_match('/(?P<number>(01)[0-9]{9})/', $phone, $matches);
        if(isset($matches['number'])) {
            $phone = '+2' . $matches['number'];

            $this->attributes['phone'] = $phone;
        }else {
            $this->attributes['phone'] = $phone;
        }
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
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
        if(is_null($picture) || $picture == '' || !\Storage::exists("public/photos/$picture"))
            return url("images/defaultPicture.png");

        return url(\Storage::url("public/photos/$picture"));
    }

}
