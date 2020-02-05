<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Programme;
use App\Course;
use App\ProgrammeRevision;
use App\CourseProgrammeRevision;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ProgrammeRevisionController extends Controller
{
    public function index(Programme $programme)
    {
        $programme_revisions = $programme->revisions->sortByDesc('revised_at');

        $grouped_revision_courses = $programme_revisions
            ->map(function ($revisions) {
                return $revisions->courses
                    ->sortBy('pivot.semester')
                    ->groupBy('pivot.semester');
            });

        return view('staff.programmes.revisions.index', [
            'programme' => $programme,
            'programmeRevisions' => $programme_revisions,
            'groupedRevisionCourses' => $grouped_revision_courses,
        ]);
    }

    public function create(Programme $programme)
    {
        $courses = Course::where('code', 'like', "{$programme->code}%")->get();
        $revision = $programme->revisions()
            ->with('courses')
            ->where('revised_at', $programme->wef)
            ->first();

        $semester_courses = $revision
            ->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');


        return view('staff.programmes.revisions.create', [
            'programme' => $programme,
            'revision' => $revision,
            'semester_courses' =>  $semester_courses,
            'courses' => $courses
        ]);
    }

    public function store(Programme $programme, Request $request)
    {
        $data = $request->validate([
            'revised_at' => ['required', 'date',
                function ($attribute, $value, $fail) use ($programme) {
                    $revisions = $programme->revisions->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.' is invalid');
                        }
                    }
                },
            ],
        ]);

        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $data['revised_at'], 'programme_id' => $programme->id]);

        foreach ($data['semester_courses'] as $semester => $courses) {
            foreach ($courses as $course) {
                Course::find($course)->programme_revisions()->attach($revision, ['semester' => $semester + 1]);
            }
        }

        if ($programme->wef < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision created successfully!", 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme, ProgrammeRevision $programme_revision)
    {
        if ($programme_revision->programme_id != $programme->id) {
            return redirect(route('staff.programmes.index'));
        }

        $semester_courses = $programme_revision->courses
                                ->groupBy('pivot.semester')
                                ->map
                                ->pluck('id');
        return view('staff.programmes.revisions.edit', [
            'programme' => $programme,
            'programme_revision' => $programme_revision,
            'semester_courses' => $semester_courses,
            'courses' => Course::where('code', 'like', "{$programme->code}%")->get(),
        ]);
    }

    public function update(Programme $programme, ProgrammeRevision $programme_revision, Request $request)
    {
        $data = $request->validate([
            'revised_at' => ['sometimes', 'required', 'date',
                function ($attribute, $value, $fail) use ($programme, $programme_revision) {
                    $revisions = $programme->revisions
                        ->filter(function ($revision) use ($programme_revision) {
                            return $revision->id != $programme_revision->id;
                        })
                        ->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['sometimes', 'required', 'array', 'min:1'],
            'semester_courses.*.*' => ['sometimes', 'numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.'is invalid');
                        }
                    }
                },
            ],
        ]);

        $programme_revision->update($data);

        $semester_courses = collect($request->semester_courses)
            ->map(function ($courses, $index) {
                return array_map(function ($course) use ($index) {
                    return [$course, $index + 1];
                }, $courses);
            })->flatten(1)->pluck('1', '0')
            ->map(function ($value) {
                return ['semester' => $value];
            })->toArray();

        $programme_revision->courses()->sync($semester_courses);

        if ($programme->wef->format('Y-m-d') < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision edited successfully!", 'success');

        return redirect(route('staff.programmes.revisions.show', $programme));
    }

    public function destroy(Programme $programme, ProgrammeRevision $programmeRevision)
    {
        $programmeRevision->delete();

        if ($programme->revisions->count() == 0) {
            $programme->delete();
            return redirect(route('staff.programmes.index'));
        } else {
            $lastRevision = $programme->revisions->max('revised_at');
            $programme->update(['wef' => $lastRevision]);
            return redirect(route('staff.programmes.revisions.show', $programme));
        }
    }
}
