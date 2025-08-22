<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Name;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('names:import', function () {
	$path = public_path('assets/list.xlsx');
	if (!file_exists($path)) {
		$this->error('Excel file not found at: ' . $path);
		return 1;
	}

	try {
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
		$sheet = $spreadsheet->getActiveSheet();
		$rows = $sheet->toArray();

		$imported = 0;
		foreach ($rows as $index => $row) {
			// Expect columns: [name, path]
			if ($index === 0) {
				// Try to detect header row; if contains 'name' and 'path', skip
				$maybeHeader = array_map(fn($v) => is_string($v) ? strtolower(trim($v)) : $v, $row);
				if (in_array('name', $maybeHeader) && in_array('path', $maybeHeader)) {
					continue;
				}
			}

			$name = isset($row[0]) ? trim((string) $row[0]) : '';
			$path = isset($row[1]) ? trim((string) $row[1]) : '';

			if ($name === '' || $path === '') {
				continue;
			}

			Name::updateOrCreate(
				['name' => $name],
				['path' => $path]
			);
			$imported++;
		}

		$this->info("Imported {$imported} rows from Excel.");
		return 0;
	} catch (\Throwable $e) {
		$this->error('Import failed: ' . $e->getMessage());
		return 1;
	}
})->purpose('Import names and music paths from public/assets/list.xlsx');
