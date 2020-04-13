<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\Scholar;
use App\Types\LeaveStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function view($user, Leave $leave)
    {
        return $user instanceof Scholar && $leave->scholar_id == $user->id;
    }

    /**
     * Determine whether the user can create leaves.
     *
     * @param mixed $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return $user instanceof Scholar;
    }

    /**
     * Determine whether the user can recommend a leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function recommend($user, Leave $leave)
    {
        return $leave->status === LeaveStatus::APPLIED
            && method_exists($user, 'isSupervisor')
            && $user->isSupervisor()
            && $user->supervisorProfile->scholars->contains($leave->scholar_id);
    }

    /**
     * Determine whether the user can respond to a leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function respond($user, Leave $leave)
    {
        return $user->can('leaves:respond')
            && in_array($leave->status, [LeaveStatus::APPLIED, LeaveStatus::RECOMMENDED]);
    }

    /**
     * Determine whether the user can extend a leave.
     *
     * @param \App\Models\Scholar $scholar
     * @param  \App\Models\Leave  $leave
     *
      * @return mixed
     */
    public function extend(Scholar $scholar, Leave $leave)
    {
        return (int) $scholar->id === (int) $leave->scholar_id
            && $leave->isApproved()
            && $leave->extensions->every->isApproved();
    }
}
