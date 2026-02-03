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
use Mail;
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
    // public function storeAttendance_old(Request $request)
    // {
        
    //    // DB::beginTransaction();
    //     try { 
    //         date_default_timezone_set('Asia/Calcutta');
    //             if(str_starts_with($request->member_code, 'AF')){
    //             $member_type='student';
    //             }else{
    //             $member_type='staff';
    //             }
    //             if($request->attend_date<date('Y-m-d')){
    //                 $time=$request->punch_time==''?date('H:i:s'):$request->punch_time;
    //                 $attn_type='past';
    //             }else{
    //                 $time=date('H:i:s');
    //                 $attn_type='present';
    //             }
                
    //         $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
    //         if($request->image!=''){
    //             $folderPath = "volume_blr1_01/".trim($request->attend_date)."/";
    //             $base64Image = explode(";base64,", $request->image);
    //             $explodeImage = explode("image/", $base64Image[0]);
    //             $imageType = $explodeImage[1];
    //             $image_base64 = base64_decode($base64Image[1]);
    //             $file = $folderPath . uniqid() . '.'.$imageType;
    //             if (!file_exists($folderPath)){
    //             mkdir($folderPath);
    //             }
    //             file_put_contents($file, $image_base64);
    //             //dd('end');
    //             $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
    //             $filename = basename($path);
    //             $input['file'] = trim($request->member_code)."_".$request->attend_date."_".time().'.jpg';
    //             $imgFile=Image::make($path)->save(public_path($folderPath.$filename));

    //             $imgFile->resize(200, 200, function ($constraint) {
    //                 $constraint->aspectRatio();
    //             })->save($folderPath.'/'.$input['file']);
    //             unlink(public_path($file));
    //         }else{
    //             $input['file']='NA'; 
    //         }    

    //         $postParameter = ['user_id' => $request->user_id,'atten_date' => $request->attend_date,'punch_in'=>$time,'lat'=>$request->lat,'long'=>$request->long,'member_id'=>$request->member_id,'member_code'=>$request->member_code,'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>$request->location,'reason'=>$request->reason,'center_id'=>$request->center_id,'photo'=>$input['file'],'batch_id'=>$request->batch_id,'batch_code'=>$request->batch_code,'app_version'=>$request->app_version];
    //         if(sizeof($details)>0){
    //             //dd($details[0]->id);
    //             $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
    //             curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
    //             curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //             $curlResponse = curl_exec($curlHandle);
    //             //dd($curlResponse);
    //             curl_close($curlHandle);
    //             Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

    //             Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

    //             $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
    //             DB::commit();
    //                 return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
    //         }
    //         //code for update end
            
    //         $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
    //         curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
    //         curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //         $curlResponse = curl_exec($curlHandle);
    //         //dd($curlResponse);
    //         // // if(){
                 
    //         // // }
    //         // if(curl_errno($curl)) {
    //         //     $postParameter['transfer_status']=0;
    //         // }
    //         // //dd($postParameter);
    //         $lastId=Attendance::create($postParameter)->id;
    //         Photo::create(['user_id' => $request->user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
    //         curl_close($curlHandle);
    //         $x=['punch_in'=>$time,'date' => $request->attend_date];
    //         DB::commit();
    //         return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

    //     } catch (Exception $e) { 
    //         DB::rollback();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function storeAttendance(Request $request)
    // {
        
    //    DB::beginTransaction();
    //     try { 
    //         date_default_timezone_set('Asia/Kolkata');
    //         $member_id=$request->member_id;
    //         $batchdata = DB::connection('mysql_2')->table('enrollments as a')
    //                     ->join('batches as b', 'a.batch_id', '=', 'b.id')
    //                     ->where('a.member_id', $member_id)
    //                     ->orderByDesc('a.id')
    //                     ->get(['a.batch_id', 'b.center_id', 'b.batch_code']);
    //         if(sizeof($batchdata)==0){
    //           $center_id=$request->center_id;
    //           $batch_code=$request->batch_code;
    //         }else{
    //           $center_id=$batchdata[0]->center_id;
    //           $batch_code=$batchdata[0]->batch_code;
    //         }            
    //             if(str_starts_with($request->member_code, 'AF')){
    //             $member_type='student';
    //             }else{
    //             $member_type='staff';
    //             }
    //             if($request->attend_date<date('Y-m-d')){
    //                 $time=$request->punch_time==''?date('H:i:s'):$request->punch_time;
    //                 $attn_type='past';
    //             }else{
    //                 $time=date('H:i:s');
    //                 $attn_type='present';
    //             }
                
            
    //         if($request->image!=''){
    //             $s3_path="attendance/".trim($request->attend_date)."/";
    //             $folderPath = "volume_blr1_01/".trim($request->attend_date)."/";
    //             $base64Image = explode(";base64,", $request->image);
    //             $explodeImage = explode("image/", $base64Image[0]);
    //             $imageType = $explodeImage[1];
    //             $image_base64 = base64_decode($base64Image[1]);
    //             $file = $folderPath . uniqid() . '.'.$imageType;
    //             if (!file_exists($folderPath)){
    //             mkdir($folderPath);
    //             }
    //             file_put_contents($file, $image_base64);
    //             //dd('end');
    //             $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
    //             $filename = basename($path);
    //             $input['file'] = trim($request->member_code)."_".$request->attend_date."_".time().'.jpg';

    //             $imgFile = Image::make($path)->resize(200, 200, function ($constraint) {
    //                 $constraint->aspectRatio();
    //             });
                
    //             // Save the resized image temporarily in a local folder (if needed)
    //             $tempPath = public_path($folderPath . $input['file']);
    //             $imgFile->save($tempPath);
                
    //             // Upload the resized image to S3
    //             Storage::disk('s3_1')->put($s3_path.$input['file'], file_get_contents($tempPath), [
    //                 'ContentType' => mime_content_type($tempPath),
    //             ]);

                
                
    //             // Optionally, remove the local temporary file
    //             unlink($tempPath);
    //             unlink($file);
    //         }else{
    //             $input['file']='NA'; 
    //         }    

           

    //         $incount = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->count();

    //         if($incount==0){

    //             // $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

    //             $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
    //             $user_id= $request->user_id;

    //             $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
    //             if(str_starts_with($users[0]->member_code, 'AF')){
    //                 $member_type='student';
    //             }else{
    //                 $member_type='staff';
    //             }

                

    //             $lastId=DB::table('attendances')->insertGetId([
    //                 'user_id'         => $user_id,
    //                 'atten_date'      => $request->attend_date,
    //                 'punch_in'        => $time,
    //                 'lat'             => $request->lat,
    //                 'long'            => $request->long,
    //                 'member_id'       => $member_id,
    //                 'member_code'     => $request->member_code,
    //                 'member_type'     => $member_type,
    //                 'transfer_status' => 1,
    //                 'atten_type'      => $attn_type,
    //                 'status'          => 2,
    //                 'atten_image'     => $input['file'],
    //                 'punch_in_place'  => $request->location,
    //                 'reason'          => $request->reason,
    //                 'bulk_type'       => 0,
    //                 'app_version'     => $request->app_version,
    //                 'created_at'      => now(),
    //                 'updated_at'      => now(),
    //             ]);
                

    //             Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
                
    //             $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                 'user_id_mob_app' => $user_id,
    //                 'atten_date' => $request->attend_date,
    //                 'punch_time' => $time,
    //                 'lat' => $request->lat,
    //                 'long' => $request->long,
    //                 'member_id' => $member_id,
    //                 'member_code' => $request->member_code,
    //                 'status' => 2,
    //                 'punch_place' => $request->location,
    //                 'atten_type' => $atten_type,
    //                 'member_type' => $member_type,
    //                 'reason' => $request->reason,
    //                 'center_id' => $center_id,///
    //                 'punch_type' =>"I",
    //                 'photo' => $input['file'],
    //                 'batch_code' => $batch_code,/////
    //                 'update_attn_status' => 1,
    //                 'bulk_type' => 0,
    //             ]);

    //             $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                 array(
    //                     'source' => 'mobile_student',
    //                     'mobile_app_id' => $mob_id,
    //                     'member_id'=>$member_id,
    //                     'member_type'=>'student',
    //                     'punch_type'=>"I",
    //                     'flag_value'=>1,
    //                     'punch_time'=>$request->attend_date." ".$time,
    //                     'onetime'=>1,
    //                     'created_at'=>now(),
    //                     'attd_month'=>'All',
    //                 )
    //             );

    //             // $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //             //     array(
    //             //         'source' => 'mobile_trainer',
    //             //         'mobile_app_id' => $mob_id,
    //             //         'member_id'=>$member_id,
    //             //         'member_type'=>'student',
    //             //         'punch_type'=>"I",
    //             //         'flag_value'=>1,
    //             //         'punch_time'=>$request->attend_date." ".$time,
    //             //         'onetime'=>1,
    //             //         'created_at'=>now(),
    //             //         'attd_month'=>'All',
    //             //     )
    //             // );
                
                
                

    //         }else{
                

    //             $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
    //             $user_id= $request->user_id;
                
    //             $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
    //             if(str_starts_with($request->member_code, 'AF')){
    //                 $member_type='student';
    //             }else{
    //                 $member_type='staff';
    //             }

    //             $studenttime=Attendance::where('atten_date',$request->attend_date)->where('user_id',$user_id)->get(['punch_in','punch_out','bulk_type','status']);

    //             //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

    //                 if($time<$studenttime[0]->punch_in){
    //                     //dd("dd",$user_id);
    //                     //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){
    //                     Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_in'=>$time,'punch_out_place'=>$request->location]);
    //                     //}
    //                 }else{
                        
    //                     // Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_place'=>$request->location]);

    //                     $checkOutTime=Attendance::where('user_id',$user_id)->where('atten_date',$request->attend_date)->value('punch_out');

    //                     if($time>$checkOutTime){

    //                         //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

    //                             Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>0,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long]);
    //                         //}
    //                     }
    //                 }
    //             //}    

                

    //             $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->get();
    //             //dd($details,$user_id);
    //             Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($users[0]->member_code)]);

    //             $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->value('punch_time');
    //             //dd($checkInTime,$user_id);
    //             //if($details[0]->bulk_type!=1 && $details[0]->status!=1){
    //                 if($checkInTime>$time){
                    
    //                         $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>0]);

    //                         $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->value('id');

    //                             $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                                 array(
    //                                     'source' => 'mobile_student',
    //                                     'mobile_app_id' => $mob_id,
    //                                     'member_id'=>$member_id,
    //                                     'member_type'=>'student',
    //                                     'punch_type'=>"I",
    //                                     'flag_value'=>1,
    //                                     'punch_time'=>$request->attend_date." ".$time,
    //                                     'onetime'=>1,
    //                                     'created_at'=>now(),
    //                                     'attd_month'=>'All',
    //                                 )
    //                             );
                       
    //                 }else{
                        
    //                     $checkOutTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->value('punch_time');
                        
                    
    //                         if($time>$checkOutTime){
                                
    //                             DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                                 'member_id'=>$member_id,
    //                                 'atten_date'=>$request->attend_date,
    //                                 'punch_type'=>"O"
    //                             ],[
    //                                 'user_id_mob_app' => $user_id,
    //                                 'atten_date' => $request->attend_date,
    //                                 'punch_time' => $time,
    //                                 'lat' => $request->lat,
    //                                 'long' => $request->long,
    //                                 'member_id' => $member_id,
    //                                 'member_code' => $users[0]->member_code,
    //                                 'status' => 2,
    //                                 'punch_place' => $request->location,
    //                                 'atten_type' => $atten_type,
    //                                 'member_type' => $member_type,
    //                                 'reason' => $request->reason,
    //                                 'center_id' => $center_id,
    //                                 'punch_type' =>"O",
    //                                 'bulk_type' =>0,
    //                                 'photo' => $input['file'],
    //                                 'batch_code' => $batch_code,
    //                                 'update_attn_status' => 1,
    //                             ]);

    //                             $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->value('id');

    //                             $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                                 array(
    //                                     'source' => 'mobile_student',
    //                                     'mobile_app_id' => $mob_id,
    //                                     'member_id'=>$member_id,
    //                                     'member_type'=>'student',
    //                                     'punch_type'=>"O",
    //                                     'flag_value'=>1,
    //                                     'punch_time'=>$request->attend_date." ".$time,
    //                                     'onetime'=>1,
    //                                     'created_at'=>now(),
    //                                     'attd_month'=>'All',
    //                                 )
    //                             );
    //                         }
                        
    //                     // else{
                            
    //                     //     $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                     //         'user_id_mob_app' => $user_id,
    //                     //         'atten_date' => $request->attend_date,
    //                     //         'punch_time' => $time,
    //                     //         'lat' => $request->lat,
    //                     //         'long' => $request->long,
    //                     //         'member_id' => $member_id,
    //                     //         'member_code' => $users[0]->member_code,
    //                     //         'status' => 2,
    //                     //         'punch_place' => $request->location,
    //                     //         'atten_type' => $atten_type,
    //                     //         'member_type' => $member_type,
    //                     //         'reason' => $request->reason,
    //                     //         'center_id' => $request->center_id,
    //                     //         'punch_type' =>"O",
    //                     //         'photo' => $input['file'],
    //                     //         'batch_code' => $request->batch_code,
    //                     //         'update_attn_status' => 1,
    //                     //     ]);
    //                     //     $mobile_id=$mob_id;

    //                     // }

    //                 }
    //             //}    
                
    //             // $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->get(['id']);
                
    //             // if(sizeof($mob_id)>0){
    //             //     $mobile_id=$mob_id[0]->id;
    //             //     DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //             //         'id' => $mob_id[0]->id,
    //             //     ],[
    //             //         'user_id_mob_app' => $user_id,
    //             //         'atten_date' => $request->attend_date,
    //             //         'punch_time' => $time,
    //             //         'lat' => $request->lat,
    //             //         'long' => $request->long,
    //             //         'member_id' => $member_id,
    //             //         'member_code' => $users[0]->member_code,
    //             //         'status' => 1,
    //             //         'punch_place' => $request->location,
    //             //         'atten_type' => $atten_type,
    //             //         'member_type' => $member_type,
    //             //         'reason' => $request->reason,
    //             //         'center_id' => $request->center_id,
    //             //         'punch_type' =>"O",
    //             //         'photo' => $input['file'],
    //             //         'batch_code' => $request->batch_code,
    //             //         'update_attn_status' => 1,
    //             //         'bulk_type' => 1,
    //             //         'approve_by' => $trainer_id,
    //             //         'approve_at' => now(),
    //             //     ]);
    //             //     //dd($mob_id);
                    
    //             // }else{
    //                 // $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                 //     'user_id_mob_app' => $user_id,
    //                 //     'atten_date' => $request->attend_date,
    //                 //     'punch_time' => $time,
    //                 //     'lat' => $request->lat,
    //                 //     'long' => $request->long,
    //                 //     'member_id' => $member_id,
    //                 //     'member_code' => $users[0]->member_code,
    //                 //     'status' => 2,
    //                 //     'punch_place' => $request->location,
    //                 //     'atten_type' => $atten_type,
    //                 //     'member_type' => $member_type,
    //                 //     'reason' => $request->reason,
    //                 //     'center_id' => $request->center_id,
    //                 //     'punch_type' =>"O",
    //                 //     'photo' => $input['file'],
    //                 //     'batch_code' => $request->batch_code,
    //                 //     'update_attn_status' => 1,
    //                 // ]);
    //                 // $mobile_id=$mob_id;
    //         }
    //         // $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
    //         // if(sizeof($details)>0){
    //         //     //dd($details[0]->id);
    //         //     $sts=Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->get(['status','punch_out']);
    //         //     //dd($sts);
    //         //     if (
    //         //         ($sts[0]->status != 1 && $sts[0]->status != 3) ||
    //         //         ($sts[0]->status == 1 && $sts[0]->punch_out == null)
    //         //     ) { 

                    
    //         //         $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
    //         //         curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
    //         //         curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //         //         $curlResponse = curl_exec($curlHandle);
    //         //         //dd($curlResponse);
    //         //         curl_close($curlHandle);
    //         //         Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

    //         //         Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

    //         //         $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
    //         //     } 
    //         //     // if($sts[0]->status==1 && $sts[0]->punch_out==null){

    //         //     //     $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
    //         //     //     curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
    //         //     //     curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //         //     //     $curlResponse = curl_exec($curlHandle);
    //         //     //     //dd($curlResponse);
    //         //     //     curl_close($curlHandle);
    //         //     //     Attendance::where('atten_date', $request->attend_date)->where('user_id', $details[0]->user_id)->update(['punch_out'=>$time,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long,'status'=>0,'punch_out_place'=>$request->location]);

    //         //     //     Photo::create(['user_id' => $request->user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);

    //         //     //     $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];

    //         //     // }  

    //         //     DB::commit();
    //         //     $x=['punch_out'=>$time,'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
    //         //         return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
    //         // }
    //         //code for update end
            
    //         // $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
    //         // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
    //         // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //         // $curlResponse = curl_exec($curlHandle);
    //         // //dd($curlResponse);
    //         // // if(){
                 
    //         // // }
    //         // if(curl_errno($curl)) {
    //         //     $postParameter['transfer_status']=0;
    //         // }

    //         // $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //         //     'user_id_mob_app' => $request->user_id,
    //         //     'atten_date' => $request->attend_date,
    //         //     'punch_time' => $time,
    //         //     'lat' => $request->lat,
    //         //     'long' => $request->long,
    //         //     'member_id' => $request->member_id,
    //         //     'member_code' => $request->member_code,
    //         //     'status' => 2,
    //         //     'punch_place' => $request->location,
    //         //     'atten_type' => $atten_type,
    //         //     'member_type' => $member_type,
    //         //     'reason' => $request->reason,
    //         //     'center_id' => $request->center_id,
    //         //     'punch_type' =>"I",
    //         //     'photo' => $input['file'],
    //         //     'batch_code' => $request->batch_code,
    //         //     'update_attn_status' => 1,
                
    //         // ]);
    //         // //dd($postParameter);
    //         // $lastId=Attendance::create($postParameter)->id;
    //         // Photo::create(['user_id' => $request->user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
    //         // curl_close($curlHandle);
    //         $punch = Attendance::where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
    //         $x=['punch_in'=>$punch[0]->punch_in,'punch_out'=>$punch[0]->punch_out,'date' => $request->attend_date];
    //         DB::commit();
    //         return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

    //     } catch (Exception $e) { 
    //         DB::rollback();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    public function storeAttendance(Request $request)
    {
        
       DB::beginTransaction();
        try { 
            date_default_timezone_set('Asia/Kolkata');
            $member_id=$request->member_id;
            $batchdata = DB::connection('mysql_2')->table('enrollments as a')
                        ->join('batches as b', 'a.batch_id', '=', 'b.id')
                        ->where('a.member_id', $member_id)
                        ->orderByDesc('a.id')
                        ->get(['a.batch_id', 'b.center_id', 'b.batch_code']);
            if(sizeof($batchdata)==0){
              $center_id=$request->center_id;
              $batch_code=$request->batch_code;
            }else{
              $center_id=$batchdata[0]->center_id;
              $batch_code=$batchdata[0]->batch_code;
            }            
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

           

            $incount = DB::table('student_attendances')->where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->count();

            if($incount==0){

                // $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

                $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                $user_id= $request->user_id;

                $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
                if(str_starts_with($users[0]->member_code, 'AF')){
                    $member_type='student';
                }else{
                    $member_type='staff';
                }

                

                $lastId=DB::table('student_attendances')->insertGetId([
                    'user_id'         => $user_id,
                    'atten_date'      => $request->attend_date,
                    'punch_in'        => $time,
                    'lat'             => $request->lat,
                    'long'            => $request->long,
                    'member_id'       => $member_id,
                    'member_code'     => $request->member_code,
                    'member_type'     => $member_type,
                    'transfer_status' => 1,
                    'atten_type'      => $attn_type,
                    'status'          => 2,
                    'atten_image'     => $input['file'],
                    'punch_in_place'  => $request->location,
                    'reason'          => $request->reason,
                    'bulk_type'       => 0,
                    'app_version'     => $request->app_version,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                

                DB::table('student_photos')->insertGetId(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($request->member_code)]);
                
                $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->insertGetId([
                    'user_id_mob_app' => $user_id,
                    'atten_date' => $request->attend_date,
                    'punch_time' => $time,
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'member_id' => $member_id,
                    'member_code' => $request->member_code,
                    'status' => 2,
                    'punch_place' => $request->location,
                    'atten_type' => $atten_type,
                    'member_type' => $member_type,
                    'reason' => $request->reason,
                    'center_id' => $center_id,///
                    'punch_type' =>"I",
                    'photo' => $input['file'],
                    'batch_code' => $batch_code,/////
                    'update_attn_status' => 1,
                    'bulk_type' => 0,
                ]);

                $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                    array(
                        'source' => 'mobile_student',
                        'mobile_app_id' => $mob_id,
                        'member_id'=>$member_id,
                        'member_type'=>'student',
                        'punch_type'=>"I",
                        'flag_value'=>1,
                        'punch_time'=>$request->attend_date." ".$time,
                        'onetime'=>1,
                        'created_at'=>now(),
                        'attd_month'=>'All',
                    )
                );

                
                
                
                

            }else{
                

                $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                $user_id= $request->user_id;
                
                $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
                if(str_starts_with($request->member_code, 'AF')){
                    $member_type='student';
                }else{
                    $member_type='staff';
                }

                $studenttime=DB::table('student_attendances')->where('atten_date',$request->attend_date)->where('user_id',$user_id)->get(['punch_in','punch_out','bulk_type','status']);

                //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

                    if($time<$studenttime[0]->punch_in){
                        //dd("dd",$user_id);
                        //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){
                            DB::table('student_attendances')->where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_in'=>$time,'punch_out_place'=>$request->location]);
                        //}
                    }else{
                        
                        // Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_place'=>$request->location]);

                        $checkOutTime=DB::table('student_attendances')->where('user_id',$user_id)->where('atten_date',$request->attend_date)->value('punch_out');

                        if($time>$checkOutTime){

                            //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

                            DB::table('student_attendances')->where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>0,'punch_out_lat'=>$request->lat,'punch_out_long'=>$request->long]);
                            //}
                        }
                    }
                //}    

                

                $details = DB::table('student_attendances')->where('atten_date', $request->attend_date)->where('user_id', $user_id)->get();
                //dd($details,$user_id);
                DB::table('student_photos')->insertGetId(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($users[0]->member_code)]);

                $checkInTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->value('punch_time');
                //dd($checkInTime,$user_id);
                //if($details[0]->bulk_type!=1 && $details[0]->status!=1){
                    if($time<$checkInTime){
                    
                            $checkInTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>0]);

                            $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('punch_type','I')->where('atten_date',$request->attend_date)->value('id');

                                $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                    array(
                                        'source' => 'mobile_student',
                                        'mobile_app_id' => $mob_id,
                                        'member_id'=>$member_id,
                                        'member_type'=>'student',
                                        'punch_type'=>"I",
                                        'flag_value'=>1,
                                        'punch_time'=>$request->attend_date." ".$time,
                                        'onetime'=>1,
                                        'created_at'=>now(),
                                        'attd_month'=>'All',
                                    )
                                );
                       
                    }else{
                        
                        $checkOutTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->value('punch_time');
                        
                    
                        if($time>$checkOutTime){
                            
                            DB::connection('mysql_2')->table('attendance_app_student')->updateOrInsert([
                                'member_id'=>$member_id,
                                'atten_date'=>$request->attend_date,
                                'punch_type'=>"O"
                            ],[
                                'user_id_mob_app' => $user_id,
                                'atten_date' => $request->attend_date,
                                'punch_time' => $time,
                                'lat' => $request->lat,
                                'long' => $request->long,
                                'member_id' => $member_id,
                                'member_code' => $users[0]->member_code,
                                'status' => 2,
                                'punch_place' => $request->location,
                                'atten_type' => $atten_type,
                                'member_type' => $member_type,
                                'reason' => $request->reason,
                                'center_id' => $center_id,
                                'punch_type' =>"O",
                                'bulk_type' =>0,
                                'photo' => $input['file'],
                                'batch_code' => $batch_code,
                                'update_attn_status' => 1,
                            ]);

                            $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->value('id');

                            $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                array(
                                    'source' => 'mobile_student',
                                    'mobile_app_id' => $mob_id,
                                    'member_id'=>$member_id,
                                    'member_type'=>'student',
                                    'punch_type'=>"O",
                                    'flag_value'=>1,
                                    'punch_time'=>$request->attend_date." ".$time,
                                    'onetime'=>1,
                                    'created_at'=>now(),
                                    'attd_month'=>'All',
                                )
                            );
                        }
                        
                        

                    }
                //}    
                
                
            }
            
            $punch = DB::table('student_attendances')->where('atten_date', $request->attend_date)->where('user_id', $request->user_id)->get();
            $x=['punch_in'=>$punch[0]->punch_in,'punch_out'=>$punch[0]->punch_out,'date' => $request->attend_date];
            DB::commit();
            return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }


    // public function offlineSync(Request $request)
    // {
        
    //    DB::beginTransaction();
    //     try { 
    //         //return $request->details;
    //         //
    //         date_default_timezone_set('Asia/Kolkata');
            
    //         foreach($request->details as $x){
              
                

    //                 if(str_starts_with($x['member_code'], 'AF')){
    //                 $member_type='student';
    //                 }else{
    //                 $member_type='staff';
    //                 }
    //                 $member_id=$x['member_id'];
    //                 $attn_type=$x['attn_type'];

    //                 $batchdata = DB::connection('mysql_2')->table('enrollments as a')
    //                     ->join('batches as b', 'a.batch_id', '=', 'b.id')
    //                     ->where('a.member_id', $member_id)
    //                     ->orderByDesc('a.id')
    //                     ->get(['a.batch_id', 'b.center_id', 'b.batch_code']);
    //                 if(sizeof($batchdata)==0){
    //                     $center_id=$x['center_id'];
    //                     $batch_code=$x['batch_code'];
    //                 }else{
    //                     $center_id=$batchdata[0]->center_id;
    //                     $batch_code=$batchdata[0]->batch_code;
    //                 }  
                    
    //                 $details1 = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->get();
                    
    //                 if($x['image']!=''){
    //                     $s3_path="attendance/".trim($x['attend_date'])."/";
    //                     $folderPath = "volume_blr1_01/".trim($x['attend_date'])."/";
    //                     $base64Image = explode(";base64,", $x['image']);
    //                     $explodeImage = explode("image/", $base64Image[0]);
    //                     $imageType = $explodeImage[1];
    //                     $image_base64 = base64_decode($base64Image[1]);
    //                     $file = $folderPath . uniqid() . '.'.$imageType;
    //                     if (!file_exists($folderPath)){
    //                     mkdir($folderPath);
    //                     }
    //                     file_put_contents($file, $image_base64);
    //                     //dd('end');
    //                     $path = 'https://attendanceapi.anudip.org/'.$file;//need some changes
    //                     $filename = basename($path);
    //                     $input['file'] = trim($x['member_code'])."_".$x['attend_date']."_".time().'.jpg';

    //                     $imgFile = Image::make($path)->resize(200, 200, function ($constraint) {
    //                         $constraint->aspectRatio();
    //                     });
                        
    //                     // Save the resized image temporarily in a local folder (if needed)
    //                     $tempPath = public_path($folderPath . $input['file']);
    //                     $imgFile->save($tempPath);
                        
    //                     // Upload the resized image to S3
    //                     Storage::disk('s3_1')->put($s3_path.$input['file'], file_get_contents($tempPath), [
    //                         'ContentType' => mime_content_type($tempPath),
    //                     ]);

                        
                        
    //                     // Optionally, remove the local temporary file
    //                     unlink($tempPath);
    //                     unlink($file);
    //                 }else{
    //                     $input['file']='NA'; 
    //                 }    
    //                 //$attn_type='present';
    //                 // $postParameter = ['user_id' => $x['user_id'],'atten_date' => $x['attend_date'],'punch_in'=>$x['punch_in'],'punch_out'=>$x['punch_out'],'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$x['member_id'],'member_code'=>$x['member_code'],'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'bulk_type'=>0,'app_version'=>$request->app_version];
                     
    //                 // if($x['punch_out']==null){
    //                 //     //dd('k');
    //                 //     $punch_in=Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->value('punch_in');
    //                 //     if($x['punch_in']<$punch_in){
    //                 //         Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->delete();
    //                 //         $lastId=Attendance::create($postParameter)->id;
    //                 //         Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_in'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

    //                 //         DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //             'member_id' => $x['member_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_type'=>"I",
    //                 //         ],[
    //                 //             'user_id_mob_app' => $x['user_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_time'=>$x['punch_in'],
    //                 //             'lat'=>$x['lat'],
    //                 //             'long'=>$x['long'],
    //                 //             'member_id'=>$x['member_id'],
    //                 //             'member_code'=>$x['member_code'],
    //                 //             'status'=>0,
    //                 //             'punch_place'=>'',
    //                 //             'atten_type'=>$attn_type,
    //                 //             'member_type'=>$member_type,
    //                 //             'reason'=>$x['reason'],
    //                 //             'center_id'=>$x['center_id'],
    //                 //             'punch_type'=>"I",
    //                 //             'photo'=>$input['file'],
    //                 //             'batch_code'=>$x['batch_code'],
    //                 //             'update_attn_status'=>0,
    //                 //         ]);
    //                 //     }    


    //                 // }else{
    //                 //     //dd($details1[0]->user_id);
    //                 //     if(sizeof($details1)>0){

    //                 //         //if($details1[0]->status!=1 && $details1[0]->status!=3){
    //                 //         $sts=Attendance::where('atten_date', $x['attend_date'])->where('user_id', $details1[0]->user_id)->get(['status','punch_out']);
    //                 //         //dd($sts,$details1[0]->user_id,$request->attend_date);
    //                 //         if(($sts[0]->status != 1 && $sts[0]->status != 3) ||
    //                 //         ($sts[0]->status == 1 && $sts[0]->punch_out == null)){

                                

    //                 //             Attendance::where('atten_date', $x['attend_date'])->where('user_id', $details1[0]->user_id)->update(['punch_in'=>$x['punch_in'],'punch_out'=>$x['punch_out'],'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long'],'status'=>0,'punch_out_place'=>'','atten_type'=>$attn_type,'reason'=>$x['reason']]);

    //                 //             Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$details1[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_out'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

    //                 //             DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //                 'member_id' => $x['member_id'],
    //                 //                 'atten_date' => $x['attend_date'],
    //                 //                 'punch_type'=>"O",
    //                 //             ],[
    //                 //                 'user_id_mob_app' => $x['user_id'],
    //                 //                 'atten_date' => $x['attend_date'],
    //                 //                 'punch_time'=>$x['punch_out'],
    //                 //                 'lat'=>$x['lat'],
    //                 //                 'long'=>$x['long'],
    //                 //                 'member_id'=>$x['member_id'],
    //                 //                 'member_code'=>$x['member_code'],
    //                 //                 'status'=>0,
    //                 //                 'punch_place'=>'',
    //                 //                 'atten_type'=>$attn_type,
    //                 //                 'member_type'=>$member_type,
    //                 //                 'reason'=>$x['reason'],
    //                 //                 'center_id'=>$x['center_id'],
    //                 //                 'punch_type'=>"O",
    //                 //                 'photo'=>$input['file'],
    //                 //                 'batch_code'=>$x['batch_code'],
    //                 //                 'update_attn_status'=>0,
    //                 //             ]);
    //                 //         }    

    //                 //     }else{
    //                 //         Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->delete();
    //                 //         $postParameter['status']=0;
    //                 //         $lastId=Attendance::create($postParameter)->id;
    //                 //         Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_in'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);
                            
    //                 //         Photo::create(['user_id' => $x['user_id'],'attendance_id'=>$lastId,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$x['punch_out'],'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);

    //                 //         DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //             'member_id' => $x['member_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_type'=>"I",
    //                 //         ],[
    //                 //             'user_id_mob_app' => $x['user_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_time'=>$x['punch_in'],
    //                 //             'lat'=>$x['lat'],
    //                 //             'long'=>$x['long'],
    //                 //             'member_id'=>$x['member_id'],
    //                 //             'member_code'=>$x['member_code'],
    //                 //             'status'=>0,
    //                 //             'punch_place'=>'',
    //                 //             'atten_type'=>$attn_type,
    //                 //             'member_type'=>$member_type,
    //                 //             'reason'=>$x['reason'],
    //                 //             'center_id'=>$x['center_id'],
    //                 //             'punch_type'=>"I",
    //                 //             'photo'=>$input['file'],
    //                 //             'batch_code'=>$x['batch_code'],
    //                 //             'update_attn_status'=>0,
    //                 //         ]);
    //                 //         DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //             'member_id' => $x['member_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_type'=>"O",
    //                 //         ],[
    //                 //             'user_id_mob_app' => $x['user_id'],
    //                 //             'atten_date' => $x['attend_date'],
    //                 //             'punch_time'=>$x['punch_out'],
    //                 //             'lat'=>$x['lat'],
    //                 //             'long'=>$x['long'],
    //                 //             'member_id'=>$x['member_id'],
    //                 //             'member_code'=>$x['member_code'],
    //                 //             'status'=>0,
    //                 //             'punch_place'=>'',
    //                 //             'atten_type'=>$attn_type,
    //                 //             'member_type'=>$member_type,
    //                 //             'reason'=>$x['reason'],
    //                 //             'center_id'=>$x['center_id'],
    //                 //             'punch_type'=>"O",
    //                 //             'photo'=>$input['file'],
    //                 //             'batch_code'=>$x['batch_code'],
    //                 //             'update_attn_status'=>0,
    //                 //         ]);
                            
    //                 //     }
    //                 // }

    //                 if($x['punch_out']==''){
    //                   $time=$x['punch_in'];
    //                 }else{
    //                     $time=$x['punch_out'];
    //                 }

    //                 $incount = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->count();

    //                 if($incount==0){

    //                     // $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

    //                     $users=DB::table('users')->where('member_id', $x['member_id'])->get(['id','member_code']);
    //                     $user_id= $x['user_id'];

    //                     $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
    //                     if(str_starts_with($x['member_code'], 'AF')){
    //                         $member_type='student';
    //                     }else{
    //                         $member_type='staff';
    //                     }

                        

    //                     $lastId=DB::table('attendances')->insertGetId([
    //                         'user_id'         => $user_id,
    //                         'atten_date'      => $x['attend_date'],
    //                         'punch_in'        => $time,
    //                         'lat'             => $x['lat'],
    //                         'long'            => $x['long'],
    //                         'member_id'       => $member_id,
    //                         'member_code'     => $x['member_code'],
    //                         'member_type'     => $member_type,
    //                         'transfer_status' => 1,
    //                         'atten_type'      => $attn_type,
    //                         'status'          => 2,
    //                         'atten_image'     => $input['file'],
    //                         'punch_in_place'  => '',
    //                         'reason'          => $x['reason'],
    //                         'bulk_type'       => 0,
    //                         'app_version'     => '1.0.1 (5)',
    //                         'created_at'      => now(),
    //                         'updated_at'      => now(),
    //                     ]);
                        

    //                     Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);
                        
    //                     $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                         'user_id_mob_app' => $user_id,
    //                         'atten_date' => $x['attend_date'],
    //                         'punch_time' => $time,
    //                         'lat' => $x['lat'],
    //                         'long' => $x['long'],
    //                         'member_id' => $member_id,
    //                         'member_code' => $x['member_code'],
    //                         'status' => 2,
    //                         'punch_place' => '',
    //                         'atten_type' => $atten_type,
    //                         'member_type' => $member_type,
    //                         'reason' => $x['reason'],
    //                         'center_id' => $center_id,///
    //                         'punch_type' =>"I",
    //                         'photo' => $input['file'],
    //                         'batch_code' => $batch_code,/////
    //                         'update_attn_status' => 1,
    //                         'bulk_type' => 0,
    //                     ]);

    //                     $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                         array(
    //                             'source' => 'mobile_student',
    //                             'mobile_app_id' => $mob_id,
    //                             'member_id'=>$member_id,
    //                             'member_type'=>'student',
    //                             'punch_type'=>"I",
    //                             'flag_value'=>1,
    //                             'punch_time'=>$x['attend_date']." ".$time,
    //                             'onetime'=>1,
    //                             'created_at'=>now(),
    //                             'attd_month'=>'All',
    //                         )
    //                     );

    //                     // $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                     //     array(
    //                     //         'source' => 'mobile_trainer',
    //                     //         'mobile_app_id' => $mob_id,
    //                     //         'member_id'=>$member_id,
    //                     //         'member_type'=>'student',
    //                     //         'punch_type'=>"I",
    //                     //         'flag_value'=>1,
    //                     //         'punch_time'=>$request->attend_date." ".$time,
    //                     //         'onetime'=>1,
    //                     //         'created_at'=>now(),
    //                     //         'attd_month'=>'All',
    //                     //     )
    //                     // );
                        
                        
                        

    //                 }else{

    //                     $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
    //                     $user_id= $x['user_id'];

    //                     $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
    //                     if(str_starts_with($x['member_code'], 'AF')){
    //                         $member_type='student';
    //                     }else{
    //                         $member_type='staff';
    //                     }

    //                     $studenttime=Attendance::where('atten_date',$x['attend_date'])->where('user_id',$user_id)->get(['punch_in','punch_out','bulk_type','status']);

    //                     //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

    //                         if($time<$studenttime[0]->punch_in){
    //                             Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_in'=>$time,'status'=>1,'punch_out_place'=>'']);
    //                         }else{
    //                             // Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_place'=>$x['location']]);

    //                             $checkOutTime=Attendance::where('user_id',$user_id)->where('atten_date',$x['attend_date'])->value('punch_out');

    //                             if($time>$checkOutTime){

    //                                 Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>0,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long']]);
    //                             }
    //                         }
    //                     //}    

                        

    //                     $details = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();

    //                     Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);

    //                     $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->value('punch_time');

                        
    //                    //if($details[0]->bulk_type!=1 && $details[0]->status!=1){

    //                         if($checkInTime>$time){
    //                             $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>0]);

    //                             $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

    //                             $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                                 array(
    //                                     'source' => 'mobile_student',
    //                                     'mobile_app_id' => $mob_id,
    //                                     'member_id'=>$member_id,
    //                                     'member_type'=>'student',
    //                                     'punch_type'=>"I",
    //                                     'flag_value'=>1,
    //                                     'punch_time'=>$x['attend_date']." ".$time,
    //                                     'onetime'=>1,
    //                                     'created_at'=>now(),
    //                                     'attd_month'=>'All',
    //                                 )
    //                             );
    //                         }else{

    //                             $checkOutTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type',"O")->value('punch_time');
    //                             if($time>$checkOutTime){
    //                                 DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                                     'member_id'=>$member_id,
    //                                     'atten_date'=>$x['attend_date'],
    //                                     'punch_type'=>"O"
    //                                 ],[
                                    
    //                                     'punch_time' => $time,
    //                                     'user_id_mob_app' => $user_id,
    //                                     'atten_date' => $x['attend_date'],
    //                                     'punch_time' => $time,
    //                                     'lat' => $x['lat'],
    //                                     'long' => $x['long'],
    //                                     'member_id' => $member_id,
    //                                     'member_code' => $users[0]->member_code,
    //                                     'status' => 2,
    //                                     'punch_place' => '',
    //                                     'atten_type' => $atten_type,
    //                                     'member_type' => $member_type,
    //                                     'reason' => $x['reason'],
    //                                     'center_id' => $center_id,
    //                                     'punch_type' =>"O",
    //                                     'photo' => $input['file'],
    //                                     'batch_code' => $batch_code,
    //                                     'update_attn_status' => 1,
    //                                     'bulk_type'=>0
                                        
    //                                 ]);

    //                                 $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

    //                                 $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                                     array(
    //                                         'source' => 'mobile_student',
    //                                         'mobile_app_id' => $mob_id,
    //                                         'member_id'=>$member_id,
    //                                         'member_type'=>'student',
    //                                         'punch_type'=>"O",
    //                                         'flag_value'=>1,
    //                                         'punch_time'=>$x['attend_date']." ".$time,
    //                                         'onetime'=>1,
    //                                         'created_at'=>now(),
    //                                         'attd_month'=>'All',
    //                                     )
    //                                 );
    //                             }
    //                             // else{

    //                             //     $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                             //         'user_id_mob_app' => $user_id,
    //                             //         'atten_date' => $x['attend_date'],
    //                             //         'punch_time' => $time,
    //                             //         'lat' => $x['lat'],
    //                             //         'long' => $x['long'],
    //                             //         'member_id' => $member_id,
    //                             //         'member_code' => $users[0]->member_code,
    //                             //         'status' => 2,
    //                             //         'punch_place' => $x['location'],
    //                             //         'atten_type' => $atten_type,
    //                             //         'member_type' => $member_type,
    //                             //         'reason' => $x['reason'],
    //                             //         'center_id' => $x['center_id'],
    //                             //         'punch_type' =>"O",
    //                             //         'photo' => $input['file'],
    //                             //         'batch_code' => $x['batch_code'],
    //                             //         'update_attn_status' => 1,
    //                             //     ]);
    //                             //     $mobile_id=$mob_id;

    //                             // }

    //                         }
    //                     //}    
                        
                        
    //                     // $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                     //     'user_id_mob_app' => $user_id,
    //                     //     'atten_date' => $request->attend_date,
    //                     //     'punch_time' => $time,
    //                     //     'lat' => $request->lat,
    //                     //     'long' => $request->long,
    //                     //     'member_id' => $member_id,
    //                     //     'member_code' => $users[0]->member_code,
    //                     //     'status' => 2,
    //                     //     'punch_place' => $request->location,
    //                     //     'atten_type' => $atten_type,
    //                     //     'member_type' => $member_type,
    //                     //     'reason' => $request->reason,
    //                     //     'center_id' => $request->center_id,
    //                     //     'punch_type' =>"O",
    //                     //     'photo' => $input['file'],
    //                     //     'batch_code' => $request->batch_code,
    //                     //     'update_attn_status' => 1,
    //                     // ]);
    //                     // $mobile_id=$mob_id;
    //                 }

    //                 // $incount = Attendance::where('atten_date', $x->attend_date)->where('user_id', $request->user_id)->count();

    //                 // if($incount==0){

    //                 //     // $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

    //                 //     $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
    //                 //     $user_id= $users[0]->id;

    //                 //     $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
    //                 //     if(str_starts_with($users[0]->member_code, 'AF')){
    //                 //         $member_type='student';
    //                 //     }else{
    //                 //         $member_type='staff';
    //                 //     }

                        

    //                 //     $lastId=DB::table('attendances')->insertGetId([
    //                 //         'user_id'         => $user_id,
    //                 //         'atten_date'      => $request->attend_date,
    //                 //         'punch_in'        => $time,
    //                 //         'lat'             => $request->lat,
    //                 //         'long'            => $request->long,
    //                 //         'member_id'       => $member_id,
    //                 //         'member_code'     => $users[0]->member_code,
    //                 //         'member_type'     => $member_type,
    //                 //         'transfer_status' => 1,
    //                 //         'atten_type'      => $attn_type,
    //                 //         'status'          => 2,
    //                 //         'atten_image'     => $input['file'],
    //                 //         'punch_in_place'  => $request->location,
    //                 //         'reason'          => $request->reason,
    //                 //         'bulk_type'       => 0,
    //                 //         'app_version'     => $request->app_version,
    //                 //         'created_at'      => now(),
    //                 //         'updated_at'      => now(),
    //                 //     ]);
                        

    //                 //     Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($users[0]->member_code)]);
                        
    //                 //     $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                 //         'user_id_mob_app' => $user_id,
    //                 //         'atten_date' => $request->attend_date,
    //                 //         'punch_time' => $time,
    //                 //         'lat' => $request->lat,
    //                 //         'long' => $request->long,
    //                 //         'member_id' => $member_id,
    //                 //         'member_code' => $users[0]->member_code,
    //                 //         'status' => 1,
    //                 //         'punch_place' => $request->location,
    //                 //         'atten_type' => $atten_type,
    //                 //         'member_type' => $member_type,
    //                 //         'reason' => $request->reason,
    //                 //         'center_id' => $request->center_id,///
    //                 //         'punch_type' =>"I",
    //                 //         'photo' => $input['file'],
    //                 //         'batch_code' => $request->batch_code,/////
    //                 //         'update_attn_status' => 1,
    //                 //         'bulk_type' => 1,
    //                 //     ]);

    //                 //     $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
    //                 //         array(
    //                 //             'source' => 'mobile_trainer',
    //                 //             'mobile_app_id' => $mob_id,
    //                 //             'member_id'=>$member_id,
    //                 //             'member_type'=>'student',
    //                 //             'punch_type'=>"I",
    //                 //             'flag_value'=>1,
    //                 //             'punch_time'=>$request->attend_date." ".$time,
    //                 //             'onetime'=>1,
    //                 //             'created_at'=>now(),
    //                 //             'attd_month'=>'All',
    //                 //         )
    //                 //     );
                        
                        
                        

    //                 // }else{

    //                 //     $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
    //                 //     $user_id= $users[0]->id;

    //                 //     $atten_type=$request->attend_date==date('Y-m-d')?'present':'past';
    //                 //     if(str_starts_with($users[0]->member_code, 'AF')){
    //                 //         $member_type='student';
    //                 //     }else{
    //                 //         $member_type='staff';
    //                 //     }

    //                 //     $studenttime=Attendance::where('atten_date',$request->attend_date)->where('user_id',$user_id)->get(['punch_in','punch_out']);

    //                 //     if($studenttime[0]->punch_in<$time){
    //                 //         Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_in'=>$time,'status'=>1,'punch_out_place'=>$request->location]);
    //                 //     }else{
    //                 //         // Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_place'=>$request->location]);

    //                 //         $checkOutTime=Attendance::where('member_id',$user_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->value('punch_time');

    //                 //         if($time>$checkOutTime){

    //                 //             Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1]);
    //                 //         }
    //                 //     }

                        

    //                 //     $details = Attendance::where('atten_date', $request->attend_date)->where('user_id', $user_id)->get();

    //                 //     Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$request->lat,'long'=>$request->long,'place'=>$request->location,'punch_time'=>$time,'punch_date'=>$request->attend_date,'member_code'=>trim($users[0]->member_code)]);

    //                 //     $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->value('punch_time');

    //                 //     if($checkInTime>$time){
    //                 //         $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type','I')->update(['punch_time' =>$time]);
    //                 //     }else{

    //                 //         $checkOutTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->value('punch_time');
    //                 //         if($time>$checkOutTime){
    //                 //             DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //                 'member_id'=>$member_id,
    //                 //                 'atten_date'=>$request->attend_date,
    //                 //                 'punch_type'=>"O"
    //                 //             ],[
                                
    //                 //                 'punch_time' => $time,
                                    
    //                 //             ]);
    //                 //         }else{

    //                 //             $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                 //                 'user_id_mob_app' => $user_id,
    //                 //                 'atten_date' => $request->attend_date,
    //                 //                 'punch_time' => $time,
    //                 //                 'lat' => $request->lat,
    //                 //                 'long' => $request->long,
    //                 //                 'member_id' => $member_id,
    //                 //                 'member_code' => $users[0]->member_code,
    //                 //                 'status' => 2,
    //                 //                 'punch_place' => $request->location,
    //                 //                 'atten_type' => $atten_type,
    //                 //                 'member_type' => $member_type,
    //                 //                 'reason' => $request->reason,
    //                 //                 'center_id' => $request->center_id,
    //                 //                 'punch_type' =>"O",
    //                 //                 'photo' => $input['file'],
    //                 //                 'batch_code' => $request->batch_code,
    //                 //                 'update_attn_status' => 1,
    //                 //             ]);
    //                 //             $mobile_id=$mob_id;

    //                 //         }

    //                 //     }
                        
    //                 //     // $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$request->attend_date)->where('punch_type',"O")->get(['id']);
                        
    //                 //     // if(sizeof($mob_id)>0){
    //                 //     //     $mobile_id=$mob_id[0]->id;
    //                 //     //     DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
    //                 //     //         'id' => $mob_id[0]->id,
    //                 //     //     ],[
    //                 //     //         'user_id_mob_app' => $user_id,
    //                 //     //         'atten_date' => $request->attend_date,
    //                 //     //         'punch_time' => $time,
    //                 //     //         'lat' => $request->lat,
    //                 //     //         'long' => $request->long,
    //                 //     //         'member_id' => $member_id,
    //                 //     //         'member_code' => $users[0]->member_code,
    //                 //     //         'status' => 1,
    //                 //     //         'punch_place' => $request->location,
    //                 //     //         'atten_type' => $atten_type,
    //                 //     //         'member_type' => $member_type,
    //                 //     //         'reason' => $request->reason,
    //                 //     //         'center_id' => $request->center_id,
    //                 //     //         'punch_type' =>"O",
    //                 //     //         'photo' => $input['file'],
    //                 //     //         'batch_code' => $request->batch_code,
    //                 //     //         'update_attn_status' => 1,
    //                 //     //         'bulk_type' => 1,
    //                 //     //         'approve_by' => $trainer_id,
    //                 //     //         'approve_at' => now(),
    //                 //     //     ]);
    //                 //     //     //dd($mob_id);
                            
    //                 //     // }else{
    //                 //         $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
    //                 //             'user_id_mob_app' => $user_id,
    //                 //             'atten_date' => $request->attend_date,
    //                 //             'punch_time' => $time,
    //                 //             'lat' => $request->lat,
    //                 //             'long' => $request->long,
    //                 //             'member_id' => $member_id,
    //                 //             'member_code' => $users[0]->member_code,
    //                 //             'status' => 2,
    //                 //             'punch_place' => $request->location,
    //                 //             'atten_type' => $atten_type,
    //                 //             'member_type' => $member_type,
    //                 //             'reason' => $request->reason,
    //                 //             'center_id' => $request->center_id,
    //                 //             'punch_type' =>"O",
    //                 //             'photo' => $input['file'],
    //                 //             'batch_code' => $request->batch_code,
    //                 //             'update_attn_status' => 1,
    //                 //         ]);
    //                 //         $mobile_id=$mob_id;
    //                 // }

                    
    //                 //curl_close($curlHandle);
    //                 //$x=['punch_in'=>$time,'date' => $x['attend_date']];
    //                 DB::commit();
    //         }   
    //         DB::commit();  
    //         return Response(['message' => 'inserted successfully','status'=>1],200);

    //     } catch (Exception $e) { 
    //         DB::rollback();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    public function offlineSync(Request $request)
    {
       ini_set('max_execution_time', config('app.php_max_time'));
	   ini_set('memory_limit', '4096M');
        
       DB::beginTransaction();
        try { 
            //return $request->details;
            //
            date_default_timezone_set('Asia/Kolkata');
            
            foreach($request->details as $x){

                    if(str_starts_with($x['member_code'], 'AF')){
                    $member_type='student';
                    }else{
                    $member_type='staff';
                    }
                    $member_id=$x['member_id'];
                    $attn_type=$x['attn_type'];

                    $batchdata = DB::connection('mysql_2')->table('enrollments as a')
                        ->join('batches as b', 'a.batch_id', '=', 'b.id')
                        ->where('a.member_id', $member_id)
                        ->orderByDesc('a.id')
                        ->get(['a.batch_id', 'b.center_id', 'b.batch_code']);
                    if(sizeof($batchdata)==0){
                        $center_id=$x['center_id'];
                        $batch_code=$x['batch_code'];
                    }else{
                        $center_id=$batchdata[0]->center_id;
                        $batch_code=$batchdata[0]->batch_code;
                    }  
                    
                    $details1 = DB::table('student_attendances')->where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->get();
                    
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
                    

                    if($x['punch_out']==''){
                      $time=$x['punch_in'];
                    }else{
                        $time=$x['punch_out'];
                    }

                    $incount = DB::table('student_attendances')->where('atten_date', $x['attend_date'])->where('user_id', $x['user_id'])->count();

                    if($incount==0){

                        // $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

                        $users=DB::table('users')->where('member_id', $x['member_id'])->get(['id','member_code']);
                        $user_id= $x['user_id'];

                        $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                        if(str_starts_with($x['member_code'], 'AF')){
                            $member_type='student';
                        }else{
                            $member_type='staff';
                        }

                        

                        $lastId=DB::table('student_attendances')->insertGetId([
                            'user_id'         => $user_id,
                            'atten_date'      => $x['attend_date'],
                            'punch_in'        => $time,
                            'lat'             => $x['lat'],
                            'long'            => $x['long'],
                            'member_id'       => $member_id,
                            'member_code'     => $x['member_code'],
                            'member_type'     => $member_type,
                            'transfer_status' => 1,
                            'atten_type'      => $attn_type,
                            'status'          => 2,
                            'atten_image'     => $input['file'],
                            'punch_in_place'  => '',
                            'reason'          => $x['reason'],
                            'bulk_type'       => 0,
                            'app_version'     => '3.0.0',
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                        

                        DB::table('student_photos')->insertGetId(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($x['member_code'])]);
                        
                        $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->insertGetId([
                            'user_id_mob_app' => $user_id,
                            'atten_date' => $x['attend_date'],
                            'punch_time' => $time,
                            'lat' => $x['lat'],
                            'long' => $x['long'],
                            'member_id' => $member_id,
                            'member_code' => $x['member_code'],
                            'status' => 2,
                            'punch_place' => '',
                            'atten_type' => $attn_type,
                            'member_type' => $member_type,
                            'reason' => $x['reason'],
                            'center_id' => $center_id,///
                            'punch_type' =>"I",
                            'photo' => $input['file'],
                            'batch_code' => $batch_code,/////
                            'update_attn_status' => 1,
                            'bulk_type' => 0,
                        ]);

                        $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                            array(
                                'source' => 'mobile_student',
                                'mobile_app_id' => $mob_id,
                                'member_id'=>$member_id,
                                'member_type'=>'student',
                                'punch_type'=>"I",
                                'flag_value'=>1,
                                'punch_time'=>$x['attend_date']." ".$time,
                                'onetime'=>1,
                                'created_at'=>now(),
                                'attd_month'=>'All',
                            )
                        );

                        
                        
                        
                        

                    }else{

                        $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                        $user_id= $x['user_id'];

                        $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                        if(str_starts_with($x['member_code'], 'AF')){
                            $member_type='student';
                        }else{
                            $member_type='staff';
                        }

                        $studenttime=DB::table('student_attendances')->where('atten_date',$x['attend_date'])->where('user_id',$user_id)->get(['punch_in','punch_out','bulk_type','status']);

                        //if($studenttime[0]->bulk_type!=1 && $studenttime[0]->status!=1){

                            if($time<$studenttime[0]->punch_in){
                                DB::table('student_attendances')->where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_in'=>$time,'status'=>1,'punch_out_place'=>'']);
                            }else{
                                // Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_place'=>$x['location']]);

                                $checkOutTime=DB::table('student_attendances')->where('user_id',$user_id)->where('atten_date',$x['attend_date'])->value('punch_out');

                                if($time>$checkOutTime){

                                    DB::table('student_attendances')->where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>0,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long']]);
                                }
                            }
                        //}    

                        

                        $details = DB::table('student_attendances')->where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();

                        DB::table('student_photos')->insertGetId(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);

                        $checkInTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->value('punch_time');

                        
                       //if($details[0]->bulk_type!=1 && $details[0]->status!=1){

                            if($time<$checkInTime){
                                $checkInTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>0]);

                                $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                    array(
                                        'source' => 'mobile_student',
                                        'mobile_app_id' => $mob_id,
                                        'member_id'=>$member_id,
                                        'member_type'=>'student',
                                        'punch_type'=>"I",
                                        'flag_value'=>1,
                                        'punch_time'=>$x['attend_date']." ".$time,
                                        'onetime'=>1,
                                        'created_at'=>now(),
                                        'attd_month'=>'All',
                                    )
                                );
                            }else{

                                $checkOutTime=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type',"O")->value('punch_time');
                                if($time>$checkOutTime){
                                    DB::connection('mysql_2')->table('attendance_app_student')->updateOrInsert([
                                        'member_id'=>$member_id,
                                        'atten_date'=>$x['attend_date'],
                                        'punch_type'=>"O"
                                    ],[
                                    
                                        'punch_time' => $time,
                                        'user_id_mob_app' => $user_id,
                                        'atten_date' => $x['attend_date'],
                                        'punch_time' => $time,
                                        'lat' => $x['lat'],
                                        'long' => $x['long'],
                                        'member_id' => $member_id,
                                        'member_code' => $users[0]->member_code,
                                        'status' => 2,
                                        'punch_place' => '',
                                        'atten_type' => $attn_type,
                                        'member_type' => $member_type,
                                        'reason' => $x['reason'],
                                        'center_id' => $center_id,
                                        'punch_type' =>"O",
                                        'photo' => $input['file'],
                                        'batch_code' => $batch_code,
                                        'update_attn_status' => 1,
                                        'bulk_type'=>0
                                        
                                    ]);

                                    $mob_id=DB::connection('mysql_2')->table('attendance_app_student')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                    $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                        array(
                                            'source' => 'mobile_student',
                                            'mobile_app_id' => $mob_id,
                                            'member_id'=>$member_id,
                                            'member_type'=>'student',
                                            'punch_type'=>"O",
                                            'flag_value'=>1,
                                            'punch_time'=>$x['attend_date']." ".$time,
                                            'onetime'=>1,
                                            'created_at'=>now(),
                                            'attd_month'=>'All',
                                        )
                                    );
                                }
                                

                            }
                        //}    
                        
                        
                        
                    }

                    
                    //DB::commit();
            }   
            DB::commit();  
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
        // $details = Attendance::where('user_id',$user_id)->where('atten_date', $attn_date)
        // ->get(['id as id','punch_in as punch_in','punch_out as punch_out','atten_date as date','status as status','user_id as user_id']);

        $details = DB::table('student_attendances')->where('user_id',$user_id)->where('atten_date', $attn_date)
        ->get(['id as id','punch_in as punch_in','punch_out as punch_out','atten_date as date','status as status','user_id as user_id']);
        return Response(['datas' => $details,'status'=>1,'cur_date'=>$attn_date],200);
    }
    public function fetchAttendance($user_id,$cur_month,$cur_year)
    {
        //
        // $details = Attendance::where('user_id',$user_id)->whereMonth('atten_date', $cur_month)
        // ->whereYear('atten_date', $cur_year)
        // ->get(['id as id','punch_in as punch_in','punch_out as punch_out','atten_date as date','status as status','user_id as user_id']);

        $details = DB::table('student_attendances')->where('user_id',$user_id)->whereMonth('atten_date', $cur_month)
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
                  ->get(['m.first_name as first_name','m.last_name as last_name','m.member_code as member_code','m.id as member_id','e.batch_id as batch_id','e.status as status']);

         return Response(['center_details' => $centers,'batches' => $batches,'members' => $members],200);
      // return Response(['center_details' => $details_from_cmis],200);            

       }catch(\Exception $e){
        DB::rollback();
        dd($e);
        //return $this->sendError($e->getMessage());
      }
    }

    public function offlineSyncBulkPunchInOutAttendance_old(Request $request)
    {
        
       DB::beginTransaction();
       ini_set('max_execution_time', config('app.php_max_time'));
	   ini_set('memory_limit', '4096M'); 
       //dd('d');
        try { 
           //dd($request->all());
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
                        //$input['file']='NA'; 
                        return Response(['message' => 'Please attach Attendance images','status'=>1],200);
                    }  
                    //$input['file']='NA';
                    // $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $x['user_id'])->value('id');
                    $trainer_username=DB::table('users')->where('id', $x['user_id'])->value('username');
                    $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $trainer_username)->value('id');
                    //if($x['type']=='in'){

                        //dd($student_list);
                        foreach($student_list as $member_id){

                            // $batchdata = DB::connection('mysql_2')->table('enrollments as a')
                            //     ->join('batches as b', 'a.batch_id', '=', 'b.id')
                            //     ->where('a.member_id', $member_id)
                            //     ->orderByDesc('a.id')
                            //     ->get(['a.batch_id', 'b.center_id', 'b.batch_code']);
                            // if(sizeof($batchdata)==0){
                            //     $center_id=$x['center_id'];
                            //     $batch_code=$x['batch_code'];
                            // }else{
                            //     $center_id=$batchdata[0]->center_id;
                            //     $batch_code=$batchdata[0]->batch_code;
                            // }  

                            $incount=Attendance::where('atten_date',$x['attend_date'])->where('member_id',$member_id)->count();
                           // dd($incount);
                            if($incount==0){
                                //dd($member_id);
                                $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);
            
                                DB::table('users')->updateOrInsert([
                                    'member_id' => $member_id,
                                ],[
                                    'name' => $members[0]->first_name." ".$members[0]->last_name,
                                    'username' => $members[0]->member_code,
                                    'email' => $members[0]->email_id,
                                    'mobile_no'=>$members[0]->mobile_no,
                                    'password'=>Hash::make($members[0]->member_code),
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
            
                                //dd('hg');
            
                                $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                                $user_id= $users[0]->id;
            
                                $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                                if(str_starts_with($users[0]->member_code, 'AF')){
                                    $member_type='student';
                                }else{
                                    $member_type='staff';
                                }
            
                                
            
                                $lastId=DB::table('attendances')->insertGetId([
                                    'user_id'         => $user_id,
                                    'atten_date'      => $x['attend_date'],
                                    'punch_in'        => $time,
                                    'lat'             => $x['lat'],
                                    'long'            => $x['long'],
                                    'member_id'       => $member_id,
                                    'member_code'     => $users[0]->member_code,
                                    'member_type'     => $member_type,
                                    'transfer_status' => 1,
                                    'atten_type'      => $attn_type,
                                    'status'          => 1,
                                    'atten_image'     => $input['file'],
                                    'reason'          => $x['reason'],
                                    'bulk_type'       => 1,
                                    'created_by'      => $trainer_id,
                                    'app_version'     => '3.0.0',
                                    'created_at'      => now(),
                                    'updated_at'      => now(),
                                ]);
                                
            
                                Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);
                                
                                $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
                                    'user_id_mob_app' => $user_id,
                                    'atten_date' => $x['attend_date'],
                                    'punch_time' => $time,
                                    'lat' => $x['lat'],
                                    'long' => $x['long'],
                                    'member_id' => $member_id,
                                    'member_code' => $users[0]->member_code,
                                    'status' => 1,
                                    'atten_type' => 'present',
                                    'member_type' => $member_type,
                                    'reason' => $x['reason'],
                                    'center_id' => $x['center_id'],
                                    'punch_type' =>"I",
                                    'photo' => $input['file'],
                                    'batch_code' => $x['batch_code'],
                                    'update_attn_status' => 1,
                                    'bulk_type' => 1,
                                    'approve_by' => $trainer_id,
                                    'approve_at' => now(),
                                ]);
            
                                $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                    array(
                                        'source' => 'mobile_trainer',
                                        'mobile_app_id' => $mob_id,
                                        'member_id'=>$member_id,
                                        'member_type'=>'student',
                                        'punch_type'=>"I",
                                        'flag_value'=>1,
                                        'punch_time'=>$x['attend_date']." ".$time,
                                        'onetime'=>1,
                                        'created_at'=>now(),
                                        'attd_month'=>'All',
                                    )
                                );
                                
                                
                                
            
                            }else{
            
                                $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                                $user_id= $users[0]->id;
            
                                $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                                if(str_starts_with($users[0]->member_code, 'AF')){
                                    $member_type='student';
                                }else{
                                    $member_type='staff';
                                }
                                  //dd('jh');
                                  //dd($user_id,$x);
                                $studenttime=Attendance::where('atten_date',$x['attend_date'])->where('member_id',$member_id)->get(['punch_in','punch_out']);

                                if($time<$studenttime[0]->punch_in){
                                    Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_in'=>$time,'status'=>1]);
                                }else{
                                    $checkOutTime=Attendance::where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('punch_out');

                                    if($time>$checkOutTime){

                                        Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long']]);
                                    }
                                }
                                
                                $details = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();
            
                                Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);

                                $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->value('punch_time');

                                if($checkInTime>$time){

                                    $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>1]);

                                    $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                        $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                            array(
                                                'source' => 'mobile_trainer',
                                                'mobile_app_id' => $mob_id,
                                                'member_id'=>$member_id,
                                                'member_type'=>'student',
                                                'punch_type'=>"I",
                                                'flag_value'=>1,
                                                'punch_time'=>$x['attend_date']." ".$time,
                                                'onetime'=>1,
                                                'created_at'=>now(),
                                                'attd_month'=>'All',
                                            )
                                        );

                                }
                                else{
                                    $checkOutTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type',"O")->value('punch_time');
                                    if($time>$checkOutTime){
                                        DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                                            'member_id'=>$member_id,
                                            'atten_date'=>$x['attend_date'],
                                            'punch_type'=>"O"
                                        ],[
                                            'user_id_mob_app' => $user_id,
                                            'atten_date' => $x['attend_date'],
                                            'punch_time' => $time,
                                            'lat' => $x['lat'],
                                            'long' => $x['long'],
                                            'member_id' => $member_id,
                                            'member_code' => $users[0]->member_code,
                                            'status' => 1,
                                            'atten_type' => 'present',
                                            'member_type' => $member_type,
                                            'reason' => $x['reason'],
                                            'center_id' => $x['center_id'],
                                            'punch_type' =>"O",
                                            'photo' => $input['file'],
                                            'batch_code' => $x['batch_code'],
                                            'update_attn_status' => 1,
                                            'bulk_type' => 1,
                                            'approve_by' => $trainer_id,
                                            'approve_at' => now(),
                                        ]);

                                        $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                        $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                            array(
                                                'source' => 'mobile_trainer',
                                                'mobile_app_id' => $mob_id,
                                                'member_id'=>$member_id,
                                                'member_type'=>'student',
                                                'punch_type'=>"O",
                                                'flag_value'=>1,
                                                'punch_time'=>$x['attend_date']." ".$time,
                                                'onetime'=>1,
                                                'created_at'=>now(),
                                                'attd_month'=>'All',
                                            )
                                        );
                                    }
                                    // else{

                                    //     $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
                                    //         'user_id_mob_app' => $user_id,
                                    //         'atten_date' => $x['attend_date'],
                                    //         'punch_time' => $time,
                                    //         'lat' => $x['lat'],
                                    //         'long' => $x['long'],
                                    //         'member_id' => $member_id,
                                    //         'member_code' => $users[0]->member_code,
                                    //         'status' => 1,
                                    //         'atten_type' => $atten_type,
                                    //         'member_type' => $member_type,
                                    //         'reason' => $x['reason'],
                                    //         'center_id' => $x['center_id'],
                                    //         'punch_type' =>"O",
                                    //         'photo' => $input['file'],
                                    //         'batch_code' => $x['batch_code'],
                                    //         'update_attn_status' => 1,
                                    //         'bulk_type' => 1,
                                    //         'approve_by' => $trainer_id,
                                    //         'approve_at' => now(),
                                    //     ]);
                                    //     $mobile_id=$mob_id;

                                    // }
                                }
                                
                                
                                //dd('end');
            
                                
                                
                            }  

                            // if($incount==0){

                            //         $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);

                            //         DB::table('users')->updateOrInsert([
                            //             'member_id' => $member_id,
                            //         ],[
                            //             'name' => $members[0]->first_name." ".$members[0]->last_name,
                            //             'username' => $members[0]->member_code,
                            //             'email' => $members[0]->email_id,
                            //             'mobile_no'=>$members[0]->mobile_no,
                            //             'password'=>Hash::make('1234567'),
                            //             'member_id'=>$member_id,
                            //             'member_code'=>$members[0]->member_code,
                            //             'batch_id'=>$x['batch_id'],
                            //             'batch_code'=>$x['batch_code'],
                            //             'center_id'=>$x['center_id'],
                            //             'center_code'=>$x['center_code'],
                            //             'status'=>1,
                            //             'role_name'=>'student',
                            //             'gender'=>$members[0]->gender
                            //         ]);
                            //         $user_id=DB::table('users')->where('member_id', $member_id)->value('id');
                                    

                            //         $datas=User::where('id',$user_id)->get(['member_code','member_id']);

                            //         $postParameter = ['user_id' => $user_id,'atten_date' => $x['attend_date'],'punch_in'=>$time,'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$datas[0]->member_id,'member_code'=>$datas[0]->member_code,'status'=>2,'bulk_type'=>1,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'created_by'=>$trainer_id,'app_version'=>$request->app_version];

                            //         $curlHandle = curl_init('https://cmis4api.anudip.org/public/api/insertFromAttenApp');
                            //         curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                            //         curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                            //         $curlResponse = curl_exec($curlHandle);
                            //         //dd($curlResponse);
                            //         curl_close($curlHandle);

                            //         if($curlResponse === false) {
                            //             return Response(['message' => 'server issue','status'=>1],200);
                            //         } 

                            //         $lastId=Attendance::create($postParameter)->id;
                            //         Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($datas[0]->member_code)]);
                                    
                            //         DB::commit();
                            // } else{
                            //     $arr=[];
                            //     $user_id=DB::table('users')->where('member_id', $member_id)->value('id');
                            //     $details = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();
                            
                            //     if(sizeof($details)>0){
                            //         $datas=User::where('id',$user_id)->get(['member_code','member_id']);
                            //         $postParameter = ['user_id' => $user_id,'atten_date' => $x['attend_date'],'punch_in'=>$time,'lat'=>$x['lat'],'long'=>$x['long'],'member_id'=>$datas[0]->member_id,'member_code'=>$datas[0]->member_code,'status'=>2,'bulk_type'=>1,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type,'punch_in_place'=>'','reason'=>$x['reason'],'center_id'=>$x['center_id'],'photo'=>$input['file'],'batch_id'=>$x['batch_id'],'batch_code'=>$x['batch_code'],'created_by'=>$trainer_id,'app_version'=>$request->app_version];

                            //         $curlHandle = curl_init('https://cmis4api.anudip.org/public/api/insertFromAttenApp');
                            //         curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                            //         curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                            //         $curlResponse = curl_exec($curlHandle);
                            //         //dd($curlResponse);
                            //         curl_close($curlHandle);

                            //         if($curlResponse === false) {
                            //             return Response(['message' => 'server issue','status'=>1],200);
                            //         } 

                            //         Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->where('created_by', $trainer_id)->update(['punch_out'=>$time,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long'],'status'=>0,'punch_out_place'=>'']);
                            //         //dd($user_id,$x['attend_date']);
                            //         Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'place'=>'','punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($datas[0]->member_code)]);

                                    
                            //         DB::commit();
                            //     } else{
                            //         $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name']);
                            //         array_push($arr,$members);
                            //     }  
                            // }       
                        }   
                        
                        
                        
            }
            DB::commit(); 
            return Response(['message' => 'sync successfully','status'=>1],200);
            

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    
    public function offlineSyncBulkPunchInOutAttendance(Request $request)
    {
        //DB::beginTransaction();
        ini_set('max_execution_time', config('app.php_max_time'));
        ini_set('memory_limit', '4096M');

        try {
           // dd($request->all());
            //$x=$request->student_list;
            $email_cc = ['arup.das@anudip.org'];

            
             foreach($request->all() as $x){
                
               

            
                

                $str = trim($x['studentList'], '"');          // remove starting/ending quotes
                $student_list = json_decode($str, true);
                //dd($student_list);
                

                $rows = [];
                //dd($student_list);

                $s3_path = "attendance/" . trim($x['attend_date']) . "/";
                    $folderPath = "volume_blr1_01/" . trim($x['attend_date']) . "/";

                    // Create folder if not exists (recursive)
                    if (!file_exists(public_path($folderPath))) {
                        mkdir(public_path($folderPath), 0777, true);
                    }

                    // Decode base64 image
                    $base64Image = explode(";base64,", $x['image']);
                    $explodeImage = explode("image/", $base64Image[0]);
                    $imageType = $explodeImage[1] ?? 'jpg';

                    $image_base64 = base64_decode($base64Image[1] ?? '', true);

                    if ($image_base64 === false) {
                        continue; // invalid base64
                    }
                    //dd($image_base64);
                    // File name
                    $inputFileName = trim($x['batch_code']) . "_" . $x['attend_date'] . "_" . time() . ".jpg";

                    // Save locally (optional)
                    $localFilePath = public_path($folderPath . $inputFileName);
                    file_put_contents($localFilePath, $image_base64);

                    // Upload original image to S3
                    Storage::disk('s3_1')->put($s3_path . $inputFileName, file_get_contents($localFilePath), [
                        'ContentType' => mime_content_type($localFilePath),
                    ]);
                foreach ($student_list as $member_id) {
                      //dd($member_id);
                    

                    //DB::table('offline_student_sync_logs')->insertOrIgnore($rows);

                     $insertEligibleStudents = DB::table('offline_student_sync_logs')->insertGetId(
                        array(
                        'attend_date'     => $x['attend_date'] ?? null,
                        'user_id' => $x['user_id'] ?? null,
                        'batch_id'        => $x['batch_id'] ?? null,   // FIXED
                        'batch_code'      => $x['batch_code'] ?? null,
                        'center_id'       => $x['center_id'] ?? null,
                        'center_code'     => $x['center_code'] ?? null,

                        'member_id'       => $member_id,

                        'punch_time'      => $x['punch_time'] ?? null,
                        'lat'             => $x['lat'] ?? null,
                        'long'            => $x['long'] ?? null,
                        'reason'          => $x['reason'] ?? null,
                        'image_name'      => $inputFileName,

                        'created_at'      => now(),
                        'updated_at'      => now(),
                        )
                    );

                    // $rows[] = [
                    //     'attend_date'     => $x['attend_date'] ?? null,
                    //     'user_id' => $x['user_id'] ?? null,
                    //     'batch_id'        => $x['batch_id'] ?? null,   // FIXED
                    //     'batch_code'      => $x['batch_code'] ?? null,
                    //     'center_id'       => $x['center_id'] ?? null,
                    //     'center_code'     => $x['center_code'] ?? null,

                    //     'member_id'       => $member_id,

                    //     'punch_time'      => $x['punch_time'] ?? null,
                    //     'lat'             => $x['lat'] ?? null,
                    //     'long'            => $x['long'] ?? null,
                    //     'reason'          => $x['reason'] ?? null,
                    //     'image_name'      => $inputFileName,

                    //     'created_at'      => now(),
                    //     'updated_at'      => now(),
                    // ];
                }

            }
                //dd($rows);

                // DB::connection('mysql_2')->table('mailer_service_details')->insert([
                //     'email_subject' => "CMIS - email attendance",
                //     'email_content'=> '<p><h3>'.json_encode($rows).'</h3></p>',
                //     'template_name'         => "auth.emails.mail_final_assessment_request",
                //     'email_receiver'       => 'arup.das@anudip.org',
                //     'email_cc_receiver'   => json_encode($email_cc),
                //     'email_attach_link'  => 'NA',
                //     'mail_topic'       => 'attn ("updateStudentEmail")',
                //     ]);

                //DB::table('offline_student_sync_logs')->insertOrIgnore($rows);
                
            //}

            //DB::commit();
            return response(['message' => 'sync successfully', 'status' => 1], 200);

        } catch (Exception $e) {
            $x=$request->all();
            $email_cc = ['arup.das@anudip.org'];
            DB::connection('mysql_2')->table('mailer_service_details')->insert([
                  'email_subject' => "CMIS - email attendance",
                  'email_content'=> '<p><h3>'.$e.'</h3></p>',
                  'template_name'         => "auth.emails.mail_final_assessment_request",
                  'email_receiver'       => 'arup.das@anudip.org',
                  'email_cc_receiver'   => json_encode($email_cc),
                  'email_attach_link'  => 'NA',
                  'mail_topic'       => 'attn ("updateStudentEmail")',
                ]);
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }



    public function offlineSyncBulkPunchInOutAttendance_cron()
    {
        
      // DB::beginTransaction();
       ini_set('max_execution_time', config('app.php_max_time'));
	   ini_set('memory_limit', '4096M'); 
       //dd('d');
        try { 
           //dd($request->all());
           $mail_content = DB::table('offline_student_sync_logs')
                            ->where('status', 0)
                            ->orderBy('id', 'asc')
                            ->limit(200)
                            ->get();
               //dd($mail_content->toArray());
            if(sizeof($mail_content)){   
               
                foreach($mail_content as $x){
                    $x=(array)$x;
                    //dd($x['image_name']);
                    
                        //dd(json_decode($a['studentList'], true));
                        //$student_list=json_decode($x['studentList'], true);
                
                        date_default_timezone_set('Asia/Kolkata');
                        $time=$x['punch_time'];
                        $attn_type='present';
                        $member_type='student';
                        $input['file']=$x['image_name'];
                        // $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $x['user_id'])->value('id');
                        $trainer_username=DB::table('users')->where('id', $x['user_id'])->value('username');
                        $trainer_id=DB::connection('mysql_2')->table('users')->where('user_id', $trainer_username)->value('id');
                        //if($x['type']=='in'){

                        //dd($student_list);
                        $member_id=$x['member_id'];
                            //foreach($student_list as $member_id){

                        
                        // dd($incount);
                        $incount=Attendance::where('atten_date',$x['attend_date'])->where('member_id',$member_id)->count();
                        
                        if($incount==0){
                            //dd($member_id);
                            $members=DB::connection('mysql_2')->table('members')->where('id',$member_id)->get(['member_code','first_name','last_name','email_id','mobile_no','gender']);
        
                            DB::table('users')->updateOrInsert([
                                'member_id' => $member_id,
                            ],[
                                'name' => $members[0]->first_name." ".$members[0]->last_name,
                                'username' => $members[0]->member_code,
                                'email' => $members[0]->email_id,
                                'mobile_no'=>$members[0]->mobile_no,
                                'password'=>Hash::make($members[0]->member_code),
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
        
                            //dd('hg');
        
                            $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                            $user_id= $users[0]->id;
        
                            $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                            if(str_starts_with($users[0]->member_code, 'AF')){
                                $member_type='student';
                            }else{
                                $member_type='staff';
                            }
        
                            
        
                            $lastId=DB::table('attendances')->insertGetId([
                                'user_id'         => $user_id,
                                'atten_date'      => $x['attend_date'],
                                'punch_in'        => $time,
                                'lat'             => $x['lat'],
                                'long'            => $x['long'],
                                'member_id'       => $member_id,
                                'member_code'     => $users[0]->member_code,
                                'member_type'     => $member_type,
                                'transfer_status' => 1,
                                'atten_type'      => $attn_type,
                                'status'          => 1,
                                'atten_image'     => $input['file'],
                                'reason'          => $x['reason'],
                                'bulk_type'       => 1,
                                'created_by'      => $trainer_id,
                                'app_version'     => '3.0.0',
                                'created_at'      => now(),
                                'updated_at'      => now(),
                            ]);
                            
        
                            Photo::create(['user_id' => $user_id,'attendance_id'=>$lastId,'punch_type'=>'I','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);
                            
                            $mob_id=DB::connection('mysql_2')->table('attendance_app')->insertGetId([
                                'user_id_mob_app' => $user_id,
                                'atten_date' => $x['attend_date'],
                                'punch_time' => $time,
                                'lat' => $x['lat'],
                                'long' => $x['long'],
                                'member_id' => $member_id,
                                'member_code' => $users[0]->member_code,
                                'status' => 1,
                                'atten_type' => 'present',
                                'member_type' => $member_type,
                                'reason' => $x['reason'],
                                'center_id' => $x['center_id'],
                                'punch_type' =>"I",
                                'photo' => $input['file'],
                                'batch_code' => $x['batch_code'],
                                'update_attn_status' => 1,
                                'bulk_type' => 1,
                                'approve_by' => $trainer_id,
                                'approve_at' => now(),
                            ]);
        
                            $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                array(
                                    'source' => 'mobile_trainer',
                                    'mobile_app_id' => $mob_id,
                                    'member_id'=>$member_id,
                                    'member_type'=>'student',
                                    'punch_type'=>"I",
                                    'flag_value'=>1,
                                    'punch_time'=>$x['attend_date']." ".$time,
                                    'onetime'=>1,
                                    'created_at'=>now(),
                                    'attd_month'=>'All',
                                )
                            );
                            
                            
                            
        
                        }else{
                           //dd('out');
                            $users=DB::table('users')->where('member_id', $member_id)->get(['id','member_code']);
                            $user_id= $users[0]->id;
        
                            $atten_type=$x['attend_date']==date('Y-m-d')?'present':'past';
                            if(str_starts_with($users[0]->member_code, 'AF')){
                                $member_type='student';
                            }else{
                                $member_type='staff';
                            }
                            //dd('jh');
                            //dd($user_id,$x);
                            $studenttime=Attendance::where('atten_date',$x['attend_date'])->where('member_id',$member_id)->get(['punch_in','punch_out']);

                            if($time<$studenttime[0]->punch_in){
                                Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_in'=>$time,'status'=>1]);
                            }else{
                                $checkOutTime=Attendance::where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('punch_out');

                                if($time>$checkOutTime){

                                    Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->update(['punch_out'=>$time,'status'=>1,'punch_out_lat'=>$x['lat'],'punch_out_long'=>$x['long']]);
                                }
                            }
                            
                            $details = Attendance::where('atten_date', $x['attend_date'])->where('user_id', $user_id)->get();
        
                            Photo::create(['user_id' => $user_id,'attendance_id'=>$details[0]->id,'punch_type'=>'O','photo_name'=>$input['file'],'lat'=>$x['lat'],'long'=>$x['long'],'punch_time'=>$time,'punch_date'=>$x['attend_date'],'member_code'=>trim($users[0]->member_code)]);

                            $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->value('punch_time');

                            if($checkInTime>$time){

                                $checkInTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type','I')->update(['punch_time' =>$time,'bulk_type'=>1]);

                                $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                    $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                        array(
                                            'source' => 'mobile_trainer',
                                            'mobile_app_id' => $mob_id,
                                            'member_id'=>$member_id,
                                            'member_type'=>'student',
                                            'punch_type'=>"I",
                                            'flag_value'=>1,
                                            'punch_time'=>$x['attend_date']." ".$time,
                                            'onetime'=>1,
                                            'created_at'=>now(),
                                            'attd_month'=>'All',
                                        )
                                    );

                            }
                            else{
                                $checkOutTime=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->where('punch_type',"O")->value('punch_time');
                                if($time>$checkOutTime){
                                    DB::connection('mysql_2')->table('attendance_app')->updateOrInsert([
                                        'member_id'=>$member_id,
                                        'atten_date'=>$x['attend_date'],
                                        'punch_type'=>"O"
                                    ],[
                                        'user_id_mob_app' => $user_id,
                                        'atten_date' => $x['attend_date'],
                                        'punch_time' => $time,
                                        'lat' => $x['lat'],
                                        'long' => $x['long'],
                                        'member_id' => $member_id,
                                        'member_code' => $users[0]->member_code,
                                        'status' => 1,
                                        'atten_type' => 'present',
                                        'member_type' => $member_type,
                                        'reason' => $x['reason'],
                                        'center_id' => $x['center_id'],
                                        'punch_type' =>"O",
                                        'photo' => $input['file'],
                                        'batch_code' => $x['batch_code'],
                                        'update_attn_status' => 1,
                                        'bulk_type' => 1,
                                        'approve_by' => $trainer_id,
                                        'approve_at' => now(),
                                    ]);

                                    $mob_id=DB::connection('mysql_2')->table('attendance_app')->where('member_id',$member_id)->where('atten_date',$x['attend_date'])->value('id');

                                    $insertGetBatchId = DB::connection('mysql_2')->table('attendance_records')->insertGetId(
                                        array(
                                            'source' => 'mobile_trainer',
                                            'mobile_app_id' => $mob_id,
                                            'member_id'=>$member_id,
                                            'member_type'=>'student',
                                            'punch_type'=>"O",
                                            'flag_value'=>1,
                                            'punch_time'=>$x['attend_date']." ".$time,
                                            'onetime'=>1,
                                            'created_at'=>now(),
                                            'attd_month'=>'All',
                                        )
                                    );
                                }
                                
                            }
                            
                            
                            //dd('end');
        
                            
                            
                        }  
                        
                        DB::table('offline_student_sync_logs')->where('id',$x['id'])
                        ->update([
                            'status' => 1,                        
                        ]); 
                        $GLOBALS['id']=$x['id'];

                        //dd($incount,$x['id']);     
                            //}   
                            
                            
                            
                } 
                DB::table('attendance_service_status')
                    ->update([
                      'status' => 0, 
                    ]); 
                    //$this->attendance_service_status();    
            } else{

                DB::table('attendance_service_status')
                ->update([
                      'status' => 0,                        
                ]); 
                //$this->offlineSyncBulkPunchInOutAttendance(); 
                //$this->attendance_service_status();

            }   
            //DB::commit(); 
            return Response(['message' => 'sync successfully','status'=>1],200);
            

        } catch (Exception $e) { 
            

            DB::table('offline_student_sync_logs')->where('id',$GLOBALS['id'])
                        ->update([
                            'status' => 2,                        
                        ]);
            DB::table('attendance_service_status')
                ->update([
                      'status' => 0,                        
                ]); 
            //DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }


    
    

    public function attendance_service_status(){
        $mail_content_status=DB::table('attendance_service_status')
               ->value('status'); 
        if($mail_content_status==0){
            DB::table('attendance_service_status')
                    ->update([
                          'status' => 1,   
                          'created_at'=>now()                       
                    ]); 
            $this->offlineSyncBulkPunchInOutAttendance_cron(); 
            //$c=$this->snsService();
            //dd($c);        
        }  
        //dd('dd');     
    }

    // public function snsService(){
    //     try{


            
    //         Artisan::call('config:clear');
    //         Artisan::call('config:cache');
    //         $mail_content=DB::table('mailer_service_details')
    //            ->where('status',0)
    //            ->orderBy('bulk_status','asc')
    //            ->limit(200)
    //            ->get(); 

    //         //dd($mail_content);
    //        //return $mail_content;   

    //         if(sizeof($mail_content)>0){
    //             $sender_mail = DB::select("SELECT * FROM mailer_service_email WHERE active_status = 1 AND email_send_date < CURDATE() ORDER BY id LIMIT 1");
    //             //dd($sender_mail);
                
    //             if(sizeof($sender_mail)>0){
    //                 DB::table('mailer_service_email')
    //                 ->where('id',$sender_mail[0]->id)
    //                 ->update([
    //                       'email_send_count' => 0,                          
    //                 ]);  
                    
    //                 $sender_mail=$sender_mail;

    //             }else{
    //                 $sender_mail1 = DB::select("SELECT * FROM mailer_service_email WHERE active_status = 1 AND email_send_count < 300 AND email_send_date = CURDATE() ORDER BY id LIMIT 1");
    //                 //dd($sender_mail1);
    //                 if(sizeof($sender_mail1)>0){
    //                     $sender_mail=$sender_mail1;
    //                 }else{                                               

    //                     DB::table('mailer_service_status')
    //                             ->update([
    //                                 'status' => 0,
    //                             ]); 

    //                     $rand_mail = DB::select("SELECT email_id FROM mailer_service_email ORDER BY RAND() LIMIT 1");

    //                     $data = [
    //                         'name' => 'check mail',
    //                         'rand_email_check' => $rand_mail[0]->email_id,
    //                     ]; 
                        
    //                     config(['mail.mailers.smtp.username' => 'tech@anudip.org']);
    //                     config(['mail.mailers.smtp.password' => 'urwlfjvnfnchcuxj']);
    //                     Mail::mailer('smtp')->send('auth.emails.no_mail_exist_sns', $data, function ($message) use ($data) {
    //                         $message->to($data['rand_email_check'])->subject("No Email Id available in SNS email list");
    //                     });
    //                 }
    //             }
    //             //dd($sender_mail);
    //             config(['mail.mailers.smtp.username' => $sender_mail[0]->email_id]);
    //             config(['mail.mailers.smtp.password' => $sender_mail[0]->email_app_pass]);
    //             try{
    //                 $data = [
    //                     'name' => 'check mail',
    //                     'new_email' => $sender_mail[0]->email_id,
    //                 ];
    //                 Mail::send('auth.emails.checkemail', $data, function ($message) use ($data) {
    //                     $message->from($data['new_email'], 'Anudip Foundation');
    //                     $message->to("tech@anudip.org")->subject("Checking email");
    //                 });
    //                 //content mail
    //                 //dd("check done",$sender_mail[0]->email_id,$sender_mail[0]->email_app_pass);
    //                 foreach($mail_content as $x){
    //                     try{
    //                          //dd($x);
    //                         if($x->email_attach_link!='NA'){
    //                             $response = Http::get($x->email_attach_link);
    //                             $fileContent = $response->body();

    //                             // Extract filename from URL
    //                             $fileName = basename(parse_url($x->email_attach_link, PHP_URL_PATH));

    //                             // Determine MIME type dynamically
    //                             $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    //                             $mimeTypes = [
    //                                 'xls'  => 'application/vnd.ms-excel',
    //                                 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //                                 'pdf'  => 'application/pdf',
    //                             ];
    //                             $mimeType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';

    //                             // Prepare email data
    //                             $data = [
    //                                 'content' => $x->email_content,
    //                                 'email'   => $x->email_receiver,
    //                                 'cc_email'=> json_decode($x->email_cc_receiver),
    //                                 'subject' => $x->email_subject,
    //                                 'new_email' => $sender_mail[0]->email_id,
    //                             ];

    //                             // Send email with attachment
                                
    //                                 Mail::mailer('smtp')->send($x->template_name, $data, function ($message) use ($data, $fileContent, $fileName, $mimeType) {
    //                                     $message->attachData($fileContent, $fileName, ['mime' => $mimeType]);
    //                                     $message->from($data['new_email'], 'Anudip Foundation');
    //                                     $message->to($data['email'])->cc($data['cc_email'])->subject($data['subject']);
    //                                 });
    //                                 //dd('send');
    //                         }else{

    //                             $data = [
    //                                 'content' => $x->email_content,
    //                                 'email'   => $x->email_receiver,
    //                                 'cc_email'=> json_decode($x->email_cc_receiver),
    //                                 'subject' => $x->email_subject,
    //                                 'new_email' => $sender_mail[0]->email_id,
    //                             ];
                                
    //                             Mail::mailer('smtp')->send($x->template_name, $data, function ($message) use ($data) {
                                    
    //                                 $message->from($data['new_email'], 'Anudip Foundation');
    //                                 $message->to($data['email'])->cc($data['cc_email'])->subject($data['subject']);
    //                             });
    //                             //dd('dd');
    //                         }  
                            
    //                         DB::table('mailer_service_details')
    //                         ->where('id',$x->id)
    //                         ->update([
    //                             'status'=>1,
    //                             'email_send_date'=>now()                           
    //                         ]); 

    //                         $x=DB::table('mailer_service_email')
    //                         ->where('email_id',$sender_mail[0]->email_id)->get();

    //                         DB::table('mailer_service_email')
    //                         ->where('email_id',$sender_mail[0]->email_id)
    //                         ->update([
    //                             'email_send_count' => $x[0]->email_send_count+1,
    //                             'email_send_date'=> date('Y-m-d')                            
    //                         ]); 

    //                         if(($x[0]->email_send_count+1)>=300){
    //                             DB::table('mailer_service_status')
    //                             ->update([
    //                                 'status' => 0,
    //                             ]); 
    //                             //break;
    //                             //$this->snsService();
    //                             $this->checkSNSstatus();
    //                         }
    //                             //dd("mail_send");

    //                     } catch (\Exception $e) {
    //                        // dd($e);
    //                         //dd("mail_not_send");
    //                         DB::table('mailer_service_details')
    //                         ->where('id',$x->id)
    //                         ->update([
    //                               'status'=>2                            
    //                         ]); 
                            
    //                         DB::table('mailer_service_status')
    //                             ->update([
    //                                 'status' => 0
    //                             ]); 
    //                             //$this->snsService();
    //                         $this->checkSNSstatus();
    //                     }     

    //                 }

    //                 DB::table('mailer_service_status')
    //                 ->update([
    //                   'status' => 0, 
    //                 ]); 
    //                 $this->checkSNSstatus(); 
                    
    //             }catch (\Exception $e) {
    //                 //dd('vv',$sender_mail[0]->email_id,$e);
    //                 DB::table('mailer_service_email')
    //                 ->where('email_id',$sender_mail[0]->email_id)
    //                 ->update([
    //                       'active_status' => 0,    
    //                       'updated_at'=>now()                        
    //                 ]); 
    //                 //dd('vv',$sender_mail[0]->email_id);
    //                 DB::table('mailer_service_status')
    //                 ->update([
    //                     'status' => 0,                           
    //                 ]); 
    //                 $this->checkSNSstatus();
    //             }    
                
    //         }else{
    //             DB::table('mailer_service_status')
    //             ->update([
    //                   'status' => 0,                        
    //             ]); 
    //             $this->checkSNSstatus(); 
    //         }  

    //     }catch(\Exception $e) { 
    //         //DB::rollback();
    //         DB::table('mailer_service_status')
    //             ->update([
    //                   'status' => 0,                        
    //             ]);
    //         //dd($e);
            
    //         //return $this->sendError($e->getMessage());
    //     }
    // }





    


}
