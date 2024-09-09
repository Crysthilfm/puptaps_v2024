<div class="row">
    <div class="col-md-12">
        @if (count($careers) == 0)
        <div class="alert alert-danger" role="alert">
            There is no available data yet.
        </div>

        @else
            @foreach ($careers as $career)
                <table class="table table-borderless career-table table-sm align-middle">
                    @foreach ($alumni as $alum)
                        @if (($alum->alumni_id) == ($career->alumni_id))
                            <tr>
                                <td rowspan="2" style="width: 5%;">
                                    @if ($alum->user_pfp == null)
                                        <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="career-post-profile" data-bs-toggle="dropdown" aria-expanded="false">
                                    @else
                                        <img src="/Uploads/Profiles/{{ $alum->user_pfp }}" class="career-post-profile" data-bs-toggle="dropdown" aria-expanded="false">
                                    @endif
                                </td>
                                <th style="width: 95%">{{ $alum->username }}</th>
                            </tr>
                            <tr>
                                <td>{{ date('F d, Y h:m:a', strtotime($career->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    @if (($career->job_ad_image) == null)
                                    @else
                                        <a href="#">
                                            <div class="career-post-image-box">
                                                <img src="/Uploads/Career/{{ $career->job_ad_image }}" alt="" class="career-post-image">
                                            </div>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td>

                        </td>
                    </tr>
                </table>
            @endforeach
        @endif
    </div>
</div>
