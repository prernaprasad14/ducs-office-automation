<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\College;
use App\Programme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CollegeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(College::class, 'college');
    }

    public function index()
    {
        return view('staff.colleges.index', [
            'colleges' => College::all(),
            'programmes' => Programme::all()
        ]);
    }

    public function create()
    {
        return view('staff.colleges.create', [
            'programmes' => Programme::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','min:3','max:20','unique:colleges,code'],
            'name' => ['required','min:3','max:100','unique:colleges,name'],
            'principal_name' => ['required', 'min:3', 'max:190'],
            'principal_phones' => ['required', 'array', 'min:1', 'max:3'],
            'principal_phones.*' => ['nullable', 'numeric', 'digits:10'],
            'principal_emails' => ['required', 'array', 'min:1', 'max:3'],
            'principal_emails.*' => ['nullable','string', 'email'],
            'address' => ['required', 'min:10', 'max:250'],
            'website' => ['required', 'url'],
            'programmes' => ['required', 'array', 'min:1'],
            'programmes.*' => ['required', 'integer', 'exists:programmes,id']
        ]);

        $college = College::create($data);

        $college->programmes()->attach($data['programmes']);

        flash('College created successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }

    public function edit(College $college)
    {
        return view('staff.colleges.edit', [
            'college' => $college,
            'programmes' => Programme::all(),
        ]);
    }

    public function update(Request $request, College $college)
    {
        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('colleges')->ignore($college)
            ],
            'name'=>[
                'sometimes', 'required', 'min:3', 'max:100',
                Rule::unique('colleges')->ignore($college)
            ],
            'principal_name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'principal_phones' => ['sometimes', 'required', 'array', 'min:1', 'max:3'],
            'principal_phones.*' => ['nullable', 'numeric', 'digits:10'],
            'principal_emails' => ['sometimes', 'required', 'array', 'min:1', 'max:3'],
            'principal_emails.*' => ['nullable','string', 'email'],
            'address' => ['sometimes', 'required', 'min:10', 'max:250'],
            'website' => ['sometimes', 'required', 'url'],
            'programmes' => ['sometimes', 'required', 'array', 'min:1'],
            'programmes.*' => ['sometimes', 'required', 'integer', 'exists:programmes,id'],
        ]);

        $college->update($data);

        if ($request->has('programmes')) {
            $college->programmes()->sync($request->programmes);
        }

        flash('College updated successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }

    public function destroy(College $college)
    {
        $college->delete();

        flash('College deleted successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }
}
