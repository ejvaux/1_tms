<div class='row mb-1'>
    <div class='col-lg table-responsive-lg'>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@sortablelink('priority_id','Priority')</th>
                    <th>@sortablelink('subject','Subject')</th>
                    <th>@sortablelink('status_id','Status')</th>
                    <th>@sortablelink('created_at','Date')</th>
                    <th>@sortablelink('assigned_to','Assigned')</th>
                    <th>@sortablelink('updated_at','Updated')</th>
                </tr>
            </thead>
            <tbody>
                @if (count($tickets)>0)
                    @foreach($tickets as $ticket)
                        <tr>
                            <th>{{ $loop->iteration + (($tickets->currentPage() - 1) * 10) }}</th>
                            <th>
                                {!! CustomFunctions::priority_format($ticket->priority_id) !!}<br>
                                <span style="font-size:.8rem">
                                    @if ($ticket->start_at == null)
                                        @if ($ticket->status_id == 2)
                                            On Queue
                                        @else
                                            For Queuing
                                        @endif                                                
                                    @else
                                        @if($ticket->finish_at == null)
                                        {!! CustomFunctions::datetimelapse($ticket->start_at) !!}
                                        @else
                                        {!! CustomFunctions::datetimefinished($ticket->start_at,$ticket->finish_at) !!}
                                        @endif
                                    @endif
                                </span>
                            </th>
                            <th style='width:35vw'>
                                <div class='row' style="font-size:1rem">
                                    <div class='col-lg' style='overflow:hidden;text-overflow:ellipsis; white-space: nowrap ;width:300px'>
                                        <a href="/1_atms/public/it/vt/{{$ticket->id}}" ><span>{{$ticket->subject}}</span></a>
                                    </div>                                                                                
                                </div>
                                <div class='row' style='font-size:.8rem'>
                                    <div class='col-lg'>
                                        <span class='text-muted'><i class="fa fa-user"></i> 
                                            @if(empty($ticket->user->name))
                                                {{-- {{$ticket->username}} --}}
                                            @else
                                                {{$ticket->user->name}}
                                            @endif
                                        </span>                                        
                                        <span class='text-muted ml-1'><i class="fa fa-folder"></i> 
                                            @if(empty($ticket->department->name))
                                                {{-- {{$ticket->department}} --}}
                                            @else
                                                {{$ticket->department->name}}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class='row' style='font-size:.8rem'>
                                    <div class='col-lg'>                                            
                                        <span class='text-muted'><i class="fa fa-folder"></i> 
                                            @if(empty ( $ticket->category->name ))
                                                {{-- {{$ticket->category}} --}}
                                            @else
                                                {{$ticket->category->name}}
                                            @endif
                                        </span>                                           
                                    </div>
                                </div>                                   
                            </th>
                            <th>
                                <div class='row'>
                                    {!! CustomFunctions::status_color($ticket->status_id) !!}
                                </div>
                                <div class='row'>
                                    <span class='text-muted' style='font-size:.8rem'>#{{$ticket->ticket_id}}</span>
                                </div>
                            </th>                    
                            <th>
                                <span style='font-size:.8rem'>{!!str_replace(' ','<br>',$ticket->created_at)!!}</span>
                            </th>
                            <th>
                                @if(!empty($ticket->assigned_to))                                        
                                    {{$ticket->assign->name}}
                                @endif                                    
                            </th>
                            <th>
                                <span style='font-size:.8rem'>{!!str_replace(' ','<br>',$ticket->updated_at)!!}</span>
                            </th>
                        </tr>
                    @endforeach                
                @else
                    <p>No Tickets Found.</p>
                @endif 
            </tbody>
        </table>
    </div>
</div>
<div class='row'>
    <div class='col-lg'>
        {!! $tickets->appends(\Request::except('page'))->render() !!}
    </div>
</div>