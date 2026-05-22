<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('local')->files('backups'))->reverse();
        return view('pages.finance.backup.index', compact('files'));
    }

    public function store(Request $request)
    {
        $backupPath = 'backups/finance-backup-' . now()->format('Ymd-His') . '.sql';
        Storage::disk('local')->makeDirectory('backups');

        $tables = ['payments', 'expenses', 'fines', 'budgets', 'discounts', 'payroll_records', 'audit_logs'];
        $dump = '';

        foreach ($tables as $table) {
            $dump .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $createRow = DB::selectOne("SHOW CREATE TABLE `{$table}`");
            if ($createRow) {
                $createSql = collect((array) $createRow)->last();
                $dump .= $createSql . ";\n\n";
            }

            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $rowData = (array) $row;
                $columns = implode('`, `', array_keys($rowData));
                $values = array_map(function ($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }
                    return "'" . str_replace("'", "''", $value) . "'";
                }, array_values($rowData));

                $dump .= "INSERT INTO `{$table}` (`{$columns}`) VALUES (" . implode(', ', $values) . ");\n";
            }

            $dump .= "\n";
        }

        Storage::disk('local')->put($backupPath, $dump);

        return redirect()->route('finance.backup-security.index')->with('success', 'Finance backup created successfully.');
    }

    public function download($filename)
    {
        return Storage::disk('local')->download('backups/' . $filename);
    }
}
