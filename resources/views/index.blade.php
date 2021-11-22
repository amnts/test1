@extends('layouts.default')

@section('body')
    <div id="form"></div>
@endsection

@push('scripts')
    <script>
        const lang = @json(__('weekdays'));
        const tariffs = @json($tariffs);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vuejs-datepicker/1.6.2/vuejs-datepicker.min.js"></script>
    <script src="form.js"></script>
@endpush
