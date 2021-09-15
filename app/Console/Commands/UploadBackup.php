<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class UploadBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:uploadbackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload backup in google drive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $date = \Carbon\Carbon::now()->format('Y_m_d');
            $zipFileName = 'db_'.$date;
            $pathdir = storage_path().'/'.'backups/'.$zipFileName;

            // Enter the name to creating zipped directory
            $pathdir = $pathdir."/";
            $zipcreated = $zipFileName.'.zip';

            // Create new zip class
            $zip = new \ZipArchive;
            if(\File::exists($pathdir)){
                if($zip->open(storage_path().'/'.'backups/'.$zipcreated, \ZipArchive::CREATE ) === TRUE) {
                    // Store the path into the variable
                    $dir = opendir($pathdir);

                    while($file = readdir($dir)) {
                        if(is_file($pathdir.$file)) {
                            $zip->addFile($pathdir.$file, $file);
                        }
                    }
                    $zip ->close();
                }
                \Storage::disk('google')->put($zipcreated, file_get_contents(storage_path().'/'.'backups/'.$zipcreated));
                \File::delete($zipcreated);
            }
        }catch(\Exceptio $e){
            log::debug($e);
        }
    }
}
