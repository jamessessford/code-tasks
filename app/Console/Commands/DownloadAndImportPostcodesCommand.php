<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Postcode;
use Error;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DownloadAndImportPostcodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postcodes {--include-terminated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import postcode data.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $postcodesUrl = 'https://www.doogal.co.uk/files/postcodes.zip';

        $this->info("Downloading postcodes from {$postcodesUrl}.");

        try {

            $response = Http::retry(3, 250)
                ->timeout(600)
                ->get($postcodesUrl);

        } catch (Exception $e) {
            $this->fail('Unable to download postcodes file.');
        }

        Storage::disk('public')->put('postcodes.zip', $response->body());

        $this->info("Downloaded postcodes from {$postcodesUrl}.");

        $this->info('Unzipping postcodes.');

        try {
            $archive = new ZipArchive();
            $archive->open(Storage::disk('public')->path('postcodes.zip'));
            $archive->extractTo(Storage::disk('public')->path('postcodes'));
        } catch (Exception|Error $e) {
            $this->fail('Invalid postcodes zip file received.');
        }

        $this->info('Unzipped postcodes.');

        $this->info('Emptying postcodes table');

        Postcode::truncate();

        $this->info('Emptied postcodes table');

        $this->info('Importing postcodes.');

        $row = 0;
        if (($handle = fopen(Storage::disk('public')->path('postcodes/postcodes.csv'), 'r')) !== false) {
            while (($data = fgetcsv($handle, 5000, ',')) !== false) {
                //  Skip header row
                if ($row > 0) {
                    //  By default, we'll only include postcodes in use
                    if ( ! $this->option('include-terminated')) {
                        if ('No' === $data[1]) {
                            continue;
                        }
                    }

                    Postcode::create([
                        'postcode' => $data[0],
                        'latitude' => $data[2],
                        'longitude' => $data[3],
                    ]);
                }

                $row++;
            }
            fclose($handle);
        }

        $this->info('Imported postcodes.');

        Storage::disk('public')->deleteDirectory('postcodes');
        Storage::disk('public')->delete('postcodes.zip');
    }
}
