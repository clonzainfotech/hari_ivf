<?php

namespace App\Console\Commands;

use App\Models\SystemSetting;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    public $rootPath = "backups/";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            ('cronadmin'),
            ('RIs2C8yn3_YnSEE4Rn'),
            ('unikwork_healthcare'),
            $this->getfile()
        ));
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $this->process->mustRun();

            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has been failed.');
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getfile()
    {
        // File::cleanDirectory('storage/backups/db_'.date('Y_m_d'));
        if(!is_dir(storage_path($this->rootPath))){
            mkdir(storage_path($this->rootPath));
        }
        $foldername = 'db_'.date('Y_m_d').'/';
        if(!is_dir(storage_path($this->rootPath.$foldername))){
            mkdir(storage_path($this->rootPath.$foldername));
        }
        $this->removeFilesAndDir(0);
        $this->removeFilesAndDir(1);
        return storage_path($this->rootPath.$foldername.date('H_i_')."backup.sql");
    }

    private function removeFilesAndDir($type){
        $fileMethod = 'isDirectory';
        $removeFileType = 'deleteDirectory';
        if($type == 1){
            $path = glob(storage_path('logs') ."\\*");
            $fPath = 'storage/logs/';
        }else{
            // $path = glob(storage_path('backups') ."\\*",GLOB_ONLYDIR);
            $path = glob(storage_path('backups').'/*', GLOB_ONLYDIR);
            $fPath = 'storage/backups/db_';
        }
       
        $systemSettings = SystemSetting::first();
        $logDays = $systemSettings->clear_logs_day;
        if($logDays > 1){
            $logDays = $logDays - 1;
        }
        $days = Carbon::now()->subDays($logDays);
        $files2 = $path; 
        $files2 = $files2 ? $files2 : [];
        $filecount = count($files2); 
       
        for($i=1; $i<=$filecount;$i++){
            if($type == 1){
                $removeDate = Carbon::parse($days)->subDays($i)->format('Y-m-d');
            }else{
                $removeDate = Carbon::parse($days)->subDays($i)->format('Y_m_d');
            }
            if(!File::$fileMethod($fPath.$removeDate)){
                break;
            }
            File::$removeFileType($fPath.$removeDate);
        }
    }
}
