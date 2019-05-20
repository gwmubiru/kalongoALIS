@extends("layout")
@section("content")

<div class="row">
	<span style="font-weight: bold; color:blue;">DATA BELOW IS FOR CURRENT MONTH - <?php echo date('01-m-Y'); ?> to 
		<?php echo date('d-m-Y'); ?></span>
						<div class="col-md-12">
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="panel panel-default"><b>Patients and Tests</b>
										<div class="stat_box">
											<div class="stat_ico color_a"><i class="ion-ios-people"></i></div>
											<div class="stat_content">
												@if(UnhlsVisit::count() > 0)
												<span class="stat_count">{{UnhlsVisit::whereMonth('created_at', '=', Carbon::today()->month)->count()}} 
													({{UnhlsVisit::where('visit_type', '=', 'Out-patient')->whereMonth('created_at', '=', Carbon::today()->month)->count()*100/UnhlsVisit::count()}}% - OPD)</span>
												@endif
												<span class="stat_name">Number of patients</span>
											</div>

										</div>
										<div class="stat_box">
											<div class="stat_ico color_a"><i class="ion-clipboard"></i></div>
											<div class="stat_content">
												<span class="stat_count">{{UnhlsTest::where('time_completed','!=', 'NULL')->whereMonth('time_created', '=', Carbon::today()->month )->count()}}</span>
												<span class="stat_name">Tests done</span>
											</div>
											
										</div>
										<div class="stat_box">
											<div class="stat_ico color_a"><i class="ion-forward"></i></div>
											<div class="stat_content">
												@if(UnhlsTest::where('test_status_id','=', 4)->count() > 0 and Referral::whereMonth('created_at', '=', Carbon::today()->month)->count())
												<span class="stat_count">{{round(Referral::whereMonth('created_at', '=', Carbon::today()->month)->count()/
													UnhlsTest::where('test_status_id','=', 4)->whereMonth('time_created', '=', Carbon::today()->month)->count()/100, 2)}}%</span>
												@endif
												<span class="stat_name">Tests referred</span>
											</div>
											
										</div>										
									</div>
								</div>

								<div class="col-lg-6 col-md-6">
									<div class="panel panel-default"><b>Prevalences</b>
										<div class="stat_box">
											<div class="stat_ico color_b"><i class="ion-ios-personadd"></i></div>
											<div class="stat_content">
												<span class="stat_count"> 8 % </span>
												<span class="stat_name">HIV Prevalence</span>
											</div>
										</div>
										<div class="stat_box">
											<div class="stat_ico color_b"><i class="ion-ios-personadd"></i></div>
											<div class="stat_content">
												<span class="stat_count"> 6 % </span>
												<span class="stat_name">Malaria Prevalence</span>
											</div>
										</div>
										<div class="stat_box">
											<div class="stat_ico color_b"><i class="ion-ios-personadd"></i></div>
											<div class="stat_content">
												<span class="stat_count">9 % </span>
												<span class="stat_name">TB Prevalence</span>
											</div>
										</div>																				
									</div>
								</div>

								

							</div>
								
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="panel panel-default"><b>Samples</b>
										<div class="stat_box">
											<div class="stat_ico color_c"><i class="ion-ios-people"></i></div>
											<div class="stat_content">
												<span class="stat_count">{{UnhlsSpecimen::whereMonth('time_collected', '=', Carbon::today()->month)->count()}}</span>
												<span class="stat_name">Samples collected</span>
											</div>
										</div>
										<div class="stat_box">
											<div class="stat_ico color_c"><i class="ion-ios-close"></i></div>
											<div class="stat_content">
												@if(UnhlsSpecimen::count() > 0 and UnhlsSpecimen::where('specimen_status_id', '=',3)->whereMonth('time_collected', '=', Carbon::today()->month)->count())
												<span class="stat_count">{{round(UnhlsSpecimen::where('specimen_status_id', '=',3)->whereMonth('time_collected', '=', Carbon::today()->month)->count()*100/
													UnhlsSpecimen::whereMonth('time_collected', '=', Carbon::today()->month)->count(), 2)}} % </span>
												@endif
												<span class="stat_name">Samples rejected</span>
											</div>
										</div>
										<div class="stat_box">
											<div class="stat_ico color_c"><i class="ion-ios-checkmark"></i></div>
											<div class="stat_content">
												@if(UnhlsSpecimen::count() > 0 and UnhlsSpecimen::whereMonth('time_collected', '=', Carbon::today()->month)->where('specimen_status_id', '=', 2)->count())
												<span class="stat_count">{{round(UnhlsSpecimen::whereMonth('time_collected', '=', Carbon::today()->month)->where('specimen_status_id', '=', 2)->count()*100/
													UnhlsSpecimen::whereMonth('time_collected', '=', Carbon::today()->month)->count(), 2)}} %</span>
												@endif
												<span class="stat_name">Samples accepted</span>
											</div>
										</div>
									</div>
								</div>

								<div class="col-lg-6 col-md-6">
									<div class="panel panel-default">
										<div class="stat_box">
											<div class="stat_ico color_f"><i class="ion-ios-people"></i></div>
											<div class="stat_content">
												<span class="stat_count">7</span>
												<span class="stat_name">Number of Lab Staff</span>
											</div>
										</div>
										<div class="stat_box">
											<div class="stat_ico color_f"><i class="ion-ios-person"></i></div>
											<div class="stat_content">
												<span class="stat_count">26 %</span>
												<span class="stat_name">Percentage of volunteers</span>
											</div>
										</div>

									</div>
								</div>

							</div>								
						</div>
					</div>


	
@stop