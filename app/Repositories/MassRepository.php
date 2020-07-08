<?php


namespace App\Repositories;

use App\Http\Requests\MassesRequest;
use App\Models\Mass\Mass;

trait MassRepository
{

    public function getAllMasses($paginate = 100)
    {
        return Mass::paginate($paginate);
    }

    public function createMass(MassesRequest $request)
    {
        $mass = Mass::create($request->all());

        return $mass != null;
    }

    public function editMass(Mass $mass, MassesRequest $request)
    {
        return $mass->update($request->all());
    }
}
