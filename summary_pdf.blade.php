@php
$ev             = array();
$he             = array();
$i              = 1;

foreach($evs_arr as $uID=>$evs) {
    if($evs['evaluate_scripts']->type=="he") {
        $he                 = $evs;
    } else {
        $ev[$i]             = $evs;
        $i++;        
    }
    $sections               =   $evs['sections'];
    $segments               =   $evs['segments'];
    $seactionswise          =   $evs['seactionswise'];
    $notseactionswise       =   $evs['notseactionswise'];
    $total_marks            =   $evs['total_marks'];
    $marks_summary_arr      =   $evs['marks_summary_arr'];
    $cardinal               =   $evs['cardinal'];
    $final_marks            =   $evs['final_marks'];
    $questionpaper          =   $evs['questionpaper'];
}



@endphp
<style type="text/css">
table { width:95%; }
table.morpion {
    border-left:        solid 1px #000000;
    border-top:        solid 1px #000000;
    width:100%;
}
table.morpion td {
    font-size:    12pt; border-bottom: solid 1px #000000; border-right: solid 1px #000000;
    padding:      3px; text-align:   center;
}
table.morpion td.j1 { color: #000; }
table.morpion td.j2 { text-align: right; }
.text-center { text-align: center; }
div.left { float: left; }
div.right { float: right; }

</style>

<h2 class="text-center">Marks Summary</h2>
<br>
<table cellspacing="0px" align="center">
    <tr>
        <td style="width:50%; text-align: left;">Enrollment No: {{$marks_summary_arr['enrollment_no']}}</td>
        <td style="width:50%; text-align: right;">Sem: {{$marks_summary_arr['semester']}}</td>
    </tr>
    <tr>
        <td style="width:100%; text-align: left;" colspan="2">Subject: {{$marks_summary_arr['subject']}}</td>
    </tr>
    <tr>
        <td style="width:100%; text-align: left; color:green;" colspan="2">Final Marks: {{$final_marks}}</td>
    </tr>
</table>
<br>

@if($governing_parameters['ev_segmented']->key_value=="on" || $governing_parameters['double_ev_segemented']->key_value=="on") 
    
<table class="morpion" cellspacing="0px" align="center">

    <tr>
        <td class="j1">Q.No</td>
        <td class="j1">Max Marks</td>
        <td class="j1" colspan="4">Marks Given By</td>
        <td class="j1">Remark</td>
    </tr>
    <tr>
        <td class="j1"></td>
        <td class="j2"></td>
        <td class="j1">Evaluator-1</td>
        <td class="j1">Evaluator-2</td>
        <td class="j1">Evaluator-3</td>
        <td class="j1"></td>
        <td class="j1"></td>
    </tr>

    @if(count($segments)>0)

    @foreach($segments as $segment)

        @php

        $sections = $questionpaper->questionpapersetup()->where('segment_id',$segment->id)->whereNotNull('section_name')->where('section_name','!=','')->groupBy('section_name')->pluck('section_name');

        @endphp

        @if(count($sections))
            @foreach($sections as $section)
                <tr>
                    <td colspan="7">{{$section}}</td>
                </tr>
                @if(isset($seactionswise[$segment->id]))
                @if(isset($seactionswise[$segment->id][$section]))
                    @foreach($seactionswise[$segment->id][$section] as $key=>$eachquesion)
                    <tr>
                        <td class="j1">{{$eachquesion['name']}}</td>
                        <td class="j2">{{$eachquesion['marks']}}</td>

                        @php for($i=1;$i<=3;$i++) { @endphp

                            @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)
                                <td class="j2">
                                    @if($ev[$i]['seactionswise'][$segment->id][$section][$key]['allow_calculate']==0)
                                        <span style="color:red;"> * </span>
                                    @endif
                                    
                                    <span style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                                    
                                    @if(array_key_exists($key,$ev[$i]['question_marks_given']))
                                        {{ isset($ev[$i]['question_marks_given'][$key])?$ev[$i]['question_marks_given'][$key]:"NA"}}
                                    @else
                                        NA                            
                                    @endif

                                    </span>
                                </td>                    
                            @else 
                                <td class="j2"></td>
                            @endif

                        @php } @endphp
                        
                        <td class="j1"></td>
                        <td class="j1"></td>
                    </tr>     
                    @endforeach
                @endif
                @endif
            @endforeach
        @else
            @if(isset($notseactionswise[$segment->id]))
            @foreach($notseactionswise[$segment->id] as $key=>$eachquesion)
            <tr>
                <td class="j1">{{$eachquesion['name']}}</td>
                <td class="j2">{{$eachquesion['marks']}}</td>
                
                @php for($i=1;$i<=3;$i++) { @endphp

                        @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)
                            <td class="j2">
                                @if($ev[$i]['notseactionswise'][$segment->id][$key]['allow_calculate']==0)
                                    <span style="color:red;"> * </span>
                                @endif
                                
                                <span style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                                
                                @if(array_key_exists($key,$ev[$i]['question_marks_given']))
                                    {{ isset($ev[$i]['question_marks_given'][$key])?$ev[$i]['question_marks_given'][$key]:"NA"}}
                                @else
                                    NA                            
                                @endif

                                </span>
                            </td>                    
                        @else 
                            <td class="j2"></td>
                        @endif

                @php } @endphp
                
                <td class="j1" style="color:green;"></td>
                <td class="j1"></td>
            </tr>
            @endforeach
            @endif
        @endif

    @endforeach

    @endif

    <tr>
        <td class="j1">Total</td>
        <td class="j2">{{$total_marks}}</td>

        @php for($i=1;$i<=3;$i++) { @endphp

                @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)

                    <td class="j2" style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                    {{isset($ev[$i]['marks_given'])?$ev[$i]['marks_given']:"NA"}}
                    </td>

                @else
                    <td class="j2"></td>
                @endif

        @php } @endphp
        <td class="j1" style="color:green;"></td>
        <td class="j1"></td>
    </tr>
    @if(isset($governing_parameters['digital_signature']) && $governing_parameters['digital_signature']->key_value=='on')
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="2">Signature: </td>
        @for($i=1;$i<=3;$i++)
            <td>
            @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)
                
                {{ $ev[$i]['evuser']->username }}<br>
                @if($ev[$i]['evuser']->user_signature_file)
                    <img src="{{ public_path().'/usersignature/' . $ev[$i]['evuser']->user_signature_file }}" style="width:100px; height:70px;">
                @endif

            @endif
            </td>
        @endfor
        <td></td>
        <td></td>
    </tr>
    @endif
