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

@isset($table)
{!! @indent($table) !!}
@endisset
@isset($vars)
{!! @indent($vars) !!}
@endisset

@unless(empty($relations))
{!! @indent( \Afterflow\Recipe\Recipe::sequence($relations,\Afterflow\Recipe\eol(2)) ) !!}
@endunless

@isset($methods)
{!! @indent($methods) !!}
@endisset
}
