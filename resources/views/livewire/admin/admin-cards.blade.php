<div class="row">
    <div class="col-12">
        <div class="sub-container-box mb-4 py-3">
            <div class="registered-user-chart">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="fs-4 fw-bold mb-2">Registered Users</p>
                    <p class="mb-2">Total: {{ $totalStudents }}</p>
                </div>
                <p class="text-secondary">As of {{ date('F d, Y') }}</p>
            </div>
            <div class="registered-user-bar">
                <script>
                    var totalRegisteredUser = <?php echo $totalRegisteredUser; ?>
                </script>
                <div>
                    <div id="registered-user-bar" style="width: 900px; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="sub-container-box py-3 h-100">
            <div class="registered-user-chart">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="fs-4 fw-bold mb-2">Registered Users by Sex</p>
                </div>
                <p class="text-secondary">As of {{ date('F d, Y') }}</p>
            </div>
            <div class="text-center">
                <script>
                    var totalregisteredUserSex = <?php echo $totalregisteredUserSex; ?>
                </script>
                <div id="registered-user-sex" class="registered-user-sex">
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="sub-container-box py-4 h-100">
            <h3 class="my-3">New Accounts</h3>
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                    <th scope="col" class="col-4">Last Name</th>
                    <th scope="col" class="col-3">Course</th>
                    <th scope="col" class="col-2">Batch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listOfNewAccounts as $alum)
                      <tr>
                          <td class="py-3">{{ $alum->last_name }}</td>
                          <td class="py-3">{{ $alum->course_id }}</td>
                          <td class="py-3">{{ $alum->batch }}</td>
                      </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
