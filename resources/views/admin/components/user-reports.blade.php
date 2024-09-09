@if ($type == 1)
    <div class="row sub-container-box mt-5 pt-4 pb-4 gx-5">
        <div class="col-5">
            {{-- Students pie chart - courses --}}
            <canvas id="userReport-chart1"></canvas>
            <script>
                var alumniPerCourses = @json($alumniPerCourses);
            </script>
        </div>
        <div class="col-7">
            <h3 class="my-3">Number of Students per Course batch {{ $batch }}</h3>
            <div class="row my-2">
                @foreach ($courses as $course)
                    @if ($listOfStudents->contains('course_id', $course->course_id))
                        <div class="col-6 mb-3">
                            <p class="my-0 fw-bold">{{ $course->course_id }}</p>
                            <p class="my-0">Total: {{ $numPerCourse = $listOfStudents->where("course_id", "=", $course->course_id)->count() }}</p>
                            <p class="my-0">Percentage: {{ $percentage = round($numPerCourse / $totalStudents * 100, 2) }}%</p>
                        </div>

                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="row sub-container-box mt-5 pt-4 pb-4 gx-5">
        <div class="col-5">
            {{-- Students pie chart - courses --}}
            <canvas id="userReport-chart2"></canvas>
            <script>
                var alumniPerAge = @json($alumniPerAge);
            </script>
        </div>
        <div class="col-7">
            <h3 class="my-3">Number of Students per Age batch {{ $batch }}</h3>
            <div class="row my-2">
                @foreach ($alumniPerAge as $key => $value)
                    <div class="col-6 mb-3">
                        <p class="my-0 fw-bold">Age of {{ $key }}</p>
                        <p class="my-0">Total: {{ $value }}</p>
                        <p class="my-0">Percentage: {{ $percentage = round($value / $totalStudents * 100, 2) }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row sub-container-box mt-5 pt-4 pb-4 gx-5">
        <div class="col-5">
            {{-- Students pie chart - courses --}}
            <canvas id="userReport-chart3"></canvas>
            <script>
                var alumniPerSex = @json($alumniPerSex);
            </script>
        </div>
        <div class="col-7">
            <h3 class="my-3">Number of Students per sex batch {{ $batch }}</h3>
            <div class="row my-2">
                @foreach ($alumniPerSex as $key => $value)
                    <div class="col-6 mb-3">
                        <p class="my-0 fw-bold">{{ $key }}</p>
                        <p class="my-0">Total: {{ $value }}</p>
                        <p class="my-0">Percentage: {{ $percentage = round($value / $totalStudents * 100, 2) }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@elseif ($type == 2)
    @foreach ($courses as $course)
    @if ($listOfStudents->contains('course_id', $course->course_id))
    <div class="row sub-container-box mt-5 pt-4 pb-4">
        <div class="col-12">
            <h3 class="mt-3 text-center">{{ $course->course_desc }}</h3>
            <h4 class="mb-5 text-center">PUP Taguig Alumni Batch of {{ $batch }}</h4>
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th style="width: 20%;">Full Name</th>
                        <th style="width: 10%;">Email</th>
                        <th style="width: 10%;">Number</th>
                        <th style="width: 20%;">Date of Birth</th>
                        <th style="width: 40%;">Address</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($listOfStudents as $student)
                    @if ($student->course_id == $course->course_id)
                        <tr>
                            <td class="text-uppercase">
                                {{ $student->last_name }}, {{ $student->first_name }} {{ $student->suffix }} {{ $student->middle_name }}
                            </td>
                            <td class="text-center">{{ $student->email }}</td>
                            <td class="text-center">{{ $student->number }}</td>
                            <td class="text-center">{{ date("F d, Y", strtotime($student->birthday)) }}</td>
                            <td class="text-center">{{ $student->city_address }}</td>
                        </tr>
                    @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endforeach

@elseif ($type == 3)
    @foreach ($sexs as $sex)
    @if ($listOfStudents->contains('sex', $sex))
    <div class="row sub-container-box mt-5 pt-4 pb-4">
        <div class="col-12">
            <h3 class="mt-3 text-center">PUP Taguig Alumni Batch of {{ $batch }}</h3>
            <h4 class="mb-5 text-center text-uppercase">{{ $sex }}</h4>
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th style="width: 20%;">Full Name</th>
                        <th style="width: 10%;">Email</th>
                        <th style="width: 10%;">Number</th>
                        <th style="width: 20%;">Date of Birth</th>
                        <th style="width: 40%;">Address</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($listOfStudents as $student)
                    @if ($student->sex == $sex)
                        <tr>
                            <td class="text-uppercase">
                                {{ $student->last_name }}, {{ $student->first_name }} {{ $student->suffix }} {{ $student->middle_name }}
                            </td>
                            <td class="text-center">{{ $student->email }}</td>
                            <td class="text-center">{{ $student->number }}</td>
                            <td class="text-center">{{ date("F d, Y", strtotime($student->birthday)) }}</td>
                            <td class="text-center">{{ $student->city_address }}</td>
                        </tr>
                    @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endforeach

@endif

