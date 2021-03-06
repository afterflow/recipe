@startPhp

@unless(empty( $namespace ))
namespace {{ $namespace }};
@endunless

@unless(empty( $imports ))
@foreach( $imports as $import)
use {{ $import }};
@endforeach
@endunless

class {{ $name }} {{ isset($extends) ? 'extends '. $extends : '' }} {{ !empty($implements) ? 'implements '. \Afterflow\Recipe\Recipe::sequence($implements) : '' }}
{
@unless(empty($traits))
    use {{ @sequence($traits) }};
@endunless

@isset($content)
{!! @indent($content) !!}
@endisset
}
