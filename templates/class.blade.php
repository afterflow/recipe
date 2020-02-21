{{-- Otherwise this file will be treated as PHP script--}}
{!! '<'.'?php' !!}

@unless(empty( $namespace ))
namespace {{ $namespace }};
@endunless

@unless(empty( $imports ))
    @foreach( $imports as $import)
import {{ $import }};
    @endforeach
@endunless

class {{ $name }} {{ isset($extends) ? 'extends '. $extends : '' }} {{ !empty($implements) ? 'implements '. collect($implements)->implode(', ') : '' }}
{
@unless(empty($traits))
    use {{ collect($traits)->implode(', ') }};
@endunless

@isset($content)
{!! \Afterflow\Recipe\Recipe::indent($content) !!}
@endisset
}
