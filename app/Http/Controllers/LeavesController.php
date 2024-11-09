<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveInformation;
use App\Models\LeavesAdmin;
use App\Models\Leave;
use DateTime;
use Session;
use DB;

class LeavesController extends Controller
{
    /** Leaves Admin Page */
    public function leavesAdmin()
    {
        $leaves = DB::table('leaves_admins')->join('users', 'users.user_id','leaves_admins.user_id')->select('leaves_admins.*', 'users.position','users.name','users.avatar')->get();
        return view('employees.leaves_manage.leavesadmin',compact('leaves'));
    }

    /** Get Information Leave */
    public function getInformationLeave(Request $request)
    {
        try {

            $numberOfDay = $request->number_of_day;
            $leaveType   = $request->leave_type;
            $leaveDay = LeaveInformation::where('leave_type', $leaveType)->first();
            
            if ($leaveDay) {
                $days = $leaveDay->leave_days - ($numberOfDay ?? 0);
            } else {
                $days = 0; // Handle case if leave type doesn't exist
            }

            $data = [
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => $days,
                'number_of_day' => $numberOfDay,
            ];
            
            return response()->json($data);

        } catch (\Exception $e) {
            // Log the exception and return an appropriate response
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    /** Apply Leave */
    public function saveRecordLeave(Request $request)
    {
        // Create an instance of the Leave model
        $leave = new Leave();
        // Call the applyLeave method
        return $leave->applyLeave($request);
    }

    /** Edit Leave */
    public function editLeave($staff_id)
    {
        $leaveInformation = LeaveInformation::all();
        $leaveDetail = Leave::where('staff_id', $staff_id)->first();
        $leaveDate   = json_decode($leaveDetail->leave_date, true); // Decode JSON to array
        $leaveDay    = json_decode($leaveDetail->leave_day, true); // Decode JSON to array
        return view('employees.leaves_manage.leavesemployee',compact('leaveInformation','leaveDetail','leaveDate','leaveDay'));
    }

    /** Edit Record */
    public function editRecordLeave(Request $request)
    {
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date   = new DateTime($request->to_date);
            $day       = $from_date->diff($to_date);
            $days      = $day->d;

            $update = [
                'id'           => $request->id,
                'leave_type'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'leave_reason' => $request->leave_reason,
            ];

            LeavesAdmin::where('id',$request->id)->update($update);
            DB::commit();
            flash()->success('Updated Leaves successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Update Leaves fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteLeave(Request $request)
    {
        try {
            LeavesAdmin::destroy($request->id);
            flash()->success('Leaves admin deleted successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Leaves admin delete fail :)');
            return redirect()->back();
        }
    }

    /** Leave Settings Page */
    public function leaveSettings()
    {
        return view('employees.leaves_manage.leavesettings');
    }

    /** Attendance Admin */
    public function attendanceIndex()
    {
        return view('employees.attendance');
    }

    /** Attendance Employee */
    public function AttendanceEmployee()
    {
        return view('employees.attendanceemployee');
    }

    /** Leaves Employee Page */
    public function leavesEmployee()
    {
        $leaveInformation = LeaveInformation::all();
        $getLeave = Leave::where('staff_id', Session::get('user_id'))->get();

        return view('employees.leaves_manage.leavesemployee',compact('leaveInformation', 'getLeave'));
    }

    /** Shift Scheduling */
    public function shiftScheduLing()
    {
        return view('employees.shiftscheduling');
    }

    /** Shift List */
    public function shiftList()
    {
        return view('employees.shiftlist');
    }
}
