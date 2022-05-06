@extends('layouts.app')

@section('content')
<div class="container">
    @component('components.status-badge')
    @endcomponent
    <div class="row justify-content-center">
        <form method="POST" action="{{ route('register') }}" class="col-md-8 pt-4">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <h1>{{ __('Sign Up for an Account') }}</h1>
                    Already have an account? <a href="/login" class="btn btn-primary">Login</a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header h4 bg-primary text-white">{{ __('Account Details') }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <?php
                    $universities = DB::table("universities")->where('id', '>', '3')->orderBy('name', 'asc')->get();
                    ?>
                    <div class="form-group row">
                        <label for="university_id" class="col-md-4 col-form-label text-md-right">University<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <select class="form-control" id="university_id" name="university_id" onChange="checkBinusian()">
                                <option value="1" {{ old('university_id') == '1' ? 'selected' : '' }}>None/Uncategorized</option>
                                @foreach($universities as $university)
                                    <option value="{{$university->id}}" {{ old('university_id') == $university->id ? 'selected' : '' }}>{{$university->name}}</option>
                                @endforeach
                                <option value="0" {{ old('university_id') == '0' ? 'selected' : '' }}>Others (Add a new one)</option>
                            </select>
                        </div>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <b>For BINUSIAN Participants:</b> Make sure to select <b>BINUS University</b> to be eligible for <a href="https://student.binus.ac.id/sat/" target="_blank">Student Activity Transcript (SAT)</a> points. Use <b>@binus.ac.id</b> email address whenever possible.
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <b>For PPTI BCA Participants at TECHNO 2021:</b> Make sure to select <b>BINUS University</b> and select <b>PPTI BCA</b> as your Regional/Campus Location.
                    </div>
                    <div class="form-group row" id="nim-container">
                        <label for="nim" class="col-md-4 col-form-label text-md-right">{{ __('NIM') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="nim" type="number" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim') }}">

                            @error('nim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row" id="binus_regional-container">
                        <label for="binus_regional" class="col-md-4 col-form-label text-md-right">Regional (Campus Location)<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <select class="form-control" id="binus_regional" name="binus_regional">
                                <option value="Alam Sutera">Alam Sutera</option>
                                <option value="Bekasi">Bekasi</option>
                                <option value="Kemanggisan">Kemanggisan (Anggrek, Syahdan, Kijang)</option>
                                <option value="Senayan">Senayan (BINUS International)</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Malang">Malang</option>
                                <option value="BASE">BASE (BINUS ASO School of Engineering)</option>
                                <option value="BNSD">BNSD (BINUS Northumbria School of Design)</option>
                                <option value="BOL">BOL (BINUS Online Learning)</option>
                                <option value="PPTI BCA">PPTI BCA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="fyp_batch-container">
                        <label for="fyp_batch" class="col-md-4 col-form-label text-md-right">FYP Batch (for TECHNO 2021)<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <select class="form-control" id="fyp_batch" name="fyp_batch">
                                <option value="0">None</option>
                                <option value="1 (AAS, ABN, ABB, PPTI)">1 (AAS, ABN, ABB, PPTI)</option>
                                <option value="2 (BAS, BBN, BBB)">2 (BAS, BBN, BBB)</option>
                                <option value="3 (ABM, CAS, CBN, CBB)">3 (ABM, CAS, CBN, CBB)</option>
                                <option value="4 (BBM, DAS, DBN)">4 (BBM, DAS, DBN)</option>
                                <option value="5 (ASN, CBM)">5 (ASN, CBM)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="new_university-container">
                        <label for="new_university" class="col-md-4 col-form-label text-md-right">{{ __('University Name') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="new_university" type="text" class="form-control @error('new_university') is-invalid @enderror" name="new_university" value="{{ old('new_university') }}">
                            @error('new_university')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="major" class="col-md-4 col-form-label text-md-right">{{ __('Major / Study Program') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="major" type="text" class="form-control @error('major') is-invalid @enderror" name="major" value="{{ old('major') }}" list="major-recommendations">
                            @error('major')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <datalist id="major-recommendations"></datalist>
                        </div>
                    </div>
                    <div class="alert alert-success" role="alert">
                        <b>For BINUS University students, please use your full program names</b> such as <b><i>Computer Science and Mathematics</i></b> instead of <b><i>TI MAT</i></b> (may be confused with <b><i>Mobile Application and Technology - MAT</i></b>), or <b><i>Computer Science Global Class</i></b> instead of <b><i>IT GC</i></b>.
                    </div>
                    <div class="alert alert-info" role="alert">
                        <b>BINUS International Computer Science</b> and <b>PPTI BCA</b> students may select <b>Computer Science (Regular)</b> as their major / study program.
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header h4 bg-primary text-white">{{ __('Contact Details') }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="line" class="col-md-4 col-form-label text-md-right">{{ __('LINE Registered Number or LINE ID') }}</label>
                        <div class="col-md-6">
                            <input id="line" type="text" class="form-control @error('line') is-invalid @enderror" name="line" value="{{ old('line') }}" autocomplete="tel">
                            @error('line')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="whatsapp" class="col-md-4 col-form-label text-md-right">{{ __('WhatsApp Registered Number') }}</label>
                        <div class="col-md-6">
                            <input id="whatsapp" type="text" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ old('whatsapp') }}" autocomplete="tel">
                            @error('whatsapp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header h4 bg-primary text-white">{{ __('Confirm Password') }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}<b class="text-danger">*</b></label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="/js/jurusan.js/jurusan.js"></script>
<script>
    var jurusan = new Jurusan();

    checkBinusian();
    function checkBinusian(){
        if (document.getElementById("university_id").value == 4){
            document.getElementById("nim-container").style.display = "flex";
            document.getElementById("nim").setAttribute("required","true");
            document.getElementById("binus_regional-container").style.display = "flex";
            document.getElementById("binus_regional").setAttribute("required","true");
            document.getElementById("major").setAttribute("required","true");
            document.querySelector('[for="major"] > b').style.display = "inline";
            document.getElementById("fyp_batch-container").style.display = "flex";
            document.getElementById("fyp_batch").setAttribute("required","true");
        } else {
            document.getElementById("nim-container").style.display = "none";
            document.getElementById("nim").removeAttribute("required");
            document.getElementById("binus_regional-container").style.display = "none";
            document.getElementById("binus_regional").removeAttribute("required");
            document.getElementById("major").removeAttribute("required");
            document.querySelector('[for="major"] > b').style.display = "none";
            document.getElementById("fyp_batch-container").style.display = "none";
            document.getElementById("fyp_batch").removeAttribute("required");
        }
        if (document.getElementById("university_id").value == 0){
            document.getElementById("new_university-container").style.display = "flex";
            document.getElementById("new_university").setAttribute("required","true");
        } else {
            document.getElementById("new_university-container").style.display = "none";
            document.getElementById("new_university").removeAttribute("required");
        }
    }

    function suggest(){
        var q = document.getElementById("major").value;
        var res = jurusan.search(q, {limit: 10});
        var i;

        for (i = 0; i < res.length; i++){
            if (res[i].name === q) return;
        }

        // Create a list of recommendations
        var dl = document.getElementById("major-recommendations");
        dl.innerHTML = "";
        for (i = 0; i < res.length; i++){
            var o = document.createElement("option");
            o.value = res[i].name;
            o.textContent = "\"" + q + "\": " + res[i].name;
            dl.appendChild(o);
        }
    }

    document.getElementById("major").addEventListener("input", suggest);
    document.getElementById("major").addEventListener("change", suggest);
    document.getElementById("major").addEventListener("keypress", suggest);
    document.getElementById("major").addEventListener("paste", suggest);

</script>
@endsection
