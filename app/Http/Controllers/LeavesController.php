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
    public function leaves()
    {
        $leaves = DB::table('leaves_admins')->join('users', 'users.user_id','leaves_admins.user_id')->select('leaves_admins.*', 'users.position','users.name','users.avatar')->get();
        return view('employees.leaves',compact('leaves'));
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

    /** Save Record Leave */
    public function saveRecordLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required',
            'date_to'    => 'required',
            'reason'     => 'required',
        ]);

        try {
            
            $save  = new Leave;
            $save->staff_id         = Session::get('user_id');
            $save->employee_name    = Session::get('name');
            $save->leave_type       = $request->leave_type;
            $save->remaining_leave  = $request->remaining_leave;
            $save->date_from        = $request->date_from;
            $save->date_to          = $request->date_to;
            $save->number_of_day    = $request->number_of_day;
            $save->leave_date       = json_encode($request->leave_date);
            $save->leave_day        = json_encode($request->select_leave_day);
            $save->status           = 'Pending';
            $save->reason           = $request->reason;
            $save->save();
    
            flash()->success('Apply Leave successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }
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
        return view('employees.leavesettings');
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
        $leave = Leave::where('staff_id', Session::get('user_id'))->get();
        return view('employees.leavesemployee',compact('leaveInformation'));
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
