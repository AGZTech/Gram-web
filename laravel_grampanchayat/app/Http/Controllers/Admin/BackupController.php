<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $backups = collect(File::files($backupPath))
            ->map(function ($file) {
                return [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('d-m-Y H:i:s', $file->getMTime()),
                ];
            })
            ->sortByDesc('date')
            ->values();
            
        return view('admin.backup.index', compact('backups'));
    }
    
    public function create()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupPath . '/' . $filename;
            
            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . config('database.connections.mysql.database');
            
            $sql = "-- Database Backup\n";
            $sql .= "-- Generated: " . date('d-m-Y H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Get create table statement
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get table data
                $rows = DB::table($tableName)->get();
                
                if ($rows->count() > 0) {
                    $columns = array_keys((array) $rows[0]);
                    $columnList = implode('`, `', $columns);
                    
                    foreach ($rows as $row) {
                        $values = array_map(function ($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return "'" . addslashes($value) . "'";
                        }, (array) $row);
                        
                        $sql .= "INSERT INTO `{$tableName}` (`{$columnList}`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            File::put($filepath, $sql);
            
            return redirect()->back()
                ->with('success', 'बॅकअप यशस्वीरित्या तयार झाला: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'बॅकअप तयार करताना त्रुटी: ' . $e->getMessage());
        }
    }
    
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($filepath)) {
            return redirect()->back()
                ->with('error', 'फाईल सापडली नाही');
        }
        
        return response()->download($filepath);
    }
    
    private function formatBytes($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
