<?php

namespace Tests\Unit;

use App\Models\Nurses;
use App\Models\User;
use App\Services\NursingHierarchy;
use PHPUnit\Framework\TestCase;

class NursingHierarchyTest extends TestCase
{
    public function test_hns_can_query_every_nursing_user_in_the_same_hospital(): void
    {
        $hns = new User(['hospital_id' => 1, 'nursing_hierarchy_role' => 'hns']);
        $nurse = new Nurses(['hospital_id' => 1, 'nursing_hierarchy_role' => 'ward_nurse']);
        $this->assertTrue(NursingHierarchy::canQuery($hns, $nurse, null));
    }

    public function test_control_can_query_operational_roles_and_report_to_hns(): void
    {
        $control = new User(['hospital_id' => 1, 'nursing_hierarchy_role' => 'control_162']);
        $supervisor = new Nurses(['hospital_id' => 1, 'nursing_hierarchy_role' => 'ward_supervision']);
        $hns = new User(['hospital_id' => 1, 'nursing_hierarchy_role' => 'hns']);
        $this->assertTrue(NursingHierarchy::canQuery($control, $supervisor, 10));
        $this->assertTrue(NursingHierarchy::canQuery($control, $hns, 10));
    }

    public function test_unit_head_can_query_ward_staff_and_report_to_hns(): void
    {
        $unitHead = new User(['hospital_id' => 1, 'nursing_hierarchy_role' => 'unit_head']);
        $hns = new User(['hospital_id' => 1, 'nursing_hierarchy_role' => 'hns']);

        $this->assertTrue(NursingHierarchy::canQuery($unitHead, $hns, null));
    }

    public function test_legacy_nursing_roles_and_super_admin_are_recognized(): void
    {
        $legacy162 = new User(['hospital_id' => 1, 'nursing_admin_role' => 'nursing_162']);
        $superAdmin = new User(['hospital_id' => 1]);
        $superAdmin->is_super_admin = true;

        $this->assertSame('control_162', NursingHierarchy::role($legacy162));
        $this->assertSame('hns', NursingHierarchy::role($superAdmin));
    }

    public function test_existing_nurses_without_a_hierarchy_role_remain_ward_nurses(): void
    {
        $nurse = new Nurses(['hospital_id' => 1]);

        $this->assertSame('ward_nurse', NursingHierarchy::role($nurse));
    }

    public function test_ward_supervisor_can_only_query_nurses_in_their_ward(): void
    {
        $supervisor = new Nurses(['hospital_id' => 1, 'ward_id' => 10, 'nursing_hierarchy_role' => 'ward_supervision']);
        $sameWardNurse = new Nurses(['hospital_id' => 1, 'ward_id' => 10, 'nursing_hierarchy_role' => 'ward_nurse']);
        $otherWardNurse = new Nurses(['hospital_id' => 1, 'ward_id' => 11, 'nursing_hierarchy_role' => 'ward_nurse']);
        $this->assertTrue(NursingHierarchy::canQuery($supervisor, $sameWardNurse, 10));
        $this->assertFalse(NursingHierarchy::canQuery($supervisor, $otherWardNurse, 11));
    }
}
