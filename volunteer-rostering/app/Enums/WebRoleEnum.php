<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WebRoleEnum extends Enum
{
    const SuperAdmin = "Super Admin";
    const Administrator =   "Administrator";
    const RosterClerk =   "Roster Clerk";
    const TrainingOfficer = "Training Officer";
    const Volunteer = "Volunteer";
}
