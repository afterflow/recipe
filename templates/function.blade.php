{{$visibility ? $visibility.' ' : ''}}{{ $abstract ? $abstract.' ' : '' }}{{ $static ? $static.' ' : '' }}function {{$methodCall}}
{
@if($body)
{!! @indent( $body ) !!}
@elseif($return)
    return {!! $return !!}
@endif
}
