<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsersForMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:create-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user accounts for all members who do not have user accounts yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to create user accounts for members...');
        
        $members = Member::whereNotNull('phone')->get();
        $created = 0;
        $updated = 0;
        $errors = 0;
        
        foreach ($members as $member) {
            try {
                // Ensure exactly ONE user per member_id.
                // If a user exists for this member, update it; otherwise create it.
                $user = User::updateOrCreate(
                    ['member_id' => $member->id],
                    [
                        'name' => $member->name,
                        'initials' => $member->initials ?? '',
                        'email' => $member->member_no . '@member.local',
                        'phone' => $member->phone,
                        // Always (re)set default password for bulk create: eldoret2010
                        'password' => Hash::make('eldoret2010'),
                        'role' => 'member',
                    ]
                );

                if ($user->wasRecentlyCreated) {
                    $this->info("✓ Created user account for member {$member->member_no} ({$member->name})");
                    $created++;
                } else {
                    $this->info("↺ Updated user account for member {$member->member_no} ({$member->name})");
                    $updated++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to create/update user for member {$member->member_no}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("  Created: {$created} user accounts");
        $this->info("  Updated: {$updated} user accounts");
        if ($errors > 0) {
            $this->warn("  Errors: {$errors}");
        }
        
        return Command::SUCCESS;
    }
}
