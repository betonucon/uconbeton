<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aplikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function index(request $request){
        $menu='MySoftware  ';
        return view('download.index',compact('menu'));
    }

    public function ubah(request $request){
        $data=Aplikasi::where('id',$request->id)->first();
        echo'
            <input type="text" name="id" value="'.$data['id'].'">
            <div class="form-group">
                <label for="exampleInputEmail1">Lampiran</label>
                <input type="file" class="form-control"  name="file" >
            </div>	

            <div class="form-group">
                <label for="exampleInputEmail1">Isi</label>
                <textarea class="textarea form-control" name="keterangan" id="textarea" placeholder="Enter text ..." rows="12">'.$data['keterangan'].'</textarea>
            </div>


        ';
        echo'
            <script type="text/javascript">
                $("#textarea").wysihtml5();
            </script>
        ';
    }

    public function hapus(request $request){
        $data=Aplikasi::where('id',$request->id)->first();
        if(Auth::user()['role_id']==1){
            if(hapuslinkdrive($data['attach'])){
                $hapus=Aplikasi::where('id',$request->id)->delete();
            }
        }
        if(Auth::user()['role_id']==2){
            $hapus=Aplikasi::where('id',$request->id)->update([
                'sts'=>0,
            ]);
        }
        
    }

    public function hapus_multiple(request $request){
        error_reporting(0);
        $jum=count($request->id);
        if(Auth::user()['role_id']==1){
            for($x=0;$x<$jum;$x++){
                $data=Aplikasi::where('id',$request->id[$x])->first();
                if(hapuslinkdrive($data['attach'])){
                    $hapus=Aplikasi::where('id',$request->id[$x])->delete();
                }
            }
        }   
        if(Auth::user()['role_id']==2){
            for($x=0;$x<$jum;$x++){
                $hapus=Aplikasi::where('id',$request->id[$x])->update([
                    'sts'=>0,
                ]);
                
            }
        }   
    }

    public function simpan(request $request){
        if (trim($request->name) == '') {$error[] = '- Masukan Nama file';}
        if (trim($request->file) == '') {$error[] = '- Upload file';}
        if (trim($request->keterangan) == '') {$error[] = '- Isi Keterangan';}
        if (isset($error)) {echo '<p style="padding:5px;color:#000;font-size:13px"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
        else{
            $patr='/\s+/';
            $link=preg_replace($patr,'_',$request->name);
            $cek=Aplikasi::where('link',$link)->count();
            if($cek==0){
                $image = $request->file('file');
                $imageFileName =$link.'.'. $image->getClientOriginalExtension();
                $filePath =$imageFileName;
                $file = \Storage::disk('google');
                
                
                if($image->getClientOriginalExtension()=='zip' || $image->getClientOriginalExtension()=='rar'){
                    if($file->put($filePath, file_get_contents($image))){
                            
                        $data=Aplikasi::create([
                            'file'=>linkdrive($imageFileName),
                            'name'=>$request->name,
                            'link'=>$link,
                            'attach'=>$filePath,
                            'sts'=>1,
                            'keterangan'=>$request->keterangan,
                            'username'=>Auth::user()['username'],
                        ]);

                        echo'ok';
                        
                    }else{
                        echo'Gagal';
                    }
                }else{
                    echo '<p style="padding:5px;color:#000;font-size:13px"><b>Error</b>: <br />- Format file harus "zip,rar"</p>';
                }
            }else{
                echo '<p style="padding:5px;color:#000;font-size:13px"><b>Error</b>: <br />- Nama Aplikasi Sudah Terdaftar</p>';
            }
                
        }
    }
    
    public function simpan_ubah(request $request){
        if (trim($request->keterangan) == '') {$error[] = '- Isi Keterangan';}
        if (isset($error)) {echo '<p style="padding:5px;color:#000;font-size:13px"><b>Error</b>: <br />'.implode('<br />', $error).'</p>';} 
        else{
            $first=Aplikasi::where('id',$request->id)->first();
            if($request->file==''){
                $data=Aplikasi::where('id',$request->id)->update([
                    'keterangan'=>$request->keterangan,
                ]);

                echo'ok';
            }else{
                $image = $request->file('file');
                $imageFileName =$first['link'].'.'. $image->getClientOriginalExtension();
                $filePath =$imageFileName;
                $file = \Storage::disk('google');
                
                
                if($image->getClientOriginalExtension()=='zip' || $image->getClientOriginalExtension()=='rar'){
                    if(hapuslinkdrive($first['attach'])){    
                        if($file->put($filePath, file_get_contents($image))){
                                
                            $data=Aplikasi::where('id',$request->id)->update([
                                'file'=>linkdrive($imageFileName),
                                'attach'=>$filePath,
                                'username'=>Auth::user()['username'],
                            ]);

                            echo'ok';
                            
                        }else{
                            echo'Gagal';
                        }
                    }
                }else{
                    echo '<p style="padding:5px;color:#000;font-size:13px"><b>Error</b>: <br />- Format file harus "zip,rar"</p>';
                }
            }
                
        }
    }
}
