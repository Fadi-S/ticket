<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class ChurchScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('church_id', '=', auth()->user()->church_id);
    }

}
