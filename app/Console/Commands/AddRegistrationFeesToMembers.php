<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Contribution;
use Carbon\Carbon;

class AddRegistrationFeesToMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:add-registration-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add registration fee contribution of 1000 for all members who do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to add registration fees for members...');
        
        $members = Member::all();
        $added = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($members as $member) {
            // Check if member already has a registration fee contribution
            $existingRegistrationFee = Contribution::where('member_id', $member->id)
                ->where('type', 'registration_fee')
                ->sum('amount');
            
            if ($existingRegistrationFee >= 1000) {
                $this->warn("Skipping member {$member->member_no} ({$member->name}) - already has registration fee of {$existingRegistrationFee}");
                $skipped++;
                continue;
            }
            
            try {
                // Calculate how much more is needed to reach 1000
                $amountNeeded = 1000 - $existingRegistrationFee;
                
                if ($amountNeeded > 0) {
                    // Create registration fee contribution
                    Contribution::create([
                        'member_id' => $member->id,
                        'amount' => $amountNeeded,
                        'type' => 'registration_fee',
                        'contribution_date' => Carbon::parse('2024-07-01'), // Club started in July 2024
                        'transaction_ref' => 'REG-FEE-' . $member->member_no . '-' . now()->format('YmdHis'),
                    ]);
                    
                    $this->info("✓ Added registration fee of {$amountNeeded} for member {$member->member_no} ({$member->name})");
                    $added++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to add registration fee for member {$member->member_no}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("  Added: {$added} registration fee contributions");
        $this->info("  Skipped: {$skipped} (already have registration fees)");
        if ($errors > 0) {
            $this->warn("  Errors: {$errors}");
        }
        
        return Command::SUCCESS;
    }
}
