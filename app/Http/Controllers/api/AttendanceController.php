<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use DB;
use Image;
use App\Models\User;
use App\Models\Photo;
use Excel;
use Illuminate\Support\Facades\Hash;
use Storage;
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAttendance_old(Request $request)
    {
        
       // DB::beginTransaction();
        try { 
            date_default_timezone_set('Asia/Calcutta');
                if(str_starts_with($request->member_code, 'AF')){
                $member_type='student';
                }else{
                $member_type='staff';
                }
                if($request->attend_date<date('Y-m-d')){
                    $time=$request->punch_time==''?date('H:i:s'):$request->punch_time;
                    $attn_type='past';
                }else{
                    $time=date('H:i:s');
                    $attn_type='present';
                }
                
            $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
            if($request->image!=''){
                $folderPath = "volume_blr1_01/".trim($request->attend_date)."/";
                $base64Image = explode(";base64,", $request->image);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $file = $folderPath . uniqid() . '.'.$imageType;
                if (!file_exists($folderPath)){
                mkdir($folderPath);
                }
                file_put_contents($file, $image_base64);
                //dd('end');
                $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
                $filename = basename($path);
                $input['file'] = trim($request->member_code)."_".$request->attend_date."_".time().'.jpg';
                $imgFile=Image::make($path)->save(public_path($folderPath.$filename));

                $imgFile->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($folderPath.'/'.$input['file']);
                unlink(public_path($file));
            }else{
                $input['file']='NA'; 
            }    

            $postParameter = ['user_id' => $request->user_id,'atten_date' => $request->attend_date,'punch_in'=>$time,'lat'=>$request->lat,'long'=>$request->long,'member_id'=>$request->member_id,'member_code'=>$request->member_code,'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>$request->location,'reason'=>$request->reason,'center_id'=>$request->center_id,'photo'=>$input['file'],'batch_id'=>$request->batch_id,'batch_code'=>$request->batch_code];
            if(sizeof($details)>0){
                //dd($details[0]->id);
                $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                $curlResponse = curl_exec($curlHandle);
                //dd($curlResponse);
                curl_close($curlHandle);
                Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

                Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

                $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
                DB::commit();
                    return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
            }
            //code for update end
            
            $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            $curlResponse = curl_exec($curlHandle);
            //dd($curlResponse);
            // // if(){
                 
            // // }
            // if(curl_errno($curl)) {
            //     $postParameter['transfer_status']=0;
            // }
            // //dd($postParameter);
            $lastId=Attendance::create($postParameter)->id;
            Photo::create(['user_id' => $request->user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
            curl_close($curlHandle);
            $x=['punch_in'=>$time,'date' => $request->attend_date];
            DB::commit();
            return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    public function storeAttendance(Request $request)
    {
        
       // DB::beginTransaction();
        try { 
            date_default_timezone_set('Asia/Kolkata');
                if(str_starts_with($request->member_code, 'AF')){
                $member_type='student';
                }else{
                $member_type='staff';
                }
                if($request->attend_date<date('Y-m-d')){
                    $time=$request->punch_time==''?date('H:i:s'):$request->punch_time;
                    $attn_type='past';
                }else{
                    $time=date('H:i:s');
                    $attn_type='present';
                }
                
            $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
            if($request->image!=''){
                $s3_path="attendance/".trim($request->attend_date)."/";
                $folderPath = "volume_blr1_01/".trim($request->attend_date)."/";
                $base64Image = explode(";base64,", $request->image);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $file = $folderPath . uniqid() . '.'.$imageType;
                if (!file_exists($folderPath)){
                mkdir($folderPath);
                }
                file_put_contents($file, $image_base64);
                //dd('end');
                $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
                $filename = basename($path);
                $input['file'] = trim($request->member_code)."_".$request->attend_date."_".time().'.jpg';

                $imgFile = Image::make($path)->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
                
                // Save the resized image temporarily in a local folder (if needed)
                $tempPath = public_path($folderPath . $input['file']);
                $imgFile->save($tempPath);
                
                // Upload the resized image to S3
                Storage::disk('s3_1')->put($s3_path.$input['file'], file_get_contents($tempPath), [
                    'ContentType' => mime_content_type($tempPath),
                ]);

                
                
                // Optionally, remove the local temporary file
                unlink($tempPath);
                unlink($file);
            }else{
                $input['file']='NA'; 
            }    

            $postParameter = ['user_id' => $request->user_id,'atten_date' => $request->attend_date,'punch_in'=>$time,'lat'=>$request->lat,'long'=>$request->long,'member_id'=>$request->member_id,'member_code'=>$request->member_code,'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>$request->location,'reason'=>$request->reason,'center_id'=>$request->center_id,'photo'=>$input['file'],'batch_id'=>$request->batch_id,'batch_code'=>$request->batch_code];
            if(sizeof($details)>0){
                //dd($details[0]->id);
                $sts=Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->get(['status','punch_out']);
                //dd($sts);
                if (
                    ($sts[0]->status != 1 && $sts[0]->status != 3) ||
                    ($sts[0]->status == 1 && $sts[0]->punch_out == null)
                ) { 

                    
                    $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    $curlResponse = curl_exec($curlHandle);
                    //dd($curlResponse);
                    curl_close($curlHandle);
                    Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

                    Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

                    $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
                } 
                // if($sts[0]->status==1 && $sts[0]->punch_out==null){

                //     $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                //     curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                //     curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                //     $curlResponse = curl_exec($curlHandle);
                //     //dd($curlResponse);
                //     curl_close($curlHandle);
                //     Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

                //     Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

                //     $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];

                // }  

                DB::commit();
                $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
                    return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
            }
            //code for update end
            
            $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            $curlResponse = curl_exec($curlHandle);
            //dd($curlResponse);
            // // if(){
                 
            // // }
            // if(curl_errno($curl)) {
            //     $postParameter['transfer_status']=0;
            // }
            // //dd($postParameter);
            $lastId=Attendance::create($postParameter)->id;
            Photo::create(['user_id' => $request->user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
            curl_close($curlHandle);
            $x=['punch_in'=>$time,'date' => $request->attend_date];
            DB::commit();
            return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    public function offlineSync(Request $request)
    {
        
       // DB::beginTransaction();
        try { 
            //return $request->details;
            //
            date_default_timezone_set('Asia/Kolkata');
            
            foreach($request->details as $x){
              
                //return Response(['message' => 'inserted successfully','status'=>1,'data11'=>$request->details],200);
                 //$x=json_encode($x);
                //return Response(['message' => 'inserted successfully vvv','status'=>1,'data11'=>$x->member_code],200);

                    if(str_starts_with($x['member_code'], 'AF')){
                    $member_type='student';
                    }else{
                    $member_type='staff';
                    }
                    // if($x['attend_date']<date('Y-m-d')){
                    //     //$time=$request->punch_time==''?date('H:i:s'):$request->punch_time;
                    //     $attn_type='past';
                    // }else{
                    //     $time=date('H:i:s');
                    //     $attn_type='present';
                    // }
                    $attn_type=$x['attn_type'];
                    
                    $details1 = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->get();
                    
                    if($x['image']!=''){
                        $s3_path="attendance/".trim($x['attend_date'])."/";
                        $folderPath = "volume_blr1_01/".trim($x['attend_date'])."/";
                        $base64Image = explode(";base64,", $x['image']);
                        $explodeImage = explode("image/", $base64Image[0]);
                        $imageType = $explodeImage[1];
                        $image_base64 = base64_decode($base64Image[1]);
                        $file = $folderPath . uniqid() . '.'.$imageType;
                        if (!file_exists($folderPath)){
                        mkdir($folderPath);
                        }
                        file_put_contents($file, $image_base64);
                        //dd('end');
                        $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
                        $filename = basename($path);
                        $input['file'] = trim($x['member_code'])."_".$x['attend_date']."_".time().'.jpg';

                        $imgFile = Image::make($path)->resize(200, 200, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        
                        // Save the resized image temporarily in a local folder (if needed)
                        $tempPath = public_path($folderPath . $input['file']);
                        $imgFile->save($tempPath);
                        
                        // Upload the resized image to S3
                        Storage::disk('s3_1')->put($s3_path.$input['file'], file_get_contents($tempPath), [
                            'ContentType' => mime_content_type($tempPath),
                        ]);

                        
                        
                        // Optionally, remove the local temporary file
                        unlink($tempPath);
                        unlink($file);
                    }else{
                        $input['file']='NA'; 
                    }    
                    //$attn_type='present';
                    $postParameter = ['user_id' => $x['user_id'],'atten_date' => $x['attend_date'],'punch_in'=>$x['punch_in'],'punch_out'=>$x['punch_out'],'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$x['member_id'],'member_code'=>$x['member_code'],'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'bulk_type'=>0];
                    // if(sizeof($details)>0){
                    //     //dd($details[0]->id);
                    //     $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                    //     curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                    //     curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    //     $curlResponse = curl_exec($curlHandle);
                    //     //dd($curlResponse);
                    //     curl_close($curlHandle);
                    //     Attendance::where('atten_date', $x->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$x->lat,'punch_out_long'=>$x->long,'status'=>0,'punch_out_place'=>$x->location]);

                    //     Photo::create(['user_id' => $x->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x->lat,'long'=>$x->long,'place'=>$x->location,'punch_time'=>$time,'punch_date'=>$x->attend_date,'member_code'=>trim($x->member_code)]);

                    //     $x=['punch_out'=>$time,'date' => $x->attend_date,'punch_in'=>$details[0]->punch_in];
                    //     DB::commit();
                    //         return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
                    // }
                    //code for update end
                    
                    // $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                    // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                    // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    // $curlResponse = curl_exec($curlHandle);
                    
                    
                   
                     
                    if($x['punch_out']==null){
                        //dd('k');
                        Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->delete();
                        $lastId=Attendance::create($postParameter)->id;
                        Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_in'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

                        DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                            'member_id' => $x['member_id'],
                            'atten_date' => $x['attend_date'],
                            'punch_type'=>"I",
                        ],[
                            'user_id_mob_app' => $x['user_id'],
                            'atten_date' => $x['attend_date'],
                            'punch_time'=>$x['punch_in'],
                            'lat'=>$x['lat'],
                            'long'=>$x['long'],
                            'member_id'=>$x['member_id'],
                            'member_code'=>$x['member_code'],
                            'status'=>0,
                            'punch_place'=>'',
                            'atten_type'=>$attn_type,
                            'member_type'=>$member_type,
                            'reason'=>$x['reason'],
                            'center_id'=>$x['center_id'],
                            'punch_type'=>"I",
                            'photo'=>$input['file'],
                            'batch_code'=>$x['batch_code'],
                            'update_attn_status'=>0,
                        ]);


                    }else{
                        //dd($details1);
                        if(sizeof($details1)>0){

                            //if($details1[0]->status!=1 && $details1[0]->status!=3){
                            if(($sts[0]->status != 1 && $sts[0]->status != 3) ||
                            ($sts[0]->status == 1 && $sts[0]->punch_out == null)){

                                

                                Attendance::where('atten_date', $x['attend_date'])->where('user_id', $details1[0]->user_id)->update(['punch_in'=>$x['punch_in'],'punch_out'=>$x['punch_out'],'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long'],'status'=>0,'punch_out_place'=>'','atten_type'=>$attn_type,'reason'=>$x['reason']]);

                                Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$details1[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_out'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

                                DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                                    'member_id' => $x['member_id'],
                                    'atten_date' => $x['attend_date'],
                                    'punch_type'=>"O",
                                ],[
                                    'user_id_mob_app' => $x['user_id'],
                                    'atten_date' => $x['attend_date'],
                                    'punch_time'=>$x['punch_out'],
                                    'lat'=>$x['lat'],
                                    'long'=>$x['long'],
                                    'member_id'=>$x['member_id'],
                                    'member_code'=>$x['member_code'],
                                    'status'=>0,
                                    'punch_place'=>'',
                                    'atten_type'=>$attn_type,
                                    'member_type'=>$member_type,
                                    'reason'=>$x['reason'],
                                    'center_id'=>$x['center_id'],
                                    'punch_type'=>"O",
                                    'photo'=>$input['file'],
                                    'batch_code'=>$x['batch_code'],
                                    'update_attn_status'=>0,
                                ]);
                            }    

                        }else{
                            Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->delete();
                            $postParameter['status']=0;
                            $lastId=Attendance::create($postParameter)->id;
                            Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_in'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);
                            
                            Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_out'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

                            DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                                'member_id' => $x['member_id'],
                                'atten_date' => $x['attend_date'],
                                'punch_type'=>"I",
                            ],[
                                'user_id_mob_app' => $x['user_id'],
                                'atten_date' => $x['attend_date'],
                                'punch_time'=>$x['punch_in'],
                                'lat'=>$x['lat'],
                                'long'=>$x['long'],
                                'member_id'=>$x['member_id'],
                                'member_code'=>$x['member_code'],
                                'status'=>0,
                                'punch_place'=>'',
                                'atten_type'=>$attn_type,
                                'member_type'=>$member_type,
                                'reason'=>$x['reason'],
                                'center_id'=>$x['center_id'],
                                'punch_type'=>"I",
                                'photo'=>$input['file'],
                                'batch_code'=>$x['batch_code'],
                                'update_attn_status'=>0,
                            ]);
                            DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                                'member_id' => $x['member_id'],
                                'atten_date' => $x['attend_date'],
                                'punch_type'=>"O",
                            ],[
                                'user_id_mob_app' => $x['user_id'],
                                'atten_date' => $x['attend_date'],
                                'punch_time'=>$x['punch_out'],
                                'lat'=>$x['lat'],
                                'long'=>$x['long'],
                                'member_id'=>$x['member_id'],
                                'member_code'=>$x['member_code'],
                                'status'=>0,
                                'punch_place'=>'',
                                'atten_type'=>$attn_type,
                                'member_type'=>$member_type,
                                'reason'=>$x['reason'],
                                'center_id'=>$x['center_id'],
                                'punch_type'=>"O",
                                'photo'=>$input['file'],
                                'batch_code'=>$x['batch_code'],
                                'update_attn_status'=>0,
                            ]);
                            
                        }
                    }

                    
                    //curl_close($curlHandle);
                    //$x=['punch_in'=>$time,'date' => $x['attend_date']];
                    DB::commit();
            }    
            return Response(['message' => 'inserted successfully','status'=>1],200);

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function fetchAttendanceBasedOnCurrentDate($user_id,$attn_date)
    {
        if($attn_date=="null"){
            $attn_date=date('Y-m-d');
        }
        //dd($attn_date);
        $details = Attendance::where('user_id',$user_id)->where('atten_date', $attn_date)
        ->get(['id as id','punch_in as punch_in','punch_out as punch_out','atten_date as date','status as status','user_id as user_id']);
        return Response(['datas' => $details,'status'=>1,'cur_date'=>$attn_date],200);
    }
    public function fetchAttendance($user_id,$cur_month,$cur_year)
    {
        //
        $details = Attendance::where('user_id',$user_id)->whereMonth('atten_date', $cur_month)
        ->whereYear('atten_date', $cur_year)
        ->get(['id as id','punch_in as punch_in','punch_out as punch_out','atten_date as date','status as status','user_id as user_id']);
        $main_arr=[];
        
        // if(sizeof($details)>0){
        //     $x=['punch_in'=>$details[0]->punch_in,'punch_out'=>$details[0]->punch_out,'atten_date'=>$details[0]->atten_date];
        // }
        // else{
        //     $x=[];
        // }
        return Response(['datas' => $details,'status'=>1,'cur_date'=>date('Y-m-d')],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
    // public function imageUpload(Request $request){
    //     // $file = $request->file('wcc_file');
        
    //     // //foreach ($files as $file) { 

    //     //   $filename = $file->getClientOriginalName();
    //     //   $file_ext = $file->extension();// get file extention
    //     //   $filename = $member_code."-".$doctype;
    //     //   $destinationPath = "uploads/wcc";

    //       $image = $request->file('file');
    //         $input['file'] = time().'.'.$image->getClientOriginalExtension();
            
    //         $destinationPath = public_path('/abc');
    //         $imgFile = Image::make($image->getRealPath());
    //         $imgFile->resize(150, 150, function ($constraint) {
    //             $constraint->aspectRatio();
    //         })->save($destinationPath.'/'.$input['file']);
    //         // $destinationPath = public_path('/uploads');
    //         // $image->move($destinationPath, $input['file']);
    // }

    public function insertIntoAttendanceFromCMIS(Request $request){
       
        
        
        try{
            //DB::beginTransaction();
            //return Response(['data' => $request->all()],200);
            foreach($request->all() as $r){
                

                $student_id = User::updateOrInsert(
                    [
                        'username' =>$r['member_code'],
                    ],
                    [
                    'name' => $r['first_name']." ".$r['last_name'],
                    'username' =>$r['member_code'],
                    'email' => $r['email_id'],
                    'mobile_no'=>$r['mobile_no'],
                    'password' => bcrypt($r['member_code']),
                    'member_code'=>$r['member_code'],
                    'member_id'=>$r['member_id'],
                    'batch_id' => $r['batch_id'],

                    'batch_code'=>$r['batch_code'],
                    'center_id' => $r['center_id'],
                    'center_code'=>$r['center_code'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                    ]);
            }   
            //DB::commit(); 
            return Response(['data' => 1],200);  
        }
        catch(\Exception $e){
           // return Response(['data' => $e],200);
           
          DB::rollback();
          return $this->sendError($e->getMessage());
        }
  
    }

    public function UpdateAttendance(Request $request){
        try{
            //
            //DB::beginTransaction();
            foreach($request->all() as $r){
                Attendance::where('atten_date', $r['atten_date'])->where('member_id', $r['member_id'])->update(['status'=>$r['status']]);
            }   
            //DB::commit(); 
            return Response(['data' => 1],200);
  
        }
        catch(\Exception $e){
          DB::rollback();
          return $this->sendError($e->getMessage());
        }
  
    }

    public function insertIntoAttendanceFromCMISFromExcel(Request $request){
       
        
        
        try{
            //DB::beginTransaction();
            $arr      = $request->all();
           $file     = $request->file('file');
           $filename = $file->getClientOriginalName();
           $file_ext = substr($filename, strripos($filename, '.'));
           if($file_ext != '.xlsx' && $file_ext != '.xls') {
             return $this->sendError(Lang::get('student.unsupported_file'));
           }
           
           //dd('dd bbb');
           $results = Excel::load($request->file('file')->getRealPath())->get();
           //$results = Excel::toArray(new TestImport(), $request->file('file'));
           //dd($results);
           $count = 2;
           $result_array = $results;
            foreach($result_array as $r){
                
                //dd($r);
                $student_id = User::create([
                    'name' => $r['first_name']." ".$r['last_name'],
                    'username' =>$r['member_code'],
                    'email' => $r['email_id'],
                    'mobile_no'=>$r['mobile_no'],
                    'password' => bcrypt($r['member_code']),
                    'member_code'=>$r['member_code'],
                    'member_id'=>$r['member_id'],
                    'batch_id' => $r['batch_id'],

                    'batch_code'=>$r['batch_code'],
                    'center_id' => $r['center_id'],
                    'center_code'=>$r['center_code'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                    ]);
            }   
            //DB::commit(); 
            return Response(['data' => 1],200);
  
        }
        catch(\Exception $e){
          DB::rollback();
          return $this->sendError($e->getMessage());
        }
  
    }
    public function fetchDataForCheckingRedis(){

        dd('kk');
        $x=Attendance::get();
        return Response(['data' => $x],200);

    }

    public function fetchAllDetailsForTrainer($username)
    {
       try{



        $centers= DB::connection('mysql_2')->table('users as u')
                    ->leftJoin('users_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->leftJoin('centers as c', 'ur.center_id', '=', 'c.id')
                    ->where('u.user_id', strtoupper($username))
                    ->where('ur.role_id', 7)
                    ->where('ur.status', 1)
                    ->where('c.status', 1)
                    ->get(['c.id as center_id','c.name as center_name','c.short_code as center_code']);
        $center_ids = $centers->pluck('center_id')->toArray();
        $batches= DB::connection('mysql_2')->table('batches')
                    ->whereIn('center_id', $center_ids)
                    ->where('status', 'running')
                    ->get(['id as batch_id','batch_code','center_id']);
        $batch_ids = $batches->pluck('batch_id')->toArray();

        $members= DB::connection('mysql_2')->table('enrollments as e')
                  ->leftJoin('members as m', 'e.member_id', '=', 'm.id')
                  ->whereIn('e.batch_id', $batch_ids)
                  ->where('e.status', 'enrolled')
                  ->get(['m.first_name as first_name','m.last_name as last_name','m.member_code as member_code','m.id as member_id','e.batch_id as batch_id']);

         return Response(['center_details' => $centers,'batches' => $batches,'members' => $members],200);
      // return Response(['center_details' => $details_from_cmis],200);            

       }catch(\Exception $e){
        DB::rollback();
        dd($e);
        //return $this->sendError($e->getMessage());
      }
    }

    public function offlineSyncBulkPunchInOutAttendance(Request $request)
    {
        
       // DB::beginTransaction();
        try { 
           // dd($request->all());
            foreach($request->all() as $x){
               //dd(json_decode($a['studentList'], true));
                    $student_list=json_decode($x['studentList'], true);
            
                    date_default_timezone_set('Asia/Kolkata');
                    $time=$x['punch_time'];
                    $attn_type='present';
                    $member_type='student';
                    if($x['image']!=''){
                        // $folderPath = "volume_blr1_01/".trim($request->attend_date)."/";
                        // $base64Image = explode(";base64,", $request->image);
                        // $explodeImage = explode("image/", $base64Image[0]);
                        // $imageType = $explodeImage[1];
                        // $image_base64 = base64_decode($base64Image[1]);
                        // $file = $folderPath . uniqid() . '.'.$imageType;
                        // if (!file_exists($folderPath)){
                        // mkdir($folderPath);
                        // }
                        // file_put_contents($file, $image_base64);
                        // //dd('end');
                        // $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
                        // $filename = basename($path);
                        // $input['file'] = trim($request->batch_code)."_".$request->attend_date."_".time().'.jpg';
                        // $imgFile=Image::make($path)->save(public_path($folderPath.$filename));

                        // $imgFile->resize(200, 200, function ($constraint) {
                        //     $constraint->aspectRatio();
                        // })->save($folderPath.'/'.$input['file']);
                        // unlink(public_path($file));

                        $s3_path="attendance/".trim($x['attend_date'])."/";
                        $folderPath = "volume_blr1_01/".trim($x['attend_date'])."/";
                        $base64Image = explode(";base64,", $x['image']);
                        $explodeImage = explode("image/", $base64Image[0]);
                        $imageType = $explodeImage[1];
                        $image_base64 = base64_decode($base64Image[1]);
                        $file = $folderPath . uniqid() . '.'.$imageType;
                        if (!file_exists($folderPath)){
                        mkdir($folderPath);
                        }
                        file_put_contents($file, $image_base64);
                        //dd('end');
                        $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
                        $filename = basename($path);
                        $input['file'] = trim($request->batch_code)."_".$x['attend_date']."_".time().'.jpg';

                        $imgFile = Image::make($path)->resize(200, 200, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        
                        // Save the resized image temporarily in a local folder (if needed)
                        $tempPath = public_path($folderPath . $input['file']);
                        $imgFile->save($tempPath);
                        
                        // Upload the resized image to S3
                        Storage::disk('s3_1')->put($s3_path.$input['file'], file_get_contents($tempPath), [
                            'ContentType' => mime_content_type($tempPath),
                        ]);

                        
                        
                        // Optionally, remove the local temporary file
                        unlink($tempPath);
                        unlink($file);
                        
                    }else{
                        $input['file']='NA'; 
                    }  
                    //$input['file']='NA';
                    // $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $x['user_id'])->value('id');
                    $trainer_username=DB::table('users')->where('id', $x['user_id'])->value('username');
                    $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $trainer_username)->value('id');
                    if($x['type']=='in'){

                        
                        foreach($student_list as $member_id){

                            $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

                            DB::table('users')->updateOrInsert([
                                'member_id' => $member_id,
                            ],[
                                'name' => $members[0]->first_name." ".$members[0]->last_name,
                                'username' => $members[0]->member_code,
                                'email' => $members[0]->email_id,
                                'mobile_no'=>$members[0]->mobile_no,
                                'password'=>Hash::make('1234567'),
                                'member_id'=>$member_id,
                                'member_code'=>$members[0]->member_code,
                                'batch_id'=>$x['batch_id'],
                                'batch_code'=>$x['batch_code'],
                                'center_id'=>$x['center_id'],
                                'center_code'=>$x['center_code'],
                                'status'=>1,
                                'role_name'=>'student',
                                'gender'=>$members[0]->gender
                            ]);
                            $user_id=DB::table('users')->where('member_id', $member_id)->value('id');
                            

                            $datas=User::where('id',$user_id)->get(['member_code','member_id']);

                                $postParameter = ['user_id' => $user_id,'atten_date' => $x['attend_date'],'punch_in'=>$time,'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$datas[0]->member_id,'member_code'=>$datas[0]->member_code,'status'=>2,'bulk_type'=>1,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'created_by'=>$trainer_id];

                                $curlHandle = curl_init('https://cmis4api.anudip.org/public/api/insertFromAttenApp');
                                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                                $curlResponse = curl_exec($curlHandle);
                                //dd($curlResponse);
                                curl_close($curlHandle);

                                if($curlResponse === false) {
                                    return Response(['message' => 'server issue','status'=>1],200);
                                } 

                                $lastId=Attendance::create($postParameter)->id;
                                Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($datas[0]->member_code)]);
                                
                                DB::commit();
                        }   
                        
                        
                        $x=['punch_in'=>$time,'date' => $x['attend_date']];

                        //return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

                    }else{
                        $arr=[];
                        //dd('jj');
                        foreach($student_list as $member_id){
                            $user_id=DB::table('users')->where('member_id', $member_id)->value('id');
                            $details = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();
                            
                            if(sizeof($details)>0){
                                $datas=User::where('id',$user_id)->get(['member_code','member_id']);
                                $postParameter = ['user_id' => $user_id,'atten_date' => $x['attend_date'],'punch_in'=>$time,'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$datas[0]->member_id,'member_code'=>$datas[0]->member_code,'status'=>2,'bulk_type'=>1,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'created_by'=>$trainer_id];

                                $curlHandle = curl_init('https://cmis4api.anudip.org/public/api/insertFromAttenApp');
                                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                                $curlResponse = curl_exec($curlHandle);
                                //dd($curlResponse);
                                curl_close($curlHandle);

                                if($curlResponse === false) {
                                    return Response(['message' => 'server issue','status'=>1],200);
                                } 

                                Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->where('created_by', $trainer_id)->update(['punch_out'=>$time,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long'],'status'=>0,'punch_out_place'=>'']);
                                //dd($user_id,$x['attend_date']);
                                Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($datas[0]->member_code)]);

                                
                                DB::commit();
                            } else{
                                $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name']);
                                array_push($arr,$members);
                            }   
                        }    
                        
                        //return Response(['message' => 'updated successfully','status'=>1,'members'=>$arr],200);

                    }
            }
            return Response(['message' => 'inserted successfully','status'=>1],200);
            

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }
}
