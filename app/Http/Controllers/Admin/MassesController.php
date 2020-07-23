<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassesRequest;
use App\Models\Mass\Mass;
use App\Repositories\MassRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MassesController extends Controller
{

    use MassRepository;

    public function create()
    {
        $start = Carbon::now();
        return view("mass.create", compact('start'));
    }

    public function store(MassesRequest $request)
    {
        if($this->createMass($request))
            flash()->success("Created mass successfully");
        else
            flash()->error("Error creating mass");

        return redirect("/masses/create");
    }

    public function edit(Mass $mass)
    {
        $start = Carbon::now();
        return view("mass.edit", compact('mass', 'start'));
    }

    public function update(Mass $mass, MassesRequest $request)
    {
        if($this->editMass($mass, $request))
            flash()->success("Edited mass successfully");
        else
            flash()->error("Error editing mass");

        return redirect("/masses/$mass->id/edit");
    }

    public function index()
    {
        $masses = $this->getAllMasses();

        return view("mass.index", compact('masses'));
    }

    public function show(Mass $mass)
    {
        return view("mass.show", compact("mass"));
    }



}
