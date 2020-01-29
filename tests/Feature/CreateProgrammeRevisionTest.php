<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use App\Programme;
use App\ProgrammeRevision;
use App\Course;

class CreateProgrammeRevision extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function programme_revision_can_be_created()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $semester_courses = create(Course::class, 2);

        foreach ($semester_courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        $this->withoutExceptionHandling()
            ->get(route('staff.programmes.revisions.create', $programme))
            ->assertSuccessful()
            ->assertViewIs('staff.programmes.revisions.create')
            ->assertViewHas('semester_courses')
            ->assertViewHas('programme');
    }

    /** @test */
    public function guest_cannot_create_any_programme_revision()
    {
        $this->expectException(AuthenticationException::class);

        $programme = create(Programme::class);

        $this->withoutExceptionHandling()
            ->get(route('staff.programmes.revisions.create', $programme));
    }
}
