@if(count($careers) > 0)

<div class="p-5 mt-4 container-career">
    
    {{-- <div class="row text-align-center justify-content-center container features">
        <div class="career-header text-align-center career-title"><h1>Career Offers</h1></div>
        <hr class="hr-white">
        @foreach ($careers as $career)
            <div class="col-3">
                <div class="career-card p-3">
                    <h2>{{$career->job_name}} </h2><br>
                        Salary: {{$career->salary}} <br>
                        Company:: {{$career->company}} <br>
                        Contact - 
                            <br>&ensp; email: <u>{{$career->email}}</u>
                            <br>&ensp; number: <u>{{$career->number}}</u>
                </div>
            </div>
        @endforeach
    </div> --}}
    <section class="features mt-4">
        
        <div class="container">
            <div class="text-align-center career-title"><h1>Career Offers</h1></div>
            <hr class="hr-white">

            <div class="row justify-content-center align-items-center slick-feature">              
                @foreach ($careers as $career)
                    <div class="col-12 py-3 mx-3"> 
                        <div class="career-card">
                            <div class="career-header p-4">
                                <h2>{{$career->job_name}} </h2><br>
                                    Salary: {{$career->salary}} <br>
                                    Company:: {{$career->company}} <br>
                                    Contact: 
                                        <br>&ensp; email: <u>{{$career->email}}</u>
                                        <br>&ensp; number: <u>{{$career->number}}</u>
                                        <br>
                                <div hidden>{{$text = $career->description}} {{ $ctrCareer++;}}</div>
                                <div style="width:100%; text-align:right;">
                                  <p>
                                    <a data-bs-toggle="collapse" href="#{{'career'.$ctrCareer}}" role="button" aria-expanded="false" aria-controls="{{'career'.$ctrCareer}}" style="color:black;">
                                      See description...
                                    </a>
                                  </p>
                                </div>
                            </div>
                        </div>
                        {{-- //input --}}
                        
                          <div class="collapse" id="{{'career'.$ctrCareer}}">
                            <div class="card card-body">
                              <?php echo $career->description ?>
                            </div>
                          </div>
                    </div> 
                    
                @endforeach
            </div>
            
        </div>
    </section>
</div>
@endif