</table>

@else

<table class="morpion" cellspacing="0px" align="center">
    <tr>
        <td class="j1">Q.No</td>
        <td class="j1">Max Marks</td>
        <td class="j1" colspan="4">Marks Given By</td>
        <td class="j1">Remark</td>
    </tr>
    <tr>
        <td class="j1"></td>
        <td class="j2"></td>
        <td class="j1">Evaluator-1</td>
        <td class="j1">Evaluator-2</td>
        <td class="j1">Evaluator-3</td>
        <td class="j1">Moderator</td>
        <td class="j1"></td>
    </tr> 

    @if(count($sections))
    @foreach($sections as $section)
        <tr>
            <td colspan="7">{{$section}}</td>
        </tr>
        @if(isset($seactionswise[$section]))
            @foreach($seactionswise[$section] as $key=>$eachquesion)
            <tr>
                <td class="j1">{{$eachquesion['name']}}</td>
                <td class="j2">{{$eachquesion['marks']}}</td>

                @php for($i=1;$i<=3;$i++) { @endphp

                    @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)
                        <td class="j2">
                            @if($ev[$i]['seactionswise'][$section][$key]['allow_calculate']==0)
                                <span style="color:red;"> * </span>
                            @endif
                            
                            <span style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                            
                            @if(array_key_exists($key,$ev[$i]['question_marks_given']))
                                {{ isset($ev[$i]['question_marks_given'][$key])?$ev[$i]['question_marks_given'][$key]:"NA"}}
                            @else
                                NA                            
                            @endif

                            </span>
                        </td>                    
                    @else 
                        <td class="j2"></td>
                    @endif

                @php } @endphp
                
                <td class="j1">
                    @php

                    if(isset($he['cardinal'])) {

                        if($governing_parameters['evaluation_type']->key_value==3) { 
                            if($he['seactionswise'][$section][$key]['allow_calculate']==0) {
                                echo '<span style="color:red;"> * </span>';
                            }
                            echo (isset($he['question_marks_given'][$key])?$he['question_marks_given'][$key]:"NA");
                        }

                    }

                    @endphp
                </td>
                <td class="j1"></td>
            </tr>     
            @endforeach
        @endif
    @endforeach
    @else
        @foreach($notseactionswise as $key=>$eachquesion)
        <tr>
            <td class="j1">{{$eachquesion['name']}}</td>
            <td class="j2">{{$eachquesion['marks']}}</td>
            
            @php for($i=1;$i<=3;$i++) { @endphp

                    @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)
                        <td class="j2">
                            @if($ev[$i]['notseactionswise'][$key]['allow_calculate']==0)
                                <span style="color:red;"> * </span>
                            @endif
                            
                            <span style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                            
                            @if(array_key_exists($key,$ev[$i]['question_marks_given']))
                                {{ isset($ev[$i]['question_marks_given'][$key])?$ev[$i]['question_marks_given'][$key]:"NA"}}
                            @else
                                NA                            
                            @endif

                            </span>
                        </td>                    
                    @else 
                        <td class="j2"></td>
                    @endif

            @php } @endphp
            
            <td class="j1" style="color:green;">
                @php

                if(isset($he['cardinal'])) {

                    if($governing_parameters['evaluation_type']->key_value==3) {                         
                        if($he['notseactionswise'][$key]['allow_calculate']==0) {
                            echo '<span style="color:red;"> * </span>';
                        }
                        echo (isset($he['question_marks_given'][$key])?$he['question_marks_given'][$key]:"NA");
                    }

                }

                @endphp
            </td>
            <td class="j1"></td>
        </tr>
        @endforeach
    @endif

    <tr>
        <td class="j1">Total</td>
        <td class="j2">{{$total_marks}}</td>

        @php for($i=1;$i<=3;$i++) { @endphp

                @if(isset($ev[$i]['cardinal']) && $ev[$i]['cardinal']==$i)

                    <td class="j2" style="color:{{ (count($he)==0 && $ev[$i]['evaluate_scripts']->marks==$final_marks)?'green':''}}">
                    {{isset($ev[$i]['marks_given'])?$ev[$i]['marks_given']:"NA"}}
                    </td>

                @else
                    <td class="j2"></td>
                @endif

        @php } @endphp
        <td class="j1" style="color:green;">

            @php

                if(isset($he['cardinal'])) {

                    if($governing_parameters['evaluation_type']->key_value==3) {                         
                        
                        echo isset($he['marks_given'])?$he['marks_given']:"NA";
                    }

                }

            @endphp
            
        </td>
        <td class="j1"></td>
    </tr>
    @if(isset($governing_parameters['digital_signature']) && $governing_parameters['digital_signature']->key_value=='on')
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="2">Signature: </td>
        <td>
            @if(isset($ev[1]) && isset($ev[1]['cardinal']))
                {{ $ev[1]['evuser']->username }}<br>
                @if($ev[1]['evuser']->user_signature_file)
                <img src="{{ public_path().'/usersignature/' . $ev[1]['evuser']->user_signature_file }}" style="width:100px; height:70px;">
                @endif
            @endif
        </td>
        <td>
            @if(isset($ev[2]) && isset($ev[2]['cardinal']))
                {{ $ev[2]['evuser']->username }}<br>
                @if($ev[2]['evuser']->user_signature_file)
                <img src="{{ public_path().'/usersignature/' . $ev[2]['evuser']->user_signature_file }}" style="width:100px; height:70px;">
                @endif
            @endif
        </td>
        <td>
            @if(isset($ev[3]) && isset($ev[3]['cardinal']))
                {{ $ev[3]['evuser']->username }}<br>
                @if($ev[3]['evuser']->user_signature_file)
                <img src="{{ public_path().'/usersignature/' . $ev[3]['evuser']->user_signature_file }}" style="width:100px; height:70px;">
                @endif
            @endif
        </td>
        <td>
            @if(isset($he) && isset($he['cardinal']))
                {{ $he['evuser']->username }}<br>
                @if($he['evuser']->user_signature_file)
                <img src="{{ public_path().'/usersignature/' . $he['evuser']->user_signature_file }}" style="width:100px; height:70px;">
                @endif
            @endif
        </td>
        <td></td>
    </tr>
    @endif

</table>

@endif

<table width="100%" cellpadding="5">
    <tr><td><span style="color:red;"> * </span> - Marks are not added in total<br><span style="color:green;"> * Final Marks</span></td></tr>
</table>
<br><br><br>Note: This is a system generated marks summary.