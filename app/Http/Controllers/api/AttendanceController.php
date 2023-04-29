<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use DB;
use Image;
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
    public function storeAttendance(Request $request)
    {
        
        DB::beginTransaction();
        try { 
            date_default_timezone_set('Asia/Calcutta');
                if(str_starts_with($request->member_code, 'AF')){
                $member_type='student';
                }else{
                $member_type='staff';
                }
                $attn_type=$request->attend_date==date('Y-m-d')?'present':'past';
                $postParameter = ['user_id' => $request->user_id,'atten_date' => $request->attend_date,'punch_in'=>date('H:i:s'),'lat'=>$request->lat,'long'=>$request->long,'member_id'=>$request->member_id,'member_code'=>$request->member_code,'status'=>2,'transfer_status'=>1,'atten_type'=>$attn_type,'member_type'=>$member_type];

                
                
            $details = Attendance::where('atten_date', $request->attend_date)->get();
            if(sizeof($details)>0){
                $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                $curlResponse = curl_exec($curlHandle);
                //dd($curlResponse);
                curl_close($curlHandle);
                Attendance::where('atten_date', $request->attend_date)->update(['punch_out'=>date('H:i:s'),'lat'=>$request->lat,'long'=>$request->long,'status'=>0]);
                $x=['punch_out'=>date('H:i:s'),'date' => $request->attend_date,'punch_in'=>$details[0]->punch_in];
                DB::commit();
                    return Response(['message' => 'updated successfully','status'=>1,'data'=>$x],200);
            }
            
            Attendance::create($postParameter);
            $curlHandle = curl_init('https://cmis3api.anudip.org/api/insertFromAttenApp');
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            $curlResponse = curl_exec($curlHandle);
            //dd($curlResponse);
            curl_close($curlHandle);
            $x=['punch_in'=>date('H:i:s'),'date' => $request->attend_date];
            DB::commit();
            return Response(['message' => 'inserted successfully','status'=>1,'data'=>$x],200);

        } catch (Exception $e) { 
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
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
       
        //return $this->continue_func(56);
        return $this->sendResponse([], "You have sucessfully save given details");
        return Response(['datas' => $request->all(),'status'=>1,'cur_date'=>date('Y-m-d')],200);
        try{
          $student_id = Attendance::create([
            'name' => 'Test User',
            'username' => 'AF0123FG',
            'email' => 'test@example.com',
            'mobile_no'=>'9878767654',
            'password' => bcrypt('123'),

            
            ]);
            return $this->sendResponse([], "You have sucessfully save given details");
  
        }
        catch(\Exception $e){
          DB::rollback();
          return $this->sendError($e->getMessage());
        }
  
    }
}
