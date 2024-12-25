@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Data</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card card-h-100">
                <div class="card-body">
                    <form action="{{ url('update-data/' . $operation->operational_id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <h3 class="header-title mb-3">Basic Information</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="operationalId" class="form-label">Operational ID</label>
                                    <input type="text" id="operationalId" name="operational_id" class="form-control"
                                           value="{{ $operation->operational_id }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="agentId" class="form-label">Select Agent</label>
                                    <select class="form-select" id="agentId" name="agent_id">
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $operation->agent_id == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->agent_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="peopleQuantity" class="form-label">People Quantity</label>
                                    <input type="number" id="peopleQuantity" name="people_quantity" class="form-control"
                                           value="{{ $operation->people_quantity }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" id="nationality" name="nationality" class="form-control"
                                           value="{{ $operation->nationality }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="voucherNumber" class="form-label">Voucher Number</label>
                                    <input type="text" id="voucherNumber" name="voucher_number" class="form-control"
                                           value="{{ $operation->voucher_number }}" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="groupLeaderName" class="form-label">Group Leader Name</label>
                                    <input type="text" id="groupLeaderName" name="group_leader_name"
                                           class="form-control" value="{{ $operation->group_leader_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="groupLeaderNumber" class="form-label">Group Leader Number</label>
                                    <input type="text" id="groupLeaderNumber" name="group_leader_number"
                                           class="form-control" value="{{ $operation->group_leader_number }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Checkbox Section -->
                                <div class="col-xl-12 col-lg-12 mb-3">
                                    <h4 class="sub-header-title">Select Sections</h4>
                                    <hr>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="toggleArrivalSection"
                                               onchange="toggleSection('arrivalSection')" <?php echo isset($operation->arrival) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="toggleArrival">Arrival</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="toggleMovementSection"
                                               onchange="toggleSection('movementSection')" <?php echo isset($operation->movement) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="toggleMovement">Movement</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="toggleMzaratSection"
                                               onchange="toggleSection('mzaratSection')" <?php echo isset($operation->mzarat) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="toggleMzarat">Mzarat</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="toggleDepartureSection"
                                               onchange="toggleSection('departureSection')" <?php echo isset($operation->departure) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="toggleDeparture">Departure</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Arrival Section -->
                            <div class="row mb-4 arrivalSection" style="{{ $operation->arrival ? '' : 'display: none;' }}">
                                <h4 class="sub-header-title">Arrival</h4>
                                <hr>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="voucherType" class="form-label">Arrival Date</label>
                                        <input type="date" id="arrival" name="arrival[arrival_date]"
                                               class="form-control" placeholder="Select Date"
                                               value="{{ old('arrival.arrival_date', $operation->arrival->arrival_date ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="arrivalTime" class="form-label">Arrival Time</label>
                                        <input type="time" id="arrivalTime" name="arrival[arrival_time]"
                                               class="form-control timepicker" placeholder="Select Time"
                                               value="{{ old('arrival.arrival_time', $operation->arrival->arrival_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="arrivalFlightNo" class="form-label">Flight Number</label>
                                        <input type="text" id="arrivalFlightNo" name="arrival[arrival_flight_no]"
                                               class="form-control" placeholder="Enter Arrival Flight Number"
                                               value="{{ old('arrival.arrival_flight_no', $operation->arrival->arrival_flight_no ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="terminalName" class="form-label">Terminal Name</label>
                                        <input type="text" id="terminalName" name="arrival[terminal_name]"
                                               class="form-control" placeholder="Enter Terminal Name"
                                               value="{{ old('arrival.terminal_name', $operation->arrival->terminal_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportTime" class="form-label">Transport Time</label>
                                        <input type="time" id="transportTime" name="arrival[transport_time]"
                                               class="form-control timepicker" placeholder="Transport Time"
                                               value="{{ old('arrival.transport_time', $operation->arrival->transport_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportCompany" class="form-label">Transport Company</label>
                                        <input type="text" id="transportCompany" name="arrival[transport_company]"
                                               class="form-control" placeholder="Enter Transport Company"
                                               value="{{ old('arrival.transport_company', $operation->arrival->transport_company ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Movement Section -->
                            <div class="row mb-4 movementSection" style="{{ $operation->movement ? '' : 'display: none;' }}">
                                <h4 class="sub-header-title">Movement</h4>
                                <hr>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="voucherType" class="form-label">Travel Date</label>
                                        <input type="date" id="movement" name="movement[travel_date]"
                                               class="form-control" placeholder="Enter Voucher Type"
                                               value="{{ old('movement.travel_date', $operation->movement->travel_date ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelTime" class="form-label">Travel Time</label>
                                        <input type="time" id="travelTime" name="movement[travel_time]"
                                               class="form-control timepicker" placeholder="Select Time"
                                               value="{{ old('movement.travel_time', $operation->movement->travel_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelFrom" class="form-label">Travel From</label>
                                        <input type="text" id="travelFrom" name="movement[travel_from]"
                                               class="form-control" placeholder="Enter Travel From"
                                               value="{{ old('movement.travel_from', $operation->movement->travel_from ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelTo" class="form-label">Travel To</label>
                                        <input type="text" id="travelTo" name="movement[travel_to]" class="form-control"
                                               placeholder="Enter Travel To"
                                               value="{{ old('movement.travel_to', $operation->movement->travel_to ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportTime" class="form-label">Transport Time</label>
                                        <input type="time" id="transportTime" name="movement[transport_time]"
                                               class="form-control timepicker" placeholder="Transport Time"
                                               value="{{ old('movement.transport_time', $operation->movement->transport_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportCompany" class="form-label">Transport Company</label>
                                        <input type="text" id="transportCompany" name="movement[transport_company]"
                                               class="form-control" placeholder="Enter Transport Company"
                                               value="{{ old('movement.transport_company', $operation->movement->transport_company ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Mzarat Section -->
                            <div class="row mb-4 mzaratSection" style="{{ $operation->mzarat ? '' : 'display: none;' }}">
                                <h4 class="sub-header-title">Mzarat</h4>
                                <hr>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="voucherType" class="form-label">Travel Date</label>
                                        <input type="date" id="mzarat" name="mzarat[travel_date]"
                                               class="form-control" placeholder="Enter Travel Date"
                                               value="{{ old('mzarat.travel_date', $operation->mzarat->travel_date ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelTime" class="form-label">Travel Time</label>
                                        <input type="time" id="travelTime" name="mzarat[travel_time]"
                                               class="form-control timepicker" placeholder="Select Time"
                                               value="{{ old('mzarat.travel_time', $operation->mzarat->travel_time ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelFrom" class="form-label">Travel From</label>
                                        <input type="text" id="travelFrom" name="mzarat[travel_from]"
                                               class="form-control" placeholder="Enter Travel From"
                                               value="{{ old('mzarat.travel_from', $operation->mzarat->travel_from ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="travelTo" class="form-label">Travel To</label>
                                        <input type="text" id="travelTo" name="mzarat[travel_to]" class="form-control"
                                               placeholder="Enter Travel To"
                                               value="{{ old('mzarat.travel_to', $operation->mzarat->travel_to ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportTime" class="form-label">Transport Time</label>
                                        <input type="time" id="transportTime" name="mzarat[transport_time]"
                                               class="form-control timepicker" placeholder="Transport Time"
                                               value="{{ old('mzarat.transport_time', $operation->mzarat->transport_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportCompany" class="form-label">Transport Company</label>
                                        <input type="text" id="transportCompany" name="mzarat[transport_company]"
                                               class="form-control" placeholder="Enter Transport Company"
                                               value="{{ old('mzarat.transport_company', $operation->mzarat->transport_company ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Departure Section -->
                            <div class="row mb-4 departureSection" style="{{ $operation->departure ? '' : 'display: none;' }}">
                                <h4 class="sub-header-title">Departure</h4>
                                <hr>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="departureDate" class="form-label">Departure Date</label>
                                        <input type="date" id="departureDate" name="departure[departure_date]"
                                               class="form-control" placeholder="Enter Travel Date"
                                               value="{{ old('departure.departure_date', $operation->departure->departure_date ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="departureTime" class="form-label">Departure Time</label>
                                        <input type="time" id="departureTime" name="departure[departure_time]"
                                               class="form-control timepicker" placeholder="Departure Time"
                                               value="{{ old('departure.departure_time', $operation->departure->departure_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="departureFlightNo" class="form-label"> Flight Number</label>
                                        <input type="text" id="departureFlightNo" name="departure[departure_flight_no]"
                                               class="form-control" placeholder="Departure Flight Number"
                                               value="{{ old('departure.departure_flight_no', $operation->departure->departure_flight_no ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="terminalName" class="form-label">Terminal Name</label>
                                        <input type="text" id="terminalName" name="departure[terminal_name]" class="form-control"
                                               placeholder="Enter Terminal Name"
                                               value="{{ old('departure.terminal_name', $operation->departure->terminal_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportTime" class="form-label">Transport Time</label>
                                        <input type="time" id="transportTime" name="departure[transport_time]"
                                               class="form-control timepicker" placeholder="Transport Time"
                                               value="{{ old('departure.transport_time', $operation->departure->transport_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="mb-3">
                                        <label for="transportCompany" class="form-label">Transport Company</label>
                                        <input type="text" id="transportCompany" name="departure[transport_company]"
                                               class="form-control" placeholder="Enter Transport Company"
                                               value="{{ old('departure.transport_company', $operation->departure->transport_company ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            @if($operation->status == 1)
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea type="text" id="comments" name="comments"
                                           class="form-control" placeholder="Enter Transport Company">
                                       {{ $operation->comments->pluck('comments')->join(', ') }}
                                    </textarea>
                                </div>
                            </div>
                            @endif
                            <div class="col-xl-12 col-lg-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </div> <!--Row End--->
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
@endsection

