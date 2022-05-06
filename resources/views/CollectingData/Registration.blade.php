@extends('layouts.app')

@section('content')
    <form method="POST" action="/re-regist/{{ $Information['Year'] }}/{{ $Information['Quartil'] }}"
        class="mt-5" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="formid" value="{{ $Information['Page'] }}">
        <input type="hidden" id="TypeForm" name="TypeForm" value="">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $Section->SectionName }}</h5>
                            @if ($PrevButton == 1 && $Section->CanGetPrev == 1)
                                <a class="btn btn-primary"
                                    onclick="document.getElementById('TypeForm').value='GetHistory'; document.forms[1].submit();">Get
                                    Prev Quartil Data</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($QuestionInformation as $QuestionInformationEach)
            @if ($QuestionInformationEach->SectionID == $Section->SectionID)
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <p for="{{ $QuestionInformationEach->CustomName }}"
                                            class="w-100 col-form-label text-md-right"
                                            style="padding-bottom: 0; margin-bottom:0;">
                                            {{ $QuestionInformationEach->QuestionText }}</p><br>
                                        @if ($QuestionInformationEach->QuestionType == '6')
                                            <img src="{{ $QuestionInformationEach->QuestionDescription }}" alt="">
                                        @else
                                            <p for="{{ $QuestionInformationEach->CustomName }}"
                                                class="w-100 col-form-label text-md-right"
                                                style="font-size: 80%; padding-top: 0; margin-top:0;">
                                                {{ $QuestionInformationEach->QuestionDescription }}</p>
                                        @endif

                                        <div class="col-md-12">
                                            @if ($QuestionInformationEach->QuestionType == '1')
                                                <input id="{{ $QuestionInformationEach->CustomName }}" type="text"
                                                    class="form-control w-50 @error('{{ $QuestionInformationEach->CustomName }}') is-invalid @enderror"
                                                    name="{{ $QuestionInformationEach->CustomName }}"
                                                    value="{{ $QuestionInformationEach->AnswerText }}" required
                                                    autofocus>

                                                @error('{{ $QuestionInformationEach->CustomName }}')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            @elseif ($QuestionInformationEach->QuestionType == '2')
                                                @error('{{ $QuestionInformationEach->CustomName }}')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @foreach ($QuestionChoice as $QuestionChoiceEach)
                                                    @if ($QuestionChoiceEach->QuestionID == $QuestionInformationEach->QuestionID)
                                                        <div class="form-check mt-3">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $QuestionInformationEach->CustomName }}"
                                                                id="{{ $QuestionInformationEach->CustomName }}"
                                                                @if ($QuestionInformationEach->AnswerText == $QuestionChoiceEach->InformationText) checked @endif required
                                                                value="{{ $QuestionChoiceEach->InformationText }}">
                                                            <label class="form-check-label"
                                                                for="{{ $QuestionInformationEach->CustomName }}">
                                                                {{ $QuestionChoiceEach->InformationText }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @elseif ($QuestionInformationEach->QuestionType == '4')
                                                <input id="{{ $QuestionInformationEach->CustomName }}" type="date"
                                                    class="form-control w-50 @error('{{ $QuestionInformationEach->CustomName }}') is-invalid @enderror"
                                                    name="{{ $QuestionInformationEach->CustomName }}"
                                                    value="{{ $QuestionInformationEach->AnswerDate }}" required
                                                    autofocus>

                                                @error('{{ $QuestionInformationEach->CustomName }}')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            @elseif ($QuestionInformationEach->QuestionType == '5')
                                                @if (!blank($QuestionInformationEach->AnswerFileName))
                                                    <input type="hidden" id="{{ $QuestionInformationEach->CustomName }}"
                                                        name="{{ $QuestionInformationEach->CustomName }}"
                                                        value="{{ $QuestionInformationEach->AnswerFileName }}">
                                                    <a href="/getFile/{{ $QuestionInformationEach->AnswerFileName }}"
                                                        target="_blank" class=" btn btn-primary mb-3">Download History</a>
                                                @endif
                                                <input id="{{ $QuestionInformationEach->CustomName }}" type="file"
                                                    class="form-control w-50 @error('{{ $QuestionInformationEach->CustomName }}') is-invalid @enderror"
                                                    name="{{ $QuestionInformationEach->CustomName }}"
                                                    @if (blank($QuestionInformationEach->AnswerFileName)) required @endif autofocus>

                                                @error('{{ $QuestionInformationEach->CustomName }}')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <button type="submit" id="SubmitForm" class="d-none"></button>
    </form>

    <div class="container mb-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if ($Information['Page'] > 1)
                            <button type="submit" class="btn btn-primary"
                                onclick="document.getElementById('TypeForm').value='Prev'; document.forms[1].submit();">Prev</button>
                        @endif
                        @if ($Information['LastPage'] == 1)
                            <button type="submit" class="btn btn-primary"
                                onclick="document.getElementById('TypeForm').value='Submit'; document.getElementById('SubmitForm').click();">Submit</button>
                        @else
                            <button type="submit" class="btn btn-primary"
                                onclick="document.getElementById('TypeForm').value='Next'; document.getElementById('SubmitForm').click();">Next</button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function Delete() {
            console.log("asadd");
            document.getElementById("UploadFile").innerHTML =
                '<p for="UploadFile" class="form-label">Upload File<br></p><input type="file" name="UploadFile" id="UploadFile">';
            document.getElementById("File").value = "Add";
        }
    </script>
@endsection
