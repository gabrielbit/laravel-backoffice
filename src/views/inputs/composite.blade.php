<div {{ HTML::attributes($options) }}>
@foreach($inputs as $input)
{{ $input->render() }}
@endforeach
</div>