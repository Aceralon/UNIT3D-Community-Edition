<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRevokePermissions
 */
class AutoRevokePermissionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $this->artisan('auto:revoke_permissions')
            ->assertExitCode(0)
            ->run();

        // TODO: perform additional assertions to ensure the command behaved as expected
    }
}
