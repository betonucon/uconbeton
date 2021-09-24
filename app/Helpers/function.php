<?php

function linkdrive($filename){
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!
    return Storage::cloud()->url($file['path']);
}

function get_aplikasi(){
    if(Auth::user()['role_id']==1){
        $data=App\Aplikasi::orderBy('id','Desc')->get();
    }
    if(Auth::user()['role_id']==2){
        $data=App\Aplikasi::where('username',Auth::user()['username'])->where('sts',1)->orderBy('id','Desc')->get();
    }
    
    return $data;
}

function hapuslinkdrive($filename){
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!
    return Storage::cloud()->delete($file['path']);
 }


?>