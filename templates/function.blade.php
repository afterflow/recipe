{{$visibility ? $visibility.' ' : ''}}{{ $abstract ? $abstract.' ' : '' }}{{ $static ? $static.' ' : '' }}function {{ $name }}({{$arguments}})
{
@if($body)
{!! \Afterflow\Recipe\Recipe::indent( $body ) !!}
@elseif($return)
    return {!! $return !!}
@endif
}
