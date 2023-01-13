<script>
    @if($errors->any())
    alert('{{$errors->first()}}');
    @endif

    @if(session()->has('msg'))
    alert('{{session()->get('msg')}}');
    @endif
</script>
