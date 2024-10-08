<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadAndImportPostcodesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_command_runs(): void
    {
        Storage::fake('public');

        Http::fake([
            'www.doogal.co.uk/*' => Http::response(Storage::disk('support')->get('postcodes.zip'), 200),
        ]);

        $this->artisan('postcodes')
            ->expectsOutputToContain('Downloaded postcodes')
            ->expectsOutputToContain('Unzipped postcodes')
            ->expectsOutputToContain('Emptied postcodes')
            ->expectsOutputToContain('Imported postcodes')
            ->assertExitCode(0);
    }

    public function test_postcodes_are_imported(): void
    {
        Storage::fake('public');

        Http::fake([
            'www.doogal.co.uk/*' => Http::response(Storage::disk('support')->get('postcodes.zip'), 200),
        ]);

        $this->artisan('postcodes');

        $this->assertEquals(Postcode::count(), 2);
    }

    public function test_terminated_postcodes_can_be_included(): void
    {
        Storage::fake('public');

        Http::fake([
            'www.doogal.co.uk/*' => Http::response(Storage::disk('support')->get('postcodes.zip'), 200),
        ]);

        $this->artisan('postcodes --include-terminated');

        $this->assertEquals(Postcode::count(), 9);
    }

    public function test_http_error_will_halt_execution(): void
    {
        Http::fake([
            'www.doogal.co.uk/*' => Http::response('Error', 404),
        ]);

        $this->artisan('postcodes')
            ->expectsOutputToContain('Unable to download postcodes file')
            ->assertFailed();
    }

    public function test_zip_error_will_halt_execution(): void
    {
        Storage::fake('public');

        Http::fake([
            'www.doogal.co.uk/*' => Http::response('Error', 200),
        ]);

        $this->artisan('postcodes')
            ->expectsOutputToContain('Invalid postcodes zip file received')
            ->assertFailed();
    }
}
