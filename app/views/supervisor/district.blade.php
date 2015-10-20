<section class="content invoice">

	<div class="row">
        <div class="col-xs-12">
       	    <h2 class="page-header">Your Districts</h2>
        </div>
    </div>

    <div class="box-body table-responsive">
                         <table id="distable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
				                <tbody>
                               @foreach(Auth::user()->districts() as $k => $value)
                                     <tr>
                                          <td> {{ $value }} </td>
                                          <td>
                                                <a class="btn btn-sm btn-info" href="{{ URL::to('facilities/districtcalendar/' . Auth::user()->id. '/'.$value ) }}">Monthly Plan</a>
                                                <button type="button" class="btn btn-default btn-sm disabled">Targets</button>
                                          </td>
                                     </tr>
                                @endforeach
                                </tbody>
                     </table>
    </div>
</section>

<section class="content invoice">
	<div class="row">
        <div class="col-xs-12">
       	    <h2 class="page-header">Your Facilities</h2>
        </div>
    </div>

    <div class="box-body table-responsive">
                         <table id="factable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
				<tbody>
                               @foreach(Auth::user()->facilities as $k => $value)
                                     <tr>
                                          <td> {{ $value->name }} </td>
                                          <td> {{ $value->district}} </td>
                                          <td>
                                                <a class="btn btn-sm btn-info" href="{{ URL::to('facilities/calendar/' . $value->id ) }}">Monthly Plan</a>
                                                <button type="button" class="btn btn-default btn-sm disabled">Targets</button>
                                          </td>
                                     </tr>
                                @endforeach
                                </tbody>
                        </table>
       </div>
</section>

<!--
<section class="content invoice">

	<div class="row">
        	<div class="col-xs-12">
                	<h2 class="page-header">Your nurses</h2>
                </div>
        </div>
        <div class="box-body table-responsive">
                         <table id="nursestable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Facility</th>
                                        <th>Actions</th>
                              <th>Title</th>
                                        <th>Phone number</th>
                                        <th>Courses Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                               @foreach($nurses as $k => $value)
                                     <tr>
                                          <td> {{ $value->getName() }} </td>
										  <td> {{ $value->myfac }}</td>
					  <td> {{ $value->title or 'No title' }} </td>
                                          <td> {{ $value->phone_number or 'No number' }} </td>
                                          <td> </td>                                         

                                          <td>
                                                <a class="btn btn-sm btn-info" href="{{ URL::to('users/calendar/' . $value->id ) }}">Monthly Plan</a>
                                                <a class="btn btn-sm btn-info" href="{{ URL::to('users/courses/' . $value->id ) }}">Courses</a>
                                                <button type="button" class="btn btn-default btn-sm disabled">Targets</button>
                                          </td>
                                     </tr>
                                @endforeach
                                </tbody>
                        </table>
               </div>
</section>
-->

@section('script')
        <script type="text/javascript">
            $(function() {
                $('#factable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
/*		$('#nursestable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
            */
            });
        </script>
@stop
