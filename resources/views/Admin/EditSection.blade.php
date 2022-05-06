@extends('layouts.app')

@section('content')
    <form method="POST"
        action="/Admin/{{ $QuartilInformation->Year }}/{{ $QuartilInformation->Quartil }}/EditQuartilInformation"
        class="mt-5" enctype="multipart/form-data">
        @csrf
        <div class="container my-5">
            <div class="card mt-5">
                <div class="card-header">
                    Section Information
                </div>
                <div class="card-body">
                    <div class="form-group row">

                        <div class="col-md-12">
                            <p class="w-100 col-form-label text-md-right" style="padding-bottom: 0; margin-bottom:0;">
                                Section Title</p>
                            <input id="QuartilTitle" type="text" class="form-control w-50" name="QuartilTitle"
                                value="{{ $QuartilInformation->QuartilTitle }}" required autofocus>

                            <p class="w-100 col-form-label text-md-right mt-4" style="padding-bottom: 0; margin-bottom:0;">
                                Section Description</p>
                            <textarea id="QuartilDescription" type="text" class="form-control w-50" name="QuartilDescription" rows="10" required
                                autofocus>{{ $QuartilInformation->QuartilDescription }}</textarea>
                        </div>
                    </div>
                    <a href="/Admin/{{ $QuartilInformation->Year }}/{{ $QuartilInformation->Quartil }}/Edit"
                        class="btn btn-primary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <div class="container my-5">
        @foreach ($SectionInformation as $SectionInformationEach)
            <div class="card mt-5">
                <div class="card-header">
                    {{ $SectionInformationEach->SectionName }}
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $SectionInformationEach->SectionDescription }}</p>
                    <p class="card-text">Need Validation :
                        @if ($SectionInformationEach->NeedValidation == 0)
                            NO
                        @elseif($SectionInformationEach->NeedValidation == 1)
                            YES
                        @else
                            Submit
                        @endif
                    </p>
                    <p class="card-text">Total Question : {{ $SectionInformationEach->TotalQuestion }}</p>
                    <a href="/Admin/{{ $QuartilInformation->Year }}/{{ $QuartilInformation->Quartil }}/{{ $SectionInformationEach->SectionID }}/Edit"
                        class="btn btn-primary">Edit</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
