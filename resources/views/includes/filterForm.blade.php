<form id="filterForm" method="POST" action="{{url('filter-data')}}">
    @csrf
    <div class="row">
        <div class="col-3 filter-box-col">
            <div class="mb-2">
                <label for="example-select" class="form-label">Districts</label>
                <select id="districtSelect" class="form-select" name="district_id" onchange="onDistrictChange()">
                    <option value="0">Select District</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->d_name }}" {{ (old('district_id', $selectedDistrict ?? '') == $district->d_name) ? 'selected' : '' }}>
                            {{ $district->d_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>
