                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center">{{ $title }}</h2>
                        <table style-"margin-top: 25px" class="factable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Indicator</th>
                                    <th>Jan - {{ $year }}</th> <th>Feb - {{ $year }}</th> <th>Mar - {{ $year }}</th> <th>Apr - {{ $year }}</th>
                                    <th>May - {{ $year }}</th> <th>Jun - {{ $year }}</th> <th>Jul - {{ $year }}</th> <th>Aug - {{ $year }}</th>
                                    <th>Sep - {{ $year }}</th> <th>Oct - {{ $year }}</th> <th>Nov - {{ $year }}</th> <th>Dec - {{ $year }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $name => $idc)
                                <tr>
                                    <td>{{ $name }}</td>
                                    @for($i=0; $i<12; $i++)

                                    <td> 
                                        {{ Form::text('idc_'.$idc[$i]['id'], $idc[$i]['actual'], 
                                                       array('class'=>'form-control','tooltip'=>'', 'tooltop-placement'=>'top', 'tooltip-trigger'=>'focus', 
                                                             'style'=>'width:50px;', 'onblur'=>'updateActual('.$idc[$i]['id'].')', 'id'=>'idc_'.$idc[$i]['id']))  }}

                                         <br/><span> T: {{ $idc[$i]['target'] }} </span>
                                         <input type="hidden" id="idc_oldval_{{ $idc[$i]['id'] }}" value="$idc[$i]['actual']" />
                                    </td>

                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                   </div>
                 </div>
