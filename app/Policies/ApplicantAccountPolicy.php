<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Applicant_account;
class ApplicantAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function approved_applicants(Applicant_account $Applicant_account){
        return $Applicant_account->is_approved != 0 && $Applicant_account->is_approved != 2;
    }
}
