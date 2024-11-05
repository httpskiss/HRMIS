
@extends('layouts.master')
@section('content')
    <style>
        .select {
            width: 100%; /* Make dropdowns responsive */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white; /* Light background color */
            color: #333; /* Text color */
            transition: border-color 0.3s; /* Smooth transition for border color */
        }
        .select:focus {
            border-color: red; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }
    </style>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Leaves <span id="year"></span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leaves</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                    </div>
                </div>
            </div>
            
            <!-- Leave Statistics -->
            <div class="row">
                @foreach($leaveInformation as $key => $leaves)
                    @if($leaves->leave_type != 'Total Leave Balance')   
                        <div class="col-md-2">
                            <div class="stats-info">
                                <h6>{{ $leaves->leave_type }}</h6>
                                <h4>{{ $leaves->leave_days }}</h4>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <!-- /Leave Statistics -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No of Days</th>
                                    <th>Reason</th>
                                    <th class="text-center">Status</th>
                                    <th>Approved by</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Casual Leave</td>
                                    <td>8 Mar 2019</td>
                                    <td>9 Mar 2019</td>
                                    <td>2 days</td>
                                    <td>Going to Hospital</td>
                                    <td class="text-center">
                                        <div class="action-label">
                                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                <i class="fa fa-dot-circle-o text-purple"></i> New
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="profile.html" class="avatar avatar-xs"><img src="{{URL::to('assets/img/profiles/avatar-09.jpg')}}" alt=""></a>
                                            <a href="#">Richard Miles</a>
                                        </h2>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Casual Leave</td>
                                    <td>10 Jan 2019</td>
                                    <td>10 Jan 2019</td>
                                    <td>First Half</td>
                                    <td>Going to Hospital</td>
                                    <td class="text-center">
                                        <div class="action-label">
                                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                <i class="fa fa-dot-circle-o text-danger"></i> Declined
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="profile.html" class="avatar avatar-xs"><img src="{{URL::to('assets/img/profiles/avatar-09.jpg')}}" alt=""></a>
                                            <a href="#">Richard Miles</a>
                                        </h2>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
       
		<!-- Add Leave Modal -->
        <div id="add_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="applyLeave" action="{{ route('form/leaves/save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" id="leave_type" name="leave_type">
                                            <option selected disabled>Select Leave Type</option>
                                            <option value="Medical Leave">Medical Leave</option>
                                            <option value="Casual Leave">Casual Leave</option>
                                            <option value="Sick Leave">Sick Leave</option>
                                            <option value="Annual Leave">Annual Leave</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="remaining_leave" name="remaining_leave" readonly value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker-cus" id="date_from" name="date_from">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker-cus" id="date_to" name="date_to">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6" id="leave_dates_display" style="display: none"></div>
                                <div class="col-md-6" id="select_leave_day" style="display: none"></div>
                            </div>
                            <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="number_of_day" name="number_of_day" value="0" readonly>
                            </div>
                            <div class="row">
                                <div id="leave_day_select" class="col-md-12">
                                    <div class="form-group">
                                        <label>Leave Day <span class="text-danger">*</span></label>
                                        <select class="select" name="select_leave_day[]" id="leave_day">
                                            <option value="Full-Day Leave">Full-Day Leave</option>
                                            <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                            <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                            <option value="Public Holiday">Public Holiday</option>
                                            <option value="Off Schedule">Off Schedule</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="2" class="form-control" name="reason"></textarea>
                            </div>
                           
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Leave Modal -->
        
        <!-- Edit Leave Modal -->
        <div id="edit_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select Leave Type</option>
                                    <option>Casual Leave 12 Days</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>To <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input class="form-control" readonly type="text" value="2">
                            </div>
                            <div class="form-group">
                                <label>Remaining Leaves <span class="text-danger">*</span></label>
                                <input class="form-control" readonly value="12" type="text">
                            </div>
                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="4" class="form-control">Going to hospital</textarea>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Modal -->
        
        <!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_approve" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to Cancel this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Modal -->

    </div>
    <!-- /Page Wrapper -->
@section('script')
    <!-- Calculate Leave  -->
    <script>
        // Define the URL for the AJAX request
        var url = "{{ route('hr/get/information/leave') }}";
        
        // Function to handle leave type change
        function handleLeaveTypeChange() {
            var leaveType   = $('#leave_type').val();
            var numberOfDay = $('#number_of_day').val();    
            $.post(url, {
                leave_type: leaveType,
                number_of_day: numberOfDay,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(data) {
                if (data.response_code == 200) {
                    $('#remaining_leave').val(data.leave_type);
                }
            }, 'json');
        }
        
        function countLeaveDays()
        {
            // Get the date values from input fields
            var dateFrom = new Date($('#date_from').val());
            var dateTo   = new Date($('#date_to').val());
            var leaveDay = $('#leave_day').val();
            
            if (!isNaN(dateFrom) && !isNaN(dateTo)) {
                var numDays = Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1;
                if (leaveDay.includes('Half-Day')) numDays -= 0.5;
                $('#number_of_day').val(numDays);
                updateRemainingLeave(numDays);

                // Clear previous display
                $('#leave_dates_display').empty();
                $('#select_leave_day').empty();

                // Display each date one by one if numDays > 0
                if (numDays > 0) {
                    for (let d = 0; d < numDays; d++) {
                        let currentDate = new Date(dateFrom);
                        currentDate.setDate(currentDate.getDate() + d);
                        var formattedDate = currentDate.getDate() + ' ' + (currentDate.getMonth() + 1) + ',' + currentDate.getFullYear();

                        document.getElementById('leave_day_select').style.display = 'block'; // or 'flex', depending on your layout
                        // Append each leave date to the display
                        if (numDays > 0) {
                            document.getElementById('leave_dates_display').style.display = 'block'; // or 'flex', depending on your layout
                            document.getElementById('select_leave_day').style.display = 'block'; // or 'flex', depending on your layout

                            const inputDate = formattedDate;
                            let [day, month, year] = inputDate.split(/[\s,]+/);
                            let date = new Date(year, month - 1, day - 1);
                            let formattedDateConvert = currentDate.getDate() + ' ' + currentDate.toLocaleString('en-GB', { month: 'short' }) + ', ' + currentDate.getFullYear();

                            // Create unique IDs for inputs and labels
                            let leaveDateInputId = `leave_date_${d}`;

                            // Append each leave date to the display
                            $('#leave_dates_display').append(`
                                <div class="form-group">
                                    <label><span class="text-danger">Leave Date ${d+1}</span></label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control" id="${leaveDateInputId}" name="leave_date[]" value="${formattedDateConvert}" readonly>
                                    </div>
                                </div>
                            `);
                            
                            // Function to generate leave day select elements
                            function generateLeaveDaySelects(numDays) {
                                $('#select_leave_day').empty(); // Clear existing elements
                                for (let d = 0; d < numDays; d++) {
                                    let leaveDayId = `leave_day_${d}`;
                                    document.getElementById('leave_day_select').style.display = 'none'; // or 'flex', depending on your layout
                                    $('#select_leave_day').append(`
                                        <div class="form-group">
                                            <label><span class="text-danger">Leave Day ${d+1}</span></label>
                                            <select class="select" name="select_leave_day[]" id="${leaveDayId}">
                                                <option value="Full-Day Leave">Full-Day Leave</option>
                                                <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                                <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                                <option value="Public Holiday">Public Holiday</option>
                                                <option value="Off Schedule">Off Schedule</option>
                                            </select>
                                        </div>
                                    `);
                                }
                            }

                            // Call this function when you need to set up the dropdowns
                            generateLeaveDaySelects(numDays);

                            // Function to update total leave days and remaining leave
                            function updateLeaveDaysAndRemaining() {
                                let totalDays = numDays; // Start with the total number of days
                                for (let d = 0; d < numDays; d++) {
                                    let leaveType = $(`#leave_day_${d}`).val(); // Get the selected leave type
                                    if (leaveType && leaveType.includes('Half-Day')) totalDays -= 0.5;
                                }
                                $('#number_of_day').val(totalDays);
                                // Update remaining leave
                                updateRemainingLeave(totalDays);
                            }

                            // Event listener for leave day selection change
                            $(document).on('change', '[id^="leave_day"]', updateLeaveDaysAndRemaining);


                            // Initial setup
                            updateLeaveDaysAndRemaining();
                        } else {
                            $('#leave_dates_display').hide();
                            $('#select_leave_day').hide();
                        }
                    }
                    
                }
            } else {
                $('#number_of_day').val('0');
                $('#leave_dates_display').text(''); // Clear the display in case of invalid dates
                $('#select_leave_day').text(''); // Clear the display in case of invalid dates
            }
        }
            
        // Function to update remaining leave
        function updateRemainingLeave(numDays) {
            $.post(url, {
                number_of_day: numDays,
                leave_type: $('#leave_type').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(data) {
                if (data.response_code == 200) {
                    $('#remaining_leave').val(data.leave_type);
                    $('#apply_leave').prop('disabled', data.leave_type <= 0);
                    if (data.leave_type < 0) {
                        toastr.info('You cannot apply for leave at this time.');
                    }
                }
            }, 'json');
        }
        
        // Event listeners
        $('#leave_type').on('change', handleLeaveTypeChange);
        $('#date_from, #date_to, #leave_day').on('dp.change', countLeaveDays);

        // Clearn data in form
        $(document).on('click', '.close', function() {
            // Clear the leave dates display
            $('#leave_dates_display').empty();
            // Clear the select leave day display
            $('#select_leave_day').empty();
            // Reset other relevant fields
            $('#number_of_day').val('');
            $('#date_from').val('');
            $('#date_to').val('');
            $('#leave_type').val(''); // Reset to default value if needed
            $('#remaining_leave').val('');
            // Optionally hide any UI elements
            $('#leave_day_select').hide(); // or reset to its original state
        });
    </script>
    
    <!-- Validate Form  -->
    <script>
        $(document).ready(function() {
            $("#applyLeave").validate({
                rules: {
                    leave_type: {
                        required: true,
                    },
                    date_from: {
                        required: true,
                    },
                    date_to: {
                        required: true,
                    },
                    reason: {
                        required: true,
                    }
                },
                messages: {
                    leave_type: {
                        required: "Please select leave type",
                    },
                    date_from: {
                        required: "Please select date from"
                    },
                    date_to: {
                        required: "Please select date to"
                    },
                    reason: {
                        required: "Please input reason leave"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.appendTo(element.parent());
                },
                submitHandler: function(form) {
                    form.submit(); // Submit the form if valid
                }
            });
        });

        $('#leave_type').on('change', function() {
            if ($(this).val()) {
                $(this).siblings('span.error').hide(); // Hide error if valid
            } else {
                $(this).siblings('span.error').show(); // Show error if invalid
            }
        });
    </script>
        
@endsection
@endsection
