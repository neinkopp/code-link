<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProcessImageGeneration implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		protected string $code,
		protected string $userId
	) {}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		try {
			$curl = curl_init();

			if ($curl === false) {
				throw new RuntimeException('Failed to initialize cURL');
			}

			curl_setopt_array($curl, [
				CURLOPT_URL => 'https://carbonara.solopov.dev/api/cook',
				CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => json_encode([
					'code' => $this->code,
					'windowTheme' => 'sharp',
					'paddingHorizontal' => '0px',
					'paddingVertical' => '0px',
					'windowControls' => false,
				])
			]);

			$rawImage = curl_exec($curl);

			if ($rawImage === false) {
				throw new RuntimeException('cURL request failed: ' . curl_error($curl));
			}

			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);

			if ($httpCode !== 200) {
				throw new RuntimeException("API request failed with status code: {$httpCode}");
			}

			$filename = $this->userId . '.png';
			if (!Storage::disk('supabase')->put($filename, $rawImage)) {
				throw new RuntimeException('Failed to store image in Supabase');
			}
		} catch (\Exception $e) {
			Log::error('Image generation failed', [
				'error' => $e->getMessage(),
				'userId' => $this->userId
			]);
			throw $e;
		}
	}
}
