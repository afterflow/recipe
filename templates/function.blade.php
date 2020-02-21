{{$visibility ? $visibility.' ' : ''}}{{ $abstract ? $abstract.' ' : '' }}{{ $static ? $static.' ' : '' }}function {{$methodCall}}
{
@if($body)
{!! \Afterflow\Recipe\Recipe::indent( $body ) !!}
@elseif($return)
    return {!! $return !!}
@endif
}
