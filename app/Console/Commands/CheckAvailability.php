<?php

namespace App\Console\Commands;

use App\Services\AvailabilityService;
use Illuminate\Console\Command;

class CheckAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:next-slot {addressId} {appointmentTypeId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get next available appointment slot for a doctor';

    /**
     * Execute the console command.
     */
    public function handle(AvailabilityService $availabilityService)
    {
        $addressId = $this->argument('addressId');
        $appointmentTypeId = $this->argument('appointmentTypeId');

        $nextSlot = $availabilityService->getNextDisponibilite($addressId, $appointmentTypeId);

        if ($nextSlot) {
            $this->info("Next available slot: " . $nextSlot);
        } else {
            $this->warn("No slot found in the next 30 days.");
        }
    }
}
