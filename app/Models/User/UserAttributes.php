<?php


namespace App\Models\User;


trait UserAttributes
{

    public function scopeSearch($query, $search)
    {
        return $query->where("name", "like", "%$search%")
            ->orWhere('username', 'like', "%$search%")
            ->orWhere("phone", "like", "%$search%")
            ->orWhere("email", "like", "%$search%");
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
        if(!$phone)
            return;

        preg_match('/(?P<number>(01)[0-9]{9})/', $phone, $matches);
        $phone = '+2' . $matches['number'];

        $this->attributes['phone'] = $phone;
    }

    public function getPhoneAttribute($phone)
    {
        if(!$phone)
            return null;

        preg_match('/(?P<number>(01)[0-9]{9})/', $phone, $matches);

        return '+2' . $matches['number'];
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    public function getPictureAttribute($picture)
    {
        if(is_null($picture) || $picture == '' || !\Storage::exists("public/photos/$picture"))
            return url("images/defaultPicture.png");

        return url(\Storage::url("public/photos/$picture"));
    }

}
