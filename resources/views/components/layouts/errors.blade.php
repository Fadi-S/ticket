@if($errors->any())
    <div class="bg-red-100 border-2
     border-red-200 flex flex-col
      items-center mx-auto px-2 py-4
       rounded-xl text-red-900 lg:w-1/2 w-full">

        <ul class="list-disc mx-auto">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif